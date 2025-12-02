@include('common.modalHead')

<div class="row">
    <div class="col-12">
        <span><span class="font-weight-bold">Nota</span>: Los campos marcados con (*) son requeridos</span>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Codigo <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="barcode" class="form-control" placeholder="Ej: 789">
            @error('barcode')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Costo ($) <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" data-type="currency" wire:model.lazy="cost" class="form-control" placeholder="Ej: 0.00">
            @error('cost')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Precio ($) <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" data-type="currency" wire:model.lazy="price" class="form-control" placeholder="Ej: 10">
            @error('price')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group">
            <label>Nombre <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" wire:model.lazy="name" class="form-control" placeholder="Ej: Caucho rin 20">
            @error('name')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Stock <span class="text-danger font-weight-bold">*</span></label>
            <input type="number" wire:model.lazy="stock" min="1" class="form-control" placeholder="Ej: 10">
            @error('stock')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Stock Mínimo <span class="text-danger font-weight-bold">*</span></label>
            <input type="number" wire:model.lazy="min_stock" class="form-control" placeholder="Ej: 2">
            @error('min_stock')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Días de garantía <span class="text-danger font-weight-bold">*</span></label>
            <input type="number" wire:model.lazy="warranty" class="form-control" placeholder="Ej: 10">
            @error('warranty')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Categoria <span class="text-danger font-weight-bold">*</span></label>
            <select name="category_id" wire:model='category_id' class="form-control"
                wire:change='setCategory($event.target.value)'>
                <option value="Elegir" selected disabled>Elegir</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"> {{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Proveedor <span class="text-danger font-weight-bold">*</span></label>
            <select wire:model='provider_id' name="provider_id" class="form-control">
                <option value="Elegir" selected disabled>Elegir</option>
                @foreach ($providers as $provider)
                    <option value="{{ $provider->id }}"> {{ $provider->name }}</option>
                @endforeach
            </select>
            @error('provider_id')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <label for="">Imagen <span class="text-danger font-weight-bold">*</span></label>
        <div class="form-group custom-file">
            <input id="image" type="file" class="custom-file-input form-control" wire:model="image"
                accept=".png,.jpg,.jpeg">
            <label class="custom-file-label">Imagen {{ $image }}</label>
            @error('image')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>

        @if (file_exists('storage/products/' . $image) && $image)
            <div class="mt-2">
                <img src="{{ asset('storage/products/' . $image) }}" alt="Current Image"
                    style="width: 100px; height: auto;">
            </div>
        @elseif ($image)
            <div class="mt-2">
                @if (is_string($image))
                    @php
                        $defaultImage = explode('.', $image)[1] === 'jpeg';
                        $image = $defaultImage
                            ? asset('/assets/products/' . $image)
                            : asset('storage/products/' . $image);
                    @endphp
                    <img src="{{ $image }}" alt="Current Image" style="width: 100px; height: auto;">
                @else
                    <img src="{{ $image->temporaryUrl() }}" alt="Current Image" style="width: 100px; height: auto;">
                @endif
            </div>
        @endif
    </div>

</div>
@include('common.modalFooter')