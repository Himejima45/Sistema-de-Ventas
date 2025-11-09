<?php

namespace App\Http\Livewire;

use App\Exports\SalesExport;
use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportsController extends Component
{
    use WithPagination;

    public $componentName, $data, $reportType, $userId, $dateFrom, $dateTo, $saleId;

    // Modal-related properties
    public $selectedSale = null;
    public $activeTab = 'products';
    public $productsPage = 1;
    public $paymentsPage = 1;
    public $productsPerPage = 10;
    public $paymentsPerPage = 10;

    public function mount()
    {
        $this->componentName = 'Reportes de Ventas';
        $this->data = [];
        $this->reportType = '0';
        $this->userId = 0;
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    protected $queryString = [
        'productsPage' => ['except' => 1],
        'paymentsPage' => ['except' => 1],
    ];

    public function render()
    {
        $this->SalesByDate();

        $employees = User::whereHas('roles', function ($query) {
            $query->where('name', 'Employee')->orWhere('name', 'Admin');
        })->orderBy('name', 'asc')->get();

        // Explicit pagination for modal
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

        return view('livewire.reports.component', [
            'users' => $employees,
            'modalProducts' => $modalProducts,
            'modalPayments' => $modalPayments,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function SalesByDate()
    {
        $from = null;
        $to = null;

        if ($this->reportType === '0') {
            $from = now()->startOfDay();
            $to = now()->endOfDay();
        } else {
            if (!$this->dateFrom || !$this->dateTo) {
                $this->data = [];
                return;
            }
            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';
        }

        $query = Sale::with(['user', 'client'])
            ->where('status', 'PAID')
            ->whereBetween('updated_at', [$from, $to]);

        if ($this->userId != 0) {
            $query->where('user_id', $this->userId);
        }

        $paginator = $query->orderBy('updated_at', 'desc')->paginate(20);

        $this->data = [
            'data' => $paginator->getCollection()->map(function ($sale, $index) use ($paginator) {
                return [
                    'number' => $index + 1 + (($paginator->currentPage() - 1) * $paginator->perPage()),
                    'items' => $sale->products->sum('quantity'),
                    'user' => $sale->user->name,
                    'client' => $sale->client->name,
                    'total' => $sale->total,
                    'type' => $sale->type,
                    'status' => $sale->status,
                    'id' => $sale->id,
                    'updated_at' => $sale->updated_at,
                ];
            })->toArray(),
            'links' => $paginator->links('pagination::bootstrap-4')->render(),
        ];
    }

    // === MODAL: Show Sale Preview ===
    public function showPreview($saleId)
    {
        $this->selectedSale = Sale::with(['client', 'products.product', 'user', 'payments.currency'])
            ->find($saleId);

        if (!$this->selectedSale) {
            return;
        }

        $this->activeTab = 'products';
        $this->productsPage = 1;
        $this->paymentsPage = 1;
        $this->emit('show-sale-preview');
    }

    public function resetModal()
    {
        $this->selectedSale = null;
        $this->activeTab = 'products';
        $this->productsPage = 1;
        $this->paymentsPage = 1;
    }

    // === COMPUTED: Modal Products ===
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
        return new LengthAwarePaginator($items, $total, $this->productsPerPage, $this->productsPage, [
            'path' => request()->url(),
        ]);
    }

    // === COMPUTED: Modal Payments ===
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
        return new LengthAwarePaginator($items, $total, $this->paymentsPerPage, $this->paymentsPage, [
            'path' => request()->url(),
        ]);
    }

    // === COMPUTED: Product Totals ===
    public function getProductsTotalsProperty()
    {
        if (!$this->selectedSale) {
            return ['subtotal' => 0, 'totalQuantity' => 0];
        }

        $subtotal = 0;
        $totalQuantity = 0;
        foreach ($this->selectedSale->products as $d) {
            $subtotal += $d->price * $d->quantity;
            $totalQuantity += $d->quantity;
        }

        return ['subtotal' => $subtotal, 'totalQuantity' => $totalQuantity];
    }

    // === COMPUTED: Payment Summary (manual logic) ===
    public function getPaymentSummaryProperty()
    {
        if (!$this->selectedSale) {
            return [];
        }

        $payments = $this->selectedSale->payments;
        $total_payed_usd = 0;
        $total_payed_bs = 0;
        $total_payed_usd_equivalent = 0;

        foreach ($payments as $p) {
            $total_payed_usd += $p->cash_usd - $p->change_usd;
            $total_payed_bs += $p->cash_bs - $p->change_bs;
            if ($p->currency) {
                $total_payed_usd_equivalent += ($p->cash_usd - $p->change_usd) + ($p->cash_bs - $p->change_bs) / $p->currency->value;
            }
        }

        $is_fully_paid = $total_payed_usd_equivalent >= $this->selectedSale->total;
        $remaining_amount = max(0, $this->selectedSale->total - $total_payed_usd_equivalent);

        return compact(
            'total_payed_usd',
            'total_payed_bs',
            'total_payed_usd_equivalent',
            'is_fully_paid',
            'remaining_amount'
        );
    }

    // === EXPORTS ===
    public function excel()
    {
        return Excel::download(new SalesExport($this->dateFrom, $this->dateTo, $this->userId), 'Reporte de ventas.xlsx');
    }

    public function pdf()
    {
        $from = $this->dateFrom ? $this->dateFrom . ' 00:00:00' : now()->startOfDay();
        $to = $this->dateTo ? $this->dateTo . ' 23:59:59' : now()->endOfDay();

        $sales = Sale::with(['user', 'client', 'products'])
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId))
            ->whereBetween('updated_at', [$from, $to])
            ->where('status', 'PAID')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($sale, $index) {
                $total = $sale->products->sum(fn($p) => $p->price * $p->quantity);
                return [
                    'id' => ++$index,
                    'name' => $sale->user->name,
                    'client' => $sale->client->name,
                    'date' => $sale->created_at->format('H:i:s d-m-Y'),
                    'total' => $total,
                    'items' => $sale->products->sum('quantity'),
                    'type' => $sale->type,
                    'status' => $sale->status
                ];
            });

        $start = $this->dateFrom;
        $end = $this->dateTo;
        $budget = false;

        return response()->streamDownload(function () use ($sales, $start, $end, $budget) {
            $pdf = App::make('dompdf.wrapper');
            $start = Carbon::parse($start)->translatedFormat('D d, F Y - h:i:s a');
            $end = Carbon::parse($end)->translatedFormat('D d, F Y - h:i:s a');
            $pdf->loadView('pdf', compact('sales', 'start', 'end', 'budget'));
            echo $pdf->stream();
        }, 'Reporte de ventas.pdf');
    }
}