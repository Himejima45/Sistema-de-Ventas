<div class="col sales layout-top-spacing gap-4">

    <div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
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
                                    $value = round($total - $payed + $change, 2);
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
                        <svg xmlns="http://www.w3.org/2000/svg" wire:click="clearMessage" style="cursor: pointer"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </div>
                </div>
            @endif
            @foreach ($carts as $cart)
                @if ($cart->products()->count() > 0)
                    <h6 class="mt-2">Solicitud - ( {{ $cart->total }}$ ) -
                        {{ $cart->created_at->format('d-m-Y h:i a') }}
                    </h6>
                    <div class="d-flex items-justify-center align-items-center">
                        <p class="font-weight-bold mb-0 mr-2">
                            {{ "{$cart->client->name} {$cart->client->last_name}" }}
                        </p>
                        <button wire:click="edit({{ $cart->id }})" class="btn btn-ghost"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-pencil">
                                <path
                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                <path d="m15 5 4 4" />
                            </svg></button>
                    </div>
                    <div class="row">
                        @foreach ($cart->products as $details)
                            <div class="card m-2" style="width: 14rem;">
                                <img class="card-img-top" height="140" src="{{ $details->product->getImage() }}"
                                    alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $details->product->name }}</h5>
                                    <small class="-mt-4">{{ $details->product->price }}$</small>
                                    <p class="card-text">
                                        <span
                                            class="badge badge-secondary">{{ $details->product->category->name }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('open', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('close', msg => {
            $('#theModal').modal('hide')
        });
    });
</script>
