<div class="col sales layout-top-spacing gap-4">
    <x-home_button />

    <!-- Header Section -->
    <div class="row mb-4 mx-0">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
            <h2 class="mb-0">Carritos de compra</h2>
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
    <div wire:ignore-self id="product-zoom" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white">
                        <b>{{ $selectedProduct->name ?? '' }}</b>
                    </h5>
                    <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                        <span class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    @if (!is_null($selectedProduct) && !is_null($selectedProduct->getImage()))
                        <img src="{{ $selectedProduct->getImage() }}" alt="imagen de ejemplo" class="img-fluid rounded"
                            style="max-height: 70vh;">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sale Preview Modal -->
    <div wire:ignore.self class="modal fade" id="salePreviewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">
                        <b>Vista Previa de Venta</b>
                    </h5>
                    <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                        <span class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($selectedSale)
                        <div class="row mb-4">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <h6 class="font-weight-bold">Cliente:</h6>
                                <p class="mb-1">{{ $selectedSale->client->name }} {{ $selectedSale->client->last_name }}</p>
                                @if(auth()->user()->roles->pluck('name')->first() === 'Admin')
                                    <small class="text-muted">Documento: {{ $selectedSale->client->document }}</small>
                                @endif
                            </div>
                            <div class="col-12 col-md-6 text-md-right">
                                <h6 class="font-weight-bold">Información de Venta:</h6>
                                <p class="mb-1">Código: <strong>#{{ $selectedSale->code }}</strong></p>
                                <p class="mb-1">Fecha: {{ $selectedSale->created_at->format('d-m-Y h:i a') }}</p>
                                <p class="mb-0">Estado:
                                    <span
                                        class="badge badge-{{ $selectedSale->status === 'PAID' ? 'success' : 'warning' }}">
                                        {{ $selectedSale->status === 'PAID' ? 'PAGADO' : 'PENDIENTE' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Precio Unitario</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
    $subtotal = 0;
    $totalQuantity = 0;
                                    @endphp
                                    @foreach($selectedSale->products as $detail)
                                        @php
        $itemSubtotal = $detail->price * $detail->quantity;
        $subtotal += $itemSubtotal;
        $totalQuantity += $detail->quantity;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $detail->product->getImage() }}"
                                                        alt="{{ $detail->product->name }}" class="rounded mr-3"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0">{{ $detail->product->name }}</h6>
                                                        <small class="text-muted">SKU:
                                                            {{ $detail->product->code ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">${{ number_format($detail->price, 2) }}</td>
                                            <td class="text-center">{{ $detail->quantity }}</td>
                                            <td class="text-center">${{ number_format($itemSubtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="2" class="text-right font-weight-bold">Totales:</td>
                                        <td class="text-center font-weight-bold">{{ $totalQuantity }}</td>
                                        <td class="text-center font-weight-bold">${{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">IVA (16%):</td>
                                        <td class="text-center font-weight-bold">${{ number_format($subtotal * 0.16, 2) }}
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="3" class="text-right font-weight-bold">TOTAL:</td>
                                        <td class="text-center font-weight-bold">${{ number_format($subtotal * 1.16, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($selectedSale->status === 'PAID')
                            <div class="row mt-4">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <h6 class="font-weight-bold">Información de Pago:</h6>
                                    <p class="mb-1">Monto Pagado: <strong>${{ number_format($selectedSale->cash, 2) }}</strong>
                                    </p>
                                    <p class="mb-1">Cambio: <strong>${{ number_format($selectedSale->change, 2) }}</strong></p>
                                </div>
                                <div class="col-12 col-md-6 text-md-right">
                                    <h6 class="font-weight-bold">Procesado por:</h6>
                                    <p class="mb-0">{{ $selectedSale->user->name ?? 'Sistema' }}</p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @if($selectedSale && $selectedSale->status === 'PENDING')
                        <button type="button" wire:click="edit({{ $selectedSale->id ?? 0 }})" class="btn btn-primary"
                            data-dismiss="modal">
                            Gestionar Pago
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Details Modal -->
    <div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white">
                        <b>Carritos</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR' }}
                    </h5>
                    <h6 class="text-center text-warning mb-0" wire:loading>POR FAVOR ESPERE</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mt-1">
                                    <thead class="text-white bg-dark">
                                        <tr>
                                            <th class="text-white">NOMBRE</th>
                                            <th class="text-white">PRECIO</th>
                                            <th class="text-white">CANTIDAD</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
$total = 0;
$quantity = 0;
                                        @endphp
                                        @foreach ($details as $detail)
                                            @php
    $total += $detail->price * $detail->quantity * 1.16;
    $quantity += $detail->quantity;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">{{ $detail->product->name }}</h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0">${{ number_format($detail->price, 2) }}</h6>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0">{{ $detail->quantity }}</h6>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-secondary">
                                            <td>
                                                <h6 class="mb-0 font-weight-bold">TOTAL</h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 font-weight-bold">${{ number_format($total, 2) }}</h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 font-weight-bold">{{ $quantity }}</h6>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Monto pagado</label>
                                <input type="number" wire:model="payed" class="form-control" placeholder="Ej: 10.00">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Cambio</label>
                                <input type="number" wire:model="change" class="form-control" placeholder="Ej: 10.00">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                @php
$value = round(floatval($total ?? 0) - floatval($payed ?? 0) + floatval($change ?? 0), 2);
                                @endphp
                                <label class="font-weight-bold">Monto a pagar</label>
                                <input type="number" disabled value="{{ $value }}" @class([
    'form-control font-weight-bold',
    'text-success' => $value <= 0,
    'text-danger' => $value > 0,
]) placeholder="Ej: 10.00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="" class="btn btn-outline-secondary"
                        data-dismiss="modal">CERRAR</button>
                    <button type="button" wire:click="update" class="btn btn-primary">MARCAR PAGADO</button>
                </div>
            </div>
        </div>
    </div>

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
                                            <div class="flex-grow-1">
                                                <h6 class="font-weight-bold text-truncate">
                                                    {{ $cart->client->name }} {{ $cart->client->last_name }}
                                                    @if(auth()->user()->roles->pluck('name')->first() === 'Admin')
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
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="mb-0 text-primary">${{ number_format($cart->total, 2) }}</h5>
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
            $('#theModal').modal('show')
        });
        window.livewire.on('close', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('show-product-zoomed', msg => {
            $('#product-zoom').modal('show')
        });
        window.livewire.on('show-sale-preview', msg => {
            $('#salePreviewModal').modal('show')
        });
    });
</script>