<div class="position-relative col sales layout-top-spacing gap-4">
    <div class="d-flex">
        <h2>Carrito</h2>
        <button wire:click="toggle" class="btn btn-ghost ml-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-shopping-cart">
                <circle cx="8" cy="21" r="1" />
                <circle cx="19" cy="21" r="1" />
                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
            </svg>
            {{ $total_items > 0 ? $total_items : '' }}
        </button>

        <button type="button" class="btn btn-ghost dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false" data-bs-auto-close="inside">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-filter">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
            </svg>
        </button>
        <form class="dropdown-menu p-4" style="max-width: 18rem" wire:ignore>
            <button type="button" id="clear_filters" wire:click="clearFilters" class="btn btn-danger"><svg
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-eraser">
                    <path d="m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" />
                    <path d="M22 21H7" />
                    <path d="m5 11 9 9" />
                </svg></button>
            <div class="form-group">
                <label>Categoria</label>
                <select name="category" wire:model.lazy='category_id' class="form-control">
                    <option value="" selected>Elegir</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"> {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('categoryid')
                    <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Proveedor</label>
                <select name="provider" wire:model.lazy='provider_id' class="form-control">
                    <option value="" selected>Elegir</option>
                    @foreach ($providers as $provider)
                        <option value="{{ $provider->id }}"> {{ $provider->name }}</option>
                    @endforeach
                </select>
                @error('categoryid')
                    <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Precio mín</label>
                        <input type="number" wire:model="priceMin" class="form-control" placeholder="Ej: 10"
                            max="{{ $priceMax - 1 }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Precio máx</label>
                        <input type="number" wire:model="priceMax" class="form-control" placeholder="Ej: 10"
                            min="{{ $priceMin + 1 }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Cantidad mín</label>
                <input type="number" wire:model="quantity" class="form-control" placeholder="Ej: 10" min="1"
                    max="1000">
            </div>
        </form>

    </div>
    @if ($showCart)
        <div id="cart-drawer" class="drawer show">
            <div class="drawer-content">
                <div class="mb-3 d-flex justify-content-between">
                    <h3>Mi Carrito</h3>
                    @if (count($cart) > 0)
                    <button wire:click="clear" class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-eraser">
                            <path
                                d="m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" />
                            <path d="M22 21H7" />
                            <path d="m5 11 9 9" />
                        </svg></button>
                        @endif
                </div>
                @if (count($cart) > 0)
                    <div class="container">
                        @foreach ($cart as $productId => $quantity)
                            @php
                                $product = \App\Models\Product::find($productId);
                            @endphp
                            <div class="row mb-1">
                                <div class="col-2">
                                    <img src="{{ $product->getImage() }}" width="40"
                                        height="40"="Product image">
                                </div>
                                <div class="col-6">
                                    <p class="align-self-center mb-0">
                                        {{ $product->name }}
                                    </p>
                                    <small>Disponible {{ $product->stock }} | Precio {{ $product->price }}$</small>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <button class="btn btn-sm"
                                            wire:click="addToCart({{ $product->id }})">+</button>
                                        <p class="align-self-center mx-2 mb-0">{{ $quantity }}</p>
                                        <button class="btn btn-sm"
                                            wire:click="removeFromCart({{ $product->id }})">-</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="row mt-5">
                            <div class="col-12">
                                <h4>Monto a pagar</h4>
                            </div>
                            <div class="col">Subtotal</div>
                            <div class="col">$ {{ $subtotal }}</div>
                            <div class="w-100"></div>
                            <div class="col">IVA</div>
                            <div class="col">$ {{ $iva }}</div>
                            <div class="w-100"></div>
                            <div class="col">Total</div>
                            <div class="col">$ {{ $total }}</div>
                        </div>
                    </div>
                @else
                    <p>Su carrito está vacio.</p>
                @endif
                <div class="row">
                    <div class="col">

                        <button wire:click="toggle" class="mt-5 btn btn-block btn-secondary">Cerrar</button>
                    </div>
                    @if (count($cart) > 0)
                        
                    <div class="col">

                        <button wire:click="save" class="mt-5 btn btn-block btn-primary">Guardar</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="widget-content">
        <div class="row mx-auto">
            @foreach ($products as $product)
                <div class="card m-2" style="width: 14rem;">
                    <img class="card-img-top" height="140" src="{{ $product->getImage() }}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <small class="-mt-4">En inventario {{ $product->stock }} | {{ $product->price }}$</small>
                        <p class="card-text">
                            <span class="badge badge-secondary">{{ $product->category->name }}</span>
                        </p>
                        <button class="btn btn-block btn-primary" wire:click="addToCart({{ $product->id }})">
                            Añadir al carrito
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $products->links('pagination::bootstrap-4') }}
    </div>

</div>

<style>
    .drawer {
        position: absolute;
        top: -17px;
        right: -300px;
        width: 500px;
        height: 100%;
        min-height: 91vh;
        background-color: white;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.5);
        transition: right 0.3s ease;
        z-index: 1000;
    }

    .drawer-content {
        padding: 20px;
    }

    .drawer.show {
        right: -17px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clearFiltersBtn = document.querySelector('#clear_filters')
        const dropdownMenu = document.querySelector('form.dropdown-menu');
        const dropdownMenuElements = document.querySelectorAll(
            '.dropdown-menu, .dropdown-menu input, .dropdown-menu label, .dropdown-menu select'
        );

        document.addEventListener('click', function(e) {
            if (!dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        dropdownMenuElements.forEach(function(element) {
            element.addEventListener('click', function(e) {
                if (['DIV', 'FORM', 'BUTTON'].includes(e.target.tagName)) {
                    dropdownMenu.classList.remove('show');
                    return;
                }

                e.stopPropagation();
            });
        });
    });
</script>
