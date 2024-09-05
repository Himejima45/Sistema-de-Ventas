@include('common.modalHead')

<div class="row">

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" wire:model.lazy="name" class="form-control" placeholder="Ej: Distribuidora Romero">
            @error('name')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Dirección</label>
            <input type="text" wire:model.lazy="address" class="form-control" placeholder="Ej: Calle Colón">
            @error('address')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Cedula</label>
            <input type="text" wire:model.lazy="rif" class="form-control" placeholder=" Ej: 25789463">
            @error('rif')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Nro. Telefono</label>
            <input type="text" wire:model.lazy="phone" class="form-control" placeholder=" Ej: 04127888844">
            @error('phone')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Documento</label>
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

</div>

@include('common.modalFooter')
