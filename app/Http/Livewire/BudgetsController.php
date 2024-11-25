<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use App\Models\Sale;
use Livewire\Component;

class BudgetsController extends Component
{
    public $search = '', $selected_id = 0, $products = [], $total = 0, $iva = 0, $subtotal = 0, $cash = 0, $bs = 0, $change = 0, $currency = 0;
    protected $listeners = ['products', 'edit', 'update'];

    public $messages = [
        'bs.required' => 'Debe colocar un monto',
        'bs.min' => 'Debe colocar un monto superior a 0',
        'bs.numeric' => 'Debe ser un número',
        'cash.required' => 'Debe colocar un monto',
        'cash.min' => 'Debe colocar un monto superior a 0',
        'cash.numeric' => 'Debe ser un número',
        'change.required' => 'Debe colocar un monto',
        'change.min' => 'Debe colocar un monto superior a 0',
        'change.numeric' => 'Debe ser un número',
    ];

    public function rules()
    {
        return [
            'bs' => $this->cash > 0 ? 'nullable' : 'required|min:1|numeric',
            'cash' => $this->bs > 0 ? 'nullable' : 'required|min:1|numeric',
            'change' => $this->change > 0 ? 'required|min:1|numeric' : 'nullable',
        ];
    }

    public function edit(Sale $record)
    {
        $this->selected_id = $record->id;
        $this->currency = Currency::latest()->first()->value;
        $this->cash = $record->cash;
        $this->bs = $record->bs;
        $this->change = $record->change;
        $this->get_products($record);
        $this->emit('show-modal');
    }

    public function update()
    {
        $this->validate();
        $record = Sale::find($this->selected_id);
        $total_payed = $this->total <= $this->cash + ($this->bs / $this->currency) - $this->change;
        $record->update([
            'status' => $total_payed ? 'PAID' : 'PENDING',
            'type' => 'SALE',
            'cash' => $this->cash,
            'bs' => $this->bs,
            'change' => $this->change,
        ]);

        $this->bs = 0;
        $this->cash = 0;
        $this->change = 0;
        $this->emit('close-modal');
    }

    public function products(Sale $record)
    {
        $this->get_products($record);
        $this->emit('show-products');
    }

    public function get_products(Sale $record)
    {
        $this->total = 0;
        $this->subtotal = 0;
        $this->iva = 0;
        $this->products = $record->products->map(function ($detail) {
            $detail->product['quantity'] = $detail->quantity;
            $prev_total = $detail->quantity * $detail->product->price;
            $this->subtotal += $prev_total;
            $this->iva += $prev_total * 0.16;

            return $detail->product;
        });

        $this->total = $this->subtotal + $this->iva;
    }

    public function render()
    {
        $budgets = Sale::where('type', 'BUDGET')
            ->whereHas('client', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(20);

        return view('livewire.budgets', [
            'budgets' => $budgets
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
