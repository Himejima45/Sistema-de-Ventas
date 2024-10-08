<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal"
                            data-target="#theModal">Agregar</a>
                    </li>
                </ul>
            </div>

            @include('common.searchbox')

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C;">
                            <tr>
                                <th class="table-th text-white">ID</th>
                                <th class="table-th text-white text-center">DESCRIPCIÓN</th>
                                <th class="table-th text-white text-center">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>

                            @foreach ($permisos as $permiso)
                                <tr>
                                    <td>
                                        <h6>{{ $permiso->id }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $permiso->name }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" wire:click="Edit({{ $permiso->id }})"
                                            class="btn btn-primary mtmobile" title="Editar Registro">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" onclick="Confirm({{ $permiso->id }})"
                                            class="btn btn-danger mtmobile" title="Eliminar Registro">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $permisos->links() }}
                </div>
            </div>
        </div>
    </div>
    @Include('livewire.permisos.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('permiso-added', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('permiso-updated', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('permiso-deleted', msg => {
            $('#theModal').modal('hide')
        });

        window.livewire.on('permiso-exists', msg => {
            noty(Msg)
        });
        window.livewire.on('permiso-error', msg => {
            noty(Msg)
        });
        window.livewire.on('hide.modal', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('show.modal', msg => {
            $('#theModal').modal('show')
        });

    });

    function Edit(id) {
        window.livewire.emit('Edit', id)
    }

    function Confirm(id) {
        swal({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('Destroy', id)
                swal.close()
            }
        })
    }
</script>
