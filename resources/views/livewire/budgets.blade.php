@php
    $statuses = [
        'PENDING' => 'Pendiente',
        'PAID' => 'Pagado',
        'CANCELED' => 'Cancelado',
    ];
@endphp

<div class="row sales layout-top-spacing">

    <div wire:ignore.self class="modal fade" id="modalDetails" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #3B3F5C">
                    <h5 class="modal-title text-white">
                        <b>Productos</b>
                    </h5>
                    <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
                </div>
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: #3B3F5C;">
                                <tr>
                                    <th class="table-th text-white">NOMBRE</th>
                                    <th class="table-th text-white">PRECIO</th>
                                    <th class="table-th text-white">CANT</th>
                                    <th class="table-th text-white">IMPORTE</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <h6>{{ $product->name }}</h6>
                                        </td>
                                        <td>
                                            <h6>{{ number_format($product->price, 2) }}</h6>
                                        </td>
                                        <td>
                                            <h6>{{ number_format($product->quantity, 0) }}</h6>
                                        </td>
                                        <td>
                                            <h6>{{ number_format($product->price * $product->quantity, 2) }}</h6>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <h5 class="text-center font-weigth-bold">SUBTOTAL</h5>
                                    </td>
                                    <td>
                                        <h5 class="text-center">{{ $subtotal }}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <h5 class="text-center font-weigth-bold">IVA</h5>
                                    </td>
                                    <td>
                                        <h5 class="text-center">{{ $iva }}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <h5 class="text-center font-weigth-bold">TOTALES</h5>
                                    </td>
                                    <td>
                                        <h5 class="text-center">{{ $total }}</h5>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light close-btn" data-dismiss="modal">CERRAR</button>

                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="edit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #3B3F5C">
                    <h5 class="modal-title text-white">
                        <b>Finalizar presupuesto</b>
                    </h5>
                    <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
                </div>
                <div class="modal-body">

                    @php
                        $new_bs = floatval($bs ?? 0);
                        $new_cash = floatval($cash ?? 0);
                        $new_total = floatval($total ?? 0);
                        $bs_to_usd = $new_bs > 0 ? round($new_bs / $currency, 2) : 0;
                        $total_to_pay = round($new_total - $new_cash - $bs_to_usd, 2);
                        $total_to_pay_bs = round(round($new_total - $new_cash - $bs_to_usd, 2) * $currency, 2);
                        $total_change_usd = abs($total_to_pay < 0 ? $total_to_pay : 0);
                        $total_change_bs = $total_change_usd !== 0 ? $total_change_usd * $currency : 0;
                    @endphp
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Monto a pagar $</label>
                                <input type="number" disabled value="{{ $total }}" class="form-control"
                                    placeholder="Ej: 10">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Monto a pagar Bs</label>
                                <input type="number" disabled value="{{ $total * $currency }}" class="form-control"
                                    placeholder="Ej: 10">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Total a pagar $</label>
                                <input type="number" disabled value="{{ $total_to_pay > 0 ? $total_to_pay : 0 }}"
                                    placeholder="Ej: 10" @class(['form-control', 'text-danger' => $total_to_pay > 0])>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Total a pagar Bs</label>
                                <input type="number" disabled value="{{ $total_to_pay_bs > 0 ? $total_to_pay_bs : 0 }}"
                                    placeholder="Ej: 10" @class(['form-control', 'text-danger' => $total_to_pay_bs > 0])>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Monto pagado $</label>
                                <input type="number" wire:model="cash" class="form-control" placeholder="Ej: 10">
                                @error('cash')
                                    <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Monto pagado Bs</label>
                                <input type="number" wire:model="bs" class="form-control" placeholder="Ej: 10">
                                @error('bs')
                                    <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Cambio $</label>
                                <input type="number" disabled value="{{ $total_change_usd }}" class="form-control"
                                    placeholder="Ej: 10">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Cambio bs</label>
                                <input type="number" disabled value="{{ $total_change_bs }}" class="form-control"
                                    placeholder="Ej: 10">
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light close-btn" data-dismiss="modal">CERRAR</button>
                    <button type="button" wire:click="update" class="btn btn-dark close-modal">ACTUALIZAR</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>Cuentas por pagar</b>
                </h4>
            </div>

            @include('common.searchbox')

            <div class="row">
                <div class="col-4">
                    <h6>Elige el tipo de reporte</h6>
                    <div class="form-group">
                        <select wire:model="reportType" class="form-control">
                            <option value="0">Ventas del dia</option>
                            <option value="1">Ventas por fecha</option>
                        </select>
                    </div>
                </div>
                @if ($reportType === '1')
                    <div class="col-4">
                        <h6>Fechas desde</h6>
                        <div class="form-group">
                            <input type="date" wire:model="fromDate" class="form-control flatpickr"
                                placeholder="Click para elegir" min=""
                                max="{{ $toDate ? \Carbon\Carbon::createFromFormat('Y-m-d', $toDate)->subDay()->format('Y-m-d') : now()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <h6>Fechas hasta</h6>
                        <div class="form-group">
                            <input type="date" wire:model="toDate" class="form-control flatpickr"
                                placeholder="Click para elegir" min="{{ $fromDate }}" max="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                @endif
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <div class="row ml-0">
                        <button class="btn btn-primary" wire:click="download" type="button">Excel</button>
                        <button class="btn btn-primary" wire:click="pdf" type="button">PDF</button>
                    </div>
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white">CLIENTE</th>
                                <th class="table-th text-white">TOTAL</th>
                                <th class="table-th text-white">PAGADO</th>
                                <th class="table-th text-white">CAMBIO</th>
                                <th class="table-th text-white">ESTADO</th>
                                <th class="table-th text-white">PRODUCTOS</th>
                                <th class="table-th text-white">FECHA</th>
                                <th class="table-th text-white">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($budgets as $budget)
                                                        @php
                                                            $payed = $budget->cash > 0 ? "{$budget->cash}$" : '-';

                                                            if ($payed === '-') {
                                                                $payed = $budget->bs > 0 ? "{$budget->bs}Bs" : '-';
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <h6>{{ $budget->client->name }}</h6>
                                                            </td>
                                                            <td>
                                                                <h6>{{ $budget->total }}$</h6>
                                                            </td>
                                                            <td>
                                                                <h6>{{ $payed }}</h6>
                                                            </td>
                                                            <td>
                                                                <h6>{{ $budget->change > 0 ? "{$budget->change}$" : '-' }}</h6>
                                                            </td>
                                                            <td>
                                                                <h6>{{ $statuses[$budget->status] }}</h6>
                                                            </td>
                                                            <td>
                                                                <h6>{{ $budget->getTotalProducts() }}</h6>
                                                            </td>
                                                            <td>
                                                                <h6 class="text-left">{{ $budget->created_at->format('d-m-Y') }}</h6>
                                                            </td>
                                                            <td class="text-center">
                                                                <x-edit_button wire:click="edit({{ $budget->id }})" />
                                                                <x-see_button wire:click="products({{ $budget->id }})" />
                                                            </td>
                                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $budgets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.livewire.on('show-modal', msg => {
            $('#edit').modal('show')
        });
        window.livewire.on('close-modal', msg => {
            $('#edit').modal('hide')
        });
        window.livewire.on('show-products', msg => {
            $('#modalDetails').modal('show')
        });
    });
</script>