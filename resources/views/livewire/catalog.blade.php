<div class="position-relative col sales layout-top-spacing">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-8 col-sm-10">
            <x-home_button />
        </div>
        <div class="col-4 col-sm-2 text-right">
            @if (auth()->user()->hasRole('Client'))
                <button id="shopping-cart" wire:click.stop="$emit('toggleCart')"
                    class="btn btn-outline-primary position-relative">
                    @livewire('cart-icon')
                    @if($total_items > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $total_items }}
                        </span>
                    @endif
                </button>
            @endif
        </div>
    </div>

    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-2">Catálogo de Productos</h1>
            <p class="text-muted mb-0">Explora nuestra selección de productos disponibles</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                        <h6 class="mb-2 mb-md-0 font-weight-bold text-muted">FILTRAR PRODUCTOS</h6>
                        <div class="d-flex flex-wrap" style="gap: 0.5rem">
                            <button type="button" wire:click="clearFilters" class="btn btn-sm btn-outline-danger">
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
                        <div class="row g-3">
                            <!-- Category Filter -->
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted mb-1">CATEGORÍA</label>
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
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted mb-1">PROVEEDOR</label>
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
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted mb-1 d-block">RANGO DE PRECIO</label>
                                    <div class="d-flex align-items-center flex-nowrap">
                                        <div class="flex-grow-1 me-2">
                                            <input type="number" wire:model="priceMin" 
                                                class="form-control form-control-sm"
                                                placeholder="Mínimo" min="0" 
                                                aria-label="Precio mínimo">
                                        </div>
                                        <span class="text-muted mx-1 flex-shrink-0">—</span>
                                        <div class="flex-grow-1 ms-2">
                                            <input type="number" wire:model="priceMax" 
                                                class="form-control form-control-sm"
                                                placeholder="Máximo" min="0" 
                                                aria-label="Precio máximo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Filter -->
                            <div class="col-12 col-md-6 col-lg-2">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted mb-1">STOCK MÍNIMO</label>
                                    <input type="number" wire:model="quantity" 
                                        class="form-control form-control-sm"
                                        placeholder="Ej: 10" min="1" max="1000" 
                                        aria-label="Stock mínimo">
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
                    <div class="d-flex align-items-center">
                        <h3 class="mb-0">Mi Carrito</h3>
                        @if($total_items > 0)
                            <span class="badge bg-primary ms-2">{{ $total_items }}</span>
                        @endif
                    </div>
                    @if (count($cart) > 0)
                        <button title="Limpiar carrito" wire:click="clear" 
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('¿Estás seguro de que deseas vaciar el carrito?')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-trash-2">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                <line x1="10" x2="10" y1="11" y2="17"/>
                                <line x1="14" x2="14" y1="11" y2="17"/>
                            </svg>
                            <span class="d-none d-sm-inline">Vaciar</span>
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
                                    <img src="{{ $product->getImage() }}" alt="{{ $product->name }}" 
                                         class="img-fluid rounded">
                                </div>
                                <div class="cart-item-details flex-grow-1">
                                    <h6 class="cart-item-title mb-1">{{ $product->name }}</h6>
                                    <p class="cart-item-stock mb-1 text-muted small">
                                        Disponible: {{ $product->stock }}
                                    </p>
                                    <p class="cart-item-price mb-2 font-weight-bold text-primary">
                                        ${{ number_format($product->price, 2) }}
                                    </p>
                                    <div class="quantity-controls d-flex align-items-center">
                                        <button class="btn btn-sm btn-outline-danger" 
                                                wire:click="removeFromCart({{ $product->id }})"
                                                {{ $quantity <= 1 ? 'disabled' : '' }}>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" 
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                                 stroke-linejoin="round">
                                                <path d="M5 12h14"/>
                                            </svg>
                                        </button>
                                        <span class="quantity mx-3 font-weight-bold">{{ $quantity }}</span>
                                        <button class="btn btn-sm btn-outline-success" 
                                                wire:click="addToCart({{ $product->id }})"
                                                {{ $quantity >= $product->stock ? 'disabled' : '' }}>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" 
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                                 stroke-linejoin="round">
                                                <path d="M12 5v14"/><path d="M5 12h14"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="drawer-summary">
                        <h5 class="mb-3">Resumen de Compra</h5>
                        <div class="summary-row total">
                            <span class="font-weight-bold">Total</span>
                            <span class="font-weight-bold text-primary">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                @else
                    <div class="empty-cart">
                        <div class="text-center py-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                class="text-muted mb-3">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                            </svg>
                            <h5 class="text-muted">Tu carrito está vacío</h5>
                            <p class="text-muted small">Agrega productos desde el catálogo</p>
                        </div>
                    </div>
                @endif

                <div class="drawer-footer">
                    <button wire:click="toggle" class="btn btn-outline-secondary flex-fill">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                        </svg>
                        Continuar Comprando
                    </button>
                    @if (count($cart) > 0)
                        <button wire:click="save" class="btn btn-primary flex-fill">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Guardar Pedido
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
            <div class="row g-3">
                @foreach ($products as $product)
                    <div class="col-12 col-md-4 col-lg-3 col-xl-2 mb-4">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="card-img-container position-relative overflow-hidden">
                                <img class="card-img-top" 
                                     src="{{ $product->getImage() }}" 
                                     alt="{{ $product->name }}"
                                     style="height: 160px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 p-2">
                                    <span class="badge bg-{{ $product->stock > 10 ? 'primary' : 'warning' }}">
                                        {{ $product->stock }}
                                    </span>
                                </div>
                                <div class="add-to-cart-overlay">
                                    <button class="btn btn-primary btn-sm rounded-pill"
                                            wire:click="addToCart({{ $product->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" 
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                             stroke-linejoin="round" class="me-1">
                                            <path d="M12 5v14"/><path d="M5 12h14"/>
                                        </svg>
                                        Agregar
                                    </button>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title mb-2 text-truncate small">{{ $product->name }}</h6>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-primary font-weight-bold h6 mb-0">
                                            ${{ number_format($product->price, 2) }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block">
                                        {{ $product->category->name }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-12">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    class="text-muted mb-4">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <h4 class="text-muted mb-3">No se encontraron productos</h4>
                <p class="text-muted mb-4">Intenta ajustar los filtros o buscar otros términos</p>
                <button wire:click="clearFilters" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <path d="M3 6h18"/><path d="M7 12h10"/><path d="M10 18h4"/>
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
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
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
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card:hover .add-to-cart-overlay {
        opacity: 1;
    }

    /* Cart Drawer */
    .drawer {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        height: 100vh;
        background: white;
        box-shadow: -2px 0 20px rgba(0,0,0,0.1);
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
        background: rgba(0,0,0,0.5);
        z-index: 1055;
        display: none;
    }

    .drawer-backdrop.show {
        display: block;
    }

    .drawer-content {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .drawer-header {
        padding: 1.25rem;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .drawer-body {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
    }

    .drawer-summary {
        padding: 1.25rem;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .drawer-footer {
        padding: 1rem;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 0.75rem;
    }

    .cart-item {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        margin-right: 1rem;
    }

    .cart-item-title {
        font-size: 0.9rem;
        line-height: 1.3;
    }

    .quantity-controls {
        max-width: 120px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .summary-row.total {
        font-weight: 600;
        font-size: 1.1rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #dee2e6;
    }

    /* Responsive Design */
    @media (min-width: 576px) {
        .drawer {
            width: 380px;
        }
        
        .product-card .card-title {
            font-size: 0.85rem;
        }
    }

    @media (min-width: 768px) {
        .drawer {
            width: 420px;
        }
        
        .cart-item-image {
            width: 100px;
            height: 100px;
        }
    }

    @media (min-width: 1200px) {
        .drawer {
            width: 450px;
        }
    }

    @media (max-width: 575.98px) {
        .card-img-container {
            height: 140px;
        }
        
        .cart-item {
            flex-direction: column;
            text-align: center;
        }
        
        .cart-item-image {
            margin-right: 0;
            margin-bottom: 1rem;
            width: 100%;
            height: 120px;
        }
        
        .quantity-controls {
            margin: 0 auto;
        }
    }

    /* Scrollbar Styling */
    .drawer-body::-webkit-scrollbar {
        width: 6px;
    }

    .drawer-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .drawer-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .drawer-body::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Close cart when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const cartDrawer = document.getElementById('cart-drawer');
            const cartButton = document.getElementById('shopping-cart');
            
            if (cartDrawer && cartDrawer.classList.contains('show') && 
                !cartDrawer.contains(e.target) && 
                !cartButton.contains(e.target)) {
                Livewire.emit('toggle');
            }
        });

        // Prevent body scroll when cart is open
        Livewire.on('cartToggled', function(showCart) {
            document.body.style.overflow = showCart ? 'hidden' : '';
        });
    });

    function NotFound (eventName, text) {
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
                if (result.value)
                {
                    window.livewire.emit(eventName, id)
                    swal.close()
                }
            })
        }
</script>