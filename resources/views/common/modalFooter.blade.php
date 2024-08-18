</div>
<div class="modal-footer">
<<<<<<< HEAD
  <button type="button" wire:click.prevent="resetUI()" class="btn btn-light close-btn" data-dismiss="modal">CERRAR</button>
=======
  <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn" data-dismiss="modal">CERRAR</button>
>>>>>>> 297e68f7f57f7ca13172559bba6a59959bfb7596
  @if ($selected_id < 1)
  <button type="button" wire:click.prevent="Store()" class="btn btn-dark close-modal" >GUARDAR</button> 
  @else
  <button type="button" wire:click.prevent="Update()" class="btn btn-dark close-modal" >ACTUALIZAR</button> 
  @endif
</div>
</div>
</div>
</div>