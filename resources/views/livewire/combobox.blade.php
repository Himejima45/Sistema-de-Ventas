<div class="col">
    <div class="form-group">
        <div class="input-group mb-3">
            <input type="text" wire:model.live.debounce.250ms="searched" class="form-control"
                placeholder="Buscar productos..." wire:keyup.enter="addOption({{ $options[0]['id'] ?? 0 }})"
                wire:keyup.escape="resetUI()">
            <div class="input-group-prepend">
                <button wire:click="resetUI()" class="btn btn-sm">Limpiar</button>
            </div>
        </div>

    </div>


    @if (!is_null($selected) && $selected->count() > 0)
        <table class="table table-bordered table-striped mt-1">
            <thead class="text-white" style="background: #3B3F5C">
                <tr>
                    <th class="table-th text-white">CODIGO</th>
                    <th class="table-th text-white">NOMBRE</th>
                    <th class="table-th text-white">COSTE</th>
                    <th class="table-th text-white">CANTIDAD</th>
                    <th class="table-th text-white">ACCIONES</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($selected as $option)
                    <tr>
                        <td>
                            <h6>{{ $option['barcode'] }}</h6>
                        </td>
                        <td>
                            <h6>{{ $option['name'] }}</h6>
                        </td>
                        <td>
                            <h6>0</h6>
                        </td>
                        <td>
                            <h6>0</h6>
                        </td>
                        <td class="text-center">

                            <button wire:click="removeOption({{ $option['id'] }})" class="btn btn-danger mtmobile"
                                title="Borrar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if (!is_null($options))
        <ul class="list-group mt-2">
            @if ($options->count() > 0)
                <p>Productos encontrados <b>{{ $options->count() }}</b></p>
            @endif
            @foreach ($options as $option)
                <li class="list-group-item" wire:click="addOption({{ $option['id'] }})">
                    {{ $option['name'] }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
