<div class="connect-sorting">

    <div class="connect-sorting-content">
        <div class="card simple-title-task ui-sortable-handle">
            <div class="card-body">

                @if ($total > 0)

                    <div class="table-responsive tblscroll" style="max-height: 650px; overflow:hidden">
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: #3b3f5c">
                                <tr>
                                    <th width="10%" class="table-th text-white">IMAGEN</th>
                                    <th width="15%" class="table-th text-white">DESCRIPCION</th>
                                    <th class="table-th text-center text-white">PRECIO</th>
                                    <th width="13%" class="table-th text-center text-white">CANTIDAD</th>
                                    <th class="table-th text-center text-white">IMPORTE</th>
                                    <th class="table-th text-center text-white">ACCIONES</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($cart as $item)
                                    <tr>
                                        <td class="text-center table-th">
                                            @if (count($item['attributes']) > 0)
                                                @if ($item['attributes'][0] !== 'img.png')
                                                    <span>
                                                        <img src="{{ $item['attributes'][0] }}" alt="imagen de producto"
                                                            height="90" width="90" class="ronded">
                                                    </span>
                                                @else
                                                    <p>Imágen pendiente</p>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <h6>{{ $item['name'] }}</h6>
                                        </td>
                                        <td class="text-center">${{ number_format($item['price'], 2) }}</td>
                                        <td>
                                            <input type="number" wire:model.live="cart.{{ $item['id'] }}.quantity"
                                                wire:change="updateQty({{ $item['id'] }}, $event.target.value)"
                                                style="font-size: 1rem!important" class="form-control text-center"
                                                value="{{ $cart[$item['id']]['quantity'] }}">
                                        </td>
                                        <td class="text-center">
                                            <h6 wire:model.live="cart.{{ $item['id'] }}.quantity">
                                                ${{ number_format($item['price'] * $item['quantity'], 2) }}

                                            </h6>
                                        </td>
                                        <td class="text-center">
                                            <div class="row">

                                                <button
                                                    onclick="DeleteItem('{{ $item['id'] }}', 'removeItem', 'CONFIRMAR ELIMINAR EL PRODUCTO?')"
                                                    class="btn btn-danger mbmobile">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <button wire:click.prevent="decreaseQty({{ $item['id'] }})"
                                                    class="btn btn-light mbmobile">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <button wire:click.prevent="increaseQty({{ $item['id'] }})"
                                                    class="btn btn-success mbmobile">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <h5 class="text-center text-muted">Agrega productos a la venta</h5>
                @endif

                <div wire:loading.inline wire:target="saveSale">
                    <h4 class="text-danger text-center">Guardando Venta...</h4>
                </div>
            </div>

        </div>
    </div>

</div>
