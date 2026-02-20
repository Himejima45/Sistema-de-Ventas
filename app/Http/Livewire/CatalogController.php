<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class CatalogController extends Component
{
    public $cart = [], $showCart = false, $total = 0, $filter = '', $showFilter = false, $total_items = 0, $exchange_rate;
    public $category_id = '', $provider_id = '', $priceMin = null, $priceMax = null, $quantity = 0;
    public $categories = [], $providers = [];
    protected $listeners = [
        'addToCart',
        'toggle',
        'removeFromCart',
        'clear',
        'save',
        'clearFilters',
        'toggleFilter',
        'toggleCart' => 'toggle'
    ];

    public function updatedQuantity($value)
    {
        if ($value > 1000) {
            $this->quantity = 1000;
        }
    }
    public function updatedPriceMin($value)
    {
        if ($value < 0) {
            $this->priceMin = null;
        }
    }

    public function updatedPriceMax($value)
    {
        if ($value < $this->priceMin) {
            $this->priceMax = $this->priceMin + 1;
        }

        if ($value < 0) {
            $this->priceMax = null;
        }
    }

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        $this->calculate();
        $this->showFilter = false;
    }

    public function toggle()
    {
        $this->showCart = !$this->showCart;
        $this->showFilter = false;
        $this->emit('$refresh');
    }

    public function toggleFilter()
    {
        $this->showFilter = !$this->showFilter;
        $this->showCart = false;
        $this->emit('$refresh');
    }

    public function clear()
    {
        $this->cart = [];
        session()->put('cart', $this->cart);
        $this->total_items = 0;
        $this->showCart = false;
        $this->emit('cartUpdated');
        $this->emit('$refresh');
        $this->emit('record-deleted', "Se removieron todos los productos del carrito");
    }

    public function clearFilters()
    {
        $this->category_id = '';
        $this->provider_id = '';
        $this->priceMin = 0;
        $this->priceMax = 0;
        $this->quantity = 0;
    }

    public function save()
    {
        $admin_id = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('reference', 'admin');
        })->first()->id;

        foreach ($this->cart as $key => $item) {
            $product = Product::find($key);

            if ($product->stock === 0) {
                unset($this->cart[$key]);
                \Cart::session(auth()->user()->id)->remove($key);
                $this->calculate();
                $this->emit('not-found', "El producto $product->name no tiene stock, será removido del carrito automáticamente");
                return;
            }

            if ($product->stock < $item) {
                $this->emit('not-found', "El producto $product->name no tiene suficiente stock, disponible: $product->stock");
                return;
            }
        }

        $currency = Currency::latest()->first();

        if (empty($currency)) {
            Artisan::call('app:fetch-dollar-rate');

            $currency = Currency::latest()->first();
        }

        $sale = Sale::create([
            'total' => $this->total,
            'cash' => 0,
            'bs' => 0,
            'change' => 0,
            'status' => 'PENDING',
            'type' => 'CART',
            'client_id' => auth()->id(),
            'user_id' => $admin_id,
            'currency_id' => $currency->id
        ]);

        $value = 0;
        foreach ($this->cart as $key => $item) {
            $product = Product::find($key);
            $value += $product->price * $item;

            SaleDetails::create([
                'price' => $product->price,
                'quantity' => $item,
                'product_id' => $product->id,
                'sale_id' => $sale->id
            ]);
        }

        $employees = User::select('id')
            ->whereHas('roles', function ($query) {
                $query->where('reference', 'employee')
                    ->orWhere('reference', 'admin');
            })
            ->get();

        $client_name = auth('')->user()->full_name;
        $products = count($this->cart);

        foreach ($employees as $employee) {
            Notification::create([
                'title' => 'Carrito registrado',
                'description' => "El cliente ($client_name) ha registrado un nuevo carrito de ($products) productos por un valor de $$value",
                'employee_id' => $employee->id,
            ]);
        }

        session()->flash('cart-finished', 'Su pedido ha sido registrado, por favor póngase en contacto por whatsapp');
        $this->cart = [];
        session()->put('cart', $this->cart);
        $this->total_items = 0;
        $this->showCart = false;
        $this->redirect('/historial');
    }

    public function calculate()
    {
        $this->total_items = 0;
        $this->total = 0;

        foreach ($this->cart as $key => $item) {
            $product = Product::find($key);
            $this->total += $product->price * $item;
            $this->total_items += $item;
        }
    }

    public function addToCart($productId)
    {
        $product = Product::select('stock')->find($productId);
        if (!$product || $product->stock <= 0) {
            $this->emit('not-found', "El producto $product->name no tiene stock");

            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId] > $product->stock) {
                $this->cart[$productId] = $product->stock;
            }
            $this->cart[$productId]++;
        } else {
            $this->cart[$productId] = 1;
        }

        session()->put('cart', $this->cart);

        $this->calculate();
        $this->emit('cartUpdated');
        $this->emit('$refresh');

        if ($this->cart[$productId] === 1) {
            $this->emit('record-created', "Se añadió el producto $product->name al carrito");
        }
    }

    public function removeFromCart($productId)
    {
        $product = Product::select('stock')->find($productId);
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]--;

            if ($this->cart[$productId] <= 0) {
                unset($this->cart[$productId]);
                $this->emit('record-deleted', "Se removió el producto $product->name del carrito");
            }

            session()->put('cart', $this->cart);

            $this->calculate();
            $this->emit('cartUpdated');
            $this->emit('$refresh');
        }
    }

    public function resetUI()
    {
        $this->total = 0;
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
            ->when($this->priceMin > 0, function ($query) {
                $price_min = number_format($this->priceMin / $this->exchange_rate, 2);
                $query->where('price', '>=', $price_min);
            })
            ->when($this->priceMax > 0, function ($query) {
                $price_max = number_format($this->priceMax / $this->exchange_rate, 2);
                $query->where('price', '<=', $price_max);
            })
            ->paginate(20);

        $this->categories = Category::all(['id', 'name']);
        $this->providers = Provider::all(['id', 'name']);
        $this->exchange_rate = Currency::latest()->first()->value;

        return view('livewire.catalog', [
            'products' => $products,
            'cart' => $this->cart,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
