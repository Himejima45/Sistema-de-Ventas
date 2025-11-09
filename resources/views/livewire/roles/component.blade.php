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
                                <th style="width: 100px;" class="table-th text-white text-center">ESTADO</th>
                                <th class="table-th text-white text-center">DESCRIPCIÓN</th>
                                <th class="table-th text-white text-center">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        <h6>{{ $role->id }}</h6>
                                    </td>
                                    <td>
                                        @if ($role->is_active)
                                            <span class="badge badge-pill badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <h6>
                                            {{  $role->name }}
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <x-edit_button wire:click="Edit({{ $role->id }})" />

                                        @if ($role->name !== 'Admin')
                                            <button wire:click="toggle({{ $role->id }})" class="btn btn-warning mtmobile"
                                                title="{{ $role->is_active ? 'Deshabilitar' : 'Habilitar' }}">
                                                @if ($role->is_active)
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M18.36 6.64A9 9 0 0 1 20.77 15" />
                                                        <path d="M6.16 6.16a9 9 0 1 0 12.68 12.68" />
                                                        <path d="M12 2v4" />
                                                        <path d="m2 2 20 20" />
                                                    </svg>
                                                @else
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M12 2v10" />
                                                        <path d="M18.4 6.6a9 9 0 1 1-12.77.04" />
                                                    </svg>
                                                @endif
                                            </button>

                                            <x-delete_button onclick="Confirm({{ $role->id }})" />
                                        @endif

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