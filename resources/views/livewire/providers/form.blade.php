@include('common.modalHead')

<div class="row">
    <div class="col-12">
        <span><span class="font-weight-bold">Nota</span>: Los campos marcados con (*) son requeridos</span>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Nombre <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="name" class="form-control" placeholder="Ej: Distribuidora Romero">
            @error('name')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Dirección <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="address" class="form-control" placeholder="Ej: Calle Colón">
            @error('address')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Documento <span class="text-danger font-weight-bold">*</span></label>
            <select name="document" id="document" class="form-control" wire:model="document">
                <option value="">Seleccionar</option>
                <option value="J">J</option>
                <option value="V">V</option>
                <option value="G">G</option>
                <option value="E">E</option>
            </select>
            @error('document')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Cedula <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="rif" class="form-control" placeholder=" Ej: 25789463">
            @error('rif')
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

</div>

@include('common.modalFooter')