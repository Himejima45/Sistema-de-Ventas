<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Provider;
use DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $name, $barcode, $cost, $price, $stock, $min_stock, $provider_id, $category_id, $search, $image, $selected_id, $pageTitle, $componentName, $warranty, $selectedProduct;
    private $pagination = 20;

    public $rules = [
        'name' => [
            'required',
            'min:2',
            'max:120',
            'regex:/^[\p{L}\p{N}\s\-\/\.\(\)\+°&"]+$/u',
            'unique:products,name'
        ],
        'barcode' => ['required', 'numeric', 'digits_between:3,20', 'unique:products,barcode'],
        'cost' => ['required', 'min:1', 'max:10000', 'numeric'],
        'price' => ['required', 'min:1', 'max:10000', 'numeric'],
        'stock' => ['required', 'min:0', 'max:100000', 'numeric'],
        'warranty' => ['required', 'min:1', 'max:100', 'numeric'],
        'min_stock' => ['required', 'min:1', 'max:100', 'numeric'],
        'image' => ['required', 'mimes:jpg,jpeg,png', 'max:2048', 'image', 'unique:products,image'],
        'category_id' => ['required', 'not_in:0,Elegir'],
        'provider_id' => ['nullable']
    ];

    protected $validationAttributes = [
        'name' => 'nombre',
        'barcode' => 'código de barras',
        'cost' => 'coste de compra',
        'price' => 'precio de venta',
        'stock' => 'stock',
        'warranty' => 'garantía',
        'min_stock' => 'stock mínimo',
        'image' => 'imágen',
        'category_id' => 'categoría',
        'provider_id' => 'proveedor',
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
                ->leftJoin('providers as p', 'p.id', 'products.provider_id')
                ->select('products.*', 'c.name as category', 'p.name as provider_name')
                ->where('products.name', 'like', '%' . $this->search . '%')
                ->orWhere('products.barcode', 'like', '%' . $this->search . '%')
                ->orWhere('c.name', 'like', '%' . $this->search . '%')
                ->orWhere('p.name', 'like', '%' . $this->search . '%')
                ->orderBy('products.created_at', 'desc')
                ->paginate($this->pagination);
        else
            $products = Product::join('categories as c', 'c.id', 'products.category_id')
                ->leftJoin('providers as p', 'p.id', 'products.provider_id')
                ->select('products.*', 'c.name as category', 'p.name as provider_name')
                ->orderBy('products.created_at', 'desc')
                ->paginate($this->pagination);

        return view('livewire.products.component', [
            'data' => $products,
            'categories' => Category::orderBy('name', 'asc')->get(),
            'providers' => Provider::orderBy('name', 'asc')->get(),
            'total_cost' => Product::where('stock', '>', 0)->sum(DB::raw('cost * stock'))
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
                if ($this->category_id == 'Elegir' || !Category::find($this->category_id)) {
                    $validator->errors()->add('category_id', 'La categoría seleccionada no existe');
                }

                // Only check provider if it's not "Elegir" and not null
                if ($this->provider_id != 'Elegir' && $this->provider_id && !Provider::find($this->provider_id)) {
                    $validator->errors()->add('provider_id', 'El proveedor seleccionado no existe');
                }
            });
        })->validate();

        $data = $this->validate();

        // Convert "Elegir" to null for provider
        if ($data['provider_id'] == 'Elegir') {
            $data['provider_id'] = null;
        }

        $img_url = '';
        if ($this->image) {
            $img_url = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $img_url);
        }

        $data = array_merge($data, ['image' => $img_url]);
        Product::create($data);

        $this->resetUI();
        $this->emit('record-created', 'Producto Registrado');
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
        $this->provider_id = $product->provider_id ?: 'Elegir';
        $this->image = $product->getImagenAttribute();
        $this->emit('modal-show', 'show modal!');
    }

    public function Update()
    {
        $rules = [
            'name' => [
                'required',
                'min:2',
                'max:120',
                'regex:/^[\p{L}\p{N}\s\-\/\.\(\)\+°&"]+$/u',
                "unique:products,name,{$this->selected_id}"
            ],
            'barcode' => [
                'required',
                'numeric',
                'digits_between:3,20',
                "unique:products,barcode,{$this->selected_id}"
            ],
            'cost' => ['required', 'min:1', 'max:10000', 'numeric'],
            'price' => ['required', 'min:1', 'max:10000', 'numeric'],
            'stock' => ['required', 'min:0', 'max:100000', 'numeric'],
            'warranty' => ['required', 'min:1', 'max:100', 'numeric'],
            'min_stock' => ['required', 'min:1', 'max:100', 'numeric'],
            'category_id' => ['required', 'not_in:0,Elegir'],
            'provider_id' => ['nullable', 'not_in:0']
        ];

        // Handle image validation - if it's a new upload, validate it
        if (is_object($this->image) && !is_string($this->image)) {
            $rules['image'] = ['required', 'mimes:jpg,jpeg,png', 'max:2048', 'image'];
        } else {
            $rules['image'] = ['nullable'];
        }

        $data = $this->validate($rules);

        // Convert "Elegir" to null for provider
        if ($data['provider_id'] == 'Elegir') {
            $data['provider_id'] = null;
        }

        $product = Product::find($this->selected_id);

        // Check if a new image was uploaded
        if (is_object($this->image) && !is_string($this->image)) {
            // Delete old image if exists
            if ($product->image) {
                $oldImagePath = 'public/products/' . $product->image;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            // Upload new image
            $img_url = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $img_url);
            $data['image'] = $img_url;
        } else {
            // Keep the existing image
            unset($data['image']);
        }

        $product->update($data);

        $this->resetUI();
        $this->emit('record-updated', 'Producto Actualizado');
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

    protected $listeners = ['Destroy' => 'delete', 'zoom'];

    public function zoom(Product $product)
    {
        $this->selectedProduct = $product;
        $this->emit('show-product-zoomed');
    }

    public function delete(Product $product)
    {
        $imageTemp = $product->image;
        $product->delete();

        if ($imageTemp != null) {
            if (file_exists('storage/products/' . $imageTemp)) {
                unlink('storage/products/' . $imageTemp);
            }
        }

        $this->resetUI();
    }
}