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
  <script src="{{secure_asset('front/js/AlertMananger.js')}}"></script>
  <script src="{{secure_asset('front/vendor/dataTable/jquery.dataTables.min.js')}}"></script>
  <script src="{{secure_asset('front/js/app.js')}}"></script>

  @yield('cabecera')
</head>
<style>

</style>

<body class="with-welcome-text sidebar-icon-only"> <!-- sidebar-icon-only -->
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
          <a class="navbar-brand brand-logo" href="{{secure_url('/soporte')}}">
            <div class="d-flex align-items-center">
              <img src="{{secure_asset('front/images/app/logo_tittle_rc_white.png')}}" alt="logo" />
            </div>
          </a>
          <!-- <a class="navbar-brand brand-logo" href="{{secure_url('/soporte')}}">
            <img src="{{secure_asset('front/images/app/logo_tittle_rc_white.png')}}" alt="logo" />
          </a> -->
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <span id="tiempo_restante_head" class="me-3" style="font-size: small;"></span>
          </li>
          <!-- <li class="nav-item dropdown">
            <a class="nav-link count-indicator" id="notificationDropdown" href="#" role="button" data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
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
            <a class="nav-link count-indicator" id="countDropdown" href="#" role="button" data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
              <i class="fas fa-envelope text-secondary"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="countDropdown">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li> -->
          <!-- <li class="nav-item dropdown user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" role="button" data-mdb-dropdown-init data-mdb-ripple-init
              aria-expanded="false">
              <img class="img-xs rounded-circle" src="{{ asset('front/images/auth/' . Auth::user()->foto_perfil) }}"
                alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="{{ asset('front/images/auth/' . Auth::user()->foto_perfil) }}"
                  alt="Profile image" style="width: 90px; height: 90px;">
                <p class="mb-1 mt-3 fw-semibold">
                  {{ explode(' ', Auth::user()->nombres)[0] . ' ' . explode(' ', Auth::user()->apellidos)[0]  }}
                </p>
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
              <a class="dropdown-item" href="{{secure_url('/logout')}}">
                <i class="dropdown-item-icon fas fa-power-off text-primary me-2"></i>
                Cerrar session
              </a>
            </div>
          </li> -->
          <!-- Avatar -->
          <div class="dropdown">
            <a data-mdb-dropdown-init class="dropdown-toggle d-flex align-items-center hidden-arrow rounded-circle"
              href="#" id="navbarDropdownMenuAvatar" role="button" aria-expanded="false" data-mdb-ripple-init>
              <img class="img-xs rounded-circle"
                src="{{ secure_asset('front/images/auth/' . Auth::user()->foto_perfil) }}" alt="Profile image">
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuAvatar">
              <li>
                <span class="dropdown-header text-center">
                  <img class="img-md rounded-circle"
                    src="{{ secure_asset('front/images/auth/' . Auth::user()->foto_perfil) }}" alt="Profile image"
                    style="width: 90px; height: 90px;">
                  <p class="mb-1 mt-3 fw-semibold">
                    {{ session('nomPerfil') }}
                  </p>
                  <p class="fw-light text-muted mb-0">{{Auth::user()->email}}</p>
                </span>
              </li>
              <li>
                <a class="dropdown-item" href="{{secure_url('/logout')}}">
                  <i class="dropdown-item-icon fas fa-power-off text-primary me-2"></i>
                  Cerrar session
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

        $('[data-bs-toggle="minimize"]').on("click", function () {
          localStorage.sidebarIconOnly = false;
          if (window.innerWidth > 992) {
            body.toggleClass('sidebar-icon-only');
            localStorage.sidebarIconOnly = body.hasClass('sidebar-icon-only') ? true : false;
          }
        });
      </script>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="sidebar-content"></div>
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          @foreach (session('customModulos') as $menu)
        <li class="nav-item menu-item">
        <a class="nav-link menu-link" {{!empty($menu->submenu) ? (string) 'data-mdb-collapse-init role=button aria-expanded=false aria-controls=' . $menu->ruta : ''}} data-mdb-ripple-init
          href={{!empty($menu->submenu) ? "#$menu->ruta" : url($menu->ruta)}}>
          <i class="{{ $menu->icon }} menu-icon"></i>
          <span class="menu-title">{{ $menu->descripcion }}</span>
          @if (!empty($menu->submenu)) <i class="menu-arrow"></i> @endif
        </a>
        @if (!empty($menu->submenu))
      <div class="collapse" id="{{$menu->ruta}}">
        <ul class="nav flex-column sub-menu">
        @foreach ($menu->submenu as $categoria => $submenus)
      @if ($categoria !== 'sin_categoria' || count($menu->submenu) > 1)
      <li class="nav-category-item">
      {{ $categoria === 'sin_categoria' ? 'Otros' : $categoria }}
      </li>
    @endif
      @foreach ($submenus as $submenu)
      <li class="nav-item">
      <a class="nav-link" href="{{secure_url($submenu->ruta)}}">{{ $submenu->descripcion }}</a>
      </li>
    @endforeach
    @endforeach
        </ul>
      </div>
    @endif
        </li>
      @endforeach
        </ul>
      </nav>

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <!-- <footer class="footer">
          <div class="d-flex justify-content-end">
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © {{date('Y')}}. All rights
              reserved.</span>
          </div>
        </footer> -->
        <!-- partial -->
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
          fetch(`${__url}/logout`, {
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
  <script type="text/javascript" src="{{secure_asset('front/vendor/mdboostrap/js/mdb.umd.min7.2.0.js')}}"></script>
  <script src="{{secure_asset('front/js/template.js') }}"></script>
  <script src="{{secure_asset('front/js/hoverable-collapse.js') }}"></script>
  <script src="{{secure_asset('front/js/off-canvas.js')}}"></script>
  <script src="{{secure_asset('front/vendor/inputmask/jquery.inputmask.bundle.min.js')}}"></script>
  <script src="{{secure_asset('front/vendor/flatpickr/flatpickr.js')}}"></script>
  <script src="{{secure_asset('front/js/TableManeger.js')}}"></script>
  <script src="{{secure_asset('front/js/FormMananger.js')}}"></script>
  <!-- plugins:js -->
  @yield('scripts')
</body>

</html>