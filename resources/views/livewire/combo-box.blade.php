<div
x-data
     x-init="window.addEventListener('deferred-client-select', (e) => {
         @this.emit('client-selected', e.detail.clientId)
     })"
class="position-relative">
    <div class="form-group">
        <input type="text" wire:model.debounce.300ms="search" wire:click.lazy="toggleDropdown"
            placeholder="Buscar clientes..." class="form-control" aria-label="Client search">

        @if($showDropdown)
            <div class="position-absolute w-100 mt-1 bg-white border border-secondary rounded shadow-lg"
                style="z-index: 1000;">
                <ul class="list-group py-1 overflow-auto" style="max-height: 250px;">
                    @forelse($options as $option)
                        @php
        $name = $option['name'] . ' ' . $option['last_name'] . ' ' . $option['document'];
                        @endphp
                        <li wire:click="selectOption('{{ $option['id'] }}', '{{ $name }}')"
                            class="list-group-item list-group-item-action py-2 px-3 text-dark hover-primary"
                            style="cursor: pointer; font-size: 0.6rem;">
                            {{ $option['name'] }} {{ $option['last_name'] }} {{ $option['document'] }}
                        </li>
                    @empty
                        <li class="list-group-item py-2 px-3 text-muted">
                            No se encontraron clientes
                        </li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>

    <input type="hidden" name="client" wire:model.defer="selectedValue">
</div>