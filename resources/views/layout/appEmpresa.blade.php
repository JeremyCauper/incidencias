<!DOCTYPE html>
<html lang="es" data-mdb-theme="light">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{secure_asset('front/images/app/LogoRC.png')}}" />
    <title>@yield('title')</title>
    <!-- Font Awesome -->
    <link href="{{secure_asset('front/vendor/mdboostrap/css/all.min6.0.0.css')}}" rel="stylesheet">
    <!-- MDB -->
    <link href="{{secure_asset('front/vendor/mdboostrap/css/mdb.min7.2.0.css')}}" rel="stylesheet">
    <!-- Iconos -->
    <link href="{{ secure_asset('front/vendor/simple-icon/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('front/vendor/simple-icon/styles.min.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('front/vendor/select/select2.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{secure_asset('front/vendor/flatpickr/flatpickr.min.css')}}">

    <link rel="stylesheet" href="{{secure_asset('front/vendor/sweetalert/animate.min.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/vendor/sweetalert/default.css')}}">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
    <!-- Home -->
    <link rel="stylesheet" href="{{ secure_asset('front/css/app.css') }}">
    <script>
        const __url = "{{secure_url('')}}";
        const __asset = "{{secure_asset('/front')}}";
        const __token = "{{ csrf_token() }}";
    </script>
    <!-- JQuery -->
    <script src="{{ secure_asset('front/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{secure_asset('front/vendor/sweetalert/sweetalert2@11.js')}}"></script>
    <script src="{{secure_asset('front/vendor/select/select2.min.js')}}"></script>
    <script src="{{secure_asset('front/vendor/select/form_select2.js')}}"></script>
    <script src="{{secure_asset('front/js/app/AlertMananger.js')}}"></script>
    <script src="{{secure_asset('front/vendor/dataTable/jquery.dataTables.min.js')}}"></script>
    <script src="{{secure_asset('front/js/app.js')}}"></script>

    @yield('cabecera')
</head>
<style>

</style>

<body class="with-welcome-text"> <!-- sidebar-icon-only -->
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top">
            <a class="navbar-brand" href="#">
                <img src="{{secure_asset('front/images/app/LogoRC_WNormal.webp')}}" class="ms-2" height="45"
                    alt="logo" />
            </a>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span id="tiempo_restante_head" class="me-3" style="font-size: small;"></span>
                    </li>
                    <!-- Avatar -->
                    <div class="dropdown">
                        <a data-mdb-dropdown-init
                            class="dropdown-toggle d-flex align-items-center hidden-arrow rounded-circle" href="#"
                            id="navbarDropdownMenuAvatar" role="button" aria-expanded="false" data-mdb-ripple-init>
                            <img class="img-xs rounded-circle" src="{{ session('config_layout')->foto_perfil }}"
                                alt="Profile image">
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuAvatar">
                            <li>
                                <span class="dropdown-header text-center">
                                    <img class="img-md rounded-circle" src="{{ session('config_layout')->foto_perfil }}"
                                        alt="Profile image" style="width: 90px; height: 90px;">
                                    <p class="mb-1 mt-3 fw-semibold">
                                        {{ session('config_layout')->nombre_perfil }}
                                    </p>
                                    <p>{{ session('config_layout')->text_acceso }}</p>
                                </span>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{secure_url('/empresa/logout')}}">
                                    <i class="dropdown-item-icon fas fa-power-off text-primary me-2"></i>
                                    Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </div>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-bs-toggle="offcanvas">
                    <span class="fas fa-bars"></span>
                </button>
            </div>
            <script>
                var body = $('body');
                if (eval(localStorage.sidebarIconOnly) && window.innerWidth > 992) {
                    body.addClass('sidebar-icon-only');
                }

                $(document).ready(function () {
                    $('#expandir-menu i').on("click", function () {
                        localStorage.sidebarIconOnly = false;
                        if (window.innerWidth > 992) {
                            body.toggleClass('sidebar-icon-only');
                            localStorage.sidebarIconOnly = body.hasClass('sidebar-icon-only') ? true : false;
                        }
                    });
                })
            </script>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="sidebar-content" role="button"></div>
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item menu-item text-center pb-1 menu-bar" tittle-menu>
                        <a class="nav-link menu-lateral" href="javascript:void(0)" role="button" id="expandir-menu">
                            <i class="fas fa-bars"></i>
                            <span class="ms-2 menu-title">Menu</span>
                        </a>
                    </li>
                    <li class="nav-item menu-item text-center" tittle-menu>
                        <div class="nav-link menu-perfil">
                            <img class="rounded-circle" src="{{ session('config_layout')->foto_perfil }}">
                            <span class="ms-2 menu-title">
                                <p class="fw-bold mb-1 nombre-personal">{{ session('config_layout')->nombre_perfil }}
                                </p>
                                <p class="text-muted mb-0 tipo-personal">{{ session('config_layout')->text_acceso }}</p>
                            </span>
                        </div>
                    </li>
                    <li class="nav-item menu-item">
                        <a class="nav-link menu-link" data-mdb-ripple-init="" href="{{ url('/empresa/incidencias') }}">
                            <i class="fas fa-house menu-icon"></i>
                            <span class="menu-title">Incidencias</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- content-wrapper ends -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->


    <script>
        setTimeout(function () {
            location.reload();
        }, 7205000);

    </script>
    <!-- MDB -->
    <script type="text/javascript" src="{{secure_asset('front/vendor/mdboostrap/js/mdb.umd.min7.2.0.js')}}"></script>
    <script src="{{secure_asset('front/js/layout/template.js') }}"></script>
    <script src="{{secure_asset('front/js/layout/hoverable-collapse.js') }}"></script>
    <script src="{{secure_asset('front/js/layout/off-canvas.js')}}"></script>
    <script src="{{secure_asset('front/vendor/inputmask/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{secure_asset('front/vendor/flatpickr/flatpickr.js')}}"></script>
    <script src="{{secure_asset('front/js/app/TableManeger.js')}}"></script>
    <script src="{{secure_asset('front/js/app/FormMananger.js')}}"></script>
    <!-- plugins:js -->
    @yield('scripts')
</body>

</html>