<div class="row">
    <div class="col-sm-12">
        <div>
            <div class="connect-sorting">
                <h5 class="text-center mb-3">RESUMEN DE VENTA</h5>
                <div class="connect-sorting-content">
                    <div class="card simple-title-task ui-sortable-handle">
                        <div class="card-body">
                            <div class="task-header">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between">
                                        <h4>TOTAL:</h4>
                                        <h4>$ {{ number_format($total, 2) }}</h4>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <h4>TOTAL:</h4>
                                        <h4>Bs {{ number_format($total * $currency, 2) }}</h4>
                                    </div>
                                    <input type="hidden" id="hiddenTotal" value="{{ $total }}">
                                </div>
                                <div>
                                    <h4 class="mt-3">Articulos: {{ $itemsQuantity }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>