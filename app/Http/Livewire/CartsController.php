<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use Livewire\Component;

class CartsController extends Component
{
    public $showModal = false, $selected_id = 0, $details = [], $payed = 0, $change = 0, $error = '';
    protected $listeners = ['edit', 'clearMessage'];

    public function clearMessage()
    {
        $this->error = '';
    }

    public function edit(Sale $record)
    {
        $this->selected_id = $record->id;
        $this->details = $record->products;
        $this->emit('open');
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

        $record->update([
            'status' => 'PAID',
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
    }

    public function render()
    {
        $carts = Sale::with('products')
            ->where('type', 'CART')
            ->where('status', 'PENDING')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.carts', [
            'carts' => $carts
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
