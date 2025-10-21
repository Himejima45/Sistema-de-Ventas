<div class="row justify-content-between">
    <div class="col-12">
        <span><span class="font-weight-bold">Nota</span>: Los campos marcados con (*) son requeridos</span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <label>Productos <span class="text-danger font-weight-bold">*</span></label>
        <livewire:combobox-products :cart="$cart" />
        @error('client')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-lg-4 col-md-4 mb-4 col-sm-12">
        <label for="sale_type">Tipo de venta <span class="text-danger font-weight-bold">*</span></label>
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
        <label>Clientes <span class="text-danger font-weight-bold">*</span></label>
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