@props(['productName', 'productImage'])

<div wire:ignore-self id="product-zoom" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white">
          <b>{{ $productName }}</b>
        </h5>
        <button class="close" data-dismiss="modal" type="button" aria-label="Close">
          <span class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        @if (!is_null($productName) && !is_null($productImage))
          <img src="{{ $productImage }}" alt="{{ $productName }}" class="img-fluid rounded" style="max-height: 70vh;">
        @else
          <p>No product image available</p>
        @endif
      </div>
    </div>
  </div>
</div>