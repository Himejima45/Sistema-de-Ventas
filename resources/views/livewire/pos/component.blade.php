<div>
    <div class="row layout-top-spacing">
        <div class="col-sm-12 col-md-8">
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

        <div class="col-sm-12 col-md-4">
            <!-- TOTAL -->
            @include('livewire.pos.partials.total')


            <!-- DENOMINACIONES -->
            @include('livewire.pos.partials.coins')

        </div>


    </div>



</div>


<script src="{{ asset('plugins/keypress/keypress.js') }}"></script>
<script src="{{ asset('plugins/onscan.js/onscan.js') }}"></script>

@include('livewire.pos.scripts.shortcurts')
{{-- @include('livewire.pos.scripts.events') --}}
@include('livewire.pos.scripts.general')
{{-- @include('livewire.pos.scripts.scan') --}}
