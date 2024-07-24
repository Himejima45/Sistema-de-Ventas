@include('common.modalHead')

<div class="row">

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" wire:model.lazy="name" class="form-control" placeholder="Ej: Pedro">
            @error('name') <span class="text-danger er">{{$message}}</span>
                
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Apellido</label>
            <input type="text" wire:model.lazy="last_name" class="form-control" placeholder="Ej: Carrizal">
            @error('last_name') <span class="text-danger er">{{$message}}</span>
                
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Cedula</label>
            <input type="text" wire:model.lazy="document" class="form-control" placeholder=" Ej: 25789463">
            @error('document') <span class="text-danger er">{{$message}}</span>
                
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Nro. Telefono</label>
            <input type="text" wire:model.lazy="phone" class="form-control" placeholder=" Ej: 04127888844">
            @error('phone') <span class="text-danger er">{{$message}}</span>
                
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Direccion</label>
            <input type="text" wire:model.lazy="address" class="form-control" placeholder="Ej: Maracay">
            @error('address') <span class="text-danger er">{{$message}}</span>
                
            @enderror
        </div>
    </div>



</div>

@include('common.modalFooter')