@include('common.modalHead')

<div class="row">
    <div class="col-12">
        <span><span class="font-weight-bold">Nota</span>: Los campos marcados con (*) son requeridos</span>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Nombre <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="name" class="form-control" placeholder="Ej: Pedro">
            @error('name')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Apellido <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="last_name" class="form-control" placeholder="Ej: Carrizal">
            @error('last_name')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Correo electrónico <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="email" class="form-control" placeholder="Ej: correo@email.com">
            @error('email')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Cedula <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="document" class="form-control" placeholder=" Ej: 25789463">
            @error('document')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Nro. Telefono <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="phone" class="form-control" placeholder=" Ej: 04127888844">
            @error('phone')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Direccion <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="address" class="form-control" placeholder="Ej: Maracay">
            @error('address')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Contraseña <span class="text-danger font-weight-bold">*</span></label>
            <input type="password" wire:model.lazy="password" class="form-control" placeholder="Ej: **********">
            @error('password')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Confirmar contraseña <span class="text-danger font-weight-bold">*</span></label>
            <input type="password" wire:model.lazy="password_confirmation" wire:loading.remove class="form-control"
                autocomplete="new-password" placeholder="Ej: **********">
            @error('password_confirmation')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>



</div>

@include('common.modalFooter')