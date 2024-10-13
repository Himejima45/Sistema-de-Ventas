<div class="row mt-3">
    <div class="col-sm-12">

        <div class="connect-sorting">
            <h5 class="text-center mb-2">PAGO</h5>

            <!-- <div class="container">
                    <div class="row">
                        {{-- @foreach ($denominations as $d)
                        <div class="col-sm mt-2">
                            <button wire:click.prevent="ACash({{$d->value}})" class="btn btn-dark btn-block den">
                                {{ $d->value > 0 ? ($d->type === 'DOLAR' ? '$' : 'Bs') . number_format($d->value,2, '.', '') : 'Exacto' }}
                            </button>
                        </div>
                            
                        @endforeach --}}
                    </div>
                </div> -->
            <div class="connect-sorting-content mt-4">
                <div class="card simple-title-task ui-sortable-handle">
                    <div class="card-body">
                        <div class="input-group input-group-md mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-gp hideonsm"
                                    style="background: #3b3f5c; color:white">$
                                    {{-- F8 --}}
                                </span>
                            </div>
                            <input min="1" type="number" id="cash" wire:model="efectivo"
                                class="form-control text-center" value="{{ $efectivo }}"
                                wire:keyup="addPayment($event.target.value, 'dollar')">

                            <div class="input-group-append" wire:click="clearPayment('dollar')">
                                <span class="input-group-text" style="background: #3b3f5c; color:white">
                                    <i class="fas fa-backspace fa-2x"></i>
                                </span>
                            </div>
                        </div>
                        <div class="input-group input-group-md mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-gp hideonsm"
                                    style="background: #3b3f5c; color:white">Bs
                                    {{-- F8 --}}
                                </span>
                            </div>
                            <input min="1" type="number" id="bs" wire:model="bs"
                                class="form-control text-center" value="{{ $bs }}"
                                wire:keyup="addPayment($event.target.value, 'bs')">

                            <div class="input-group-append" wire:click="clearPayment('bs')">
                                <span class="input-group-text" style="background: #3b3f5c; color:white">
                                    <i class="fas fa-backspace fa-2x"></i>
                                </span>
                            </div>
                        </div>

                        <h4
                            class="{{ $change === 0 ? 'text-muted' : ($change >= 0 ? 'text-success' : 'text-danger') }}">
                            Cambio $: {{ number_format($change, 2) }}</h4>
                        <h3
                            class="{{ $change === 0 ? 'text-muted' : ($change >= 0 ? 'text-success' : 'text-danger') }}">
                            Cambio Bs: {{ number_format($change * $currency, 2) }}</h3>

                        <select name="type" id="type" class="col-12 form-control" wire:model="type"
                            wire:change='setType($event.target.value)' required>
                            <option value="Elegir">Seleccionar</option>
                            <option value="PAID">Pagada</option>
                            <option value="PENDING">Pendiente</option>
                        </select>
                        @error('type')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror
                        <div class="row justify-content-between mt-5">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if ($total > 0)
                                    <button onclick="Confirm('','clearCart','¿SEGURO DE ELIMINAR LAS VENTAS?')"
                                        class="btn btn-danger mtmobile">
                                        CANCELAR
                                        {{-- F4 --}}
                                    </button>
                                @endif
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-6">
                                @if ($efectivo >= $total && $total > 0)
                                    <button wire:click.prevent="saveSale" class="btn btn-primary btn-md btn-block">
                                        GUARDAR
                                        {{-- F9 --}}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
