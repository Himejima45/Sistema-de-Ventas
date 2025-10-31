@php
    $statuses = [
        'PENDING' => 'Pendiente',
        'PAID' => 'Pagado',
        'CANCELED' => 'Cancelado',
    ];
@endphp

<div class="col sales layout-top-spacing gap-4">
    <x-home_button />

    <div class="widget-content">
        <div class="col mx-auto">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-0">Mis Pedidos</h2>
                    <p class="text-muted mb-0">Historial de tus solicitudes de compra</p>
                </div>
            </div>

            @if(count($carts) > 0)
                <div class="row">
                    @foreach ($carts as $cart)
                        @if ($cart->products()->count() > 0)
                            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <!-- Card Header -->
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1 font-weight-bold">Solicitud #{{ $cart->code }}</h6>
                                                <small class="text-muted">
                                                    {{ $cart->created_at->format('d-m-Y h:i a') }}
                                                </small>
                                            </div>
                                            <span class="badge badge-{{ 
                                                                                                $cart->status === 'PAID' ? 'success' :
                            ($cart->status === 'CANCELED' ? 'danger' : 'warning') 
                                                                                            }}">
                                                {{ $statuses[$cart->status] }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Products -->
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($cart->products->take(4) as $details)
                                                <div class="col-6 col-sm-3 mb-3">
                                                    <div class="product-item text-center">
                                                        <img class="img-fluid rounded mb-2" src="{{ $details->product->getImage() }}"
                                                            alt="{{ $details->product->name }}"
                                                            style="height: 80px; width: 80px; object-fit: cover;">
                                                        <h6 class="small mb-1 text-truncate">{{ $details->product->name }}</h6>
                                                        <p class="mb-1 text-primary font-weight-bold">
                                                            ${{ number_format($details->product->price, 2) }}
                                                        </p>
                                                        <span class="badge badge-light border">
                                                            {{ $details->product->category->name }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Show more products indicator -->
                                        @if(count($cart->products) > 4)
                                            <div class="text-center mt-2">
                                                <small class="text-muted">
                                                    +{{ count($cart->products) - 4 }} productos más
                                                </small>
                                            </div>
                                        @endif

                                        <!-- Total and Actions -->
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0 text-primary">
                                                    Total: ${{ number_format($cart->total, 2) }}
                                                </h5>
                                                <span class="badge badge-secondary">
                                                    {{ count($cart->products) }} productos
                                                </span>
                                            </div>

                                            @if ($cart->status === 'PENDING')
                                                <div class="btn-group w-100">
                                                    <button wire:click="delete({{ $cart->id }})"
                                                        class="btn btn-outline-danger btn-sm flex-fill"
                                                        onclick="return confirm('¿Estás seguro de que deseas cancelar esta orden?')">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="mr-1">
                                                            <path d="M3 6h18" />
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                        </svg>
                                                        <span class="d-none d-sm-inline">Cancelar</span>
                                                    </button>
                                                    <a class="btn btn-outline-success btn-sm flex-fill"
                                                        href="https://wa.me/584124692459?text=Hola%2C%20acabo%20de%20registrar%20los%20productos%20que%20deseo%20comprar%2C%20este%20fue%20mi%20pedido%20%23{{ urlencode($cart->code) }}%20%C2%BFMe%20podr%C3%ADa%20indicar%20cuando%20podr%C3%ADa%20retirarlos%20de%20la%20tienda%3F%20Gracias"
                                                        target="_blank" title="Contactar por WhatsApp">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="mr-1">
                                                            <path d="M19 12v6a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6" />
                                                            <path d="m11 13 8.5-8.5" />
                                                            <path d="M15 4h5v5" />
                                                        </svg>
                                                        <span class="d-none d-sm-inline">Contactar</span>
                                                    </a>
                                                </div>
                                            @elseif($cart->status === 'PAID')
                                                <div class="text-center">
                                                    <span class="badge badge-success p-2">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="mr-1">
                                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                            <path d="m9 11 3 3L22 4" />
                                                        </svg>
                                                        Pedido completado
                                                    </span>
                                                </div>
                                            @else
                                                <div class="text-center">
                                                    <span class="badge badge-danger p-2">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="mr-1">
                                                            <path d="M18 6 6 18" />
                                                            <path d="m6 6 12 12" />
                                                        </svg>
                                                        Pedido cancelado
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $carts->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                        class="text-muted mb-4">
                        <circle cx="8" cy="21" r="1"></circle>
                        <circle cx="19" cy="21" r="1"></circle>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                    </svg>
                    <h4 class="text-muted mb-3">No tienes pedidos registrados</h4>
                    <p class="text-muted mb-4">Cuando realices pedidos, aparecerán aquí para que puedas hacerles
                        seguimiento.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="m5 12 7-7 7 7" />
                            <path d="M12 19V5" />
                        </svg>
                        Ir a comprar
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>