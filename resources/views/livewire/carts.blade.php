<div class="col sales layout-top-spacing gap-4">
    <x-home_button />

    <!-- Header Section -->
    <div class="row mb-4 mx-0">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
            <h2 class="mb-0">Mis pedidos</h2>
        </div>
        <div class="col-12 col-md-6">
            <!-- Search Input -->
            <div class="input-group">
                <input type="text" wire:model.debounce.300ms="searchedCode" class="form-control"
                    placeholder="Buscar por código...">
            </div>
        </div>
    </div>

    <!-- Product Zoom Modal -->
    <x-carts.product_zoom
        :productName="$selectedProduct->name ?? ''"
        :productImage="$selectedProduct?->getImage() ?? ''" 
    />
    
    <!-- Sale Preview Modal -->
    <x-carts.preview_sale :sale="$selectedSale" :activeTab="$activeTab" :modalProducts="$modalProducts"
        :modalPayments="$modalPayments" />
    
    <!-- Cart Details Modal -->
    <x-carts.sale_payments
        :selectedId="$selected_id"
        :totalSale="$totalSale"
        :currencyId="$currency_id"
    />

    <!-- Main Content -->
    <div class="widget-content">
        <div class="col mx-auto">
            <!-- Error Alert -->
            @if (!empty($error))
                <div class="alert alert-danger d-flex justify-content-between align-items-center mb-4">
                    <p class="mb-0">
                        {{ $error }}
                    </p>
                    <button type="button" class="btn-close" wire:click="clearMessage" aria-label="Close"></button>
                </div>
            @endif

            <!-- Carts List -->
            @if(count($carts) > 0)
                <div class="row">
                    @foreach ($carts as $index => $cart)
                        @if ($cart->products()->count() > 0)
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="grow">
                                                <h6 class="font-weight-bold text-truncate">
                                                    {{ $cart->client->name }} {{ $cart->client->last_name }}
                                                    @if(auth()->user()->roles->pluck('reference')->first() === 'admin')
                                                        <small class="d-block text-muted">
                                                            ({{ $cart->client->document }}) {{ $cart->code ? "- (#$cart->code)" : '' }}
                                                        </small>
                                                    @endif
                                                </h6>
                                            </div>
                                            <span
                                                class="badge badge-{{ $cart->status === 'PAID' ? 'success' : 'primary' }} badge-pill ml-2">
                                                {{ count($cart->products) }}
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            {{ $cart->created_at->format('d-m-Y h:i a') }}
                                        </small>
                                    </div>

                                    <div class="card-body">
                                        <!-- Products Grid - Show only 3 products -->
                                        <div class="row no-gutters">
                                            @foreach ($cart->products->take(3) as $details)
                                                <div class="col-4 px-1 mb-2">
                                                    <div class="product-card text-center">
                                                        <img class="img-fluid rounded cursor-pointer"
                                                            src="{{ $details->product->getImage() }}"
                                                            alt="{{ $details->product->name }}"
                                                            wire:click="zoom({{ $details->product->id }})"
                                                            style="height: 60px; width: 60px; object-fit: cover;">
                                                        <p class="small mb-0 mt-1 text-truncate">{{ $details->product->name }}</p>
                                                        <small class="text-muted">x{{ $details->quantity }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Show indicator if there are more than 3 products -->
                                        @if(count($cart->products) > 3)
                                            <div class="text-center mt-2">
                                                <small class="text-muted">
                                                    +{{ count($cart->products) - 3 }} más
                                                </small>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="card-footer bg-white pt-3">
                                        <div class="d-flex justify-content-between a
                                        lign-items-center mb-2">
                                            <h5 class="mb-0 text-primary">
                                                ${{ number_format($cart->getRemainingAmount(), 2) }}
                                            </h5>
                                            <small
                                                class="text-{{ $cart->status === 'PAID' ? 'success' : 'warning' }} font-weight-bold">
                                                {{ $cart->status === 'PAID' ? 'Pagado' : 'Pendiente' }}
                                            </small>
                                        </div>
                                        <div class="btn-group w-100">
                                            <button wire:click="showPreview({{ $cart->id }})"
                                                class="btn btn-outline-info btn-sm flex-fill">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-eye mr-1">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                                <span class="d-none d-sm-inline">Ver</span>
                                            </button>
                                            @if($cart->status === 'PENDING')
                                                <button wire:click="edit({{ $cart->id }})" class="btn btn-primary btn-sm flex-fill">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-pencil mr-1">
                                                        <path
                                                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                                        <path d="m15 5 4 4" />
                                                    </svg>
                                                    <span class="d-none d-sm-inline">Pagar</span>
                                                </button>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shopping-cart text-muted mb-3">
                        <circle cx="8" cy="21" r="1"></circle>
                        <circle cx="19" cy="21" r="1"></circle>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                    </svg>
                    <h4 class="text-muted">No hay carritos registrados actualmente</h4>
                    @if($searchedCode)
                        <p class="text-muted">No se encontraron resultados para "{{ $searchedCode }}"</p>
                        <button wire:click="$set('searchedCode', '')" class="btn btn-outline-primary mt-2">
                            Limpiar búsqueda
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.livewire.on('open', msg => {
            $('#theModal').modal('show');
        });

        window.livewire.on('close', msg => {
            $('#theModal').modal('hide');
            // Reset the modal when it's closed
            livewire.emit('resetModal');
        });

        window.livewire.on('show-product-zoomed', msg => {
            $('#product-zoom').modal('show');
        });

        window.livewire.on('show-sale-preview', msg => {
            $('#salePreviewModal').modal('show');
        });

        // Reset modal when it's hidden via backdrop click or ESC key
        $('#theModal').on('hidden.bs.modal', function () {
            livewire.emit('resetModal');
        });
    });
</script>