<div class="row justify-content-between">
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <span class="input-group-text input-gp">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <input id="code"type="text" wire:keydown.enter.prevent="$emit('scan-code', $('#code').val())"
                class="form-control search-form-control  ml-lg-auto" placeholder="Codigo producto...">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-12">
        <select name="client" id="client" class="form-control" wire:model="selected_client"
            wire:change='selectClient($event.target.value)'>
            <option value="">Seleccionar</option>

            @foreach ($clients as $clientRow)
                <option value="{{ $clientRow->id }}"
                    selected="{{ is_null($selected_client) ? false : $selected_client === $clientRow->id }}">
                    {{ $clientRow->document }} {{ $clientRow->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        livewire.on('scan-code', action => {
            $('#code').val('')
        })
    })
</script>
