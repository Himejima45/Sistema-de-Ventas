@include('common.modalHead')

<div class="row">
    <div class="col-12">
        <span><span class="font-weight-bold">Nota</span>: Los campos marcados con (*) son requeridos</span>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Nombre <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror"
                placeholder="Ej: Pedro" required minlength="2" maxlength="30" pattern="^[\p{L}]+(?: [\p{L}]+)*$"
                title="Solo letras y espacios, entre 2 y 30 caracteres">
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
            <input type="email" wire:model.lazy="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="Ej: correo@email.com" required>
            @error('email')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Cedula <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="document" required minlength="6" maxlength="9" pattern="^\d"
                class="form-control" placeholder=" Ej: 25789463">
            @error('document')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Nro. Telefono <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="phone" class="form-control @error('phone') is-invalid @enderror"
                placeholder=" Ej: 04127888844" required minlength="11" maxlength="11" pattern="^\d{11}$"
                title="Debe tener exactamente 11 dígitos numéricos">
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
            <input type="password" wire:model.lazy="password"
                class="form-control @error('password') is-invalid @enderror" placeholder="Ej: **********" required
                minlength="3" maxlength="12" pattern="^(?=.*[a-zA-Z])(?=.*\d).{3,12}$"
                title="Entre 3 y 12 caracteres, con al menos una letra y un número">
            @error('password')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Confirmar contraseña <span class="text-danger font-weight-bold">*</span></label>
            <input type="password" wire:model.lazy="password_confirmation" class="form-control"
                placeholder="Ej: **********" required>
            @error('password_confirmation')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')