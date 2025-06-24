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

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white">MONTO</th>
                                <th class="table-th text-white">FECHA</th>
                                <th class="table-th text-white">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($currencies as $currency)
                                <tr>
                                    <td>
                                        <h6>{{ $currency->value }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $currency->created_at->format('Y-m-d') }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <x-edit_button wire:click="Edit({{ $currency->id }})" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    @include('livewire.currency.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('currency-added', msg => {
            $('#theModal').modal('hide')
            Success('Registrado', "Se ha añadido una nueva tasa")
        });
        window.livewire.on('currency-deleted', msg => {
            $('#theModal').modal('hide')
            Deleted()
        });
        window.livewire.on('currency-updated', msg => {
            $('#theModal').modal('hide')
            Success('Actualizado', "Se ha actualizado la tasa seleccionada")
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
            text: "Se ha eliminado la tasa seleccionada",
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