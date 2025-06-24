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

            <ul class="navbar-item flex-row search-ul">

            </ul>

            {{-- Shopping cart --}}
            @if (auth()->user()->hasRole('Client'))
                <li class="navbar-item flex-row navbar-dropdown pt-2" title="Carrito">
                    @livewire('cart-icon')
                </li>
            @endif

            {{-- Help --}}
            <li class="navbar-item flex-row navbar-dropdown pt-2" title="Ayuda">
                <a target="_blank" href="https://www.youtube.com/playlist?list=PLZcHK-bOWfdbRMw_bgWm6dxvjKLPet8na">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-circle-help-icon lucide-circle-help">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <path d="M12 17h.01" />
                    </svg>
                </a>
            </li>

            {{-- Profile --}}
            <ul class="navbar-item flex-row navbar-dropdown pt-2">
                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>
                    <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <img src="assets/users/profile_4.png" class="img-fluid mr-2" alt="avatar">
                                <div class="media-body">
                                    <h5>{{ $user->full_name }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit()">

                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg> <span>Salir</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
@endif