<div class="position-relative col sales layout-top-spacing">
    <div class="col-12" style="margin: 0; padding: 0;">
        <div class="col-sm-12 col-md-2 mb-4">
            <a href="{{ route('historial') }}" class="btn btn-outline-primary d-flex align-items-center w-100"
                style="gap: 0.5rem" data-active="false">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                    <path d="M12 11h4" />
                    <path d="M12 16h4" />
                    <path d="M8 11h.01" />
                    <path d="M8 16h.01" />
                </svg>
                <span class="font-weight-bold small">Mis pedidos</span>
            </a>
        </div>
    </div>

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <h1 class="h4 mb-2">Catálogo de Productos</h1>
            <p class="text-muted small mb-0">Explora nuestra selección de productos disponibles</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <h6 class="mb-0 font-weight-bold text-muted small text-uppercase">Filtrar Productos</h6>
                        <div class="d-flex flex-wrap w-100 w-md-auto" style="gap: 0.5rem">
                            @if (auth()->user()->canAccess('client'))
                                <button id="shopping-cart" wire:click.stop="$emit('toggleCart')"
                                    class="d-flex align-items-center btn btn-sm btn-outline-primary flex-fill flex-md-grow-0">
                                    <span class="small">Mi carrito</span>
                                    @livewire('cart-icon')
                                </button>
                            @endif
                            <button type="button" wire:click="clearFilters" class="btn btn-sm btn-outline-danger flex-fill flex-md-grow-0"
                                @if(!$showFilter) disabled @endif>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-eraser mr-1">
                                    <path
                                        d="m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" />
                                    <path d="M22 21H7" />
                                    <path d="m5 11 9 9" />
                                </svg>
                                <span class="d-none d-sm-inline">Limpiar</span>
                            </button>
                            <button type="button" wire:click="toggleFilter" class="btn btn-sm btn-outline-primary flex-fill flex-md-grow-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-filter mr-1">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                                </svg>
                                <span class="d-none d-sm-inline">Filtros</span>
                            </button>
                        </div>
                    </div>

                    @if($showFilter)
                        <div class="row g-2">
                            <!-- Category Filter -->
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form-group mb-2 mb-lg-0">
                                    <label class="small font-weight-bold text-muted mb-1">Categoría</label>
                                    <select name="category" wire:model.lazy='category_id'
                                        class="form-control form-control-sm">
                                        <option value="" selected>Todas las categorías</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Provider Filter -->
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="form-group mb-2 mb-lg-0">
                                    <label class="small font-weight-bold text-muted mb-1">Proveedor</label>
                                    <select name="provider" wire:model.lazy='provider_id'
                                        class="form-control form-control-sm">
                                        <option value="" selected>Todos los proveedores</option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="col-12 col-sm-8 col-lg-4">
                                <div class="form-group mb-2 mb-lg-0">
                                    <label class="small font-weight-bold text-muted mb-1 d-block">Rango de Precio</label>
                                    <div class="d-flex align-items-center flex-nowrap gap-2">
                                        <input type="number" wire:model="priceMin" class="form-control form-control-sm"
                                            placeholder="Min" min="0" aria-label="Precio mínimo">
                                        <span class="text-muted flex-shrink-0">—</span>
                                        <input type="number" wire:model="priceMax" class="form-control form-control-sm"
                                            placeholder="Max" min="0" aria-label="Precio máximo">
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Filter -->
                            <div class="col-12 col-sm-4 col-lg-2">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted mb-1">Stock Mín.</label>
                                    <input type="number" wire:model="quantity" class="form-control form-control-sm"
                                        placeholder="Ej: 10" min="1" max="1000" aria-label="Stock mínimo">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Shopping Cart Drawer -->
    @if ($showCart)
        <div id="cart-drawer" class="drawer show">
            <div class="drawer-content">
                <div class="drawer-header">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 h6">Mi Carrito</h5>
                            @if($total_items > 0)
                                <span class="badge bg-primary ms-2">{{ $total_items }}</span>
                            @endif
                        </div>
                        @if (count($cart) > 0)
                            <button title="Limpiar carrito" wire:click="clear" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('¿Estás seguro de que deseas vaciar el carrito?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-trash-2">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                @if (count($cart) > 0)
                    <div class="drawer-body">
                        @foreach ($cart as $productId => $quantity)
                            @php
                                $product = \App\Models\Product::find($productId);
                            @endphp
                            <div class="cart-item">
                                <div class="cart-item-image">
                                    <img src="{{ $product->getImage() }}" alt="{{ $product->name }}" class="img-fluid rounded">
                                </div>
                                <div class="cart-item-details flex-grow-1 min-width-0">
                                    <h6 class="cart-item-title mb-1 text-truncate">{{ $product->name }}</h6>
                                    <p class="cart-item-stock mb-1 text-muted small">
                                        Disp: {{ $product->stock }}
                                    </p>
                                    <p class="cart-item-price mb-2 font-weight-bold text-primary small">
                                        Bs. {{ number_format($product->price * $exchange_rate, 2) }}
                                    </p>
                                    <div class="quantity-controls d-flex align-items-center">
                                        <button class="btn btn-xs btn-outline-danger"
                                            wire:click="removeFromCart({{ $product->id }})">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14" />
                                            </svg>
                                        </button>
                                        <span class="quantity mx-2 font-weight-bold small">{{ $quantity }}</span>
                                        <button class="btn btn-xs btn-outline-success" wire:click="addToCart({{ $product->id }})" {{ $quantity >= $product->stock ? 'disabled' : '' }}>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 5v14" />
                                                <path d="M5 12h14" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="drawer-summary">
                        <h6 class="mb-2 small font-weight-bold">Resumen</h6>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span class="font-weight-bold text-primary">Bs.
                                {{ number_format($exchange_rate * $total, 2) }}</span>
                        </div>
                    </div>
                @else
                    <div class="empty-cart flex-grow-1 d-flex align-items-center justify-content-center">
                        <div class="text-center py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                class="text-muted mb-2">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12">
                                </path>
                            </svg>
                            <h6 class="text-muted small mb-1">Tu carrito está vacío</h6>
                            <p class="text-muted small mb-0">Agrega productos desde el catálogo</p>
                        </div>
                    </div>
                @endif

                <div class="drawer-footer">
                    <button wire:click="toggle" class="btn btn-outline-secondary btn-sm flex-fill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="me-1">
                            <path d="M19 12H5" />
                            <path d="m12 19-7-7 7-7" />
                        </svg>
                        <span class="small">Seguir</span>
                    </button>
                    @if (count($cart) > 0)
                        <button wire:click="save" class="btn btn-primary btn-sm flex-fill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                <polyline points="17 21 17 13 7 13 7 21" />
                                <polyline points="7 3 7 8 15 8" />
                            </svg>
                            <span class="small">Guardar</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="drawer-backdrop show" wire:click="toggle"></div>
    @endif

    <!-- Products Grid -->
    <div class="widget-content">
        @if($products->count() > 0)
            <div class="row g-2 g-md-3">
                @foreach ($products as $product)
                    <div class="col-12 col-md-4 col-lg-3 col-xl-2 mb-3 mb-md-4">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="card-img-container position-relative overflow-hidden">
                                <img class="card-img-top" src="{{ $product->getImage() }}" alt="{{ $product->name }}"
                                    style="height: 140px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 p-1 p-md-2">
                                    <span class="badge bg-{{ $product->stock > 10 ? 'primary' : 'warning' }} badge-sm">
                                        {{ $product->stock }}
                                    </span>
                                </div>
                                <div class="add-to-cart-overlay">
                                    <button class="btn btn-primary btn-sm rounded-pill px-3"
                                        wire:click="addToCart({{ $product->id }})">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                            <path d="M12 5v14" />
                                            <path d="M5 12h14" />
                                        </svg>
                                        Agregar
                                    </button>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column p-2 p-md-3">
                                <h6 class="card-title mb-1 text-truncate small" title="{{ $product->name }}">{{ $product->name }}</h6>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-primary font-weight-bold small mb-0">
                                            Bs. {{ number_format($product->price * $exchange_rate, 2) }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;">
                                        {{ $product->category->name }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-3 mt-md-4">
                <div class="col-12">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    class="text-muted mb-3">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <h5 class="text-muted mb-2 h6">No se encontraron productos</h5>
                <p class="text-muted small mb-3">Intenta ajustar los filtros</p>
                <button wire:click="clearFilters" class="btn btn-primary btn-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="me-1">
                        <path d="M3 6h18" />
                        <path d="M7 12h10" />
                        <path d="M10 18h4" />
                    </svg>
                    Limpiar Filtros
                </button>
            </div>
        @endif
    </div>
</div>

<style>
    /* Product Cards */
    .product-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .card-img-container {
        position: relative;
        overflow: hidden;
    }

    .add-to-cart-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card:hover .add-to-cart-overlay {
        opacity: 1;
    }

    .badge-sm {
        font-size: 0.65rem;
        padding: 0.25em 0.5em;
    }

    /* Cart Drawer - Mobile First */
    .drawer {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        height: 100vh;
        height: 100dvh; /* Dynamic viewport height for mobile */
        background: white;
        box-shadow: -2px 0 20px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 1060;
        display: flex;
        flex-direction: column;
    }

    .drawer.show {
        right: 0;
    }

    .drawer-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1055;
        display: none;
        backdrop-filter: blur(2px);
    }

    .drawer-backdrop.show {
        display: block;
    }

    .drawer-content {
        display: flex;
        flex-direction: column;
        height: 100%;
        max-height: 100vh;
        max-height: 100dvh;
    }

    .drawer-header {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
        flex-shrink: 0;
    }

    .drawer-body {
        flex: 1;
        overflow-y: auto;
        padding: 0.75rem;
        -webkit-overflow-scrolling: touch; /* Smooth scroll on iOS */
    }

    .drawer-summary {
        padding: 1rem;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        flex-shrink: 0;
    }

    .drawer-footer {
        padding: 0.75rem;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .cart-item {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f3f4;
        gap: 0.75rem;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
        border-radius: 0.375rem;
        overflow: hidden;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-title {
        font-size: 0.875rem;
        line-height: 1.3;
        font-weight: 500;
    }

    .cart-item-details {
        min-width: 0; /* Prevent flex item overflow */
    }

    .quantity-controls {
        max-width: 100px;
    }

    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .summary-row.total {
        font-weight: 600;
        font-size: 1rem;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid #dee2e6;
    }

    /* Responsive Design */
    @media (min-width: 576px) {
        .drawer {
            width: 360px;
        }

        .cart-item-image {
            width: 70px;
            height: 70px;
        }

        .drawer-header,
        .drawer-summary {
            padding: 1.25rem;
        }

        .drawer-body {
            padding: 1rem;
        }

        .drawer-footer {
            padding: 1rem;
            gap: 0.75rem;
        }
    }

    @media (min-width: 768px) {
        .drawer {
            width: 400px;
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
        }

        .card-img-container {
            height: 160px !important;
        }
    }

    @media (min-width: 1200px) {
        .drawer {
            width: 420px;
        }
    }

    @media (max-width: 575.98px) {
        /* Prevent horizontal scroll */
        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }

        .col-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        /* Touch-friendly tap targets */
        .btn-sm {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Compact cart items for very small screens */
        .cart-item {
            padding: 0.5rem 0;
        }
    }

    /* Scrollbar Styling */
    .drawer-body::-webkit-scrollbar {
        width: 4px;
    }

    .drawer-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .drawer-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }

    .drawer-body::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Safe area for notched phones */
    @supports (padding: max(0)) {
        .drawer-footer {
            padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Close cart when clicking outside
        document.addEventListener('click', function (e) {
            const cartDrawer = document.getElementById('cart-drawer');
            const cartButton = document.getElementById('shopping-cart');

            if (cartDrawer && cartDrawer.classList.contains('show') &&
                !cartDrawer.contains(e.target) &&
                (!cartButton || !cartButton.contains(e.target)))
            {
                Livewire.emit('toggle');
            }
        });

        // Prevent body scroll when cart is open
        Livewire.on('cartToggled', function (showCart) {
            if (showCart) {
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
            } else {
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
            }
        });

        // Handle resize to reset body styles if cart closed externally
        window.addEventListener('resize', function() {
            const cartDrawer = document.getElementById('cart-drawer');
            if (!cartDrawer || !cartDrawer.classList.contains('show')) {
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
            }
        });
    });

    function NotFound(eventName, text) {
        swal({
            title: 'NO ENCONTRADO',
            text: text,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function (result) {
            if (result.value) {
                window.livewire.emit(eventName, id);
                swal.close();
            }
        });
    }
</script>