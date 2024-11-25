<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use Livewire\Component;

class CartsController extends Component
{
    public $showModal = false, $selected_id = 0, $details = [], $payed = 0, $change = 0;
    protected $listeners = ['edit'];

    public function edit(Sale $record)
    {
        $this->selected_id = $record->id;
        $this->details = $record->products;
        $this->emit('open');
    }

    public function update()
    {
        $record = Sale::find($this->selected_id);
        $record->update([
            'status' => 'PAID',
            'cash' => $this->payed,
            'change' => $this->change,
        ]);

        $this->payed = 0;
        $this->change = 0;
        $this->emit('close');
    }

    public function render()
    {
        $carts = Sale::with('products')
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
