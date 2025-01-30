<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Provider;
use App\Models\Purchase;
use Livewire\Component;

class PurchaseController extends Component
{
    public $pageTitle, $componentName, $providers = [], $products = [], $productsList = [], $selectedProducts = [];
    public $cost, $payed, $status, $payment_type, $provider;
    public $editingPurchaseId;
    public $startDate, $endDate;

    private $pagination = 20;

    protected $listeners = ['addProduct', 'removeProduct', 'editPurchase', 'showProducts'];

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Compras';
        $this->productsList = Product::all(['id', 'barcode', 'name']);
    }

    public function addProduct()
    {
        if (count($this->products) < count($this->productsList)) {
            $this->products[] = ['name' => '', 'quantity' => 1, 'price' => ''];
        }
    }

    public function removeProduct($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public $rules = [
        'cost' => ['required', 'numeric', 'min:0'],
        'payed' => ['required', 'numeric', 'min:0'],
        'status' => ['required', 'string'],
        'provider' => ['required', 'string', 'exists:providers,id'],
        'payment_type' => ['required', 'string'],
        'products.*.name' => ['required', 'exists:products,id'],
        'products.*.quantity' => ['required', 'integer', 'min:1'],
        'products.*.price' => ['required', 'numeric', 'min:0'],
    ];

    public $messages = [
        'cost.required' => 'El costo es requerido.',
        'cost.numeric' => 'El costo debe ser un número.',
        'cost.min' => 'El costo debe ser al menos 0.',
        'payed.required' => 'El monto pagado es requerido.',
        'payed.numeric' => 'El monto pagado debe ser un número.',
        'payed.min' => 'El monto pagado debe ser al menos 0.',
        'status.required' => 'El estado es requerido.',
        'provider.required' => 'El proveedor es requerido.',
        'provider.string' => 'El proveedor debe ser un texto.',
        'provider.exists' => 'El proveedor seleccionado no existe.',
        'payment_type.required' => 'El tipo de pago es requerido.',
        'products.*.name.required' => 'El nombre del producto es requerido.',
        'products.*.name.exists' => 'El producto seleccionado no existe.',
        'products.*.quantity.required' => 'La cantidad es requerida.',
        'products.*.quantity.integer' => 'La cantidad debe ser un número entero.',
        'products.*.quantity.min' => 'La cantidad debe ser al menos 1.',
        'products.*.price.required' => 'El precio es requerido.',
        'products.*.price.numeric' => 'El precio debe ser un número.',
    ];

    public function Store()
    {
        $this->validate();

        $purchase = Purchase::create([
            'cost' => $this->cost,
            'payed' => $this->payed,
            'status' => $this->status,
            'provider' => $this->provider,
            'payment_type' => $this->payment_type,
        ]);

        foreach ($this->products as $productData) {
            $product = Product::find($productData['name']);

            if ($product) {
                $product->update(['price' => $productData['price']]);
                $product->increment('stock', (int)$productData['quantity']);

                $purchase->products()->attach($product->id, [
                    'quantity' => (int)$productData['quantity'],
                    'price' => (float)$productData['price'],
                ]);
            }
        }

        $this->resetUI();
        $this->emit('hide-modal');
        $this->emit('record-created', 'Compra guardada exitosamente');
    }

    public function editPurchase($purchaseId)
    {
        if ($purchase = Purchase::find($purchaseId)) {
            $this->editingPurchaseId = $purchaseId;
            $this->payed = $purchase->payed;
            $this->status = $purchase->status;

            $this->emit('show-edit');
        }
    }

    public function showProducts($purchaseId)
    {
        if ($purchase = Purchase::with('products')->find($purchaseId)) {
            $this->selectedProducts = $purchase->products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'price' => number_format($product->pivot->price, 2),
                ];
            })->toArray();

            $this->emit('show-products');
        }
    }

    public function Update()
    {
        $rules = [
            'payed' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string']
        ];
        $this->validate($rules);

        if ($this->editingPurchaseId) {
            Purchase::where('id', $this->editingPurchaseId)->update([
                'payed' => $this->payed,
                'status' => $this->status,
            ]);

            session()->flash('message', 'Compra actualizada exitosamente.');
            $this->resetUI();
            $this->emit('hide-edit');
            $this->emit('record-updated', 'Compra actualizada exitosamente');
        }
    }

    public function searchByDate()
    {
        if ($this->startDate && !$this->endDate) {
            session()->flash('error', "Por favor seleccione una fecha de fin.");
            return;
        }

        if ($this->endDate && !$this->startDate) {
            session()->flash('error', "Por favor seleccione una fecha de inicio.");
            return;
        }

        if ($this->startDate && $this->endDate) {
            return Purchase::whereBetween('created_at', [
                $this->startDate . " 00:00:00",
                $this->endDate . " 23:59:59"
            ])
                ->paginate($this->pagination);
        }

        return Purchase::paginate($this->pagination); // Default pagination if no dates are set
    }

    public function resetUI()
    {
        $this->cost = '';
        $this->payed = '';
        $this->status = '';
        $this->provider = '';
        $this->payment_type = '';
        $this->products = [];
        $this->selectedProducts = [];
        $this->startDate = null;
        $this->endDate = null;
        $this->editingPurchaseId = null;
    }

    public function render()
    {
        $this->providers = Provider::all(['id', 'name', 'rif', 'document']);

        $data = Purchase::paginate($this->pagination);
        if ($this->startDate || $this->endDate) {
            $data = Purchase::whereBetween('created_at', [
                $this->startDate . " 00:00:00",
                $this->endDate . " 23:59:59"
            ])
                ->paginate($this->pagination);
        }

        return view('livewire.purchase.component', [
            'data' => $data,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
