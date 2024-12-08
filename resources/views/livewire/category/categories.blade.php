<div class="row sales layout-top-spacing">
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
                                <th class="table-th text-white">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>
                                        <h6>{{ $category->name }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <x-edit_button wire:click="Edit({{ $category->id }})" />
                                        @if ($category->products->count() < 1)
                                            <x-delete_button
                                                onclick="Confirm({{ $category->id }}, {{ count($category->products) }})" />
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.category.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('category-added', msg => {
            $('#theModal').modal('hide')
            Success('Registrado', "Se ha registrado los datos de la categoría")

        });
        window.livewire.on('category-updated', msg => {
            $('#theModal').modal('hide')
            Success('Actualizado', "Se ha actualizado los datos del cliente")
        });
        window.livewire.on('category-deleted', msg => {
            $('#theModal').modal('hide')
            Deleted()
        });
    });

    function Confirm(id, products) {
        if (products > 0) {
            swal('NO SE PUEDE ELIMINAR LA CATEGORIA PORQUE TIENES PRODUCTOS RELACIONADOS')
            return;
        }
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

    function Deleted() {
        swal({
            icon: "warning",
            type: "warning",
            title: "Eliminado",
            text: "Se ha eliminado al cliente seleccionado",
            showConfirmButton: false
        })
    }

    function Success(title, message) {
        swal({
            icon: "success",
            type: "success",
            title,
            text: message,
            showConfirmButton: false,
        })
    }
</script>
