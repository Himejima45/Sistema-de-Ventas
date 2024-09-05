@include('common.modalHead')

<div class="row">

    <div class="col-sm-12">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">

                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="name" class="form-control" placeholder=" Ej; Cauchos" maxlength="255">
        </div>
        @error('name')
            <span class="text-danger error">{{ $message }}</span>
        @enderror

    </div>

</div>

@include('common.modalFooter')
