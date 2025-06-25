<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>SISTEMA DE VENTAS</title>
    <link rel="icon" type="image/x-icon" href="assets/img/Logoico.ico" />
    <link href="{{ public_path() . '/bootstrap/css/bootstrap.min.css' }}" rel="stylesheet" type="text/css">
</head>

<body class="dashboard-analytics">

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="relative mt-5 layout-px-spacing">
                <img src="{{ public_path() . '/assets/img/document_bg.png' }}" alt="Background"
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 14rem; z-index: -1; opacity: 0.75;">

                <div class="text-center">
                    <img src="{{ public_path() . '/assets/img/Logo2.png' }}" class="navbar-logo img-fluid" alt="logo"
                        style="max-width: 75px;">
                    <p>
                        Moto<b style="font-size: 19px; color:#ee1b0c">Parts</b>HM</b>
                    </p>
                </div>
                <h1 class="text-center">SISTEMA DE VENTAS</h1>

                <div class="text-center mt-5">
                    @if ($budget)
                        <h6>Reportes de cuentas por cobrar</h6>
                        <p>({{ $start }} al {{ $end }})</p>
                    @else
                        <h6>Reportes de ventas</h6>
                        <p>({{ $start }} al {{ $end }})</p>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3b3f5c">
                            <tr>
                                <th class="table-th text-center text-white">FOLIO</th>
                                <th class="table-th text-center text-white">IMPORTE</th>
                                <th class="table-th text-center text-white">ITEMS</th>
                                <th class="table-th text-center text-white">TIPO</th>
                                @if (!$budget)
                                    <th class="table-th text-center text-white">ESTATUS</th>
                                @endif
                                <th class="table-th text-center text-white">EMPLEADO</th>
                                <th class="table-th text-center text-white">CLIENTE</th>
                                <th class="table-th text-center text-white">FECHA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                $items = 0;
                              @endphp
                            @foreach ($sales as $sale)
                                @php
                                    $total += $sale['total'];
                                    $items += $sale['items'];
                                  @endphp
                                <tr>
                                    <td class="text-center">
                                        <h6>{{ $sale['id'] }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>$ {{ $sale['total'] }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $sale['items'] }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $sale['type'] === 'SALE' ? 'VENTA' : 'CARRITO' }}</h6>
                                    </td>
                                    @if (!$budget)
                                                            <td class="text-center">
                                                                <h6>{{ $sale['status'] === 'PAID' ? 'Pagado' : ($sale['status'] === 'PENDING' ? 'Pendiente' :
                                        'Cancelado') }}
                                                                </h6>
                                                            </td>
                                    @endif
                                    <td class="text-center">
                                        <h6>{{ $sale['name'] }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $sale['client'] }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $sale['date'] }}</h6>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center">
                                    <h6>TOTAL</h6>
                                </td>
                                <td class="text-center">
                                    <h6>$ {{ $total }}</h6>
                                </td>
                                <td class="text-center">
                                    <h6>{{ $items }}</h6>
                                </td>
                                <td class="text-center">

                                </td>
                                <td class="text-center">

                                </td>
                                <td class="text-center">

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</body>

</html>