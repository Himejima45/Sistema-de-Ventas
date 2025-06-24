<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CartIcon extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public $total_items = 0;

    public function mount()
    {
        $this->total_items = array_sum(session()->get('cart', []));
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}