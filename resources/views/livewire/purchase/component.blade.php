<div class="row sales layout-top-spacing">

    @include('livewire.purchase.edit-modal')
    @include('livewire.purchase.show-products-modal')

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <x-add_button />
                    </li>
                </ul>
            </div>

            <div class="row mb-3">
                <div class="col-md-5">
                    <input type="date" wire:model.lazy="startDate" class="form-control" placeholder="Fecha de Inicio"
                        max="{{ $endDate ?? now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-5">
                    <input type="date" wire:model.lazy="endDate" class="form-control" placeholder="Fecha de Fin"
                        min="{{ $startDate ?? 'undefined' }}" max="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <button wire:click.prevent="searchByDate" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        Buscar</button>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C;">
                            <tr>
                                <th class="table-th text-white">FECHA</th>
                                <th class="table-th text-white">PROVEEDOR</th>
                                <th class="table-th text-white">COSTE</th>
                                <th class="table-th text-white">PAGADO</th>
                                <th class="table-th text-white">ESTADO</th>
                                <th class="table-th text-white">TIPO DE PAGO</th>
                                <th class="table-th text-white">PRODUCTOS</th>
                                <th class="table-th text-white">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        <h6 class="text-left">{{ $item->created_at->format('d-m-Y') }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $item->provider_model->name ?? '-' }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $item->cost }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $item->payed }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">
                                            {{ $item->status === 'PENDING' ? 'Pendiente' : ($item->status === 'GOING' ? 'En proceso' : 'Recibido') }}
                                        </h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">
                                            {{ $item->payment_type === 'CASH' ? 'Efectivo' : 'Transferencia' }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">
                                            {{ count($item->products) }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <x-edit_button wire:click="editPurchase({{ $item->id }})" />
                                        <x-see_button wire:click="showProducts({{ $item->id }})" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.purchase.form')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('show-products', msg => {
            $('#productsModal').modal('show')
        });
        window.livewire.on('show-edit', msg => {
            $('#editModal').modal('show')
        });
        window.livewire.on('hide-edit', msg => {
            $('#editModal').modal('hide')
        });
        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide')
        });
    });
</script>
