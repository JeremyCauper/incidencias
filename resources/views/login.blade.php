<!DOCTYPE html>
<html lang="es" class="h-100" data-mdb-theme="light">

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
    <link rel="stylesheet" href="{{secure_asset('front/css/app/auth.css')}}">

    <script src="{{secure_asset('front/vendor/jquery/jquery.min.js')}}"></script>

    <link rel="stylesheet" href="{{secure_asset('front/css/tema.css')}}">
    <script src="{{secure_asset('front/js/app/ToggleTema.js')}}"></script>
    <script>
        const intervalToken = setInterval(() => {
            if (!document.cookie.includes('XSRF-TOKEN')) {
                clearInterval(intervalToken);
                location.reload();
            }
        }, 1000);
    </script>
    <style>
        :root {
            --color-blue-50: oklch(.97 .014 254.604);
            --color-blue-100: oklch(.932 .032 255.585);
            --color-blue-400: oklch(.707 .165 254.624);
            --color-blue-500: oklch(.623 .214 259.815);
            --color-blue-600: oklch(.546 .245 262.881);
            --color-blue-700: oklch(.488 .243 264.376);
            --color-blue-900: oklch(.379 .146 265.522);
            --color-indigo-400: oklch(.673 .182 276.935);
            --color-indigo-500: oklch(.585 .233 277.117);
            --color-indigo-600: oklch(.511 .262 276.966);
            --color-indigo-700: oklch(.457 .24 277.023);
            --color-purple-400: oklch(.714 .203 305.504);
            --color-slate-50: oklch(.984 .003 247.858);
            --color-slate-100: oklch(.968 .007 247.896);
            --color-slate-200: oklch(.929 .013 255.508);
            --color-slate-300: oklch(.869 .022 252.894);
            --color-slate-400: oklch(.704 .04 256.788);
            --color-slate-500: oklch(.554 .046 257.417);
            --color-slate-600: oklch(.446 .043 257.281);
            --color-slate-700: oklch(.372 .044 257.287);
            --color-slate-800: oklch(.279 .041 260.031);
            --color-slate-900: oklch(.208 .042 265.755);
            --color-gray-100: oklch(.967 .003 264.542);
        }

        .to-slate-900 {
            --tw-gradient-to: oklch(.208 .042 265.755);
            --tw-gradient-stops: to bottom right in oklab, oklch(.208 .042 265.755) 0%, oklch(.279 .041 260.031) 50%, oklch(.208 .042 265.755) 100%;
        }

        .via-slate-800 {
            --tw-gradient-via: oklch(.279 .041 260.031);
            --tw-gradient-via-stops: to bottom right in oklab, oklch(.208 .042 265.755) 0%, oklch(.279 .041 260.031) 50%, oklch(.208 .042 265.755) 100%;
            --tw-gradient-stops: to bottom right in oklab, oklch(.208 .042 265.755) 0%, oklch(.279 .041 260.031) 50%, oklch(.208 .042 265.755) 100%;
        }

        .from-slate-900 {
            --tw-gradient-from: oklch(.208 .042 265.755);
            --tw-gradient-stops: to bottom right in oklab, oklch(.208 .042 265.755) 0%, oklch(.279 .041 260.031) 50%, oklch(.208 .042 265.755) 100%;
        }

        .bg-gradient-to-br {
            --tw-gradient-position: to bottom right in oklab;
            background-image: linear-gradient(to bottom right in oklab, oklch(.208 .042 265.755) 0%, oklch(.279 .041 260.031) 50%, oklch(.208 .042 265.755) 100%);
        }

        .contenedor-imagen {
            width: 60%;
        }

        .img-wrapper {
            overflow: hidden;
            inset: 0;
        }

        .bg-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: blur(2px);
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .bg-img.active {
            opacity: 0.2;
        }


        .contenedor-login {
            width: 40%;
        }

        @media (max-width: 1370px) {

            .contenedor-imagen,
            .contenedor-login {
                width: 50%;
            }
        }

        @media (max-width: 1025px) {
            .contenedor-imagen {
                display: none;
            }

            .contenedor-login {
                width: 100%;
            }
        }

        .botones-modo {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            padding: .75rem;
            border: 0;
            border-radius: inherit;
            color: rgb(29 41 61 / 60%);
            background-color: transparent;
            font-size: .75rem;
            flex: 1 0 0%;
            transition: all .35s ease-in-out;
        }

        .botones-modo.active {
            color: rgb(29 41 61);
            background-color: white;
            box-shadow: 0px 3px 9px -7px rgb(0 0 0 / 80%);
            transition: all .35s ease-in-out;
        }

        .informacion-modo {
            display: flex;
            gap: 1rem;
            background-color: rgb(239 246 255);
            opacity: 1;
            transform: none;
            border-radius: .65rem;
            padding: .75rem 1.25rem;
            border: 1px solid rgb(134 187 255 / 20%);
            margin-top: calc(.25rem * 4);
            margin-bottom: calc(.25rem * 8);
        }

        .informacion-modo svg {
            color: rgb(21 93 252);
            width: calc(.25rem * 5);
            height: calc(.25rem * 5);
        }

        .informacion-modo div p {
            margin: 0;
        }

        .informacion-modo div p:first-child {
            font-weight: bold;
            color: rgb(29 41 61);
            font-size: .9rem;
        }

        .informacion-modo div p:last-child {
            color: rgb(69 85 108);
            font-size: .75rem;
        }

        .form-login div {
            margin-bottom: calc(.25rem * 5);
        }

        .form-login div label {
            color: rgb(49 65 88);
            font-weight: bold;
            font-size: .875rem;
            margin-bottom: calc(.25rem * 2);
        }

        .form-login div div .form-control {
            padding-left: calc(.25rem * 12);
            border-radius: calc(.625rem + 4px);
            border-width: 2px;
            border-color: rgb(144 161 185);
        }

        .form-login div div .form-control::placeholder {
            color: rgb(144 161 185);
            opacity: 1;
        }

        .form-login div div .form-control:focus {
            border-color: #3b71ca;
        }

        .form-login div div .form-icon {
            position: absolute;
            left: calc(.25rem * 4);
            top: 50%;
            transform: translateY(-50%);
            color: rgb(144 161 185);
            width: calc(.25rem * 5);
            height: calc(.25rem * 5);
        }

        .form-login div div .button-password {
            position: absolute;
            right: calc(.25rem * 4);
            top: 50%;
            transform: translateY(-50%);
            background-color: transparent;
            color: rgb(144 161 185);
            width: calc(.25rem * 5);
            height: calc(.25rem * 5);
            border: 0;
            padding: 0;
            line-height: 1;
        }

        .form-login div div .button-password svg {
            width: calc(.25rem * 5);
            height: calc(.25rem * 5);
        }

        .informacion-title-acceso {
            margin-block: calc(.25rem * 8);
            position: relative;
        }

        .informacion-title-acceso div:last-child span {
            color: rgb(98 116 142);
            padding-inline: calc(.25rem * 4);
            background-color: rgb(255 255 255);
            font-size: .875rem;
            z-index: 1;
        }

        .informacion-acceso {
            text-align: center;
            padding: calc(.25rem * 4);
            background-color: rgb(252 249 250);
            border-radius: calc(.625rem + 4px);
            border: 1px solid rgb(226 232 240);
        }

        .informacion-acceso svg {
            color: rgb(144 161 185);
            margin-bottom: calc(.25rem * 2);
        }

        .informacion-acceso p {
            margin: 0;
            color: rgb(69 85 108);
            font-size: .75rem;
            line-height: calc(1 / .75);
        }
    </style>
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
            style="z-index: 10;height: 100%;">
            <div style="opacity: 1; transform: none;">
                <div class="d-flex align-items-center gap-3 mb-5">
                    <img src="{{ secure_asset('../public/front/images/app/LogoRC_WBlanco.webp') }}"
                        style="height: 5rem;">
                </div>
            </div>
            <div class="d-flex flex-column justify-content-center" style="flex: 1;">
                <div style="opacity: 1; transform: none;">
                    <h2 class="fw-bold mb-5" style="font-size: 3rem;line-height: 1.25;">
                        Gestión de<br>
                        Incidencias
                    </h2>
                    <p class="mb-5"
                        style="line-height: calc(1.75 / 1.25);font-size: 1.25rem;color: oklch(.869 .022 252.894);max-width: 28rem;">
                        Plataforma para control, seguimiento y resolución de tickets de soporte técnico</p>
                </div>
            </div>
            <div class="text-sm text-slate-400" style="opacity: 1;">
                © 2026 Todos los derechos reservados.</div>
        </div>
    </div>

    <div class="contenedor-login d-flex justify-content-center p-5 bg-white">
        <div style="max-width: 28rem;width: 100%;">
            <div class="d-flex align-items-center gap-3 mb-5 d-none">
                <img src="{{ secure_asset('../public/front/images/app/LogoRC_TBlanco.webp') }}" alt="">
            </div>
            <div style="opacity: 1; transform: none;">
                <div class="mb-4">
                    <h2 class="fw-bold mb-2" style="font-size: 1.875rem;color: oklch(.279 .041 260.031);">Iniciar Sesión
                    </h2>
                    <p class="mb-0" style="color: oklch(.446 .043 257.281);">Accede al portal de incidencias
                    </p>
                </div>
                <div class="mb-4">
                    <div class="rounded p-1 d-flex gap-2" style="background-color: oklch(.968 .007 247.896);">
                        <button class="botones-modo" data-modo="soporte">
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
                        <button class="botones-modo" data-modo="cliente">
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
                            class="lucide lucide-building2 lucide-building-2 w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0"
                            aria-hidden="true">
                            <path d="M10 12h4"></path>
                            <path d="M10 8h4"></path>
                            <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                            <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2">
                            </path>
                            <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>
                        </svg>
                        <div>
                            <p data-modo="titulo">Portal de Cliente</p>
                            <p data-modo="descripcion">Portal para empresas y usuarios que reportan incidencias</p>
                        </div>
                    </div>
                </div>
                <form id="form-login" class="form-login">
                    <div>
                        <label>Correo Electrónico</label>
                        <div class="position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="form-icon" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            </svg>
                            <input class="form-control form-control-lg" type="email" placeholder="cliente@empresa.com"
                                required="">
                        </div>
                    </div>
                    <div>
                        <label>Contraseña</label>
                        <div class="position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="form-icon" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input class="form-control form-control-lg" type="password" placeholder="••••••••"
                                required="">
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
                        style="transform: none;border-radius: calc(.625rem + 4px);">Iniciar Sesión</button>
                </form>
                <div class="informacion-title-acceso">
                    <div class="position-absolute d-flex align-items-center" style="inset: calc(.25rem * 0);">
                        <div class="w-100" style="border: 1px solid rgb(226 232 240);"></div>
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
                    <p data-acceso>El acceso al portal de soporte está restringido al personal autorizado. Contacta al
                        administrador
                        del sistema para obtener credenciales.</p>
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
    <script>
        const configuracion = {
            soporte: {
                modo: {
                    titulo: "Portal de Soporte Técnico",
                    descripcion: "Acceso exclusivo para el equipo interno de soporte RC",
                },
                acceso: "El acceso al portal de soporte está restringido al personal autorizado. Contacta al administrador del sistema para obtener credenciales."
            },
            cliente: {
                modo: {
                    titulo: "Portal de Cliente",
                    descripcion: "Portal para empresas y usuarios que reportan incidencias",
                },
                acceso: "Para obtener acceso al portal de cliente, contacta con el equipo de atención al cliente."
            }
        }

        const botones_modo = $('.botones-modo');
        botones_modo.click(function () {
            botones_modo.removeClass('active');
            $(this).addClass('active');
            const modo = $(this).data('modo');
            const config = configuracion[modo];
            $('[data-imagen').removeClass('active');
            $(`[data-imagen="${modo}"]`).addClass('active');

            $('[data-modo="titulo"]').text(config.modo.titulo);
            $('[data-modo="descripcion"]').text(config.modo.descripcion);
            $('[data-acceso]').text(config.acceso);
        });
    </script>
</body>

</html>