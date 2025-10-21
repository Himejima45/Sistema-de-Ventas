@include('common.modalHead')

<div class="row">

    <div class="col-sm-12">
        <span><span class="font-weight-bold">Nota</span>: Los campos marcados con (*) son requeridos</span>
    </div>

    <div class="col-sm-12">
        <div class="form-group">
            <label>Nombre <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="name" class="form-control" placeholder=" Ej; Cauchos" maxlength="255">
            @error('name')
                <span class="text-danger error">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')