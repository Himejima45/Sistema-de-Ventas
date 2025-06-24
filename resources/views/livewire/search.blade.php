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

    <div class="col-lg-4 col-md-4 mb-4 col-sm-12">
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