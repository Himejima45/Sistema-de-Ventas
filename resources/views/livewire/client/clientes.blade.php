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
                            data-target="#theModal">AGREGAR</a>
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

                                        <a href="javascript:void(0)" wire:click="Edit({{ $client->id }})"
                                            class="btn btn-primary mtmobile" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- @if ($client->products->count() < 1)
                                    <a href="javascript:void(0)" onclick="Confirm({{$category->id, count($category->products)}})" class="btn btn-danger" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                    </a>
                                    @endif --}}
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('client-added', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('category-updated', msg => {
            $('#theModal').modal('hide')
        });


    });

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
