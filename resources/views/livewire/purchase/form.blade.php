<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>{{ $componentName }}</b> | CREAR
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Costo</label>
                            <input type="text" data-type="currency" wire:model.lazy="cost" class="form-control"
                                placeholder="Ej: 57.33">
                            @error('cost')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Pagado</label>
                            <input type="text" data-type="currency" wire:model.lazy="payed" class="form-control"
                                placeholder="Ej: 13.00">
                            @error('payed')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class='col-sm-12 col-md-6'>
                        <div class='form-group'>
                            <label>Estado</label>
                            <select wire:model='status' name='status' class='form-control'>
                                <option value="" selected>Elegir</option>
                                <option value='PENDING'>Pendiente</option>
                                <option value='GOING'>En Proceso</option>
                                <option value='RECEIVED'>Recibido</option>
                            </select>
                            @error('status')
                                <span class='text-danger er'>{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class='col-sm-12 col-md-6'>
                        <div class='form-group'>
                            <label>Tipo de pago</label>
                            <select wire:model='payment_type' name='payment_type' class='form-control'>
                                <option value="" selected>Elegir</option>
                                <option value='CASH'>Efectivo</option>
                                <option value='TRANSFER'>Transferencia</option>
                            </select>
                            @error('payment_type')
                                <span class='text-danger er'>{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Dynamic Products Section -->
                    <div class='col-sm-12'>
                        <h5>Productos</h5>
                        <button type='button' wire:click.prevent='addProduct()' class='btn btn-primary mb-3'>Agregar
                            Producto</button>
                        @if (!is_null($products))
                            @foreach ($products as $index => $product)
                                <div class='row' id='product-row-{{ $index }}'>
                                    <div class='col-md-5'>
                                        <div class='form-group'>
                                            <select wire:model.lazy='products.{{ $index }}.name'
                                                name='products.{{ $index }}.name' class='form-control'>
                                                <option value="" selected>Elegir</option>
                                                @foreach ($productsList as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <span class='text-danger er'>{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @error("products.$index.name")
                                            <span class='text-danger er'>{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class='col-md-3'>

                                        <input type='number' wire:model.lazy='products.{{ $index }}.quantity'
                                            class='form-control' placeholder='Cantidad' min='1' />
                                        @error("products.$index.quantity")
                                            <span class='text-danger er'>{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class='col-md-3'>
                                        <input type='text' data-type='currency'
                                            wire:model.lazy='products.{{ $index }}.price' class='form-control'
                                            placeholder='Ej: 7.00' />
                                        @error("products.$index.price")
                                            <span class='text-danger er'>{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Remove Button -->
                                    <div class='col-md-1'>
                                        <button type='button' wire:click.prevent='removeProduct({{ $index }})'
                                            class='btn btn-danger btn-sm'>X</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn"
                        data-dismiss="modal">CERRAR</button>
                    <button type="button" wire:click.prevent="Store()"
                        class="btn btn-dark close-modal">GUARDAR</button>
                </div>
            </div>
        </div>
    </div>
</div>
