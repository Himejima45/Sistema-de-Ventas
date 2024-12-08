<div class="row sales layout-top-spacing">
    <div wire:ignore-self id="product-zoom" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #3B3F5C">
                    <h5 class="modal-title text-white">
                        <b>{{ $selectedProduct->name ?? '' }}</b>
                    </h5>
                    <button class="close" data-miss="modal" type="button" aria-label="Close">
                        <span class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (!is_null($selectedProduct) && !is_null($selectedProduct->getImage()))
                        <span class="d-flex justify-content-center mx-auto">
                            <img src="{{ $selectedProduct->getImage() }}" alt="imagen de ejemplo" height="400"
                                width="400" class="rounded">
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
                        <thead class="text-white" style="background: #3B3F5C;">
                            <tr>
                                <th class="table-th text-white">DESCRIPCIÓN</th>
                                <th class="table-th text-white text-center">CODIGO DE BARRA</th>
                                <th class="table-th text-white text-center">CATEGORIA</th>
                                <th class="table-th text-white text-center">GARANTÍA (DÍAS)</th>
                                <th class="table-th text-white text-center">PRECIO</th>
                                <th class="table-th text-white text-center">STOCK</th>
                                <th class="table-th text-white text-center">INV.MIN</th>
                                <th class="table-th text-white text-center">IMAGEN</th>
                                <th class="table-th text-white text-center">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($data as $product)
                                <tr>
                                    <td>
                                        <h6 class="text-left">{{ $product->name }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $product->barcode }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $product->category }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $product->warranty }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $product->price }}</h6>
                                    </td>
                                    <td>
                                        <h6
                                            class="text-center font-weight-bold @if ($product->stock < $product->min_stock) text-danger @else text-success @endif">
                                            {{ $product->stock }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="text-center">{{ $product->min_stock }}</h6>
                                    </td>

                                    <td class="text-center">
                                        @if (!is_null($product->getImage()))
                                            <span role="button" wire:click="zoom({{ $product->id }})">
                                                <img src="{{ $product->getImage() }}" alt="imagen de ejemplo"
                                                    height="70" width="80" class="rounded">
                                            </span>
                                        @else
                                            <p>Imágen pendiente...</p>
                                        @endif
                                    </td>
                                    <td class="text-center">

                                        <a href="javascript:void(0)" wire:click.prevent="Edit({{ $product->id }})"
                                            class="btn btn-primary mtmobile" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" onclick="Confirm('{{ $product->id }}')"
                                            class="btn btn-danger mtmobile" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
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
    @include('livewire.products.form')



</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('product-added', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('product-updated', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('product-deleted', msg => {
            //noty
        });

        window.livewire.on('modal-show', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('modal-hide', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('hidden.bs.modal', msg => {
            $('.er').css('display', 'none')
        });
        window.livewire.on('show-product-zoomed', msg => {
            $('#product-zoom').modal('show')
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
