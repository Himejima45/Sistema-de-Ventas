@php
    $user = auth()->user();
@endphp

@if ($user !== null)
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">
            <ul class="navbar-item flex-row">
                <li class="nav-item theme-logo">
                    <a href="{{ route('home') }}">
                        <img src="assets/img/Logo2.png" class="navbar-logo" alt="logo"><b
                            style="font-size: 19px; color:#3B3F5C"> Moto<b
                                style="font-size: 19px; color:#ee1b0c">Parts</b>HM</b>
                    </a>
                </li>
            </ul>


            {{-- Profile --}}
            <ul class="navbar-item flex-row search-ul"
                style="display: flex; justify-items: center; align-items: center; gap: 0.5rem;">

                {{-- Notification --}}
                @unlessrole('Client')
                <livewire:notification-dropdown />
                @endunlessrole

                <li class="nav-item">
                    <h6 class="font-weight-bold">{{ $user->full_name }}</h6>
                </li>

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