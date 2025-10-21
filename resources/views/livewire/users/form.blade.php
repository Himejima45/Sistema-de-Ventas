@include('common.modalHead')

<div class="row">
    <div class="col-12">
        <span><span class="font-weight-bold">Nota</span>: Los campos marcados con (*) son requeridos</span>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Nombre <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="name" class="form-control" placeholder="Ej: Pedro Azuaje">
            @error('name')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Telefono <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="phone" class="form-control" placeholder="Ej: 04124897786">
            @error('phone')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Email <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="email" class="form-control" placeholder="Ej: usuario@email.com">
            @error('email')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Contraseña <span class="text-danger font-weight-bold">*</span></label>
            <input type="password" wire:model.lazy="password" class="form-control" placeholder="Ej: ****** ">
            @error('password')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

</div>
@include('common.modalFooter')