<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\User;
use Livewire\Component;

class CatalogController extends Component
{
    public $cart = [], $showCart = false, $subtotal = 0, $iva = 0, $total = 0, $filter = '', $showFilter = false;
    public $category_id = '', $provider_id = '', $priceMin = 0, $priceMax = 0, $quantity = 0;
    public $categories = [], $providers = [];
    protected $listeners = ['addToCart', 'toggle', 'removeFromCart', 'clear', 'save', 'clearFilters', 'toggleFilter'];

    public function updatedQuantity($value)
    {
        if ($value > 1000) {
            $this->quantity = 1000;
        }
    }
    public function updatedPriceMin($value)
    {
        if ($this->priceMax === 0) {
            $this->priceMax = $value + 1;
        }

        if ($value > $this->priceMax) {
            $this->priceMin = $this->priceMax - 1;
        }

        if ($value < 0) {
            $this->priceMin = 1;
        }
    }

    public function updatedPriceMax($value)
    {
        if ($value < $this->priceMin) {
            $this->priceMax = $this->priceMin + 1;
        }

        if ($value < 0) {
            $this->priceMax = 2;
        }
    }

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        $this->calculate();
    }

    public function toggle()
    {
        $this->showCart = ! $this->showCart;
        $this->showFilter = false;
        $this->emit('$refresh');
    }

    public function toggleFilter()
    {
        $this->showFilter = ! $this->showFilter;
        $this->showCart = false;
        $this->emit('$refresh');
    }

    public function clear()
    {
        $this->cart = [];
        session()->put('cart', $this->cart);
        $this->showCart = false;
        $this->emit('$refresh');
    }

    public function clearFilters()
    {
        $this->category_id = null;
        $this->provider_id = null;
        $this->priceMin = 0;
        $this->priceMax = 0;
        $this->quantity = 0;
    }

    public function save()
    {
        $admin_id = User::where('email', 'admin@email.com')->first()->id;
        $sale = Sale::create([
            'total' => $this->total,
            'cash' => 0,
            'bs' => 0,
            'change' => 0,
            'status' => 'PENDING',
            'type' => 'CART',
            'client_id' => auth()->id(),
            'user_id' => $admin_id,
            'currency_id' => Currency::latest()->first()->id
        ]);

        foreach ($this->cart as $key => $item) {
            $product = Product::find($key);
            SaleDetails::create([
                'price' => $product->price,
                'quantity' => $item,
                'product_id' => $product->id,
                'sale_id' => $sale->id
            ]);
        }

        $this->resetUI();
        $this->emit('$refresh');
    }

    public function calculate()
    {
        foreach ($this->cart as $key => $item) {
            $product = Product::find($key);
            $this->subtotal += $product->price * $item;
        }

        $this->iva = $this->subtotal * 0.16;
        $this->total = $this->subtotal + $this->iva;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product || $product->stock <= 0) {
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]++;
        } else {
            $this->cart[$productId] = 1;
        }

        session()->put('cart', $this->cart);

        $this->calculate();
        $this->emit('$refresh');
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]--;

            if ($this->cart[$productId] <= 0) {
                unset($this->cart[$productId]);
            }

            session()->put('cart', $this->cart);

            $this->calculate();
            $this->emit('$refresh');
        }
    }

    public function resetUI()
    {
        $this->total = 0;
        $this->iva = 0;
        $this->subtotal = 0;
        $this->showCart = false;
        $this->cart = [];
        session()->put('cart', $this->cart);
        $this->clear();
        $this->clearFilters();
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $products = Product::where('stock', '>', 0)
            ->whereNotIn('id', array_keys($this->cart))
            ->when($this->category_id, function ($query) {
                $query->whereHas('category', function ($query) {
                    $query->where('id', $this->category_id);
                });
            })
            ->when($this->provider_id, function ($query) {
                $query->whereHas('provider', function ($query) {
                    $query->where('id', $this->provider_id);
                });
            })
            ->when($this->quantity > 0, function ($query) {
                $query->where('stock', '>=', $this->quantity);
            })
            ->paginate(20);

        $this->categories = Category::all(['id', 'name']);
        $this->providers = Provider::all(['id', 'name']);

        return view('livewire.catalog', [
            'products' => $products,
            'cart' => $this->cart,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
