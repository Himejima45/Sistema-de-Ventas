<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use App\Models\SaleDetails;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class PosController extends Component
{
    public $subtotal, $sale_type = 'SALE', $iva, $total, $barcode, $currency, $itemsQuantity, $efectivo, $change, $totalPayed, $client, $cart, $bs, $user, $currency_id, $clients, $type, $prevBs, $prevEfectivo, $total_dollar;

    public function mount()
    {
        $this->user = Auth::user()->id;
        $last_currency = Currency::latest('created_at')->first();
        $this->currency_id = $last_currency->id ?? 0;
        $this->subtotal = Cart::getTotal();
        $this->iva = $this->subtotal * 0.16;
        $this->total = $this->subtotal + $this->iva;
        $this->efectivo = null;
        $this->bs = null;
        $this->change = 0;
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->totalPayed = 0;
        $this->client = User::where('document', '999999999')->first()->id ?? '';
        $this->currency = $last_currency;
        $this->type = 'Elegir';
        $this->cart = Cart::getContent()->sortBy('name');
        $this->clients = User::whereHas('roles', function ($query) {
            $query->where('name', 'client');
        })->get(['id', 'name', 'last_name', 'document']);
    }

    public function render()
    {
        is_null($this->currency)
            ? $this->redirect('/currencies')
            : $this->currency = is_string($this->currency)
            ? $this->currency
            : $this->currency->value;

        return view('livewire.pos.component', [
            'currency' => Currency::orderBy('value', 'desc')->first(),
            'cart' => $this->cart,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    protected $listeners = [
        'scan-code' => 'ScanCode',
        'removeItem',
        'clearCart',
        'saveSale' => 'saveSale',
        'client-selected' => 'setClient',
        'selectClient',
        'type-selected' => 'setType',
        'updateQty',
        'addPayment',
        'clearPayment',
    ];

    public function selectClient($id)
    {
        $client = User::find($id);
        $this->client = $client->id;
    }

    public function updatedCart($value, $id)
    {
        $id = explode('.', $id)[0];
        $product = Product::select('stock')->find($id);

        if ($product && $product->stock < $value) {
            if (is_array($this->cart)) {
                $this->cart[$id]['quantity'] = $product->stock;
            } else {
                $this->cart = $this->cart->toArray();
                $this->cart[$id]['quantity'] = $product->stock;
            }

            Cart::update($id, ['quantity' => $product->stock]);
        } else {
            if (is_array($this->cart)) {
                $this->cart[$id]['quantity'] = $value;
            } else {
                $this->cart = $this->cart->toArray();
                $this->cart[$id]['quantity'] = $value;
            }

            Cart::update($id, ['quantity' => $value]);
        }
    }



    public function updateCartInfo()
    {
        $this->subtotal = Cart::getTotal();
        $this->iva = $this->subtotal * 0.16;
        $this->total = $this->subtotal + $this->iva;
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->cart = Cart::getContent()->sortBy('name');
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function ScanCode($barcode, $cant = 1)
    {
        $product = Product::where('barcode', $barcode)
            ->orWhere('name', 'like', "%$barcode%")
            ->first();

        if (is_null($product)) {
            session()->flash('scan', "No hay productos registrados con el código de barras: $barcode");
            return null;
        }

        if ($product->stock <= 0) {
            session()->flash('scan', "El producto $product->name no tiene stock");
            return null;
        }

        if (!isset($product)) {
            $this->emit('not-found', $barcode);
        } else {
            $cartItem = Cart::get($product->id);

            if (!$cartItem) {
                Cart::add($product->id, $product->name, $product->price, $cant, [$product->getImage()]);
            } else {
                if ($cartItem['quantity'] <= $product->stock) {
                    $this->increaseQty($product->id);
                }
            }

            $this->total = Cart::getTotal();
            $this->emit('scan-ok', 'Producto agregado');
        }

        $this->updateCartInfo();
    }

    public function InCart($productId)
    {
        $exist = Cart::get($productId);
        return is_null($exist);
    }

    public function increaseQty($productId, $quantity = 1)
    {
        $title = '';
        $product = Product::find($productId);
        $cartItem = Cart::get($productId);

        if ($cartItem) {
            $title = 'Cantidad Actualizada';
            $newQuantity = $cartItem->quantity + $quantity;

            if ($newQuantity > $product->stock) {
                Cart::update($productId, ['qty' => $product->stock]);

                $this->emit('no-stock', 'Stock insuficiente');
            }

            if ($cartItem->quantity + $quantity <= $product->stock) {
                // Cart::update($productId, ['qty' => $newQuantity]);
                Cart::add($product->id, $product->name, $product->price, $quantity, [$product->getImage()]);
            }
        } else {
            Cart::add($product->id, $product->name, $product->price, $quantity, [$product->getImage()]);

            $title = 'Producto Agregado';
        }

        $this->emit('scan-ok', $title);
        $this->updateCartInfo();
        // dd($this->cart[2]);
    }

    public function updateQty($productId, $quantity)
    {
        $product = Product::find($productId);
        $exist = Cart::get($productId);

        if ($exist->quantity === $product->stock) {
            session()->flash('scan', "El stock de $product->name es de $product->stock, no se pueden añadir más a la venta");
            return null;
        }

        if ($quantity + 1 > $product->stock) {
            Cart::add($product->id, $product->name, $product->price, $product->stock);
            return null;
        } else {
            Cart::update($productId, [
                'quantity' => [
                    'relative' => false,
                    'value' => $quantity
                ]
            ]);
        }

        $this->updateCartInfo();
    }

    public function removeItem($productId)
    {
        Cart::remove($productId);
        $this->updateCartInfo();
        $this->emit('scan-ok', 'Producto Eliminado');
    }

    public function addPayment($value, $type)
    {
        if ($this->bs !== '') {
            $this->prevBs = $this->bs;
        }
        if ($this->efectivo !== '') {
            $this->prevEfectivo = $this->efectivo;
        }
        $this->total_dollar = floatval($this->prevBs / $this->currency) + $this->prevEfectivo;
        $type === 'dollar'
            ? $this->efectivo = $value
            : $this->bs = $value;
        // dd($total_dollar, $this->total);

        if ($this->total_dollar === $this->total) {
            $this->change = 0;
        } else {
            $this->change = $this->total_dollar > $this->total
                ? abs($this->total - $this->total_dollar)
                : $this->total_dollar - $this->total;
        }
    }

    public function clearPayment($type)
    {
        $type === 'dollar'
            ? $this->efectivo = 0
            : $this->bs = 0;
        $total_dollar = floatval($this->bs / $this->currency) + $this->efectivo;
        $this->change = $total_dollar > $this->total
            ? abs($this->total - $total_dollar)
            : $total_dollar - $this->total;
    }

    public function decreaseQty($productId)
    {
        $item = Cart::get($productId);
        Cart::remove($productId);

        $newQty = (!is_null($item) ? $item->quantity : 0) - 1;

        if ($newQty > 0) {
            Cart::add($item->id, $item->name, $item->price, $newQty, $item->attributes[0]);
        }

        $this->updateCartInfo();
        $this->emit('scan-ok', 'Cantidad Actualizada');
    }

    public function clearCart()
    {
        $this->resetUI();
        $this->emit('scan-ok', 'Carrito vacio');
    }

    public function saveSale()
    {
        $messages = [
            'total.required' => 'El monto es requerido',
            'total.min' => 'El monto debe ser al menos 1',
            'total.numeric' => 'El monto debe ser un número',
            'efectivo.required' => 'El monto es requerido',
            'efectivo.min' => 'El monto debe ser un numero',
            'efectivo.numeric' => 'El monto debe ser un número',
            'bs.required' => 'El monto es requerido',
            'bs.min' => 'El monto debe ser al menos 1',
            'bs.numeric' => 'El monto debe ser un número',
            'type.required' => 'El tipo es requerido',
            'type.in' => 'La opción seleccionada, debe ser pagada o pendiente',
            'type.not_in' => 'La opción seleccionada, no es valida',
            'currency_id.required' => 'La tasa es requerido',
            'currency_id.min' => 'La tasa debe ser al menos 1',
            'currency_id.numeric' => 'La tasa debe ser un número',
            'client.required' => 'El cliente es requerido',
            'client.exists' => 'Debe estar registrado',
            'sale_type.required' => 'El tipo de venta es requerido',
            'sale_type.in' => 'El tipo de venta debe ser "Presupuesto" o "Venta"',
        ];

        $rules = [
            'total' => [
                'required',
                'min:1',
                'numeric',
            ],
            'efectivo' => [
                $this->bs > 0 ? 'nullable' : (Rule::when($this->sale_type === 'SALE', 'required|min:1|numeric')),
            ],
            'bs' => [
                $this->efectivo > 0 ? 'nullable' : (Rule::when($this->sale_type === 'SALE', 'required|min:1|numeric')),
            ],
            'type' => [
                'sometimes',
                Rule::when($this->sale_type === 'SALE', 'required|not_in:Elegir|in:PAID,PENDING')
            ],
            'currency_id' => [
                'required',
                'numeric'
            ],
            'client' => ['required', 'exists:users,id'],
            'sale_type' => ['required', Rule::in(['SALE', 'BUDGET'])]
        ];

        $this->validate($rules, $messages);

        try {
            $items = Cart::getContent();
            foreach ($items as $item) {
                $product = Product::select(['stock', 'name'])->find($item->id);
                if ($item->quantity > $product->stock) {
                    $rest = $item->quantity - $product->stock;
                    $this->emit('error-modal', "El producto $product->name no cuenta con suficiente inventario para proceder con la venta (En inventario: $product->stock, solicitud: $item->quantity, faltante: $rest)");
                    return;
                }
            }

            // ! TODO #4
            $sale = Sale::create([
                'total' => $this->total,
                'cash' => $this->efectivo == '' ? 0 : $this->efectivo ?? 0,
                'bs' => $this->bs == '' ? 0 : $this->bs ?? 0,
                'change' => $this->change,
                'status' => $this->sale_type === 'BUDGET' ? 'PENDING' : $this->type,
                'client_id' => $this->client,
                'user_id' => $this->user,
                'type' => $this->sale_type,
                'currency_id' => $this->currency_id
            ]);


            if ($sale) {

                foreach ($items as $item) {
                    SaleDetails::create([
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'product_id' => $item->id,
                        'sale_id' => $sale->id
                    ]);

                    $product = Product::find($item->id);
                    $product->stock = $product->stock - $item->quantity;
                    $product->save();
                }
            }

            $this->resetUI();
            $this->emit('record-created', 'Venta Registrada con exito');
            $this->emit('print-ticket', $sale->id);
        } catch (Exception $e) {
            $this->emit('sale-error', $e->getMessage());
        }
    }

    public function resetUI()
    {
        Cart::clear();
        $this->efectivo = null;
        $this->bs = null;
        $this->change = 0;
        $this->subtotal = 0;
        $this->iva = 0;
        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->client = 'Elegir';
        $this->type = 'Elegir';
        $this->sale_type = 'SALE';
    }

    // ! TODO 5
    public function printTicket($sale)
    {
        return Redirect::to("print://$sale->id");
    }
}
