<?php

namespace App\Http\Livewire;

use App\Models\Client;
use App\Models\Currency;
use App\Models\SaleDetails;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PosController extends Component
{
    public $subtotal, $iva, $total, $barcode, $currency, $itemsQuantity, $efectivo, $change, $totalPayed, $client, $cart, $bs, $user, $currency_id, $clients, $type, $prevBs, $prevEfectivo, $total_dollar;

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
        $this->client = '';
        $this->currency = $last_currency;
        $this->type = 'Elegir';
        $this->cart = Cart::getContent()->sortBy('name');
        $this->clients = Client::all('id', 'name', 'last_name', 'document');
    }

    public function render()
    {
        is_null($this->currency)
            ? $this->redirect('/currencies')
            : $this->currency = is_string($this->currency)
            ? $this->currency
            : $this->currency->value;
        // $this->denominations = Denomination::all();
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
        'clearPayment'
    ];

    public function selectClient($id)
    {
        $client = Client::find($id);
        $this->client = $client->id;
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
        $product = Product::where('barcode', $barcode)->first();

        if (is_null($product)) {
            session()->flash('scan', "No hay productos registrados con el código de barras: $barcode");
            return null;
        }

        if ($product->stock === 0) {
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
            Cart::update($productId, ['quantity' => [
                'relative' => false,
                'value' => $quantity
            ]]);
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

        if (!is_null($item)) {
        }

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

        // if ($this->total <= 0) {
        //     $this->emit('sale-error', 'AGREGA PRODUCTOS A LA VENTA');
        //     return;
        // }
        // if ($this->efectivo <= 0) {
        //     $this->emit('sale-error', 'INGRESA EL EFECTIVO');
        //     return;
        // }
        // if ($this->total > $this->efectivo) {
        //     $this->emit('sale-error', 'El EFECTIVO DE SER MAYOR O IGUAL AL TOTAL');
        //     return;
        // }

        // if ($this->type !== 'PAID' && $this->type !== 'PENDING') {
        //     $this->validateOnly('type', [
        //         'required',
        //         'not_in:Elegir',
        //         'in:PAID,PENDING'
        //     ]);
        //     $this->emit('sale-error', 'Debe seleccionar el estado de la venta');
        //     return;
        // }



        // DB::beginTransaction();

        $messages = [
            'total.required' => 'El monto es requerido',
            'total.min' => 'El monto debe ser al menos 1',
        ];

        $rules = [
            'total' => [
                'required',
                'min:1',
                'numeric',
            ],
            'efectivo' =>
            $this->bs > 0 ? 'nullable' :
                [
                    'required',
                    'min:1',
                    'numeric'
                ],
            'bs' => $this->efectivo > 0 ? 'nullable' :
                [
                    'required',
                    'min:1',
                    'numeric'
                ],
            'type' => [
                'required',
                'not_in:Elegir',
                'in:PAID,PENDING'
            ],
            'currency_id' => [
                'required',
                'numeric'
            ],
            'client' => ['required', 'exists:clients,id']
        ];

        $this->validate($rules, $messages);

        try {
            // ! TODO #4
            $sale = Sale::create([
                'total' => $this->total,
                'cash' => $this->efectivo ?? 0,
                'bs' => $this->bs ?? 0,
                'change' => $this->change,
                'status' => $this->type,
                'client_id' => $this->client,
                'user_id' => $this->user,
                'currency_id' => $this->currency_id
            ]);


            if ($sale) {
                $items = Cart::getContent();
                foreach ($items as $item) {
                    SaleDetails::create([
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'product_id' => $item->id,
                        'sale_id' => $sale->id
                    ]);

                    //update stock
                    $product = Product::find($item->id);
                    $product->stock = $product->stock - $item->quantity;
                    $product->save();
                }
            }

            // DB::commit();
            $this->resetUI();

            $this->emit('sale-ok', 'Venta Registrada con exito');
            $this->emit('print-ticket', $sale->id);
        } catch (Exception $e) {
            // DB::rollback();
            $this->emit('sale-error', $e->getMessage());
        }
    }

    public function resetUI()
    {
        Cart::clear();
        $this->efectivo = null;
        $this->bs = null;
        $this->change = 0;
        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->client = 'Elegir';
        $this->type = 'Elegir';
    }

    // ! TODO 5
    public function printTicket($sale)
    {
        return Redirect::to("print://$sale->id");
    }
}
