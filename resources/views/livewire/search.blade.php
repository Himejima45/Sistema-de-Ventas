<div class="row justify-content-between">
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <span class="input-group-text input-gp">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <input id="code" type="text" wire:keydown.enter.prevent="$emit('scan-code', $('#code').val())"
                class="form-control search-form-control  ml-lg-auto" placeholder="Codigo producto...">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-12">
        <select name="sale_type" id="sale_type" class="form-control" wire:model="sale_type">
            <option value="null">Seleccionar</option>
            <option value="SALE">Venta</option>
            <option value="BUDGET">Presupuesto</option>
        </select>
        @error('sale_type')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-lg-4 col-md-4 col-sm-12">
        <livewire:combobox-clients />
        {{-- <select name="client" id="client" class="form-control" wire:model="client"
            wire:change='selectClient($event.target.value)'>
            <option value="Elegir">Seleccionar</option>

            @foreach ($clients as $clientRow)
            <option value="{{ $clientRow->id }}" selected="{{ is_null($client) ? false : $client === $clientRow->id }}">
                {{ $clientRow->document }} {{ $clientRow->name }} {{ $clientRow->last_name }}
            </option>
            @endforeach
        </select> --}}
        @error('client')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        livewire.on('scan-code', action => {
            $('#code').val('')
        })
    })
</script>