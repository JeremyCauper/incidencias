<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <!-- Requiredd meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{secure_asset('front/images/app/LogoRC.png')}}?v={{ time() }}" />
    <title>RC Incidencias | Login</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{secure_asset('front/vendor/mdboostrap/css/all.min6.0.0.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/vendor/mdboostrap/css/mdb.min7.2.0.css')}}">
</head>

<body class="w-100 h-100 d-flex justify-content-center align-items-center">

    <nav class="navbar bg-dark-subtle fixed-top">
        <div class="container-fluid py-1 mx-2">
            <a class="navbar-brand" href="{{url('/soporte')}}">
                <img src="{{secure_asset('front/images/app/logo_tittle_rc.png')}}?v={{ time() }}" height="34" alt="RC Logo" loading="lazy" style="margin-top: -1px;">
            </a>
            <span class="navbar-brand text-white me-0" style="font-size: smaller;">
                INCIDENCIAS - RICARDO CALDERON INGENIEROS SAC
            </span>
        </div>
    </nav>

    <div class="mt-5 pt-5">


        <div class="row">
            <div class="col-md-12 text-center float-md-none mx-auto">
                <img src="{{secure_asset('front/images/errors/403_mdb.webp')}}?v={{ time() }}" alt="error 403" class="img-fluid wow fadeIn">
            </div>
        </div>



        <div class="row mt-5">
            <div class="col-md-12 text-center mb-5">
                <h2 class="h2-responsive wow fadeIn mb-4" data-wow-delay="0.2s" style="font-weight:500;">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">No tienes permiso para acceder a esta página.</font>
                    </font>
                </h2>
                <p class="wow fadeIn" data-wow-delay="0.4s" style="font-size:1.25rem;">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">Por favor, háganos saber cómo llegó hasta aquí y utilice el siguiente enlace para regresar al puerto seguro.</font>
                    </font>
                </p>
                <a class="btn btn-primary btn-sm" href="javascript:void(0);" onclick="history.back();">REGRESEMOS</a>
            </div>
        </div>

    </div>

    <script src="{{secure_asset('front/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{secure_asset('front/vendor/mdboostrap/js/mdb.umd.min7.2.0.js')}}"></script>
</body>

</html>