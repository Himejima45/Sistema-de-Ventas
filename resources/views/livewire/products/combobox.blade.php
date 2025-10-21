<div x-data x-init="window.addEventListener('deferred-product-select', (e) => {
  @this.emit('product-selected', e.detail.productId)
})" class="position-relative">
  <div class="form-group">
    <input type="text" wire:model.debounce.300ms="search" wire:click.lazy="toggleDropdown"
      placeholder="Buscar productos..." class="form-control" aria-label="Product search">

    @if($showDropdown)
      <div class="position-absolute w-100 mt-1 bg-white rounded shadow-lg" style="z-index: 1000;">
        <ul class="list-group list-group-flush py-1 overflow-auto" style="max-height: 280px;">
          @forelse($options as $option)
            <li wire:click="selectOption('{{ $option['barcode'] }}', '{{ $option['id'] }}')" class="list-group-item d-flex justify-content-between align-items-center
                                              px-3 py-2 lh-sm border-0 rounded-1 mb-1 mx-1
                                              text-dark bg-hover-light-primary cursor-pointer transition-base">
              <div>
                <div class="fw-semibold fs-6">{{ $option['name'] }}</div>
                <small class="text-muted">{{ $option['barcode'] }}</small>
              </div>
              <i class="fas fa-chevron-right text-gray-400 fs-7"></i>
            </li>
          @empty
            <li class="list-group-item border-0 text-muted text-center py-4">
              <i class="fas fa-search fs-4 mb-2 d-block opacity-50"></i>
              No se encontraron productos
            </li>
          @endforelse
        </ul>
      </div>
    @endif
  </div>

  <input type="hidden" name="product" wire:model.defer="selectedValue">
</div>