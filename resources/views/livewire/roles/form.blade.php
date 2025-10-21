<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #3B3F5C">
                <h5 class="modal-title text-white">
                    <b>{{ $componentName }}</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR' }}
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <span><span class="font-weight-bold">Nota</span>: Los campos marcados con (*) son requeridos</span>
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="roleName">Nombre <span class="text-danger font-weight-bold">*</span></label>
                            <input type="text" id="roleName" wire:model.lazy="roleName" class="form-control"
                                placeholder=" Ej; Admin" maxlength="255">
                            @error('roleName')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-light close-btn"
                    data-dismiss="modal">CERRAR</button>
                @if ($selected_id < 1)
                    <button type="button" wire:click.prevent="CreateRole()"
                        class="btn btn-dark close-modal">GUARDAR</button>
                @else
                    <button type="button" wire:click.prevent="UpdateRole()"
                        class="btn btn-dark close-modal">ACTUALIZAR</button>
                @endif
            </div>
        </div>
    </div>
</div>