<div class="row justify-content-between">
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <span class="input-group-text input-gp">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <input id="code"type="text" wire:keydown.enter.prevent="$emit('scan-code', $('#code').val())" class="form-control search-form-control  ml-lg-auto" placeholder="Codigo producto...">
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <select name="client" id="client" class="form-control" wire.change='selectClient(this.val)'>
            <option value="" disabled selected>Seleccionar</option>

            @foreach ($clients as $client)
                <option value="{{ $client->id }}">
                {{ $client->document }} {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){
        livewire.on('scan-code', action => {
            $('#code').val('')

        })
    })
</script>
