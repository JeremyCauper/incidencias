<!DOCTYPE html>
<!-- <html lang="es" class="h-100" data-mdb-theme="light"> -->
<html lang="es" class="h-100" data-mdb-theme="dark">

<head>
    <!-- Requiredd meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{secure_asset('front/images/app/LogoRC.png')}}" />
    <title>RC Incidencias | Inicio</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{secure_asset('front/vendor/mdboostrap/css/all.min6.0.0.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/vendor/mdboostrap/css/mdb.min7.2.0.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/vendor/sweetalert/default.css')}}">
    <!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/auth.css')}}"> -->
    <link rel="stylesheet" href="{{secure_asset('front/css/app/login/login.css')}}">

    <script src="{{secure_asset('front/vendor/jquery/jquery.min.js')}}"></script>

    <script src="{{secure_asset('front/js/app/ToggleTema.js')}}"></script>
    <script>
        const intervalToken = setInterval(() => {
            if (!document.cookie.includes('XSRF-TOKEN')) {
                clearInterval(intervalToken);
                location.reload();
            }
        }, 1000);
    </script>
</head>

<body class="d-flex" style="min-height: 100vh;">
    <div
        class="contenedor-imagen position-relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-900 via-slate-800">
        <div class="position-absolute" style="inset: calc(.25rem * 0);">
            <div class="img-wrapper position-absolute">
                <img data-imagen="cliente" class="bg-img active"
                    src="{{ secure_asset('../public/front/images/app/fondo_login_cliente.png') }}"
                    alt="Modern workspace">

                <img data-imagen="soporte" class="bg-img"
                    src="{{ secure_asset('../public/front/images/app/fondo_login_soporte.png') }}"
                    alt="Modern workspace">
            </div>
        </div>
        <div class="d-flex flex-column justify-content-between p-5 position-relative text-white"
            style="z-index: 10;height: 100vh;">
            <div class="fade-in-down" style="opacity: 1; transform: none;animation-duration: .65s;">
                <div class="d-flex align-items-center gap-3 mb-5">
                    <img src="{{ secure_asset('../public/front/images/app/LogoRC_WBlanco.webp') }}"
                        style="height: 5rem;">
                </div>
            </div>
            <div class="d-flex flex-column justify-content-center fade-in-left"
                style="flex: 1;animation-duration: .5s;">
                <div style="opacity: 1; transform: none;">
                    <h2 class="fw-bold mb-5" style="font-size: 3rem;line-height: 1.25;">
                        Gestión de<br>
                        <span style="color: rgb(59 113 202)">Incidencias</span>
                    </h2>
                    <p class="mb-5"
                        style="line-height: calc(1.75 / 1.25);font-size: 1.25rem;color: rgb(202 213 226);max-width: 28rem;">
                        Plataforma para control, seguimiento y resolución de tickets de soporte técnico</p>
                </div>
            </div>
            <div class="text-sm text-slate-400" style="opacity: 1;">
                © 2026 Todos los derechos reservados.</div>
        </div>
    </div>

    <div class="contenedor-login d-flex justify-content-center overflow-hidden px-4 py-3" style="height: 100vh;">
        <div class="fade-in-up" style="max-width: 28rem;animation-duration: .45s;">
            <div class="logo-login">
                <img src="{{ secure_asset('../public/front/images/app/LogoRC_WNormal.webp') }}" alt="">
            </div>
            <div style="opacity: 1; transform: none;padding-bottom: 2rem;">
                <div class="header-login d-flex justify-content-between mb-3">
                    <div>
                        <h2 class="header-login-title fw-bold">Iniciar Sesión</h2>
                        <p class="header-login-subtitle mb-0">Accede al portal de incidencias</p>
                    </div>
                    <button class="rounded-6" id="toggleTema" data-theme="light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-moon w-5 h-5" aria-hidden="true">
                            <path
                                d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401">
                            </path>
                        </svg>
                    </button>
                    <script>
                        const toggle_tema = (tema) => {
                            let toggleTema = $('#toggleTema');
                            let tema_cambio = tema == 'light' ? 'dark' : 'light';
                            let icon = {
                                light: '<circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path>',
                                dark: '<path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"></path>'
                            }

                            toggleTema.attr('data-theme', tema_cambio).find('svg').html(icon[tema_cambio]);
                            localStorage.data_mdb_theme = tema;
                            $('html').attr('data-mdb-theme', tema);
                        };

                        $('#toggleTema').click(function () {
                            let tema = $(this).attr('data-theme');
                            toggle_tema(tema);
                        });

                        toggle_tema(localStorage.data_mdb_theme);
                    </script>
                </div>
                <div class="mb-4">
                    <div class="contenedor-modo">
                        <div class="indicador"></div>
                        <button class="botones-modo" data-tipo-modo="soporte">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="width: 16px; height: 16px;" aria-hidden="true">
                                <path
                                    d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z">
                                </path>
                                <path d="M21 16v2a4 4 0 0 1-4 4h-5"></path>
                            </svg>
                            <span>Soporte</span>
                        </button>
                        <button class="botones-modo" data-tipo-modo="cliente">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="width: 16px; height: 16px;" aria-hidden="true">
                                <path d="M10 12h4"></path>
                                <path d="M10 8h4"></path>
                                <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                                <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2">
                                </path>
                                <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                            </svg>
                            <span>Cliente</span>
                        </button>
                    </div>
                    <div class="informacion-modo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                        </svg>
                        <div>
                            <p data-modo="titulo">Portal de Cliente</p>
                            <p data-modo="descripcion">Portal para empresas y usuarios que reportan incidencias</p>
                        </div>
                    </div>
                </div>
                <form id="form-login" class="form-login">
                    <div form-alerta class="alert alert-danger py-2 custom-alert" role="alert" style="display: none;">
                        <i class="fas fa-circle-info" style="font-size: 0.9rem"></i>
                        <span class="ms-2"></span>
                    </div>
                    <div>
                        <label class="form-label-usuario d-flex justify-content-between">
                            <span data-text></span>
                            <span class="text-warning pt-1" data-error-message="usuario"
                                style="font-size: 0.7rem;display: none;">
                                <i class="fas fa-circle-info" style="font-size: 0.75rem"></i>
                                <span></span>
                            </span>
                        </label>
                        <div class="position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="form-icon form-icon-usuario" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            </svg>
                            <input class="form-control form-control-lg" type="text" name="usuario" id="usuario">

                        </div>
                    </div>
                    <div>
                        <label class="form-label-password d-flex justify-content-between">Contraseña
                            <span class="text-warning pt-1" data-error-message="password"
                                style="font-size: 0.7rem;display: none;">
                                <i class="fas fa-circle-info" style="font-size: 0.75rem"></i>
                                <span></span>
                            </span>
                        </label>
                        <div class="position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="form-icon" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input class="form-control form-control-lg" type="password" placeholder="••••••••"
                                name="password" id="password">

                            <button type="button" class="button-password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path
                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                    </path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button class="btn btn-lg btn-primary fw-bold mt-2 py-3 w-100" type="submit" tabindex="0"
                        style="transform: none;border-radius: calc(.625rem + 4px);" id="btn-ingresar">Iniciar
                        Sesión</button>
                </form>
                <div class="informacion-title-acceso">
                    <div class="position-absolute d-flex align-items-center" style="inset: calc(.25rem * 0);">
                        <div class="w-100"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center"><span>Información de acceso</span>
                    </div>
                </div>
                <div class="informacion-acceso">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path
                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                        </path>
                    </svg>
                    <p data-acceso></p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{secure_asset('front/vendor/mdboostrap/js/mdb.umd.min7.2.0.js')}}"></script>
    <script src="{{secure_asset('front/vendor/sweetalert/sweetalert2@11.js')}}"></script>
    <script src="{{secure_asset('front/js/app/AlertMananger.js')}}"></script>
    <script>
        const __url = "{{secure_url('')}}";
        const __token = "{{ csrf_token() }}";
    </script>

    <script src="{{secure_asset('front/js/app/login/login.js')}}"></script>
</body>

</html>