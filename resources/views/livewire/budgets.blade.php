@php
    $statuses = ['PENDING' => 'Pendiente', 'PAID' => 'Pagado', 'CANCELED' => 'Cancelado'];
@endphp

<div class="row sales layout-top-spacing">
    <x-home_button />

    {{-- re-usable carts components --}}
    <x-carts.preview_sale :sale="$selectedSale" :activeTab="$activeTab" :modalProducts="$modalProducts"
        :modalPayments="$modalPayments" />

    <x-carts.sale_payments :selectedId="$selected_id" :totalSale="$totalSale" :currencyId="$currency_id"
        :netPayment="$this->getNetPaymentForView()" :remainingAmount="$this->getRemainingAmountForView()" />

    {{-- your existing table / filters --}}
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title"><b>Cuentas por pagar</b></h4>
            </div>

            <!-- Search Box -->
            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <input type="text" wire:model.debounce.300ms="search" class="form-control"
                            placeholder="Buscar por nombre del cliente...">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-4">
                    <h6>Elige el tipo de reporte</h6>
                    <select wire:model="reportType" class="form-control">
                        <option value="0">Ventas del día</option>
                        <option value="1">Ventas por fecha</option>
                    </select>
                </div>
                @if ($reportType === '1')
                    <div class="col-4">
                        <h6>Fechas desde</h6>
                        <input type="date" wire:model="fromDate" class="form-control flatpickr">
                    </div>
                    <div class="col-4">
                        <h6>Fechas hasta</h6>
                        <input type="date" wire:model="toDate" class="form-control flatpickr">
                    </div>
                @endif
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <div class="row ml-0 mb-3">
                        <button class="btn btn-primary" wire:click="download">Excel</button>
                        <button class="btn btn-primary ml-2" wire:click="pdf">PDF</button>
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
                                <tr>
                                    <td>
                                        <h6>{{ $budget->client->name }}</h6>
                                    </td>
                                    <td>
                                        <h6>${{ number_format($budget->total, 2) }}</h6>
                                    </td>
                                    <td>
                                        <h6>${{ number_format($budget->total_paid_usd, 2) }}</h6>
                                    </td>
                                    <td>
                                        <h6>${{ number_format($budget->total_change_usd, 2) }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $statuses[$budget->status] }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $budget->getTotalProducts() }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-left">{{ $budget->updated_at->format('d-m-Y') }}</h6>
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
        window.livewire.on('open', msg => {
            $('#theModal').modal('show');
        });

        window.livewire.on('close', msg => {
            $('#theModal').modal('hide');
            // Reset the modal when it's closed
            livewire.emit('resetModal');
        });

        window.livewire.on('show-sale-preview', msg => {
            $('#salePreviewModal').modal('show');
        });

        // Reset modal when it's hidden via backdrop click or ESC key
        $('#theModal').on('hidden.bs.modal', function () {
            livewire.emit('resetModal');
        });

        window.livewire.on('sales_found', msg => {
            function showMessageAlert (message) {
                swal({
                    type: "info",
                    title: 'Ventas encontradas',
                    text: message,
                    showConfirmButton: false
                })
            }

            showMessageAlert(msg)
        });
    });
</script>