<!DOCTYPE html>
<html lang="es" data-mdb-theme="light">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <link rel="shortcut icon" href="{{secure_asset('front/images/app/LogoRC.png')}}?v={{ time() }}" />
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
  <link rel="stylesheet" href="{{ secure_asset('front/css/app.css') }}?v={{ time() }}">
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
  <script src="{{secure_asset('front/js/app/AlertMananger.js')}}?v={{ time() }}"></script>
  <script src="{{secure_asset('front/vendor/dataTable/jquery.dataTables.min.js')}}"></script>
  <script src="{{secure_asset('front/js/app.js')}}?v={{ time() }}"></script>

  <!-- DataTables Buttons -- >
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>

  < !-- Excel export -- >
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> -->

  <link rel="stylesheet" href="{{secure_asset('front/css/tema.css')}}">
  <script src="{{secure_asset('front/js/app/ToggleTema.js')}}"></script>

  @yield('cabecera')
</head>
<style>

</style>

<body class="with-welcome-text"> <!-- sidebar-icon-only -->
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top">
      <a class="navbar-brand" href="#">
        <div class="logo_rci"></div>
      </a>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <span id="tiempo_restante_head" class="me-3" style="font-size: small;"></span>
          </li>
          <div class="me-2">
            <input id="check" type="checkbox">
            <label for="check" class="check-trail">
              <span class="check-handler"></span>
            </label>
            <script>
              if (!localStorage.hasOwnProperty('data_mdb_theme') || !localStorage.data_mdb_theme) {
                localStorage.setItem('data_mdb_theme', 'light');
              }
              $('html').attr('data-mdb-theme', localStorage.data_mdb_theme);

              $('#check').prop('checked', localStorage.data_mdb_theme == 'light' ? true : false);
              if (!esCelularTema()) {
                $('.check-trail').append(`<span class="badge badge-secondary toltip-theme">
                      <b class="fw-bold">Shift</b><i class="fas fa-plus fa-2xs text-white"></i> <b class="fw-bold">D</b>
                  </span>`);
              }
            </script>
          </div>
          <!-- Avatar -->
          <div class="dropdown">
            <a data-mdb-dropdown-init class="dropdown-toggle d-flex align-items-center hidden-arrow rounded-circle"
              href="#" id="navbarDropdownMenuAvatar" role="button" aria-expanded="false" data-mdb-ripple-init>
              <img class="img-xs rounded-circle" src="{{ session('config_layout')->foto_perfil }}?v={{ time() }}"
                alt="Profile image">
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuAvatar">
              <li>
                <span class="dropdown-header text-center">
                  <img class="img-md rounded-circle" src="{{ session('config_layout')->foto_perfil }}?v={{ time() }}"
                    alt="Profile image" style="width: 90px; height: 90px;">
                  <p class="mb-1 mt-3 fw-semibold">
                    {{ session('config_layout')->nombre_perfil }}
                  </p>
                  <p>{{ session('config_layout')->text_acceso }}</p>
                </span>
              </li>
              <li>
                <a class="dropdown-item" href="{{secure_url('/soporte/logout')}}?v={{ time() }}">
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
            <a class="nav-link menu-lateral py-0" href="javascript:void(0)" role="button" id="expandir-menu">
              <i class="fas fa-bars"></i>
              <span class="ms-2 menu-title">Menu</span>
            </a>
          </li>
          <li class="nav-item menu-item text-center" tittle-menu>
            <div class="nav-link menu-perfil pt-0">
              <img class="rounded-circle" src="{{ session('config_layout')->foto_perfil }}">
              <span class="ms-2 menu-title">
                <p class="fw-bold mb-1 nombre-personal">{{ session('config_layout')->nombre_perfil }}</p>
                <p class="text-muted mb-0 tipo-personal">{{ session('config_layout')->text_acceso }}</p>
              </span>
            </div>
          </li>
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
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->


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
  <script type="text/javascript" src="{{secure_asset('front/vendor/mdboostrap/js/mdb.umd.min7.2.0.js')}}"></script>
  <script src="{{secure_asset('front/js/layout/template.js') }}"></script>
  <script src="{{secure_asset('front/js/layout/hoverable-collapse.js') }}"></script>
  <script src="{{secure_asset('front/js/layout/off-canvas.js')}}"></script>
  <script src="{{secure_asset('front/vendor/inputmask/jquery.inputmask.bundle.min.js')}}"></script>
  <script src="{{secure_asset('front/vendor/flatpickr/flatpickr.js')}}"></script>
  <script src="{{secure_asset('front/js/app/TableManeger.js')}}?v={{ time() }}"></script>
  <script src="{{secure_asset('front/js/app/FormMananger.js')}}?v={{ time() }}"></script>
  <!-- plugins:js -->
  @yield('scripts')
</body>

</html>