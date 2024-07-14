<?php

namespace App\Http\Livewire;

use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Denomination;
use Livewire\Component;
use App\Models\Product;
use DB;

class PosController extends Component
{
    public $total, $itemsQuantity, $efectivo, $change;

    public function mount()
    {
        $this->total = Cart::getTotal();
        $this->efectivo = 0;
        $this->change = 0;
        $this->itemsQuantity = Cart::getTotalQuantity();
    }

    public function render()
    {
        $this->denominations = Denomination::all();
        return view('livewire.pos.component',[ 
        'denominations' => Denomination::orderBy('value','desc')->get(),
        'cart'=> Cart::getContent()->sortBy('name')
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function ACash($value)
    {
        $this->efectivo += ($value == 0 ?  $this->total : $value);
        $this->change = ($this->efectivo - $this->total);                                                                                                                                                                                                                                                                                                                                                                            
    }

    protected $listeners = [
        'scan-code' => 'ScanCode',
        'removeItem' => 'removeItem',
        'clearCart',
        'saveSale' => 'saveSale'
    ] ;

    public function ScanCode($barcode, $cant = 1)
    {
        $product = Product::where('barcode', $barcode)->first();

        if (!isset($product)) 
        {
            $this->emit('scan-notfound', 'El producto no esta registrado');
        } else {

            if($this->InCart($product->id))
            {
                $this->increaseQty($product->id);
                return;
            }

            if($product->stock <1)
            {
                $this->emit('no-stock','Stock insuficiente :/');
                return;
            }

            Cart::add($product->id, $product->name, $product->price, $cant, $product->image);
            $this->total = Cart::getTotal();

            $this->emit('scan-ok', 'Producto agregado');
        }
    }

    public function InCart($productId)
    {
        $exist = Cart::get($productId);
        if ($exist)
          return true;
        else
          return false;
    }

    public function increaseQty($productId, $cant = 1)
    {
        $title='';
        $product = Product::find($productId);
        $exist = Cart::get($productId);
        if($exist)
           $title = 'Cantidad Actualizada';
        else
           $title = 'Producto Agregado';

        if($exist)
        {
            if($product->stock < ($cant + $exist->quantity))
            {
                $this->emit('no-stock', 'Stock insuficiente');
                return;
            }
        }  
        
        Cart::add($product->id, $product->name, $product->price, $cant, $product->image);

        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        
        $this->emit('scan-ok', $title);

    }

    public function updateQty($productId, $cant = 1)
    {
        $title ='';
        $product = Product::find($productId);
        $exist = Cart::get($productId);
        if($exist)
           $title = 'Cantidad Actualizada';
        else
           $title = 'Producto Agregado';

        if($exist)
        {
            if($product->stock < $cant)
            {
                $this->emit('no-stock', 'Stock insuficiente :/');
                return;
            }
        }

        // $this->removeItem($productId);

        if($cant > 0 )
        {
            Cart::add($product->id, $product->name, $product->price, $cant, $product->image);
            
            $this->total = Cart::getTotal();
            $this->itemsQuantity = Cart::getTotalQuantity();
        
            $this->emit('scan-ok', $title);

        }

        
    }

    public function removeItem($productId)
    {
        Cart::remove($productId);

        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
    
        $this->emit('scan-ok', 'Producto Eliminado');

    }

    public function decreaseQty($productId)
    {
        $item = Cart::get($productId); 
        Cart::remove($productId);

        $newQty = ($item->quantity) - 1;
        
        if ($newQty > 0 ) 
            Cart::add($item->id, $item->name, $item->price, $newQty, $item->attributes[0]);

        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->emit('scan-ok', 'Cantidad Actualizada');

    }

    public function clearCart()
    {
        Cart::clear();
        $this->efectivo = 0;
        $this->change = 0;
        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();

        $this->emit('scan-ok', 'Carrito vacio');
    }

    public function saveSale()
    {
        if($this->total <=0)
        {
            $this->emit('sale-error', 'AGREGA PRODUCTOS A LA VENTA');
            return;
        }
        if($this->efectivo <=0)
        {
            $this->emit('sale-error', 'INGRESA EL EFECTIVO');
            return;
        }
        if($this->total > $this->efectivo)
        {
            $this->emit('sale-error', 'El EFECTIVO DE SER MAYOR O IGUAL AL TOTAL');
            return;
        }

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'total' => $this->total, 
                'items' => $this->itemsQuantity,
                'cash' => $this->efectivo,
                'change' => $this->change,
                'user_id' => Auth()->user()->id
            ]);

            if($sale)
            {
                $items = Cart::getContent();
                foreach($items as $item){
                    SaleDetail::create([
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'product_id' => $item->id,
                        'sale' => $sale->id
                    ]);

                    //update stock
                    $product = Product::find($item->id);
                    $product->stock = $product->stock - $item->quantity;
                    $product->save();
                }
            }

            DB::commit();

            Cart::clear();
            $this->efectivo = 0;
            $this->change = 0;
            $this->total = Cart::getTotal();
            $this->itemsQuantity = Cart::getTotalQuantity();
            $this->emit('sale-ok', 'Venta Registrada con exito');
            $this->emit('print-ticket', $sale->id);


        } catch (Exception $e) {
            DB::rollback();
            $this->emit('sale-error', $e->getMessage());
        }
    }

    public function printTicket($sale)
    {
        return Redirect::to("print://$sale->id");
    }


}
