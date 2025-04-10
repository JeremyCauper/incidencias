<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <!-- Requiredd meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{secure_asset('front/images/app/LogoRC.png')}}" />
    <title>RC Incidencias | Login</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{secure_asset('front/vendor/mdboostrap/css/all.min6.0.0.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/vendor/mdboostrap/css/mdb.min7.2.0.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/vendor/sweetalert/default.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/css/app/auth.css')}}">
</head>

<body style="height: 100% !important;">

    <nav class="navbar bg-dark-subtle fixed-top">
        <div class="container-fluid py-1 mx-2">
            <a class="navbar-brand" href="{{secure_url('/soporte')}}">
                <img src="{{secure_asset('front/images/app/logo_tittle_rc.png')}}" height="34" alt="RC Logo" loading="lazy"
                    style="margin-top: -1px;">
            </a>
            <span class="navbar-brand text-white me-0" style="font-size: smaller;">
                INCIDENCIAS - RICARDO CALDERON INGENIEROS SAC
            </span>
        </div>
    </nav>

    <div class="content-fluid h-100 d-flex justify-content-center align-items-center">
        <div style="width: 22rem;">
            <form id="form-login" class="m-2">
                <div class="text-center title-login">
                    <img src="{{secure_asset('front/images/app/tittle_login.png')}}" height="60" alt="">
                </div>
                <div class="alert alert-danger hidden" role="alert">
                    <i class="fas fa-triangle-exclamation"></i> Usuario incorrecto
                </div>
                <!-- Usuario input -->
                <div class="input-group form-outline my-4" style="padding: .1152rem;" data-mdb-input-init aria-cinput="usuario">
                    <span class="input-group-text border-0 px-2" for="usuario"><i class="fas fa-circle-user"></i></span>
                    <input type="text" name="usuario" id="usuario" class="form-control border-start-0 ps-1" placeholder="Usuario" autofocus require="usuario">
                </div>

                <!-- Password input -->
                <div class="input-group form-outline my-4" style="padding: .1152rem;" data-mdb-input-init aria-cinput="contraseña">
                    <span class="input-group-text border-0 px-2" for="contrasena"><i class="fas fa-key"></i></span>
                    <input type="password" name="password" id="contrasena" class="form-control border-start-0 ps-1" placeholder="Contraseña" autofocus require="contraseña">
                    <span class="input-group-text border-0 px-2" style="padding-top: 7px;"><i class="fas fa-eye-slash" id="icon-pass"></i></span>
                </div>

                <!-- Submit button -->
                <div class="text-end">
                    <button type="submit" id="btn-ingresar" data-mdb-ripple-init
                        class="btn btn-primary mb-4">Ingresar</button>
                </div>
            </form>
            <div class="text-center border-top mt-2 pt-2">
                <p class="text-secondary" style="font-size: small;">©{{date("Y")}} Derechos Reservados. Ricardo Calderon
                    Ingenieros!</p>
            </div>
        </div>
    </div>

    <script src="{{secure_asset('front/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{secure_asset('front/vendor/mdboostrap/js/mdb.umd.min7.2.0.js')}}"></script>
    <script src="{{secure_asset('front/vendor/sweetalert/sweetalert2@11.js')}}"></script>
    <script src="{{secure_asset('front/js/app/AlertMananger.js')}}"></script>
    <script>
        const __url = "{{secure_url('')}}";
        const __token = "{{ csrf_token() }}";
    </script>
    <script src="{{secure_asset('front/js/auth/auth.js')}}"></script>
</body>

</html>