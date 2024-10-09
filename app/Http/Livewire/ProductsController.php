<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use File;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $name, $barcode, $cost, $price, $stock, $min_stock, $provider_id, $category_id, $search, $image, $selected_id, $pageTitle, $componentName, $warranty;
    private $pagination = 5;

    public $rules = [
        'name' => [
            'required',
            'min:2',
            'max:120',
            'regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/',
            'unique:products,name'
        ],
        'barcode' => ['required', 'numeric', 'digits_between:3,20', 'unique:products,barcode'],
        'cost' => ['required', 'min:1', 'max:100', 'numeric'],
        'price' => ['required', 'min:1', 'max:100', 'numeric'],
        'stock' => ['required', 'min:1', 'max:100000', 'numeric'],
        'warranty' => ['required', 'min:1', 'max:100', 'numeric'],
        'min_stock' => ['required', 'min:1', 'max:100', 'numeric'],
        'image' => ['required', 'mimes:jpg,jpeg,png', 'max:2048', 'image', 'unique:products,image'],
        'category_id' => ['required', 'not_in:0,Elegir'],
        'provider_id' => ['required', 'not_in:0,Elegir']
    ];

    // ! TODO 10
    public $messages = [
        'name.required' => 'El monto es requerido',
        'name.min' => 'El nombre debe contener al menos 2 letras',
        'provider_id.not_in' => 'Debe seleccionar un proveedor de la lista'
    ];

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Productos';
        $this->category_id = 'Elegir';
        $this->provider_id = 'Elegir';
    }

    public function render()
    {
        if (strlen($this->search) > 0)

            $products = Product::join('categories as c', 'c.id', 'products.category_id')
                ->select('products.*', 'c.name as category')
                ->where('products.name', 'like', '%' . $this->search . '%')
                ->orWhere('products.barcode', 'like', '%' . $this->search . '%')
                ->orWhere('c.name', 'like', '%' . $this->search . '%')
                ->orderBy('products.name', 'asc')
                ->paginate($this->pagination);
        else
            $products = Product::join('categories as c', 'c.id', 'products.category_id')
                ->select('products.*', 'c.name as category')
                ->orderBy('products.name', 'asc')
                ->paginate($this->pagination);




        return view('livewire.products.component', [
            'data' => $products,
            'categories' => Category::orderBy('name', 'asc')->get(),
            'providers' => Provider::orderBy('name', 'asc')->get()
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function setCategory($value)
    {
        $this->category_id = $value;
    }

    public function setProvider($value)
    {
        $this->provider_id = $value;
    }

    public function Store()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                if (is_null($this->category_id) || !Category::find($this->category_id)->exists()) {
                    $validator->errors()->add('category_id', 'La categoria seleccionada no existe');
                }

                if ($this->provider_id !== 'Elegir' && !Provider::find($this->provider_id)->exists()) {
                    $validator->errors()->add('provider_id', 'El proveedor seleccionado no existe');
                }
            });
        })->validate();

        $data = $this->validate();

        $img_url = '';
        if ($this->image) {
            $img_url = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $img_url);
        }

        $data = array_merge($data, ['image' => $img_url]);
        Product::create($data);

        $this->resetUI();
        $this->emit('product-added', 'Producto Registrado');
    }

    public function Edit(Product $product)
    {
        $this->selected_id = $product->id;
        $this->name = $product->name;
        $this->barcode = $product->barcode;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->warranty = $product->warranty;
        $this->min_stock = $product->min_stock;
        $this->category_id = $product->category_id;
        $this->provider_id = $product->provider_id;
        $this->image = $product->getImagenAttribute();
        $this->emit('modal-show', 'show modal!');
    }

    public function Update()
    {
        $rules = array_merge(
            $this->rules,
            [
                'name' => [
                    'required',
                    'min:2',
                    'max:120',
                    'regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/',
                    "unique:products,name,{$this->selected_id}"
                ],
                'barcode' => [
                    'required',
                    'numeric',
                    'digits_between:3,20',
                    "unique:products,barcode,{$this->selected_id}"
                ],
                'image' => [
                    is_string($this->image) ? [
                        'required',
                        'mimes:jpg,jpeg,png',
                        'max:2048',
                        'image',
                        "unique:products,image,{$this->selected_id}"
                    ] : [
                        'nullable'
                    ]
                ]
            ]
        );

        $data = $this->validate($rules);
        $product = Product::find($this->selected_id);
        if ($this->image !== $product->getImagenAttribute()) {
            $path = $product->getImagenAttribute();
            Storage::delete("/products/$path");

            $img_url = '';
            $img_url = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $img_url);
            $data['image'] = $img_url;
        }

        $product->update($data);

        $this->resetUI();
        $this->emit('product-updated', 'Producto Actulizado');
    }
    public function resetUI()
    {

        $this->name = '';
        $this->warranty = '';
        $this->barcode = '';
        $this->cost = '';
        $this->price = '';
        $this->stock = '';
        $this->min_stock = '';
        $this->category_id = 'Elegir';
        $this->provider_id = 'Elegir';
        $this->image = null;
        $this->search = '';
        $this->selected_id = 0;
    }

    protected $listeners = ['Destroy'];

    public function Destroy(Product $product)
    {
        $imageTemp = $product->image;
        $product->delete();


        if ($imageTemp != null) {
            if (file_exists('storage/products/' . $imageTemp)) {
                unlink('storage/products/' . $imageTemp);
            }
        }

        $this->resetUI();
        $this->emit('product-deleted', 'Producto Eliminada');
    }
}
