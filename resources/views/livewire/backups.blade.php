<div class="row sales layout-top-spacing">
    <x-home_button />

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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-cloud-download">
                                                <path d="M12 13v8l-4-4" />
                                                <path d="m12 21 4-4" />
                                                <path d="M4.393 15.269A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.436 8.284" />
                                            </svg>
                                        </button>
                                        <x-delete_button onclick="Confirm('{{ $backup['key'] }}')" />
                                        <button wire:click="upload('{{ $backup['key'] }}')"
                                            class="btn btn-secondary mtmobile" title="Cargar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m16 6-4-4-4 4" />
                                                <path d="M12 2v8" />
                                                <rect width="20" height="8" x="2" y="14" rx="2" />
                                                <path d="M6 18h.01" />
                                                <path d="M10 18h.01" />
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
    document.addEventListener('DOMContentLoaded', function () {
        window.livewire.on('upload-action', msg => {
            Info('Cargando respaldo', "Se va a cargar el respaldo de la base de datos, este proceso puede demorar unos minutos")
        });
        window.livewire.on('upload-success', msg => {
            Success('Respaldo exitoso', "Se ha cargado el respaldo de la base de datos exitosamente")
        });
        window.livewire.on('upload-error', msg => {
            Warning('Ocurrió un error', msg)
        });
    });
</script>