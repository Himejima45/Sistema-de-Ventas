@include('common.modalHead')

<div class="row">

    <div class="col-12">
        <div class="form-group">
            <label>Monto</label>
            <input type="number" wire:model.lazy="value" class="form-control" placeholder="Ej: 45">
            @error('value')
                <span class="text-danger errror">{{ $message }}</span>
            @enderror
        </div>
    </div>

</div>

@include('common.modalFooter')
