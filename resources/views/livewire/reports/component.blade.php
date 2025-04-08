<div class="row sales layouts-top-spacing">
    <div class="col-sm-12">
        <div class="widget">
            <div class="widget-heading">
                <h4 class="card-title text-center"><b>{{ $componentName }}</b></h4>
            </div>

            <div class="widget-content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Elige el empleado</h6>
                                <div class="form-group">
                                    <select wire:model="userId" class="form-control">
                                        <option value="0">Todos</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Elige el tipo de reporte</h6>
                                <div class="form-group">
                                    <select wire:model="reportType" class="form-control">
                                        <option value="0">Ventas del dia</option>
                                        <option value="1">Ventas por fecha</option>
                                    </select>
                                </div>
                            </div>
                            @if ($reportType === '1')
                                <div class="col-sm-12 mt-2">
                                    <h6>Fechas desde</h6>
                                    <div class="form-group">
                                        <input type="date" wire:model="dateFrom" class="form-control flatpickr"
                                            placeholder="Click para elegir" min=""
                                            max="{{ $dateTo ? \Carbon\Carbon::createFromFormat('Y-m-d', $dateTo)->subDay()->format('Y-m-d') : now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <h6>Fechas hasta</h6>
                                    <div class="form-group">
                                        <input type="date" wire:model="dateTo" class="form-control flatpickr"
                                            placeholder="Click para elegir" min="{{ $dateFrom }}"
                                            max="{{ now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                            @endif
                            @php
                                $condition =
                                    count($data) > 0 &&
                                    ((!is_null($dateFrom) && !is_null($dateTo)) || $reportType === 1);
                            @endphp
                            @if ($condition)
                                <div class="col-sm-12">
                                    <button wire:click="pdf" class="btn btn-primary btn-block">
                                        Generar PDF <i class="fas fa-list"></i>
                                    </button>
                                    <button wire:click="excel" class="btn btn-primary btn-block">
                                        Exportar a Excel <i class="fas fa-print"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-9">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #3B3F5C">
                                    <tr>
                                        <th class="table-th text-white text-center">FOLIO</th>
                                        <th class="table-th text-white text-center">TOTAL</th>
                                        <th class="table-th text-white text-center">ITEMS</th>
                                        <th class="table-th text-white text-center">TIPO</th>
                                        <th class="table-th text-white text-center">ESTADO</th>
                                        <th class="table-th text-white text-center">EMPLEADO</th>
                                        <th class="table-th text-white text-center">CLIENTE</th>
                                        <th class="table-th text-white text-center">FECHA</th>
                                        <th class="table-th text-white text-center" width="50px"></th>
                                    </tr>
                                </thead>
                                @if ($data && $data['data'])
                                <tbody>
                                    @foreach ($data['data'] as $d)
                                    <tr>
                                        <td class="text-center">
                                            <h6>{{ $d['number'] }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ number_format($d['total'], 2) }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ $d['items'] }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ $d['type'] === 'SALE' ? 'VENTA' : 'CARRITO' }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ $d['status'] === 'PAID' ? 'Pagado' : ($d['status'] === 'PENDING' ? 'Pendiente' : 'Cancelado') }}
                                                </h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ $d['user'] }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ $d['client'] }}</h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>{{ \Carbon\Carbon::parse($d['updated_at'])->translatedFormat('h:i:s d-M-Y a') }}</h6>
                                            </td>
                                            <td class="text-center" width="50px">
                                                <button type="button" wire:click="getDetails({{ $d['id'] }})"
                                                    class="btn btn-dark btn-sm">
                                                    <i class="fas fa-list"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @endif
                            </table>
                            @if ($data && $data['links'] !== '' && $data['links'] != null)
                                {!! $data['links'] !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.reports.sales-detail')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // flatpickr(document.getElementsByClassName('flatpickr'), {
        //     enableTime: false,
        //     dateFormat: 'Y-m-d',
        //     locale: {
        //         firstDayofWeek: 1,
        //         weekdays: {
        //             shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        //             longhand: [
        //                 "Domingo",
        //                 "Lunes",
        //                 "Martes",
        //                 "Miércoles",
        //                 "Jueves",
        //                 "Viernes",
        //                 "Sábado",
        //             ],
        //         },
        //         months: {
        //             shorthand: [
        //                 "Ene",
        //                 "Feb",
        //                 "Mar",
        //                 "Abr",
        //                 "May",
        //                 "Jun",
        //                 "Jul",
        //                 "Ago",
        //                 "Sep",
        //                 "Oct",
        //                 "Nov",
        //                 "Dic",
        //             ],
        //             longhand: [
        //                 "Enero",
        //                 "Febrero",
        //                 "Marzo",
        //                 "Abril",
        //                 "Mayo",
        //                 "Junio",
        //                 "Julio",
        //                 "Agosto",
        //                 "Septiembre",
        //                 "Octubre",
        //                 "Noviembre",
        //                 "Diciembre",
        //             ],
        //         },

        //     }
        // })
        window.livewire.on('show-modal', Msg => {
            $('#modalDetails').modal('show')
        });
    });
</script>
