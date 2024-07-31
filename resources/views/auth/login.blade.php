<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <!-- Requiredd meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{asset('front/images/app/LogoRC.png')}}" />
    <title>RC Incidencias | Login</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('front/vendor/mdboostrap/css/all.min6.0.0.css')}}">
    <link rel="stylesheet" href="{{asset('front/vendor/mdboostrap/css/mdb.min7.2.0.css')}}">
    <link rel="stylesheet" href="{{asset('front/css/app/auth.css')}}">
</head>

<body class="w-100 h-100 d-flex justify-content-center align-items-center">

    <nav class="navbar bg-dark-subtle fixed-top">
        <div class="container-fluid py-1 mx-2">
            <a class="navbar-brand" href="{{url('/inicio')}}">
                <img src="{{asset('front/images/app/logo_tittle_rc.png')}}" height="34" alt="RC Logo" loading="lazy" style="margin-top: -1px;">
            </a>
            <span class="navbar-brand text-white me-0" style="font-size: smaller;">
                INCIDENCIAS - RICARDO CALDERON INGENIEROS SAC
            </span>
        </div>
    </nav>

    <div style="width: 22rem;">
        <form id="form-login" class="m-2">
            <div class="text-center title-login">
                <img src="{{asset('front/images/app/tittle_login.png')}}" height="60" alt="">
            </div>
            <div class="alert alert-danger hidden" role="alert">
                <i class="fas fa-triangle-exclamation"></i> Usuario incorrecto
            </div>
            <!-- Usuario input -->
            <div data-mdb-input-init class="form-outline my-4" aria-cinput="usuario">
                <input type="text" name="usuario" id="usuario" class="form-control" autofocus require="usuario">
                <label class="form-label" for="usuario">Usuario</label>
            </div>

            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-4" aria-cinput="contraseña">
                <input type="password" name="password" id="contrasena" class="form-control" require="contraseña">
                <label class="form-label" for="contrasena">Contraseña</label>
            </div>

            <!-- Submit button -->
            <div class="text-end">
                <button type="submit" id="btn-ingresar" data-mdb-ripple-init class="btn btn-primary mb-4">Ingresar</button>
            </div>
        </form>
        <div class="text-center border-top mt-2 pt-2">
            <p class="text-secondary" style="font-size: small;">©{{date("Y")}} Derechos Reservados. Ricardo Calderon Ingenieros!</p>
        </div>
    </div>
    
    <script src="{{asset('front/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('front/vendor/mdboostrap/js/mdb.umd.min7.2.0.js')}}"></script>
    <script src="{{asset('front/vendor/sweetalert/sweetalert2@11.js')}}"></script>
    <script>
        const __url = "{{url('')}}";
        const __token = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('front/js/app/auth.js')}}"></script>
</body>

</html>