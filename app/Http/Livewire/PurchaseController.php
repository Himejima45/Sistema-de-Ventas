<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Purchase;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Component
{
    public $pageTitle, $componentName, $products = [], $productsList = [], $selectedProducts = [];
    public $cost, $payed, $status, $payment_type;
    public $editingPurchaseId; // To hold the ID of the purchase being edited
    public $startDate, $endDate; // Properties for date filtering

    private $pagination = 5;

    protected $listeners = ['addProduct', 'removeProduct', 'editPurchase', 'showProducts'];

    public function mount()
    {
        $this->pageTitle = 'Compras';
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
        // Re-index the array to maintain sequential keys
        $this->products = array_values($this->products);
    }

    public $rules = [
        'cost' => ['required', 'numeric', 'min:0'],
        'payed' => ['required', 'numeric', 'min:0'],
        'status' => ['required', 'string'],
        'payment_type' => ['required', 'string'],
        'products.*.name' => ['required', 'exists:products,id'],
        'products.*.quantity' => ['required', 'integer', 'min:1'],
        'products.*.price' => ['required', 'numeric', 'min:0'],
    ];

    // Custom messages for validation
    public $messages = [
        'cost.required' => 'El costo es requerido.',
        'cost.numeric' => 'El costo debe ser un número.',
        'cost.min' => 'El costo debe ser al menos 0.',
        'payed.required' => 'El monto pagado es requerido.',
        'payed.numeric' => 'El monto pagado debe ser un número.',
        'payed.min' => 'El monto pagado debe ser al menos 0.',
        'status.required' => 'El estado es requerido.',
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
        // Validate input data
        $this->validate();

        // Create a new purchase
        $purchase = Purchase::create([
            'cost' => $this->cost,
            'payed' => $this->payed,
            'status' => $this->status,
            'payment_type' => $this->payment_type,
        ]);

        // Attach products to the purchase and update their quantities
        foreach ($this->products as $productData) {
            // Find the product
            $product = Product::find($productData['name']);

            if ($product) {
                $product->update(['price' => $productData['price']]);
                $product->decrement('stock', (int)$productData['quantity']);

                $purchase->products()->attach($product->id, [
                    'quantity' => (int)$productData['quantity'],
                    'price' => (float)$productData['price'],
                ]);
            }
        }

        // Reset UI after saving
        $this->resetUI();

        session()->flash('message', 'Compra guardada exitosamente.');
    }

    public function editPurchase($purchaseId)
    {
        // Load the purchase details into properties
        if ($purchase = Purchase::find($purchaseId)) {
            $this->editingPurchaseId = $purchaseId;
            $this->payed = $purchase->payed;
            $this->status = $purchase->status;

            $this->emit('show-edit');
        }
    }

    public function showProducts($purchaseId)
    {
        // Load the purchase and its products
        if ($purchase = Purchase::with('products')->find($purchaseId)) {
            // Prepare selected products data
            $this->selectedProducts = $purchase->products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'price' => number_format($product->pivot->price, 2),
                ];
            })->toArray();

            // Emit event to show modal
            $this->emit('show-products');
        }
    }

    public function updatePurchase()
    {
        $this->validate();

        if ($this->editingPurchaseId) {
            Purchase::where('id', $this->editingPurchaseId)->update([
                'payed' => $this->payed,
                'status' => $this->status,
            ]);

            session()->flash('message', 'Compra actualizada exitosamente.');

            // Reset UI after updating
            $this->resetUI();
        }

        // Hide edit modal
        $this->emit('hide-edit');
    }

    public function searchByDate()
    {
        // Validate date inputs
        if ($this->startDate && !$this->endDate) {
            session()->flash('error', "Por favor seleccione una fecha de fin.");
            return;
        }

        if ($this->endDate && !$this->startDate) {
            session()->flash('error', "Por favor seleccione una fecha de inicio.");
            return;
        }

        if ($this->startDate && $this->endDate) {
            // Fetch purchases between selected dates
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
        // Reset all fields
        $this->cost = '';
        $this->payed = '';
        $this->status = '';
        $this->payment_type = '';
        $this->products = [];
        $this->selectedProducts = [];
        $this->startDate = null;
        $this->endDate = null;

        // Reset editing ID
        $this->editingPurchaseId = null;
    }

    public function render()
    {
        // Fetch purchases for display with optional filtering by date
        if ($this->startDate || $this->endDate) {
            return view('livewire.purchase.component', [
                'purchases' => Purchase::whereBetween('created_at', [
                    $this->startDate . " 00:00:00",
                    $this->endDate . " 23:59:59"
                ])
                    ->paginate($this->pagination),
            ]);
        }

        return view('livewire.purchase.component', [
            'purchases' => Purchase::paginate($this->pagination),
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
