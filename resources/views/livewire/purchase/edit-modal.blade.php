<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #3B3F5C">
                <h5 class="modal-title text-white">Editar Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Pago</label>
                    <input type="text" wire:model.lazy="payed" class="form-control" placeholder="Ej: 57.33">
                    @error('payed')
                        <span class='text-danger'>{{ $message }}</span>
                    @enderror
                </div>

                <div class='form-group'>
                    <label>Estado</label>
                    <select wire:model='status' name='status' class='form-control'>
                        <option value="" selected disabled>Elegir</option>
                        <option value='PENDING'>Pendiente</option>
                        <option value='GOING'>En Proceso</option>
                        <option value='RECEIVED'>Recibido</option>
                    </select>
                    @error('status')
                        <span class='text-danger'>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="resetUI()" class="btn  close-btn"
                        data-dismiss="modal">CERRAR</button>
                    <button type="button" class="btn btn-dark close-modal"
                        wire:click.prevent="Update()">ACTUALIZAR</button>
                </div>

            </div>

        </div>
    </div>
</div>
