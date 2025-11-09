<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Currency;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CartsController extends Component
{
    use WithPagination;

    public $selectedSale = null;
    public $activeTab = 'products';

    /* modal mini-pagination */
    public $productsPage = 1;
    public $paymentsPage = 1;
    public $productsPerPage = 10;
    public $paymentsPerPage = 10;

    /* ---------- NORMAL CART LIST ---------- */
    private $pagination = 10;
    public $showModal = false, $selected_id = 0, $details = [], $error = '';
    public $searchedCode, $selectedProduct;
    public $cash_usd = null, $cash_bs = null, $change_usd = null, $change_bs = null;
    public $currency_id, $exchange_rate = 1;
    public $totalSale = 0, $paymentStatus = 'no_payment';
    public $existingPayments = [], $paymentSummary = [];
    public $products = [], $payments = [];

    protected $listeners = [
        'edit',
        'clearMessage',
        'zoom',
        'showPreview',
        'gotoProductsPage',
        'gotoPaymentsPage'
    ];

    // Use query string for modal pagination only
    protected $queryString = [
        'productsPage' => ['except' => 1],
        'paymentsPage' => ['except' => 1],
    ];

    public function mount()
    {
        $this->loadDefaultCurrency();
    }

    private function loadDefaultCurrency()
    {
        $cur = Currency::latest('last_update')->first();
        if ($cur) {
            $this->currency_id = $cur->id;
            $this->exchange_rate = $cur->value;
        }
    }

    public function gotoProductsPage($page)
    {
        $this->productsPage = $page;
    }

    public function gotoPaymentsPage($page)
    {
        $this->paymentsPage = $page;
    }

    public function clearMessage()
    {
        $this->error = '';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function edit(Sale $record)
    {
        $this->selected_id = $record->id;
        $this->details = $record->products;
        $this->totalSale = $record->total;

        // Load existing payments
        $this->existingPayments = $record->payments()->with('currency')->get()->toArray();

        // Calculate payment summary
        $this->calculatePaymentSummary();

        $this->resetPaymentFields();
        $this->updatePaymentStatus();
        $this->emit('open');
    }

    private function calculatePaymentSummary()
    {
        $sale = Sale::find($this->selected_id);
        if ($sale) {
            $this->paymentSummary = $sale->getPaymentSummary();
        }
    }

    private function resetPaymentFields()
    {
        $this->cash_usd = 0;
        $this->cash_bs = 0;
        $this->change_usd = 0;
        $this->change_bs = 0;
        $this->paymentStatus = 'no_payment';
    }

    public function resetModal()
    {
        $this->resetPaymentFields();
        $this->error = '';
        $this->selected_id = 0;
        $this->details = [];
        $this->totalSale = 0;
        $this->existingPayments = [];
        $this->paymentSummary = [];
        $this->activeTab = 'products';
        $this->productsPage = 1;
        $this->paymentsPage = 1;

        $defaultCurrency = Currency::latest('last_update')->first();
        if ($defaultCurrency) {
            $this->currency_id = $defaultCurrency->id;
            $this->exchange_rate = $defaultCurrency->value;
        }
    }

    public function showPreview($saleId)
    {
        $this->selectedSale = Sale::with(['client', 'products.product', 'user', 'payments.currency'])->find($saleId);
        $this->activeTab = 'products';
        $this->productsPage = 1;
        $this->paymentsPage = 1;
        $this->emit('show-sale-preview');
    }

    // Method to switch tabs
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Products pagination methods
    public function previousProductsPage()
    {
        if ($this->productsPage > 1) {
            $this->productsPage--;
        }
    }

    public function nextProductsPage()
    {
        $this->productsPage++;
    }

    // Payments pagination methods
    public function previousPaymentsPage()
    {
        if ($this->paymentsPage > 1) {
            $this->paymentsPage--;
        }
    }

    public function nextPaymentsPage()
    {
        $this->paymentsPage++;
    }

    public function getProductsProperty()
    {
        if (!$this->selectedSale) {
            return new LengthAwarePaginator([], 0, $this->productsPerPage, $this->productsPage);
        }

        $query = $this->selectedSale->products()->with('product');
        $total = $query->count();
        $items = $query->skip(($this->productsPage - 1) * $this->productsPerPage)
            ->take($this->productsPerPage)
            ->get();

        return new LengthAwarePaginator(
            $items,
            $total,
            $this->productsPerPage,
            $this->productsPage,
            ['path' => url()->current()]
        );
    }

    public function getPaymentsProperty()
    {
        if (!$this->selectedSale) {
            return new LengthAwarePaginator([], 0, $this->paymentsPerPage, $this->paymentsPage);
        }

        $query = $this->selectedSale->payments()->with('currency')->latest();
        $total = $query->count();
        $items = $query->skip(($this->paymentsPage - 1) * $this->paymentsPerPage)
            ->take($this->paymentsPerPage)
            ->get();

        return new LengthAwarePaginator(
            $items,
            $total,
            $this->paymentsPerPage,
            $this->paymentsPage,
            ['path' => url()->current()]
        );
    }


    public function getProductsTotalsProperty()
    {
        if (!$this->selectedSale) {
            return ['subtotal' => 0, 'totalQuantity' => 0];
        }
        $sub = 0;
        $qty = 0;
        foreach ($this->selectedSale->products as $d) {
            $sub += $d->price * $d->quantity;
            $qty += $d->quantity;
        }
        return ['subtotal' => $sub, 'totalQuantity' => $qty];
    }

    public function getPaymentSummaryProperty()
    {
        return $this->selectedSale ? $this->selectedSale->getPaymentSummary() : [];
    }

    public function updatedCurrencyId($value)
    {
        $currency = Currency::find($value);
        if ($currency) {
            $this->exchange_rate = $currency->value;
            $this->calculateChange();
        }
    }

    public function updatedCashUsd()
    {
        $this->calculateChange();
    }

    public function updatedCashBs()
    {
        $this->calculateChange();
    }

    public function calculateChange()
    {
        if (!$this->exchange_rate || $this->exchange_rate <= 0) {
            $this->exchange_rate = 1;
        }

        $totalPaidUSD = ($this->cash_usd ?? 0) + (($this->cash_bs ?? 1) / ($this->exchange_rate ?? 1));
        $remainingAmount = $this->paymentSummary['remaining_amount'] ?? $this->totalSale;
        $overpaid = $totalPaidUSD - $remainingAmount;

        if ($overpaid > 0) {
            $this->change_usd = floor($overpaid * 100) / 100;
            $remainingChangeUSD = $overpaid - $this->change_usd;
            $this->change_bs = round($remainingChangeUSD * $this->exchange_rate, 2);
        } else {
            $this->change_usd = 0;
            $this->change_bs = 0;
        }

        $this->updatePaymentStatus();
    }

    private function updatePaymentStatus()
    {
        $netPayment = $this->getNetPayment();
        $remainingAmount = $this->getRemainingAmount();

        if ($netPayment <= 0) {
            $this->paymentStatus = 'no_payment';
        } elseif ($remainingAmount <= 0) {
            $this->paymentStatus = 'fully_paid';
        } else {
            $this->paymentStatus = 'partial_payment';
        }
    }

    private function getNetPayment()
    {
        return ($this->cash_usd - $this->change_usd) + (($this->cash_bs - $this->change_bs) / $this->exchange_rate);
    }

    private function getRemainingAmount()
    {
        $currentPayment = $this->getNetPayment();
        $previouslyPaid = $this->paymentSummary['total_payed_usd_equivalent'] ?? 0;
        $totalPaid = $previouslyPaid + $currentPayment;

        return max(0, $this->totalSale - $totalPaid);
    }

    public function getNetPaymentForView()
    {
        return $this->getNetPayment();
    }

    public function getRemainingAmountForView()
    {
        return $this->getRemainingAmount();
    }

    public function render()
    {
        $carts = Sale::with(['products', 'payments.currency', 'client'])
            ->where('type', 'CART')
            ->where('status', 'PENDING')
            ->when($this->searchedCode, function ($query) {
                $query->where('code', 'like', "%$this->searchedCode%");
            })
            ->orderByDesc('created_at')
            ->paginate($this->pagination);

        $modalProducts = collect();
        $modalPayments = collect();

        if ($this->selectedSale) {
            $modalProducts = $this->selectedSale
                ->products()
                ->with('product')
                ->paginate($this->productsPerPage, ['*'], 'productsPage', $this->productsPage);

            $modalPayments = $this->selectedSale
                ->payments()
                ->with('currency')
                ->latest()
                ->paginate($this->paymentsPerPage, ['*'], 'paymentsPage', $this->paymentsPage);
        }

        return view('livewire.carts', [
            'carts' => $carts,
            'modalProducts' => $modalProducts,
            'modalPayments' => $modalPayments,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function update()
    {
        $record = Sale::find($this->selected_id);
        if (!$record) {
            $this->error = 'Venta no encontrada';
            return;
        }

        // Validate stock only if this payment will complete the sale
        $willBeFullyPaid = $this->getRemainingAmount() <= 0.01;

        if ($willBeFullyPaid) {
            foreach ($record->products as $detail) {
                if ($detail->product->stock < $detail->quantity) {
                    $this->error = "El producto {$detail->product->name} no cuenta con suficiente stock para procesar el pedido del cliente {$record->client->name} {$record->client->last_name}";
                    $this->emit('close');
                    return;
                }
            }
        }

        // Validate payment
        $netPayment = $this->getNetPayment();
        if ($netPayment <= 0) {
            $this->emit('record-warning', 'El monto pagado no puede ser menor o igual a 0');
            return;
        }

        if (!$this->currency_id) {
            $this->emit('record-warning', 'Debe seleccionar una moneda');
            return;
        }

        DB::beginTransaction();
        try {
            // Create payment record
            Payment::create([
                'sale_id' => $record->id,
                'currency_id' => $this->currency_id,
                'cash_usd' => $this->cash_usd,
                'cash_bs' => $this->cash_bs,
                'change_usd' => $this->change_usd,
                'change_bs' => $this->change_bs,
            ]);

            // Update sale status based on total payments
            $paymentSummary = $record->getPaymentSummary();
            $updateData = ['user_id' => auth()->id()];

            if ($paymentSummary['is_fully_paid']) {
                $updateData['status'] = 'PAID';

                // Update stock only if fully paid
                foreach ($record->products as $detail) {
                    $detail->product->decrement('stock', $detail->quantity);
                }
                $message = 'Pago completado exitosamente y stock actualizado';
            } else {
                $updateData['status'] = 'PENDING';
                $message = 'Pago registrado exitosamente';
            }

            $record->update($updateData);

            DB::commit();

            $this->emit('record-created', $message);
            $this->resetModal();
            $this->emit('close');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error = 'Error al procesar el pago: ' . $e->getMessage();
        }
    }

    public function zoom(Product $product)
    {
        $this->selectedProduct = $product;
        $this->emit('show-product-zoomed');
    }
}