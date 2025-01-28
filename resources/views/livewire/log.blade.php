<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C;">
                            <tr>
                                <th class="table-th text-white text-center">ACCIÓN</th>
                                <th class="table-th text-white text-center">MÓDULO</th>
                                <th class="table-th text-white text-center">USUARIO</th>
                                <th class="table-th text-white text-center">ROL</th>
                                <th class="table-th text-white text-center">ESTADO</th>
                                <th class="table-th text-white text-center">FECHA</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                ];
                            @endphp
                            @foreach ($data as $row)
                                <tr>
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
                                        <h6>{{ $row->rol }}</h6>
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
