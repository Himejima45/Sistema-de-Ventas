@if (auth()->user() !== null)
    <div class="sidebar-wrapper sidebar-theme">

        <nav id="compactSidebar">

            <ul class="menu-categories">
                @if (auth()->user()->hasRole('Client'))
                    <li class="active">
                        <a href="{{ url('catalog') }}" class="menu-toggle" data-active="true">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="14" width="7" height="7"></rect>
                                        <rect x="3" y="14" width="7" height="7"></rect>
                                    </svg>
                                </div>
                                <span>Catálogo</span>
                            </div>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ url('historial') }}" class="menu-toggle" data-active="true">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-calendar-days">
                                        <path d="M8 2v4" />
                                        <path d="M16 2v4" />
                                        <rect width="18" height="18" x="3" y="4" rx="2" />
                                        <path d="M3 10h18" />
                                        <path d="M8 14h.01" />
                                        <path d="M12 14h.01" />
                                        <path d="M16 14h.01" />
                                        <path d="M8 18h.01" />
                                        <path d="M12 18h.01" />
                                        <path d="M16 18h.01" />
                                    </svg>
                                </div>
                                <span>Historial</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasAnyRole(['Admin', 'Employee']))
                    <li class="active">
                        <a href="{{ url('categories') }}" class="menu-toggle" data-active="true">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag">
                                        <path
                                            d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z" />
                                        <circle cx="7.5" cy="7.5" r=".5" fill="currentColor" />
                                    </svg>
                                </div>
                                <span>Categorias</span>
                            </div>
                        </a>
                    </li>

                    <li class="">
                        <a href="{{ url('carts') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-shopping-cart">
                                        <circle cx="8" cy="21" r="1" />
                                        <circle cx="19" cy="21" r="1" />
                                        <path
                                            d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                    </svg>
                                </div>
                                <span>Carritos</span>
                            </div>
                        </a>
                    </li>

                    <li class="">
                        <a href="{{ url('purchases') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-shopping-bag">
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                                        <path d="M3 6h18" />
                                        <path d="M16 10a4 4 0 0 1-8 0" />
                                    </svg>
                                </div>
                                <span>Compras</span>
                            </div>
                        </a>
                    </li>

                    <li class="">
                        <a href="{{ url('products') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-package-search">
                                        <path
                                            d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                                        <path d="m7.5 4.27 9 5.15" />
                                        <polyline points="3.29 7 12 12 20.71 7" />
                                        <line x1="12" x2="12" y1="22" y2="12" />
                                        <circle cx="18.5" cy="15.5" r="2.5" />
                                        <path d="M20.27 17.27 22 19" />
                                    </svg>
                                </div>
                                <span>Productos</span>
                            </div>
                        </a>
                    </li>

                    <li class=" ">
                        <a href="{{ url('budgets') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-circle-dollar-sign">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                                        <path d="M12 18V6" />
                                    </svg>
                                </div>
                                <span>Cuentas por pagar</span>
                            </div>
                        </a>
                    </li>

                    <li class=" ">
                        <a href="{{ url('pos') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-shopping-basket">
                                        <path d="m15 11-1 9" />
                                        <path d="m19 11-4-7" />
                                        <path d="M2 11h20" />
                                        <path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4" />
                                        <path d="M4.5 15.5h15" />
                                        <path d="m5 11 4-7" />
                                        <path d="m9 11 1 9" />
                                    </svg>
                                </div>
                                <span>Ventas</span>
                            </div>
                        </a>
                    </li>

                    <li class=" ">
                        <a href="{{ url('providers') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-book-user">
                                        <path d="M15 13a3 3 0 1 0-6 0" />
                                        <path
                                            d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" />
                                        <circle cx="12" cy="8" r="2" />
                                    </svg>
                                </div>
                                <span>Proveedores</span>
                            </div>
                        </a>
                    </li>

                    <li class=" ">
                        <a href="{{ url('clients') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-user-check">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <polyline points="17 11 19 13 23 9"></polyline>
                                    </svg>
                                </div>
                                <span>Clientes</span>
                            </div>
                        </a>
                    </li>

                    @role('Admin')
                        <li class="">
                            <a href="{{ url('roles') }}" class="menu-toggle" data-active="false">
                                <div class="base-menu">
                                    <div class="base-icons">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-key">
                                            <path
                                                d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                            </path>
                                        </svg>
                                    </div>
                                    <span>Roles</span>
                                </div>
                            </a>
                        </li>

                        <li class="active">
                            <a href="{{ url('user') }}" class="menu-toggle" data-active="false">
                                <div class="base-menu">
                                    <div class="base-icons">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </div>
                                    <span>Empleados</span>
                                </div>
                            </a>
                        </li>
                    @endrole

                    <li class="active">
                        <a href="{{ url('currencies') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-stop-circle">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <rect x="9" y="9" width="6" heigth="6"></rect>
                                    </svg>
                                </div>
                                <span>Tasa</span>
                            </div>
                        </a>
                    </li>

                    <li class="active">
                        <a href="{{ url('cashout') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-dollar-sign">
                                        <line x1="12" y1="1" x2="12" y2="23" />
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                    </svg>
                                </div>
                                <span>Cierre</span>
                            </div>
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{ url('reports') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-pie-chart">
                                        <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                                        <path d="M22 12A10 10 0 0 0 12 2v10z" />
                                    </svg>
                                </div>
                                <span>Reportes</span>
                            </div>
                        </a>
                    </li>
                    <li class="active">
                        <a href="{{ url('backups') }}" class="menu-toggle" data-active="false">
                            <div class="base-menu">
                                <div class="base-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-database-backup">
                                        <ellipse cx="12" cy="5" rx="9" ry="3" />
                                        <path d="M3 12a9 3 0 0 0 5 2.69" />
                                        <path d="M21 9.3V5" />
                                        <path d="M3 5v14a9 3 0 0 0 6.47 2.88" />
                                        <path d="M12 12v4h4" />
                                        <path
                                            d="M13 20a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L12 16" />
                                    </svg>
                                    </svg>
                                </div>
                                <span>Respaldos</span>
                            </div>
                        </a>
                    </li>
                @endif
            </ul>

        </nav>
    </div>
@endif
