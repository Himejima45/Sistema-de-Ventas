<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>SISTEMA DE VENTAS</title>
    <link rel="icon" type="image/x-icon" href="assets/img/Logoico.ico" />



    <script src="{{ asset('assets/js/alpine.js') }}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    @include('layouts.theme.styles')
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head>

<body class="dashboard-analytics">

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    @include('layouts.theme.header')
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('layouts.theme.sidebar')
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="mt-5 layout-px-spacing">
                @php
                    $currency = \App\Models\Currency::orderByDesc('created_at')->first();
                    $date = is_null($currency) ? 'Error' : $currency->created_at->diffForHumans();
                @endphp

                @if (session()->has('fetch_status'))
                            <div @class([
                                'alert',
                                'alert-danger' => session('fetch_status') === 'error',
                                'alert-success' => session('fetch_status') !== 'error',
                            ])>
                                {{ session('fetch_status') === 'error'
                    ? 'No se pudo obtener la tasa del día'
                    : "La tasa del día ha sido registrada. Última actualización: $date" }}
                            </div>
                            @php
                                session()->forget('fetch_status');
                            @endphp
                @endif

                @yield('content')


            </div>


            @include('layouts.theme.footer')
        </div>
        <!--  END CONTENT AREA  -->


    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    @include('layouts.theme.scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

</body>

</html>