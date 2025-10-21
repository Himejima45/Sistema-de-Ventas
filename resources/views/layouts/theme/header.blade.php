@php
    $user = auth()->user();
@endphp

@if ($user !== null)
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm" style="padding: 0 20px">
            <ul class="navbar-item flex-row">
                <li class="nav-item theme-logo">
                    <a href="{{ route('home') }}">
                        <img src="assets/img/Logo2.png" class="navbar-logo" alt="logo"><b
                            style="font-size: 19px; color:#3B3F5C"> Moto<b
                                style="font-size: 19px; color:#ee1b0c">Parts</b>HM</b>
                    </a>
                </li>
            </ul>

            {{-- Shopping cart --}}
            @if (auth()->user()->hasRole('Client'))
                <li class="navbar-item flex-row navbar-dropdown pt-2" title="Carrito">
                    @livewire('cart-icon')
                </li>
            @endif

            {{-- Profile --}}
            <ul class="navbar-item flex-row search-ul"
                style="display: flex; justify-items: center; align-items: center; gap: 0.5rem; margin-left: auto; margin-right: 0;">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y H:i:s a')  }}

                <li class="nav-item">
                    <h6 class="font-weight-bold" style="margin:0; padding: 0">{{ $user->full_name }}</h6>
                </li>

                {{-- Help --}}
                <li class="navbar-item flex-row" title="Ayuda">
                    <a target="_blank" href="https://www.youtube.com/playlist?list=PLZcHK-bOWfdbRMw_bgWm6dxvjKLPet8na"
                        class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-help-icon lucide-circle-help">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <path d="M12 17h.01" />
                        </svg>
                    </a>
                </li>

                @unlessrole('Client')
                <livewire:notification-dropdown />
                @endunlessrole

                <li class="nav-item" style="margin:0; padding: 0;" title="Cerrar sesión">
                    <a href="{{ route('logout') }}" class="icon"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit()">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                    </form>
                </li>
            </ul>
        </header>
    </div>
@endif