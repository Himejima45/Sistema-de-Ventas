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
                        <thead class="text-white" style="background: #3B3F5C;">
                            <tr>
                                <th class="table-th text-white">ID</th>
                                <th class="table-th text-white text-center">DESCRIPCIÓN</th>
                                <th class="table-th text-white text-center">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @php
                                $translations = [
                                    'Admin' => 'Administrador',
                                    'Client' => 'Cliente',
                                    'Employee' => 'Empleado',
                                ];
                            @endphp
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        <h6>{{ $role->id }}</h6>
                                    </td>
                                    <td>
                                        <h6>
                                            {{ array_key_exists($role->name, $translations) ? $translations[$role->name] : $role->name }}
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <x-edit_button wire:click="Edit({{ $role->id }})" />
                                        <x-delete_button onclick="Confirm({{ $role->id }})" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.roles.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.livewire.on('role-added', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('role-updated', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('role-deleted', msg => {
            $('#theModal').modal('hide')
        });

        window.livewire.on('role-exists', msg => {
            noty(Msg)
        });
        window.livewire.on('role-error', msg => {
            noty(Msg)
        });
        window.livewire.on('hide.modal', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('show.modal', msg => {
            $('#theModal').modal('show')
        });
    });

    function Edit (id) {
        // swal({
        //     title: 'EDITAR',
        //     text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
        //     type: 'warning',
        //     showCancelButton: true,
        //     cancelButtonText: 'Cerrar',
        //     cancelButtonColor: '#fff',
        //     confirmButtonColor: '#3B3F5C',
        //     confirmButtonText: 'Aceptar'
        // }).then(function(result) {
        //     if (result.value) {
        //         window.livewire.emit('Edit', id)
        //         swal.close()
        //     }
        // })
        window.livewire.emit('Edit', id)
    }

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
</script>