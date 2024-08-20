<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}}</b>
                </h4>
                
            </div>

            <div class="widget-content">
                <div class="form-inline">
                    <div class="form-group mr-5 col-sm-2">
                        <select wire:model ="role" class="form-control">
                            <option value="Elegir">== Selecciona un Rol ==</option>
                            @foreach ($roles as $role)
                            <option value="{{$role->id}}">{{$role->name}}</option>    
                            @endforeach
                        </select>
                    </div>

                    <button wire:click.prevent="SyncAll()" type="button" class="btn btn-primary mtmobile inblock mr-5">Sincronizar Todos</button>

                    <button onclick="Revocar()" type="button" class="btn btn-secondary mtmobile mr-5">Revocar Todos</button>

                </div>
                
                <div class="row-mt-3">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #3B3F5C;">
                                <tr>
                                    <th class="table-th text-white">ID</th>
                                    <th class="table-th text-white text-center">PERMISO</th>
                                    <th class="table-th text-white text-center">ROLES CON EL PERMISO</th>
                                </tr>
        
                                </thead>
                                <tbody>
                                    @foreach ($permisos as $permiso)     
                                    <tr>
                                        <td><h6 class="text-center">{{$permiso->id}}</h6></td>
                                        <td class="text-center">
                                            <div class="n-check">
                                                <label class="new-control new-checkbox checkbox-success">
                                                    <input type="checkbox" wire:change="syncPermiso($('#p' + {{ $permiso->id }}).is(':checked'), '{{ $permiso->name}}' )" id="p{{ $permiso->id }}" value="{{ $permiso->id }}" class="new-control-input" {{ $permiso->checked == 1 ? 'checked' : '' }} >
                                                    <span class="new-control-indicador"></span>
                                                    <h6>{{ $permiso->name }}</h6>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ \App\Models\User::permission($permiso->name)->count() }}</h6>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$permisos->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('show-modal', msg =>{
            $('#theModal').modal('show')
         });
         window.livewire.on('sync-error', msg =>{
            noty(Msg)
         });
         window.livewire.on('permi', msg =>{
            noty(Msg)
         });
         window.livewire.on('syncall', msg =>{
            noty(Msg)
         });
         window.livewire.on('removeall', msg =>{
            noty(Msg)
         });
    });

    
    function Revocar()
    {
        swal({
                title: 'CONFIRMAR',
                text: '¿CONFIRMAS REVOCAR TODOS LOS PERMISOS?',
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cerrar',
                cancelButtonColor: '#fff',
                confirmButtonColor:'#3B3F5C',
                confirmButtonText: 'Aceptar'
            }).then(function(result) {
                if(result.value){
                    window.livewire.emit('revokall')
                    swal.close()
                }
            })
    }

</script>