<div class="position-relative col sales layout-top-spacing">
    <x-home_button />

    <div class="row" style="flex-direction: column;">
        <h2>Carrito</h2>

        {{-- Filter --}}
        <div class="d-flex">
            <div class="filter-container card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 font-weight-bold text-muted">FILTRAR PRODUCTOS</h6>
                        <div class="d-flex">
                            <button type="button" wire:click="clearFilters" class="btn btn-sm btn-outline-danger mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-eraser mr-1">
                                    <path
                                        d="m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" />
                                    <path d="M22 21H7" />
                                    <path d="m5 11 9 9" />
                                </svg>
                                Limpiar
                            </button>
                            <button type="button" wire:click="toggleFilter" class="btn btn-sm btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-filter mr-1">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                                </svg>
                                {{ $showFilter ? 'Ocultar' : 'Mostrar' }}
                            </button>
                        </div>
                    </div>

                    @if($showFilter)
                        <div class="row">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted">CATEGORÍA</label>
                                    <select name="category" wire:model.lazy='category_id'
                                        class="form-control form-control-sm">
                                        <option value="" selected>Todas las categorías</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted">PROVEEDOR</label>
                                    <select name="provider" wire:model.lazy='provider_id'
                                        class="form-control form-control-sm">
                                        <option value="" selected>Todos los proveedores</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted d-block mb-2">RANGO DE PRECIO</label>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 mr-2">
                                            <label class="sr-only">Precio mínimo</label>
                                            <input type="number" wire:model="priceMin" class="form-control form-control-sm"
                                                placeholder="Mínimo" min="0" max="{{ $priceMax - 1 }}"
                                                aria-label="Precio mínimo">
                                        </div>
                                        <span class="text-muted mx-1">—</span>
                                        <div class="flex-grow-1 ml-2">
                                            <label class="sr-only">Precio máximo</label>
                                            <input type="number" wire:model="priceMax" class="form-control form-control-sm"
                                                placeholder="Máximo" min="{{ $priceMin + 1 }}" aria-label="Precio máximo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted">STOCK MÍNIMO</label>
                                    <input type="number" wire:model="quantity" class="form-control form-control-sm"
                                        placeholder="Ej: 10" min="1" max="1000" aria-label="Stock mínimo">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <style>
            .filter-container {
                background: white;
                border-radius: 8px;
                width: 100%;
            }

            .form-control-sm {
                height: calc(1.8125rem + 2px);
                font-size: 0.875rem;
                border-radius: 4px;
            }

            .lucide {
                vertical-align: middle;
            }

            @media (max-width: 767.98px) {

                .filter-container .col-md-3,
                .filter-container .col-md-4,
                .filter-container .col-md-2 {
                    margin-bottom: 1rem;
                }

                .price-range-container {
                    flex-direction: column;
                }

                .price-range-container span {
                    margin: 0.5rem 0;
                    text-align: center;
                }
            }
        </style>

    </div>
    @if ($showCart)
        <div id="cart-drawer" class="drawer show">
            <div class="drawer-content">
                <div class="mb-3 d-flex justify-content-between">
                    <h3>Mi Carrito</h3>
                    @if (count($cart) > 0)
                        <button title="Limpiar" wire:click="clear" class="btn btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-eraser">
                                <path
                                    d="m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" />
                                <path d="M22 21H7" />
                                <path d="m5 11 9 9" />
                            </svg>
                        </button>
                    @endif
                </div>
                @if (count($cart) > 0)
                    <div class="container">
                        @foreach ($cart as $productId => $quantity)
                            @php
                                $product = \App\Models\Product::find($productId);
                            @endphp
                            <div class="row mb-2">
                                <div class="col-4 p-0">
                                    <img src="{{ $product->getImage() }}" width="150" height="150" alt="Product image">
                                </div>
                                <div class="col-8">
                                    <div class="row m-2">
                                        <div class="col p-0">
                                            <p class="h5 mb-0">
                                                {{ $product->name }}
                                            </p>
                                            <p>Disponible {{ $product->stock }}</p>
                                            <p class="h4">Precio {{ $product->price }}$</p>
                                        </div>
                                    </div>
                                    <div class="row m-2 h6">
                                        <button class="btn btn-success" wire:click="addToCart({{ $product->id }})">+</button>
                                        <p class="align-self-center mx-4 mb-0">{{ $quantity }}</p>
                                        <button class="btn btn-danger" wire:click="removeFromCart({{ $product->id }})">-</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="row mt-5 h5">
                            <div class="col-12">
                                <h4>Monto a pagar</h4>
                            </div>
                            <div class="col">Subtotal</div>
                            <div class="col">
                                <p class="h5">$ {{ $subtotal }}</p>
                            </div>
                            <div class="w-100"></div>
                            <div class="col">IVA</div>
                            <div class="col">
                                <p class="h5">$ {{ $iva }}</p>
                            </div>
                            <div class="w-100"></div>
                            <div class="col">Total</div>
                            <div class="col">
                                <p class="h5">$ {{ $total }}</p>
                            </div>
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
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 product-card" wire:click="addToCart({{ $product->id }})"
                        style="cursor: pointer;">
                        <div class="card-img-container position-relative">
                            <img class="card-img-top img-fluid" src="{{ $product->getImage() }}" alt="{{ $product->name }}">
                            <span class="badge badge-pill badge-primary position-absolute" style="top: 10px; right: 10px;">
                                {{ $product->stock }}
                            </span>
                            <div class="add-to-cart-overlay d-md-none">
                                <span class="add-to-cart-text">Añadir al carrito</span>
                            </div>
                            <div class="add-to-cart-overlay d-none d-md-flex">
                                <span class="add-to-cart-text">Añadir al carrito</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column text-center">
                            <h6 class="card-title font-weight-bold mb-1 text-muted">{{ $product->name }}</h6>
                            <span class="font-weight-bold text-primary h4">{{ $product->price }}$</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <style>
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-img-container {
            height: 240px;
            overflow: hidden;
            position: relative;
        }

        .card-img-top {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: opacity 0.3s ease;
        }

        .add-to-cart-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .d-md-none.add-to-cart-overlay {
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .product-card:hover .d-none.d-md-flex.add-to-cart-overlay {
            opacity: 1;
        }

        .add-to-cart-text {
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            padding: 8px 16px;
            border: 2px solid white;
            border-radius: 4px;
        }
    </style>

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
    document.addEventListener('DOMContentLoaded', function () {
        const clearFiltersBtn = document.querySelector('#clear_filters')
        const dropdownMenu = document.querySelector('form.dropdown-menu');
        const dropdownMenuElements = document.querySelectorAll(
            '.dropdown-menu, .dropdown-menu input, .dropdown-menu label, .dropdown-menu select'
        );

        document.addEventListener('click', function (e) {
            if (!dropdownMenu.contains(e.target))
            {
                dropdownMenu.classList.remove('show');
            }
        });

        dropdownMenuElements.forEach(function (element) {
            element.addEventListener('click', function (e) {
                if (['DIV', 'FORM', 'BUTTON'].includes(e.target.tagName))
                {
                    dropdownMenu.classList.remove('show');
                    return;
                }

                e.stopPropagation();
            });
        });
    });
</script>