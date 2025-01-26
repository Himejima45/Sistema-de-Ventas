<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" wire:click="save()"
                            style="padding: 0.5rem; display: flex; gap: 0.5rem">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12h8" />
                                <path d="M12 8v8" />
                            </svg>
                            GENERAR
                        </a>

                    </li>
                </ul>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C;">
                            <tr>
                                <th class="table-th text-white">FECHA</th>
                                <th class="table-th text-white text-center">PESO</th>
                                <th class="table-th text-white text-center">ACCIONES</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($backups as $backup)
                                <tr>
                                    <td>
                                        <h6>{{ $backup['created_at'] }}</h6>
                                    </td>
                                    <td>
                                        <h6>
                                            {{ $backup['size'] }}
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="download('{{ $backup['key'] }}')"
                                            class="btn btn-primary mtmobile" title="Descargar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-cloud-download">
                                                <path d="M12 13v8l-4-4" />
                                                <path d="m12 21 4-4" />
                                                <path
                                                    d="M4.393 15.269A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.436 8.284" />
                                            </svg>
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $backups->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('role-added', msg => {
            $('#theModal').modal('hide')
        });
    });

    function Confirm(id) {
        swal({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('Destroy', id)
                swal.close()
            }
        })
    }
</script>
