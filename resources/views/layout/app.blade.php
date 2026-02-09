<!DOCTYPE html>
<html lang="es" data-mdb-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" href="{{ secure_asset($ft_img->icon) }}" />

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#000000">

    <title>@yield('title')</title>

    <!-- Para iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Asistencias">
    <link rel="apple-touch-icon" href="{{ secure_asset($ft_img->icon_192) }}">

    <!-- Para Windows -->
    <meta name="msapplication-TileImage" content="{{ secure_asset($ft_img->icon_192) }}">
    <meta name="msapplication-TileColor" content="#000000">

    <!-- Font Awesome -->
    <link href="{{ secure_asset($ft_css->mdb_all_min6_0_0) }}" rel="stylesheet">
    <!-- MDB -->
    <link href="{{ secure_asset($ft_css->mdb_min7_2_0) }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ secure_asset($ft_css->customSelect2) }}">

    <link rel="stylesheet" href="{{ secure_asset($ft_css->sweet_animate) }}">
    <link rel="stylesheet" href="{{ secure_asset($ft_css->sweet_default) }}">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="{{ secure_asset($ft_css->fonts) }}" />
    <!-- Home -->
    <link rel="stylesheet" href="{{ secure_asset($ft_css->layout) }}">
    <link rel="stylesheet" href="{{ secure_asset($ft_css->app) }}">
    <script>
        const __url = "{{ secure_url('') }}";
        const __asset = "{{ secure_asset('front/') }}";
        const __token = "{{ csrf_token() }}";;
    </script>
    <!-- JQuery -->
    <script src="{{ secure_asset($ft_js->jquery) }}"></script>
    <script src="{{ secure_asset($ft_js->sweet_sweetalert2) }}"></script>
    <script src="{{ secure_asset($ft_js->customSelect2) }}"></script>
    <script src="{{ secure_asset($ft_js->form_customSelect2) }}"></script>
    <script src="{{ secure_asset($ft_js->AlertMananger) }}"></script>
    <script src="{{ secure_asset($ft_js->CardTable) }}"></script>
    <script src="{{ secure_asset($ft_js->jquery_dataTables) }}"></script>
    <script src="{{ secure_asset($ft_js->app) }}"></script>

    @yield('cabecera')
</head>

<body>
    <div class="layout-container">
        <script>
            const layout_Container = document.querySelector('.layout-container');
            if (eval(localStorage.sidebarIconOnly_asistencias) && window.innerWidth > 767) {
                layout_Container.classList.add('sidebar-only-icon');
            } else {
                layout_Container.classList.remove('sidebar-only-icon');
            }
        </script>
        <div class="sidevar__overlay"></div>

        <!-- SIDEBAR sidebar-only-icon-->
        <aside class="sidebar">

            <!-- Header -->
            <nav class="sidebar__header sidebar__header-only-icon">
                <button class="sidebar-close hover-layout" type="button" aria-label="Cerrar barra lateral">
                    <i class="fas fa-bars" style="color: #8f8f8f;"></i>
                </button>

                <a class="sidebar-icon-logo hover-layout" href="/">
                    <div></div>
                </a>
            </nav>

            <!-- Body -->
            <div class="sidebar__body">

                @foreach ($customModulos as $menu)
                    <div class="sidebar__item" {{ !empty($menu['submenu']) ? 'data-collapse="false"' : '' }}>
                        <a class="sidebar__link{{ !empty($menu['submenu']) ? ' sidebar__link-menu' : '' }}"
                            {{ empty($menu['submenu']) ? 'data-mdb-ripple-init' : '' }}
                            href="{{ !empty($menu['submenu']) ? 'javascript:void(0)' : secure_url($menu['ruta']) }}"
                            @if (!empty($menu['submenu'])) data-menu="{{ $menu['ruta'] }}" @endif>
                            <div class="sidebar__link-icon">
                                <i class="{{ $menu['icon'] }}"></i>
                            </div>
                            <div class="sidebar__link-text">
                                <div class="truncate">{{ $menu['descripcion'] }}</div>
                            </div>
                        </a>

                        @if (!empty($menu['submenu']))
                            <ul class="sidebar__submenu">
                                @foreach ($menu['submenu'] as $categoria => $submenus)
                                    @if ($categoria !== 'sin_categoria' || count($menu['submenu']) > 1)
                                        <li class="sidebar__submenu-title">
                                            {{ $categoria === 'sin_categoria' ? 'Otros' : $categoria }}
                                        </li>
                                    @endif
                                    @foreach ($submenus as $submenu)
                                        <li class="sidebar__submenu-item">
                                            <a class="sidebar__submenu-link" data-mdb-ripple-init
                                                href="{{ secure_url($submenu['ruta']) }}"
                                                data-ruta="{{ $menu['ruta'] }}">
                                                {{ $submenu['descripcion'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach

            </div>

            <!-- Footer -->
            <div class="sidebar__footer dropup">
                <button class="sidebar__footer-user hover-layout" data-mdb-dropdown-init data-mdb-ripple-init
                    aria-expanded="false" data-mdb-dropdown-animation="off">
                    <div class="sidebar__footer-user-sigla image-user" style="background-image: url('{{ $config->foto_perfil }}');"></div>
                    <div class="sidebar__footer-user-text">
                        <h5>{{ $config->nombre_perfil }}</h5>
                        <h6>{{ $config->acceso }}</h6>
                    </div>
                </button>
                <ul class="dropdown-menu py-2 px-1" style="width: 15.25rem !important;">
                    <li>
                        <div class="dropdown-header align-items-center d-flex" style="user-select: none">
                            <div class="align-items-center d-flex justify-content-center rounded-circle text-white image-user"
                                style="width: 2rem; height: 2rem; background-image: url('{{ $config->foto_perfil }}');"></div>
                            <div class="dropdown-header__text ms-2">
                                <span>{{ $config->nombre_perfil }}</span>
                                <p class="fw-bold mb-0 mt-2 text-secondary">{{ $config->acceso }}</p>
                            </div>
                        </div>
                        <hr class="mx-2 mt-0 mb-1">
                    </li>
                    <li>
                        <a class="dropdown-item py-3 rounded" href="{{ secure_url('/logout') }}"
                            onclick="boxAlert.loading()">
                            <i class="fas fa-arrow-right-from-bracket me-2"></i> Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        <!-- MAIN CONTENT -->
        <main class="content-full flex-grow-1">
            <!-- Navbar -->
            <nav class="navbar pe-3" style="padding-left: 16px;">
                <div class="navbar-brand mb-0 p-0">
                    <div class="logo_rci"></div>
                </div>
                <div class="navbar-brand mb-0 p-0">
                    {{-- Switch Layout --}}
                    @include('layout.partials.swicth_layout')
                    {{-- Notifications --}}
                    {{-- <div class="ms-1">
                        <div class="dropdown" id="contenedor-notificaciones">
                            <button data-mdb-dropdown-init class="btn-notification hover-layout" role="button"
                                data-mdb-auto-close="outside" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="badge rounded-pill bg-danger" badge-notification></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right p-0 pb-1">
                                <div class="dropdown-header d-flex align-items-center justify-content-between px-2">
                                    <h6 class="mb-0" style="user-select: none">Notificaciones</h6>
                                    <button class="btn btn-sm px-2" noti-btn="reload" data-mdb-ripple-init><i
                                            class="fas fa-rotate"></i></button>
                                </div>
                                <div class="dropdown-body rounded px-2">
                                    <div class="dropdown-text text-center text-muted py-3">
                                        Sin notificaciones
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="ms-1">
                        <div class="dropdown" id="contenedor-sigla">
                            <button class="btn-notification hover-layout" data-mdb-dropdown-init data-mdb-ripple-init
                                aria-expanded="false" data-mdb-dropdown-animation="off">
                                <div class="sigla image-user" style="background-image: url('{{ $config->foto_perfil }}');"></div>
                            </button>
                            <ul class="dropdown-menu pt-1 pb-2 px-1">
                                <li class="p-2">
                                    <div class="dropdown-header p-0" style="user-select: none">
                                        <div class="text-center rounded py-3 px-2">
                                            <div class="align-items-center d-flex justify-content-center rounded-circle text-white mx-auto image-user"
                                                style="width: 3.5rem; height: 3.5rem; font-size: 1.5rem; background-image: url('{{ $config->foto_perfil }}');"></div>
                                            <p class="fw-bold mb-0 mt-2 text-secondary">{{ $config->nombre_perfil }}
                                            </p>
                                            <small>{{ $config->acceso }}</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="px-2">
                                    <a class="dropdown-item py-3 rounded" href="{{ secure_url('/logout') }}"
                                        onclick="boxAlert.loading()">
                                        <i class="fas fa-arrow-right-from-bracket me-2"></i> Cerrar sesión
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="ms-1">
                        <button class="sidebar-close__navbar hover-layout" type="button"
                            aria-label="Cerrar barra lateral">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Content Wrapper -->
            <div class="content-wrapper p-3">
                @yield('content')
            </div>

        </main>
        <script src="{{ secure_asset($ft_js->toggle_template) }}"></script>
    </div>

    <button hidden data-mdb-modal-init data-mdb-target="#modal_pdf"></button>
    <div class="modal fade" id="modal_pdf" aria-labelledby="modal_pdf" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Visualización de PDF
                        <span class="badge badge-success badge-lg" aria-item="codigo"></span>
                        <span class="badge badge-info badge-lg" aria-item="codigo_orden"></span>
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 position-relative">
                    <iframe id="contenedor_doc" class="w-100" frameborder="0"></iframe>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-1">
                    <button type="button" class="btn btn-link " data-mdb-ripple-init
                        data-mdb-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const intervalToken = setInterval(() => {
            if (!document.cookie.includes('XSRF-TOKEN')) {
                clearInterval(intervalToken);
                location.reload();
            }
        }, 1000);

        let alertaMostrada = false;
        let logoutEjecutado = false;

        const intervalo = setInterval(() => {
            const fechaTurno = "{{ session('turno_fin') }}";

            if (fechaTurno) {
                const fechaIngresada = new Date("{{ session('turno_fin') }}");
                const fechaActual = new Date();

                // Calculamos la diferencia en milisegundos
                const diferenciaMs = fechaIngresada - fechaActual;

                // Convertimos la diferencia a minutos
                const minutosRestantes = Math.floor(diferenciaMs / 60000); // 1 minuto = 60000 ms
                const segundosRestantes = Math.floor((diferenciaMs % 60000) / 1000);
                const strmin = String(minutosRestantes).padStart(2, '0');
                const strseg = String(segundosRestantes).padStart(2, '0');

                // Mostrar la alerta solo una vez cuando falte 1 minuto
                if (minutosRestantes < 1 && !alertaMostrada) {
                    boxAlert.toast({
                        i: 'warning',
                        h: `<p class="mb-1" style="font-size: .88rem;"><b>SESSION TURNO DE APOYO</b></p><p class="mb-0" style="font-size: .85rem;" id="tiempo_restante">cerrará en ${strmin}m ${strseg}s.</p>`,
                        b: "#dfb45d",
                        c: "#ffffff",
                        tr: diferenciaMs
                    });
                    alertaMostrada = true; // Marcar como mostrada para no repetir
                } else {
                    if (minutosRestantes >= 0 && minutosRestantes < 1 && segundosRestantes >= 0) {
                        $('#tiempo_restante').html(`cerrará en ${strmin}m ${strseg}s.`);
                        $('#tiempo_restante_head').html(`cerrará session en ${strmin}m ${strseg}s.`);
                    }
                }

                // Ejecutar el logout solo una vez cuando la fecha ya pasó
                if (fechaIngresada <= fechaActual && !logoutEjecutado) {
                    fetch(`${__url}/soporte/logout`, {
                        method: 'GET',
                    }).then(response => {
                        if (response.ok) {
                            logoutEjecutado = true; // Marcar como ejecutado
                            clearInterval(intervalo); // Detener el setInterval
                            location.reload();
                        }
                    });
                }
            }
        }, 1000);
    </script>
    <!-- MDB -->
    <script type="text/javascript" src="{{ secure_asset($ft_js->mdb_umd_min7_2_0) }}"></script>
    <script src="{{ secure_asset($ft_js->template) }}"></script>
    <script src="{{ secure_asset($ft_js->inputmask) }}"></script>
    <script src="{{ secure_asset($ft_js->TableManeger) }}"></script>
    <script src="{{ secure_asset($ft_js->FormMananger) }}"></script>

    @yield('scripts')
</body>

</html>
