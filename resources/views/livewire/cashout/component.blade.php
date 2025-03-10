<div class="row sales layouts-top-spcing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-center"><b>
                        Corte de Caja</b></h4>
            </div>

            <div class="widget-content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label>Usuario</label>
                            <select wire:model="userid" class="form-control">
                                <option value="0" selected>Todos</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            @error('userid')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if (count($sales) > 0)
                        <div class="col-sm-12 aling-self-center d-flex">
                            <button class="btn btn-primary" wire:click="download" type="button">Excel</button>
                            <button class="btn btn-primary" wire:click="pdf" type="button">PDF</button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-sm-12 col-md-4 mbmobile">
                    <div class="connect-sorting bg-dark">
                        <h5 class="text-white">Ventas Totales: {{ number_format($total, 2) }}</h5>
                        <h5 class="text-white">Articulos: {{ $items }}</h5>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: #3b3f5c">
                                <tr>
                                    <th class="table-th text-center text-white">FOLIO</th>
                                    <th class="table-th text-center text-white">TOTAL</th>
                                    <th class="table-th text-center text-white">ITEMS</th>
                                    <th class="table-th text-center text-white">FECHA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($total <= 0)
                                    <tr>
                                        <td colspan="5">
                                            <h6 class="text-center">No hay ventas en la fecha</h6>
                                        </td>
                                    </tr>
                                @endif

                                @foreach ($sales as $row)
                                    <tr>
                                        <td class="text-center">
                                            <h6>{{ $row->number }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ number_format($row->total, 2) }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $row->getTotalProducts() }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ \Carbon\Carbon::parse($row->created_at)->format('H:m:s d-M-Y') }}
                                            </h6>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" wire:click="viewDetails({{ $row->id }})"
                                                class="btn btn-dark btn-sm">
                                                <i class="fas fa-list"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.cashout.modalDetails')
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('show-modal', Msg => {
            $('#modal-details').modal('show')
        });
    });
</script>
