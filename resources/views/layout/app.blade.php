<!DOCTYPE html>
<html lang="es">


<!-- Mirrored from demo.bootstrapdash.com/star-admin-2-pro/themes/vertical-default-light/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 26 Jun 2024 17:36:14 GMT -->

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title')</title>   
  <!-- Font Awesome -->
  <link href="{{asset('front/vendor/mdboostrap/css/all.min6.0.0.css')}}" rel="stylesheet">
  <!-- MDB -->
  <link href="{{asset('front/vendor/mdboostrap/css/mdb.min7.2.0.css')}}" rel="stylesheet">
  <!-- Iconos -->
  <link href="{{ asset('front/vendor/simple-icon/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('front/vendor/simple-icon/styles.min.css') }}" rel="stylesheet">
  <link href="{{ asset('front/vendor/select/select2.min.css') }}" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <!-- Home -->
  <link href="{{ asset('front/css/panel.css') }}" rel="stylesheet">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('front/images/app/LogoRC.png')}}" />
  @yield('style')
</head>
<style>

</style>

<body class="with-welcome-text">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="fas fa-bars"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="{{url('/soporte')}}">
            <div class="d-flex align-items-center">
              <img class="img-xs rounded-circle" src="{{asset('assets/images/LogoRC.png')}}" style="height: 26px; width: 27px;" alt="logo" />
              <h6 class="ms-1 mb-0"><b>RC Ingenieros</b></h6>
            </div>
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{url('/soporte')}}">
            <img class="rounded-circle" src="{{asset('assets/images/LogoRC.png')}}" style="height: 38px; width: 39px;" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav ms-auto">

          <li class="nav-item dropdown">
            <a class="nav-link count-indicator" id="notificationDropdown" href="#" role="button"  data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
              <i class="fas fa-bell text-secondary"></i>
              <span class="count"></span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="notificationDropdown">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link count-indicator" id="countDropdown" href="#" role="button"  data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
              <i class="fas fa-envelope text-secondary"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="countDropdown">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" role="button"  data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
              <img class="img-xs rounded-circle" src="{{ asset('assets/images/auth/' . Auth::user()->foto_perfil) }}" alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="{{ asset('assets/images/auth/' . Auth::user()->foto_perfil) }}" alt="Profile image">
                <p class="mb-1 mt-3 fw-semibold">{{ Auth::user()->nombres . ' ' . Auth::user()->apellidos }}</p>
                <p class="fw-light text-muted mb-0">{{Auth::user()->email}}</p>
              </div>
              <a class="dropdown-item">
                <i class="dropdown-item-icon far fa-circle-user text-primary me-2"></i>
                Mi Perfil
                <span class="badge badge-pill badge-danger">1</span>
              </a>
              <a class="dropdown-item">
                <i class="dropdown-item-icon far fa-calendar-check text-primary me-2"></i>
                Actividad
              </a>
              <a class="dropdown-item" href="{{url('/logout')}}">
                <i class="dropdown-item-icon fas fa-power-off text-primary me-2"></i>
                Cerrar session
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
          data-bs-toggle="offcanvas">
          <span class="fas fa-bars"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{url('/soporte')}}">
              <i class="fas fa-house menu-icon"></i>
              <span class="menu-title">Panel Inicio</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-mdb-collapse-init data-mdb-ripple-init href="#ControlEmpresas" role="button" aria-expanded="false" aria-controls="ControlEmpresas">
              <i class="far fa-building menu-icon"></i>
              <span class="menu-title">Empresas</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ControlEmpresas">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/soport-empresa/empresas')}}"><b>Empresas</b></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/soport-empresa/grupos')}}"><b>Grupos Empresas</b></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/soport-empresa/sucursales')}}"><b>Sucursales Empresas</b></a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-mdb-collapse-init data-mdb-ripple-init href="#ControlUsarios" role="button" aria-expanded="false" aria-controls="ControlUsarios">
              <i class="fas fa-user-group menu-icon"></i>
              <span class="menu-title">Control de Usuarios</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ControlUsarios">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/control-de-usuario/usuarios')}}"><b>Usuarios</b></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{url('/control-de-usuario/mi-perfil')}}"><b>Mi Perfil Usuarios</b></a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-flex justify-content-end">
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © {{date('Y')}}. All rights
              reserved.</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <script src="{{ asset('front/vendor/jquery/jquery.min.js') }}"></script>
  <!-- MDB -->
  <script type="text/javascript" src="{{asset('front/vendor/mdboostrap/js/mdb.umd.min7.2.0.js')}}"></script>
  <script src="{{ asset('front/js/template.js') }}"></script>
  <script src="{{ asset('front/js/hoverable-collapse.js') }}"></script>
  <script src="{{asset('assets/js/off-canvas.js')}}"></script>
  <script src="{{asset('front/vendor/dataTable/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('front/vendor/select/select2.min.js')}}"></script>
  <script src="{{asset('front/vendor/select/form_select2.js')}}"></script>
  <script src="{{asset('front/vendor/sweetalert/sweetalert2@11.js')}}"></script>
  <!-- plugins:js -->
  @yield('scripts')
</body>

</html>