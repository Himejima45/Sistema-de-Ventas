<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class CartController extends Component
{
    public $cart = [];

    public function mount()
    {
        $this->cart = Session::get('cart', []);
    }

    public function addToCart($productId)
    {
        $this->cart[$productId] = isset($this->cart[$productId]) ? $this->cart[$productId] + 1 : 1;
        Session::put('cart', $this->cart);
        $this->emit('cartUpdated');
    }

    public function render()
    {
        return view('livewire.cart', [
            'cart' => $this->cart,
        ]);
    }
}
