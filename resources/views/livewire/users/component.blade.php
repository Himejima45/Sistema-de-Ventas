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
                                <th class="table-th text-white text-center">USUARIO</th>
                                <th class="table-th text-white text-center">TELEFONO</th>
                                <th class="table-th text-white text-center">EMAIL</th>
                                <th class="table-th text-white text-center">ESTATUS</th>
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
                            @foreach ($data as $r)
                                <tr>
                                    <td>
                                        <h6>{{ $r->name }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $r->phone }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $r->email }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $r->active == 1 ? 'badge-success' : 'badge-danger' }}
                                                            text-uppercase">{{ $r->active ? 'Activo' : 'Inactivo' }}</span>
                                    </td>

                                    <td class="text-center">
                                        <x-edit_button wire:click="Edit({{ $r->id }})" />

                                        @if ($r->email !== 'admin@email.com')
                                            <x-delete_button onclick="Confirm('{{ $r->id }}')" />
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.users.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.livewire.on('user-added', Msg => {
            $('#theModal').modal('hide')
            noty(Msg)
        });
        window.livewire.on('user-updated', Msg => {
            $('#theModal').modal('hide')
            noty(Msg)
        });
        window.livewire.on('user-deleted', Msg => {
            noty(Msg)
        });
        window.livewire.on('hide-modal', Msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('users-withsales', Msg => {
            noty(Msg)
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
                window.livewire.emit('Destroy', $id)
                swal.close()
            }
        })
    }
</script>