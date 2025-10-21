@include('common.modalHead')

<div class="row">
    <div class="col-12">
        <span><span class="font-weight-bold">Nota</span>: Los campos marcados con (*) son requeridos</span>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label>Monto <span class="text-danger font-weight-bold">*</span></label>
            <input type="number" wire:model.lazy="value" class="form-control" placeholder="Ej: 45">
            @error('value')
                <span class="text-danger errror">{{ $message }}</span>
            @enderror
        </div>
    </div>

</div>

@include('common.modalFooter')