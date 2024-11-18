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
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal"
                            data-target="#theModal">AGREGAR</a>
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
                    <button wire:click.prevent="searchByDate" class="btn btn-primary">Buscar</button>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C;">
                            <tr>
                                <th class="table-th text-white">FECHA</th>
                                <th class="table-th text-white">COSTE</th>
                                <th class="table-th text-white">PAGADO</th>
                                <th class="table-th text-white">ESTADO</th>
                                <th class="table-th text-white">TIPO DE PAGO</th>
                                <th class="table-th text-white">PRODUCTOS</th>
                                <th class="table-th text-white">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($purchases as $item)
                                <tr>
                                    <td>
                                        <h6 class="text-left">{{ $item->created_at->format('d-m-Y') }}</h6>
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
                                        <a href="javascript:void(0)" wire:click="editPurchase({{ $item->id }})"
                                            class="btn btn-primary mtmobile" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" wire:click="showProducts({{ $item->id }})"
                                            class="btn btn-info mtmobile" title="Ver Productos">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $purchases->links() }}
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
    });
</script>
