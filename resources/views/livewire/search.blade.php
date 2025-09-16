<div class="row justify-content-between">
    <div class="col-lg-4 col-md-4 col-sm-12">
        <livewire:combobox-products :cart="$cart" />
        @error('client')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
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