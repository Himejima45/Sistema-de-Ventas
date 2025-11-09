@props(['selectedId', 'totalSale', 'currencyId'])

<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white">
          <b>Gestionar Pago</b> | Carrito #{{ $selectedId }}
        </h5>
        <h6 class="text-center text-warning mb-0" wire:loading>PROCESANDO...</h6>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- Payment Section -->
          <div class="col-12">
            <h5 class="border-bottom pb-2">Nuevo Pago</h5>
          </div>

          <!-- Currency Selection -->
          <div class="col-12">
            <div class="form-group">
              <label class="font-weight-bold">Tasa</label>
              <input type="number" wire:model="exchange_rate" class="form-control" readonly>
            </div>
          </div>

          <!-- Payment Inputs -->
          <div class="col-12 col-md-6">
            <div class="form-group">
              <label class="font-weight-bold">Efectivo USD $</label>
              <input type="number" wire:model="cash_usd" wire:change="calculateChange" class="form-control"
                placeholder="0.00" step="0.01" min="0">
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-group">
              <label class="font-weight-bold">Efectivo Bs</label>
              <input type="number" wire:model="cash_bs" wire:change="calculateChange" class="form-control"
                placeholder="0.00" step="0.01" min="0">
            </div>
          </div>

          <!-- Change Outputs -->
          <div class="col-12 col-md-6">
            <div class="form-group">
              <label class="font-weight-bold">Cambio USD $</label>
              <input type="number" wire:model="change_usd" class="form-control" readonly>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-group">
              <label class="font-weight-bold">Cambio Bs</label>
              <input type="number" wire:model="change_bs" class="form-control" readonly>
            </div>
          </div>

          <!-- Payment Summary -->
          <div class="col-12">
            @php
              $currentPayment = $this->getNetPaymentForView();
              $previouslyPaid = $paymentSummary['total_payed_usd_equivalent'] ?? 0;
              $totalPaid = $previouslyPaid + $currentPayment;
              $remainingAmount = $this->getRemainingAmountForView();
              $status = $currentPayment <= 0 ? 'no_payment' : ($remainingAmount <= 0 ? 'fully_paid' : 'partial_payment');
            @endphp

            <div
              class="alert @if($status === 'fully_paid') alert-success @elseif($status === 'partial_payment') alert-warning @else alert-info @endif">
              <div class="row text-center">
                <div class="col-md-3 border-right">
                  <strong>Total Venta</strong><br>
                  <span class="h5">${{ number_format($totalSale, 2) }}</span><br>
                  <small>USD</small>
                </div>
                <div class="col-md-2 border-right">
                  <strong>Pagado Anterior</strong><br>
                  <span class="h5">${{ number_format($previouslyPaid, 2) }}</span><br>
                  <small>USD</small>
                </div>
                <div class="col-md-2 border-right">
                  <strong>Pago Actual</strong><br>
                  <span class="h5">${{ number_format($currentPayment, 2) }}</span><br>
                  <small>USD</small>
                </div>
                <div class="col-md-2 border-right">
                  <strong>Total Pagado</strong><br>
                  <span class="h5">${{ number_format($totalPaid, 2) }}</span><br>
                  <small>USD</small>
                </div>
                <div class="col-md-3">
                  <strong>Por Pagar</strong><br>
                  <span class="h5">${{ number_format($remainingAmount, 2) }}</span><br>
                  <small>USD</small>
                </div>
              </div>
              <div class="row mt-2 text-center">
                <div class="col-12">
                  <strong>Estado: </strong>
                  @if($status === 'fully_paid')
                    <span class="badge badge-success">PAGADO COMPLETO</span>
                  @elseif($status === 'partial_payment')
                    <span class="badge badge-warning">PAGO PARCIAL</span>
                  @else
                    <span class="badge badge-info">SIN PAGO</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" wire:click.prevent="resetModal" class="btn btn-outline-secondary"
          data-dismiss="modal">CANCELAR</button>
        <button type="button" wire:click="showPreview({{ $selectedId }})" class="btn btn-info" data-dismiss="modal">
          <span>DETALLES</span>
        </button>
        <button type="button" wire:click="update" class="btn btn-primary" {{ !$currencyId || $currentPayment <= 0 ? 'disabled' : '' }}>
          <span wire:loading.remove>PROCESAR PAGO</span>
          <span wire:loading>PROCESANDO...</span>
        </button>
      </div>
    </div>
  </div>
</div>