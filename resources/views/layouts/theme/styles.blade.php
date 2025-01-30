<script src="{{ asset('assets/js/loader.js') }}"></script>
<link href="{{ asset('https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap') }}"
    rel="stylesheet">
<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/structure.css') }}" rel="stylesheet" type="text/css" class="structure" />
<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link href="{{ asset('plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('plugins/sweetalerts/sweetalert.css') }}">
<link href="{{ asset('assets/css/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css" class="dashboard-sales" />
<link href="{{ asset('assets/css/apps/notes.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/apps/scrumboard.css') }}" rel="stylesheet" type="text/css">

<style>
    aside {
        display: none !important;
    }

    .page-item.active . page-link {
        z-index: 3;
        color: #fff;
        background-color: #3b3f5c;
        border-color: #3b3f5c;
    }

    @media (max-width: 480px) {
        .mtmobile {
            margin-bottom: 20px !important;
        }

        .mbmobile {
            margin-bottom: 10px !important;
        }

        .hideonsm {
            display: none !important;
        }

        .inblock {
            display: block;
        }
    }

    /*sidebar background*/
    .sidebar-theme #compactSidebar {
        background: #191e3a !important;
    }

    /*sidebar collapse background*/
    .header-container .sidebarCollapse {
        color: #3B3F5C !important;
    }

    .navbar .navbar-item .nav-item form.form-inline input.search-form-control {
        font-size: 15px;
        background-color: #fdeeee;
        padding-right: 40px;
        padding-top: 12px;
        border: none;
        color: #000;
        box-shadow: none;
        border-radius: 30px;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0;
        /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type="number"] {
        -moz-appearance: textfield;
        /* Firefox */
    }

    #sidebar-search-input {
        width: 100%;
        padding: 16px;
        border: none;
        border-bottom: 2px solid #ccc;
        background-color: #191e3a;
        color: #e0e6ed;
        font-size: 0.9rem;
        box-sizing: border-box;
        font-weight: bolder;
    }

    #sidebar-search-input::placeholder {
        color: #e0e6ed;
        font-weight: bolder;
    }
</style>

{{-- <link href="{{ asset('plugins/flatpickr/flatpickr.material.blue.css') }}" rel="stylesheet" type="text/css"> --}}
@livewireStyles
