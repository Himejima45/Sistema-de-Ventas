@props(['sale', 'activeTab', 'modalProducts', 'modalPayments'])

<div wire:ignore.self class="modal fade" id="salePreviewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">
          <b>Vista Previa de Venta</b>
        </h5>
        <button class="close" data-dismiss="modal" type="button" aria-label="Close" wire:click="resetModal">
          <span class="text-white">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        @if($sale)
          {{-- header --}}
          <div class="row mb-4">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
              <h6 class="font-weight-bold">Cliente:</h6>
              <p class="mb-1">{{ $sale->client->name }} {{ $sale->client->last_name }}</p>
              @if(auth()->user()->roles->pluck('reference')->first() === 'admin')
                <small class="text-muted">Documento: {{ $sale->client->document }}</small>
              @endif
            </div>
            <div class="col-12 col-md-6 text-md-right">
              <h6 class="font-weight-bold">Información de Venta:</h6>
              <p class="mb-1">Código: <strong>#{{ $sale->code }}</strong></p>
              <p class="mb-1">Fecha: {{ $sale->created_at->format('d-m-Y h:i a') }}</p>
              <p class="mb-0">Estado:
                <span class="badge badge-{{ $sale->status === 'PAID' ? 'success' : 'warning' }}">
                  {{ $sale->status === 'PAID' ? 'PAGADO' : 'PENDIENTE' }}
                </span>
              </p>
            </div>
          </div>

          {{-- tabs nav --}}
          <ul class="nav nav-tabs" id="salePreviewTabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link {{ $activeTab === 'products' ? 'active' : '' }}" id="products-tab"
                wire:click="$set('activeTab','products')" href="javascript:void(0)" role="tab">
                <i class="fas fa-boxes mr-1"></i> Productos
                <span class="badge badge-primary ml-1">{{ $sale->products->count() }}</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $activeTab === 'payments' ? 'active' : '' }}" id="payments-tab"
                wire:click="$set('activeTab','payments')" href="javascript:void(0)" role="tab">
                <i class="fas fa-money-bill-wave mr-1"></i> Pagos
                <span class="badge badge-info ml-1">{{ $sale->payments->count() }}</span>
              </a>
            </li>
          </ul>

          {{-- tab content --}}
          <div class="tab-content" id="salePreviewTabsContent">
            {{-- PRODUCTS TAB --}}
            <div class="tab-pane fade {{ $activeTab === 'products' ? 'show active' : '' }}" id="products" role="tabpanel">
              <div class="mt-3">
                {{-- table --}}
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                      <tr>
                        <th>Producto</th>
                        <th class="text-center">Precio Unitario</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($modalProducts as $detail)
                        @php $itemSubtotal = $detail->price * $detail->quantity; @endphp
                        <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <img src="{{ $detail->product->getImage() }}" alt="{{ $detail->product->name }}"
                                class="rounded mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                              <div>
                                <h6 class="mb-0">{{ $detail->product->name }}</h6>
                                <small class="text-muted">SKU: {{ $detail->product->code ?? 'N/A' }}</small><br>
                                <small class="text-muted">Stock: {{ $detail->product->stock }}</small>
                              </div>
                            </div>
                          </td>
                          <td class="text-center">${{ number_format($detail->price, 2) }}</td>
                          <td class="text-center">
                            <span class="badge badge-primary badge-pill">{{ $detail->quantity }}</span>
                          </td>
                          <td class="text-center font-weight-bold">${{ number_format($itemSubtotal, 2) }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                {{ $modalProducts->links('pagination::bootstrap-4', ['paginator' => 'productsPage']) }}

                {{-- summary --}}
                <div class="row mt-3">
                  <div class="col-12">
                    <div class="card bg-light">
                      <div class="card-body py-2">
                        <div class="row text-center">
                          <div class="col-6">
                            <strong>Total Productos:</strong>
                            <div class="h5 mb-0 text-primary">{{ $this->productsTotals['totalQuantity'] }}</div>
                          </div>
                          <div class="col-6">
                            <strong>Total:</strong>
                            <div class="h5 mb-0 text-dark">${{ number_format($this->productsTotals['subtotal'], 2) }}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>{{-- /products tab --}}

            {{-- PAYMENTS TAB --}}
            <div class="tab-pane fade {{ $activeTab === 'payments' ? 'show active' : '' }}" id="payments" role="tabpanel">
              <div class="mt-3">
                {{-- summary cards --}}
                <div class="row mb-4">
                  <div class="col-md-4 mb-3">
                    <div class="card border-success">
                      <div class="card-body text-center py-3">
                        <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                        <div class="h5 mb-1 text-success">
                          ${{ number_format($this->paymentSummary['total_payed_usd'] ?? 0, 2) }}</div>
                        <small class="text-muted font-weight-bold">PAGADO EN USD</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card border-info">
                      <div class="card-body text-center" style="padding: 1.719rem 0;">
                        <i class="fas fa-bolivar-sign fa-2x text-info mb-2"></i>
                        <div class="h5 mb-1 text-info">
                          {{ number_format($this->paymentSummary['total_payed_bs'] ?? 0, 2) }} Bs
                        </div>
                        <small class="text-muted font-weight-bold">PAGADO EN BOLÍVARES</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card {{ $sale->status === 'PAID' ? 'border-success' : 'border-warning' }}">
                      <div class="card-body text-center" style="padding: 1.401rem 0;">
                        <i
                          class="fas fa-{{ $sale->status === 'PAID' ? 'check-circle' : 'clock' }} fa-2x text-{{ $sale->status === 'PAID' ? 'success' : 'warning' }} mb-2"></i>
                        <div class="h6 mb-1 text-{{ $sale->status === 'PAID' ? 'success' : 'warning' }}">
                          {{ $sale->status === 'PAID' ? 'COMPLETADO' : 'PENDIENTE' }}
                        </div>
                        <small class="text-muted font-weight-bold">ESTADO</small>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- payments table --}}
                @if($sale->payments->count())
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead class="bg-light">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Fecha y Hora</th>
                          <th class="text-center">Efectivo Recibido</th>
                          <th class="text-center">Cambio Entregado</th>
                          <th class="text-center">Neto USD</th>
                          <th class="text-center">Tasa</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($modalPayments as $index => $payment)
                          @php
                            $netUSD = $payment->cash_usd - $payment->change_usd;
                            $netBS = $payment->cash_bs - $payment->change_bs;
                            $netTotalUSD = $netUSD + ($netBS / $payment->currency->value);
                          @endphp
                          <tr>
                            <td class="text-center">
                              {{ $index + 1 + (($modalPayments->currentPage() - 1) * $modalPayments->perPage()) }}
                            </td>
                            <td>
                              {{ $payment->created_at->format('d-m-Y h:i a') }}
                            </td>
                            <td class="text-center">
                              <div class="text-success font-weight-bold">${{ number_format($payment->cash_usd, 2) }}</div>
                              <div class="text-success small">{{ number_format($payment->cash_bs, 2) }} Bs</div>
                            </td>
                            <td class="text-center">
                              <div class="text-danger">${{ number_format($payment->change_usd, 2) }}</div>
                              <div class="text-danger small">{{ number_format($payment->change_bs, 2) }} Bs</div>
                            </td>
                            <td class="text-center font-weight-bold bg-light">${{ number_format($netTotalUSD, 2) }}</td>
                            <td class="text-center text-muted small">{{ number_format($payment->currency->value, 2) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  {{ $modalPayments->links('pagination::bootstrap-4', ['paginator' => 'paymentsPage']) }}
                @else
                  <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h5>No hay pagos registrados</h5>
                    <p class="mb-0">Esta venta no tiene pagos registrados aún.</p>
                  </div>
                @endif
              </div>
            </div>{{-- /payments tab --}}
          </div>{{-- /tab-content --}}
        @endif
      </div>{{-- /modal-body --}}

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="resetModal">
          Cerrar
        </button>
        @if($sale && $sale->status === 'PENDING')
          <button type="button" wire:click="edit({{ $sale->id }})" class="btn btn-primary" data-dismiss="modal">
            Gestionar Pago
          </button>
        @endif
      </div>
    </div>
  </div>
</div>