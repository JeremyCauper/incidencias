<!DOCTYPE html>
<html lang="es">


<!-- Mirrored from demo.bootstrapdash.com/star-admin-2-pro/themes/vertical-default-light/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 26 Jun 2024 17:36:14 GMT -->

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title')</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('assets/vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/typicons/typicons.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/simple-line-icons/css/simple-line-icons.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="{{asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('assets/css/vertical-layout-light/style.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/select/select2.min.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('assets/images/LogoRC.png')}}" />
</head>
<style>
    .sidebar-icon-only .sidebar .nav .active::before {
      position: absolute;
      content: "";
      width: 2px;
      height: 50px;
      background-color: #1F3BB3;
      top: 0px;
      right: 0px;
      z-index: 999;
    }
</style>

<body class="with-welcome-text">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="{{url('/soporte')}}">
            <div class="d-flex align-items-center">
              <img class="img-xs rounded-circle" src="{{asset('assets/images/LogoRC.png')}}" style="height: 26px; width: 27px;" alt="logo" />
              <h4 class="ms-1 mb-0"><b>RC Ingenieros</b></h4>
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
            <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
              <i class="icon-bell"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
              aria-labelledby="notificationDropdown">
              <a class="dropdown-item py-3 border-bottom">
                <p class="mb-0 fw-medium float-start">You have 4 new notifications </p>
                <span class="badge badge-pill badge-primary float-end">View all</span>
              </a>
              <a class="dropdown-item preview-item py-3">
                <div class="preview-thumbnail">
                  <i class="mdi mdi-alert m-auto text-primary"></i>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal text-dark mb-1">Application Error</h6>
                  <p class="fw-light small-text mb-0"> Just now </p>
                </div>
              </a>
              <a class="dropdown-item preview-item py-3">
                <div class="preview-thumbnail">
                  <i class="mdi mdi-lock-outline m-auto text-primary"></i>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal text-dark mb-1">Settings</h6>
                  <p class="fw-light small-text mb-0"> Private message </p>
                </div>
              </a>
              <a class="dropdown-item preview-item py-3">
                <div class="preview-thumbnail">
                  <i class="mdi mdi-airballoon m-auto text-primary"></i>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal text-dark mb-1">New user registration</h6>
                  <p class="fw-light small-text mb-0"> 2 days ago </p>
                </div>
              </a>
            </div>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown"
              aria-expanded="false">
              <i class="icon-mail icon-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
              aria-labelledby="countDropdown">
              <a class="dropdown-item py-3">
                <p class="mb-0 fw-medium float-start">You have 7 unread mails </p>
                <span class="badge badge-pill badge-primary float-end">View all</span>
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <img src="{{asset('assets/images/faces/face10.jpg')}}" alt="image" class="img-sm profile-pic">
                </div>
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis fw-medium text-dark">Marian Garner </p>
                  <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <img src="{{asset('assets/images/faces/face12.jpg')}}" alt="image" class="img-sm profile-pic">
                </div>
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis fw-medium text-dark">David Grey </p>
                  <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <img src="{{asset('assets/images/faces/face1.jpg')}}" alt="image" class="img-sm profile-pic">
                </div>
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis fw-medium text-dark">Travis Jenkins </p>
                  <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                </div>
              </a>
            </div>
          </li>

          <li class="nav-item dropdown user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle" src="{{ asset('assets/images/auth/' . Auth::user()->foto_perfil) }}" alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="{{ asset('assets/images/auth/' . Auth::user()->foto_perfil) }}" alt="Profile image">
                <p class="mb-1 mt-3 fw-semibold">{{ Auth::user()->nombres . ' ' . Auth::user()->apellidos }}</p>
                <p class="fw-light text-muted mb-0">{{Auth::user()->email}}</p>
              </div>
              <a class="dropdown-item">
                <i class="dropdown-item-icon mdi mdi-account-circle-outline text-primary me-2"></i>
                Mi Perfil
                <span class="badge badge-pill badge-danger">1</span>
              </a>
              <a class="dropdown-item">
                <i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i>
                Actividad
              </a>
              <a class="dropdown-item" href="{{url('/logout')}}">
                <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>
                Cerrar session
              </a>
            </div>
          </li>

        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
          data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
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
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Panel Inicio</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ControlEmpresas" aria-expanded="false"
              aria-controls="ControlEmpresas">
              <i class="mdi mdi-home-modern menu-icon"></i>
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
            <a class="nav-link" data-bs-toggle="collapse" href="#ControlUsarios" aria-expanded="false"
              aria-controls="ControlUsarios">
              <i class="mdi mdi-account-multiple menu-icon"></i>
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

  <!-- plugins:js -->
  <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{asset('assets/vendors/chart.js/chart.umd.js')}}"></script>
  <script src="{{asset('assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{asset('assets/js/off-canvas.js')}}"></script>
  <script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('assets/js/template.js')}}"></script>
  <script src="{{asset('assets/js/settings.js')}}"></script>
  <script src="{{asset('assets/js/todolist.js')}}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{asset('assets/js/jquery.cookie.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendors/sweetalert/sweetalert.min.js')}}"></script>
  <script src="{{asset('assets/vendors/select/select2.min.js')}}"></script>
  <script src="{{asset('assets/vendors/select/form_select2.js')}}"></script>
  @yield('scripts')
</body>

</html>