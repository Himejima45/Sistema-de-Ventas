<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class CartsController extends Component
{
    use WithPagination;

    public $showModal = false, $selected_id = 0, $details = [], $payed = 0, $change = 0, $error = '', $selectedProduct, $searchedCode, $selectedSale;
    protected $listeners = ['edit', 'clearMessage', 'zoom', 'showPreview'];

    private $pagination = 20;

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
        $this->payed = $record->cash;
        $this->change = $record->change;
        $this->emit('open');
    }

    public function showPreview($saleId)
    {
        $this->selectedSale = Sale::with(['client', 'products.product', 'user'])->find($saleId);
        $this->emit('show-sale-preview');
    }

    public function update()
    {
        $record = Sale::find($this->selected_id);
        foreach ($record->products as $detail) {
            if ($detail->product->stock < $detail->quantity) {
                $this->error = "El producto {$detail->product->name} no cuenta con suficiente stock para procesar el pedido del cliente {$record->client->name} {$record->client->last_name}";

                $this->emit('close');
                return;
            }
        }

        if ($this->payed === 0 || $this->payed == '') {
            $this->emit('record-warning', 'El monto pagado no puede ser menor a 0 o al total del pedido');
            return;
        }

        if (floatval($record->cash) === 0.0 && floatval($record->bs) === 0.0 && floatval($this->payed) > 0.0) {
            $record->update([
                'user_id' => auth()->id()
            ]);
        }

        $record->update([
            'status' => $this->payed - $this->change >= $record->total ? 'PAID' : 'PENDING',
            'cash' => $this->payed,
            'change' => $this->change,
        ]);

        foreach ($record->products as $detail) {
            $detail->product->update([
                'stock' => $detail->product->stock - $detail->quantity
            ]);
        }

        $this->payed = 0;
        $this->change = 0;
        $this->error = '';
        $this->emit('close');
        $this->emit('record-created', 'Se ha pagado el pedido exitosamente');
    }

    public function render()
    {
        $carts = Sale::with('products')
            ->where('type', 'CART')
            ->where('status', 'PENDING')
            ->when($this->searchedCode, function ($query) {
                $query->where('code', 'like', "%$this->searchedCode%");
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.carts', [
            'carts' => $carts
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function zoom(Product $product)
    {
        $this->selectedProduct = $product;
        $this->emit('show-product-zoomed');
    }
}