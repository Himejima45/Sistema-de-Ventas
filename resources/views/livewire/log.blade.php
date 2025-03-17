@php
    $statusses = [
        'successfull' => 'Exitoso',
        'warning' => 'Advertencia',
        'info' => 'Información',
        'danger' => 'Peligro',
    ];
    $models = [
        'Category' => 'Categorías',
        'ShoppingCart' => 'Carritos',
        'Currency' => 'Tasas',
        'Product' => 'Productos',
        'Provider' => 'Proveedores',
        'Purchase' => 'Compras',
        'Sale' => 'Ventas',
        'SaleDetails' => 'Detalles de la venta',
        'User' => 'Usuarios',
        'Client' => 'Clientes',
        'Employee' => 'Empleados',
        'Rol' => 'Roles',
        'Sistema' => 'Sistema',
        'categories' => 'Categorías',
        'logs' => 'Bitácora',
        'products' => 'Productos',
        'currencies' => 'Tasas',
        'pos' => 'Ventas',
        'clients' => 'Clientes',
        'roles' => 'Roles',
        'permisos' => 'Permisos',
        'user' => 'Empleados',
        'cashout' => 'Caja',
        'reports' => 'Reportes',
        'providers' => 'Proveedores',
        'purchases' => 'Compras',
        'catalog' => 'Catálogo',
        'historial' => 'Carritos del usuario',
        'carts' => 'Carritos',
        'budgets' => 'Presupuestos',
        'backups' => 'Respaldos',
        'logout' => 'Cierre de sesión',
        'home' => 'Inicio',
        'login' => 'Inicio de sesión',
        'register' => 'Registro de usuario',
    ];
    $rol_translations = [
        'Admin' => 'Administrador',
        'Employee' => 'Empleado',
        'Client' => 'Cliente',
        'Sistema' => 'Sistema',
    ];
@endphp

<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>
            </div>

            <div class="widget-content">
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="state">Estado</label>
                            <select wire:model="state" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($statusses as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="role">Rol</label>
                            <select wire:model="role" class="form-control">
                                <option value="">Todos</option>
                                <option value="Sistema">Sistema</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->name }}">{{ $rol_translations[$rol->name] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="user">Usuario</label>
                            <select wire:model="user" class="form-control">
                                <option value="">Todos</option>
                                <option value="Sistema">Sistema</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="module">Módulo</label>
                            <select wire:model="module" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($models as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="date">Fecha</label>
                            <input type="date" wire:model="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div style="margin-top: 2.25rem">
                            <button wire:click="clear_filters" class="btn btn-block btn-ghost">Limpiar filtros</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C;">
                            <tr>
                                <th class="table-th text-white text-center">ID</th>
                                <th class="table-th text-white text-center">ACCIÓN</th>
                                <th class="table-th text-white text-center">MÓDULO</th>
                                <th class="table-th text-white text-center">USUARIO</th>
                                <th class="table-th text-white text-center">ROL</th>
                                <th class="table-th text-white text-center">ESTADO</th>
                                <th class="table-th text-white text-center">FECHA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>
                                    <td>
                                        <h6>{{ $row->id }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $row->action }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $models[$row->module] }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $row->user }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $rol_translations[$row->rol] }}</h6>
                                    </td>
                                    <td>
                                        <h6 @class([
                                            'font-weight-bold',
                                            'text-success' => $row->status == 'successfull',
                                            'text-warning' => $row->status == 'warning',
                                            'text-info' => $row->status == 'info',
                                            'text-danger' => $row->status == 'danger',
                                        ])>{{ $statusses[$row->status] }}</h6>
                                    </td>
                                    <td>
                                        <h6>{{ $row->created_at->format('d/m/Y - h:i a') }}</h6>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
