<div class="col sales layout-top-spacing gap-4">
    <x-home_button />

    <div class="row" style="flex-direction: column;">
        <h2 style="padding-left: 1rem;">Carritos de compra</h2>
    </div>

    <div wire:ignore-self id="product-zoom" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #3B3F5C">
                    <h5 class="modal-title text-white">
                        <b>{{ $selectedProduct->name ?? '' }}</b>
                    </h5>
                    <button class="close" data-miss="modal" type="button" aria-label="Close">
                        <span class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (!is_null($selectedProduct) && !is_null($selectedProduct->getImage()))
                        <span class="d-flex justify-content-center mx-auto">
                            <img src="{{ $selectedProduct->getImage() }}" alt="imagen de ejemplo" height="400" width="400"
                                class="rounded">
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #3B3F5C">
                    <h5 class="modal-title text-white">
                        <b>Carritos</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR' }}
                    </h5>
                    <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mt-1">
                                    <thead class="text-white" style="background: #3B3F5C;">
                                        <tr>
                                            <th class="table-th text-white">NOMBRE</th>
                                            <th class="table-th text-white">PRECIO</th>
                                            <th class="table-th text-white">CANTIDAD</th>
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
                                                    <h6>{{ $detail->product->name }}</h6>
                                                </td>
                                                <td>
                                                    <h6>{{ $detail->price }}</h6>
                                                </td>
                                                <td>
                                                    <h6>{{ $detail->quantity }}</h6>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>
                                                <h6>TOTAL</h6>
                                            </td>
                                            <td>
                                                <h6>{{ $total }}</h6>
                                            </td>
                                            <td>
                                                <h6>{{ $quantity }}</h6>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Monto pagado</label>
                                <input type="number" wire:model="payed" class="form-control" placeholder="Ej: 10">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Cambio</label>
                                <input type="number" wire:model="change" class="form-control" placeholder="Ej: 10">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                @php
$value = round(floatval($total ?? 0) - floatval($payed ?? 0) + floatval($change ?? 0), 2);
                                @endphp
                                <label>Monto a pagar</label>
                                <input type="number" disabled value="{{ $value }}" @class([
    'form-control font-weight-bold',
    'text-success' => $value < 0,
    'text-danger' => $value > 0,
])
                                    placeholder="Ej: 10">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="" class="btn btn-light close-btn"
                        data-dismiss="modal">CERRAR</button>
                    <button type="button" wire:click="update" class="btn btn-dark close-modal">MARCAR PAGADO</button>
                </div>
            </div>
        </div>
    </div>

    <div class="widget-content">
        <div class="col mx-auto">
            @if (!empty($error))
                <div class="alert alert-danger d-flex justify-items-between align-items-center">
                    <p class="mb-0">
                        {{ $error }}
                    </p>
                    <div class="justify-self-end">
                        <svg xmlns="http://www.w3.org/2000/svg" wire:click="clearMessage" style="cursor: pointer" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </div>
                </div>
            @endif
            @foreach ($carts as $index => $cart)
                @if ($cart->products()->count() > 0)
                        <div class="row p-0">
                            <div class="col-sm-12 order-sm-2 order-md-1 col-md-10">
                                <div class="row">
                                    @foreach ($cart->products as $details)
                                        <div class="card m-2 mx-md-1 mx-auto" style="width: 16rem">
                                            <img class="card-img-top" height="240" width="240" src="{{ $details->product->getImage() }}"
                                                alt="Card image cap" wire:click="zoom({{ $details->product->id }})">
                                            <div class="card-body">
                                                <h5 class="card-title text-center">{{ $details->product->name }}</h5>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-12 order-sm-1 order-md-2 col-md-2 m-0 p-0">
                                <h3>
                                    <?php
        $document = '';
        if (auth()->user()->roles->pluck('name')->first() === 'Admin') {
            $document = "({$cart->client->document})";
        }
                                                                                                                                                                                                                                    ?>
                                    {{
            "{$cart->client->name} {$cart->client->last_name} {$document}"
                                                                                                                                                                                                                                }}
                                </h3>
                                <p style="margin-top: -0.5rem;">
                                    {{ $cart->created_at->format('d-m-Y h:i a') }}
                                </p>
                                <h6 class="mt-2 h6">
                                    Productos: {{ count($cart->products) }}
                                </h6>
                                <h6 class="mt-2 h2 text-right">${{ $cart->total }}</h6>
                                <button wire:click="edit({{ $cart->id }})" class="btn btn-block btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-pencil">
                                        <path
                                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                        <path d="m15 5 4 4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @if ($index < count($carts) - 1)
                            <div class="my-4" style="border: 1px solid rgba(0, 0, 0, 0.1)"></div>
                        @endif
                @endif
            @endforeach
            {{ $carts->links() }}

            @if (count($carts) === 0)
                <div class="table-responsive">
                    <p>No hay carritos registrados actualmente</p>
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
    });
</script>