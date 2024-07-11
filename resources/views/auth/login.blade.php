<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RC Sistema de Incidencias | Login</title>
    <link rel="stylesheet" href="{{asset('assets/css/vertical-layout-light/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/LogoRC.png')}}" />
</head>
<style>
    .separator {
        border-top: 2px solid #D8D8D8;
        margin-top: 10px;
        padding-top: 10px;
    }

    .separator p {
        color: #6c757d;
    }

    .title-login {
        position: relative;
        color: #6c757d;
    }

    .title-login:before,
    .title-login:after {
        content: "";
        height: 2px;
        position: absolute;
        top: 50%;
        width: 27%;
    }

    .title-login:before {
        background: linear-gradient(to left, #6c757d 0%, #fff 100%);
        border-top-right-radius: 15px;
        border-bottom-right-radius: 15px;
        left: -10px;
    }

    .title-login::after {
        background: linear-gradient(to right, #6c757d 0%, #fff 100%);
        border-top-left-radius: 15px;
        border-bottom-left-radius: 15px;
        right: -10px;
    }

    .form-control {
        border-color: #bbbbbb;
    }

    .error {
        color: red;
        font-size: 14px;
    }
</style>

<body class="container-scroller d-flex align-items-center justify-content-center w-100" style="min-height: 100vh;">
    <nav class="navbar fixed-top d-flex align-items-center flex-row" style="color: #fff; background-color: #333;">
        <div class="w-100 py-3 pe-3" style="text-align: right;">
            <span class="">INCIDENCIAS - RICARDO CALDERON INGENIEROS SAC</span>
        </div>
    </nav>
    <pre>{{Auth::user()}}</pre>
    <div style="width: 25rem;">
        <form action="{{route('login')}}" method="post">
            @csrf
            <div class="text-center mb-4">
                <h3 class="title-login">Iniciar Sesión</h3>
            </div><br>
            @if ($errors->has('usuario'))
                <div class="alert alert-danger" role="alert">
                    <i class="mdi mdi-alert-circle me-2"></i>{{ $errors->first('usuario') }}
                </div>
            @endif
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Usuario" name="usuario" id="usuario" required="">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Contraseña" name="password" id="contrasena"
                    required="">
            </div>
            <div style="text-align: right;">
                <button type="submit" class="btn btn-block btn-primary">Ingresar</button>
            </div>
        </form>
        <br>
        <div class="separator text-center">
            <p>©{{date("Y")}} Derechos Reservados. Ricardo Calderon Ingenieros!</p>
        </div>
    </div>
    <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
</body>

</html>