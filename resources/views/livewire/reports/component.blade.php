<div class="row sales layouts-top-spacing">

    <div class="col-sm-12">
        <div class="widget">
            <div class="widget-heading">
                <h4 class="card-title text-center"><b>{{$componentName}}</b></h4>
            </div>

            <div class="widget-content">
               <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Elige el usuario</h6>
                            <div class="form-group">
                                <select wire:model="userId" class="form-control">
                                    <option value="0">Todos</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
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
                        <div class="col-sm-12 mt-2">
                            <h6>fechas desde</h6>
                            <div class="form-group">
                                <input type="date" wire:model="dateFrom" class="form-control flatpickr" placeholder="Click para elegir">
                            </div>
                        </div>
                        <div class="col-sm-12 mt-2">
                            <h6>fechas hasta</h6>
                            <div class="form-group">
                                <input type="date" wire:model="dateTo" class="form-control flatpickr" placeholder="Click para elegir">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <buttton wire:click="$refresh" class="btn btn-dark btn-block">
                                Consultar
                            </buttton>

                            <a class="btn btn-primary btn-block {{count($data) < 1 ? 'disabled' : '' }}" href="{{ url('report/pdf' . '/' . $userid . '/' . $reportType . '/' . $dateFrom . '/' . $dateTo) }}" target="_blank">Generar PDf <i class="fas fa-list"></a>

                            <a class="btn btn.success btn-block {{count($data) < 1 ? 'disabled' : '' }}" href="{{ url('report/excel' . '/' . $userid . '/' . $reportType . '/' . $dateFrom . '/' . $dateTo) }}" target="_blank">Exportar a excel <i class="fas fa-print"></i></a>
                        </div>
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
                                    <th class="table-th text-white text-center">ESTADO</th>
                                    <th class="table-th text-white text-center">USUARIO</th>
                                    <th class="table-th text-white text-center">FECHA</th>
                                    <th class="table-th text-white text-center" width="50px"></th>
                                </tr>
        
                                </thead>
                                <tbody>
                                    @foreach($data as $d)
                                    <tr>
                                        <td class="text-center"><h6>{{$d->id}}</h6></td>
                                        <td class="text-center"><h6>{{number_format($d->total,2)}}</h6></td>
                                        <td class="text-center"><h6>{{$d->items}}</h6></td>
                                        <td class="text-center"><h6>{{$d->status}}</h6></td>
                                        <td class="text-center"><h6>{{$d->user}}</h6></td>
                                        <td class="text-center">
                                            <h6>
                                                {{\Carbon\Carbon::parse($d->created_at)->format('d-m-Y')}}
                                            </h6>
                                        </td>
                                        <td class="text-center" width="50px">
                                            <button wire:click.prevent="getDetails({{$d->id}})" class="btn btn-dark btn-sm">
                                                <i class="fas fa-list">
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
    </div>
    @include('livewire.reports.sales-detail')
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
        flatpickr(document.getElementsByClassName('flatpickr'),{
            enableTime: false,
            dateFormat: 'Y-m-d',
            locale: {
                firstDayofWeek: 1,
                weekdays: {
                shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                longhand: [
                "Domingo",
                "Lunes",
                "Martes",
                "Miércoles",
                "Jueves",
                "Viernes",
                "Sábado",
                ],
              },
              months: {
                shorthand: [
                "Ene",
                "Feb",
                "Mar",
                "Abr",
                "May",
                "Jun",
                "Jul",
                "Ago",
                "Sep",
                "Oct",
                "Nov",
                "Dic",
                ],
                longhand: [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre",
                ],
            },

            }
        })
        window.livewire.on('show-modal', Msg => {
            $('#modalDetails').modal('show')
        });
    });
</script>