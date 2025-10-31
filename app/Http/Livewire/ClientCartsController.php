<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use App\Models\SaleDetails;
use Livewire\Component;

class ClientCartsController extends Component
{
    protected $listeners = ['delete'];

    public function delete(Sale $record)
    {
        $this->restoreStock($record);

        $record->delete();
        $this->emit('record-deleted', 'Se ha cancelado la ordens');
    }

    protected function restoreStock(Sale $sale)
    {
        $saleDetails = SaleDetails::with('product')
            ->where('sale_id', $sale->id)
            ->get();

        foreach ($saleDetails as $detail) {
            if ($detail->product) {
                $detail->product->increment('stock', $detail->quantity);
            }
        }
    }

    public function render()
    {
        $carts = Sale::with(['products.product'])
            ->where('client_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.historial', [
            'carts' => $carts
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}