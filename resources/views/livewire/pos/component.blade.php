<div class="row layout-top-spacing">
    <x-home_button />

    <div class="col-sm-12 col-md-8">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>
            </div>

            @if (session()->has('scan'))
                <div class="alert alert-warning">
                    {{ session('scan') }}
                </div>
                @php
                    session()->forget('fetch_status');
                @endphp
            @endif
            <!-- DETALLES -->
            @include('livewire.search')

            @include('livewire.pos.partials.detail')
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <!-- TOTAL -->
        @include('livewire.pos.partials.total')


        <!-- DENOMINACIONES -->
        @include('livewire.pos.partials.coins')

    </div>
</div>

<script src="{{ asset('plugins/keypress/keypress.js') }}"></script>
<script src="{{ asset('plugins/onscan.js/onscan.js') }}"></script>

<script>
    function DeleteItem (id) {
        swal({
            title: 'CONFIRMAR',
            text: "¿ESTÁ SEGURO QUE DESEA BORRAR EL REGISTRO?",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function (result) {
            console.log('here')
            if (result.value)
            {
                window.livewire.emit('removeItem', id)
                swal.close()
                Deleted('Eliminado', 'Se ha eliminado el registro')
            }
        })
    }
</script>

@include('livewire.pos.scripts.shortcurts')
{{-- @include('livewire.pos.scripts.events') --}}
@include('livewire.pos.scripts.general')
{{-- @include('livewire.pos.scripts.scan') --}}