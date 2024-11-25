<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use Livewire\Component;

class ClientCartsController extends Component
{
    protected $listeners = ['delete'];

    public function delete(Sale $record)
    {
        $record->delete();
    }

    public function render()
    {
        $carts = Sale::with('products')
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
