<div class="row sales layout-top-spacing">
    <x-home_button />

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

            @include('common.searchbox')

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white">NOMBRE</th>
                                <th class="table-th text-white">DIRECCIÓN</th>
                                <th class="table-th text-white">TELÉFONO</th>
                                <th class="table-th text-white">RIF</th>
                                <th class="table-th text-white">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($providers as $provider)
                                <tr>
                                    <td>
                                        <h6>{{ $provider->name }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $provider->address }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $provider->phone }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $provider->rif }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <x-edit_button wire:click="Edit({{ $provider->id }})" />
                                        @if (!$provider->getHasProductsAttribute())
                                            <x-delete_button
                                                onclick="Confirm({{ $provider->id }}, 'Eliminar', '¿Está seguro de eliminar a este cliente?')" />
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    @include('livewire.providers.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('provider-added', msg => {
            $('#theModal').modal('hide')
            Success('Registrado', "Se ha añadido un nuevo proveedor")
        });
        window.livewire.on('provider-deleted', msg => {
            $('#theModal').modal('hide')
            Deleted()
        });
        window.livewire.on('provider-updated', msg => {
            $('#theModal').modal('hide')
            Success('Actualizado', "Se ha actualizado el proveedor seleccionado")
        });
    });

    function Confirm (id) {
        swal({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function (result) {
            if (result.value)
            {
                window.livewire.emit('Destroy', id)
                swal.close()
            }
        })
    }

    function Deleted () {
        swal({
            icon: "warning",
            type: "warning",
            title: "Eliminado",
            text: "Se ha eliminado el proveedor seleccionado",
            showConfirmButton: false
        })
    }

    function Success (title, message) {
        swal({
            icon: "success",
            type: "success",
            title,
            text: message,
            showConfirmButton: false,
        })
    }
</script>