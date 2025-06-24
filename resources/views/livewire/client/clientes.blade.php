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
                                <th class="table-th text-white">APELLIDO</th>
                                <th class="table-th text-white">CEDULA</th>
                                <th class="table-th text-white">NRO.TELE</th>
                                <th class="table-th text-white">DIRECCION</th>
                                <th class="table-th text-white">CORREO</th>
                                <th class="table-th text-white">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <td>
                                        <h6>{{ $client->name }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $client->last_name }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $client->document }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $client->phone }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $client->address }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $client->email }}</h6>
                                    </td>

                                    <td class="text-center">
                                        <x-edit_button wire:click="Edit({{ $client->id }})" />
                                        <x-delete_button
                                            onclick="Confirm({{ $client->id }}, 'Eliminar', '¿Está seguro de eliminar a este cliente?')" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    @include('livewire.client.form')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.livewire.on('modal-show', msg => {
                $('#theModal').modal('show')
            });
        });
    </script>
</div>