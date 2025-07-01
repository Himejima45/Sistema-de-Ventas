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
                <div class="drawer-header">
                    <h3>Mi Carrito</h3>
                    @if (count($cart) > 0)
                        <button title="Limpiar" wire:click="clear" class="btn btn-sm btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-eraser">
                                <path
                                    d="m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" />
                                <path d="M22 21H7" />
                                <path d="m5 11 9 9" />
                            </svg>
                            <span class="d-none d-sm-inline">Limpiar</span>
                        </button>
                    @endif
                </div>

                @if (count($cart) > 0)
                    <div class="drawer-body">
                        @foreach ($cart as $productId => $quantity)
                            @php
                                $product = \App\Models\Product::find($productId);
                            @endphp
                            <div class="cart-item">
                                <div class="cart-item-image">
                                    <img src="{{ $product->getImage() }}" alt="{{ $product->name }}">
                                </div>
                                <div class="cart-item-details">
                                    <h5>{{ $product->name }}</h5>
                                    <p class="stock">Disponible: {{ $product->stock }}</p>
                                    <p class="price">{{ $product->price }}$</p>
                                    <div class="quantity-controls">
                                        <button class="btn btn-sm btn-success" wire:click="addToCart({{ $product->id }})">+</button>
                                        <span class="quantity">{{ $quantity }}</span>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="removeFromCart({{ $product->id }})">-</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="drawer-summary">
                        <h4>Monto a pagar</h4>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>$ {{ $subtotal }}</span>
                        </div>
                        <div class="summary-row">
                            <span>IVA</span>
                            <span>$ {{ $iva }}</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>$ {{ $total }}</span>
                        </div>
                    </div>
                @else
                    <div class="empty-cart">
                        <p>Su carrito está vacío.</p>
                    </div>
                @endif

                <div class="drawer-footer">
                    <button wire:click="toggle" class="btn btn-secondary">Cerrar</button>
                    @if (count($cart) > 0)
                        <button wire:click="save" class="btn btn-primary">Guardar</button>
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
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        height: 100vh;
        background-color: white;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 1050;
        display: flex;
        flex-direction: column;
    }

    .drawer.show {
        right: 0;
    }

    .drawer-content {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .drawer-header {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
    }

    .drawer-header h3 {
        margin: 0;
        font-size: 1.25rem;
    }

    .drawer-body {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
    }

    .cart-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f5f5f5;
    }

    .cart-item-image {
        width: 100px;
        height: 100px;
        flex-shrink: 0;
        margin-right: 1rem;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }

    .cart-item-details {
        flex: 1;
        flex-direction: row;
    }

    .cart-item-details h5 {
        margin-top: 0;
        margin-bottom: 0rem;
        font-size: 1rem;
    }

    .stock {
        color: #666;
        font-size: 0.875rem;
        margin-bottom: 0rem;
    }

    .price {
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
    }

    .quantity-controls button {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity {
        margin: 0 0.75rem;
        min-width: 20px;
        text-align: center;
    }

    .drawer-summary {
        padding: 1rem;
        border-top: 1px solid #eee;
    }

    .drawer-summary h4 {
        margin-bottom: 1rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .summary-row.total {
        font-weight: bold;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid #eee;
    }

    .drawer-footer {
        padding: 1rem;
        display: flex;
        gap: 1rem;
        border-top: 1px solid #eee;
    }

    .drawer-footer .btn {
        flex: 1;
    }

    .empty-cart {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem;
    }

    @media (min-width: 576px) {
        .drawer {
            width: 400px;
        }

        .drawer-header h3 {
            font-size: 1.5rem;
        }

        .drawer-header .btn {
            font-size: 0.875rem;
        }

        .cart-item-details h5 {
            font-size: 1.1rem;
        }
    }

    @media (min-width: 992px) {
        .drawer {
            width: 450px;
        }
    }

    .drawer-body::-webkit-scrollbar {
        width: 6px;
    }

    .drawer-body::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
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