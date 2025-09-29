<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ secure_asset('front/revelacion/temporizador/temporizador.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('front/revelacion/fonts/fonts_local.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('front/revelacion/carta/style.css') }}">
    <link rel="shortcut icon" href="{{ secure_asset('front/revelacion/img/teddy-bear.ico') }}" type="image/x-icon">
    <script src="{{ secure_asset('front/revelacion/carta/main.js') }}"></script>
    <title>Invitación</title>
</head>

<body>
    <script>
        // Verificamos si tiene acceso
        if (localStorage.getItem("carta_acceso") !== "true") {
            // No tiene permiso, redirigimos al sobre
            window.location.href = "{{ url('revelacion/sobre') }}";
        } else {
            // Tiene acceso, borramos el permiso para que no pueda recargar directo
            if (!localStorage.getItem("invitado")) {
                localStorage.removeItem("carta_acceso");
                console.log("Bienvenido a la carta 🎉");
            }
        }
    </script>
    <audio src="{{ secure_asset('front/revelacion/musica/indigo.mp3') }}" id="musica" autoplay loop></audio>
    <div class="contenedor">
        <div class="carta">
            <div class="borde-interno">
                <!-- Tu contenido aquí -->
                <div class="foto-papas">
                    <!-- Titulo curvo -->
                    <svg width="100%" height="1" viewBox="0 0 450 1" style="overflow:visible; line-height: 0;">
                        <defs>
                            <!-- Más curvo: el valor Y del punto de control (el segundo número de Q) es mayor -->
                            <path id="curva-titulo" d="M30,150 Q225,-190 420,150" />
                        </defs>
                        <text font-size="45" fill="#f0b988" font-family="'Great Vibes', cursive" text-anchor="middle">
                            <textPath xlink:href="#curva-titulo" startOffset="50%">
                                ¡Estás invitado a nuestra Revelación!
                            </textPath>
                        </text>
                    </svg>
                    <img src="{{ secure_asset('front/revelacion/img/carta/papas.jpeg') }}" alt="Papás">
                </div>

                <div class="concepto">
                    <p class="nombres">Mariella y Jhon</p>
                    <p>Un pedacito de nuestro corazón late más fuerte cada día. El amor ya lo conocemos, pero ahora
                        queremos compartir contigo el misterio más dulce: <br><br><b>¿será niña o niño?</b><br> ven y
                        acompáñanos a
                        descubrirlo.</p>
                </div>

                <div class="presentacion-bebe">
                    <img src="{{ secure_asset('front/revelacion/img/carta/bebe.jpeg') }}" alt="Bebé">
                    <p>Hola, soy el bebé y muy pronto sabrás si estaré en el Team Niño o Team Niña 💕</p>
                </div>

                <div class="team-container">
                    <p class="">¿Tú qué team eres?</p>
                    <div class="team-row">
                        <div class="team-box team-niño" type="button" data-team="0">
                            <h3>Team Niño 💙</h3>
                            <p>Traer pañitos húmedos (0% alcohol)</p>
                        </div>
                        <div class="team-box team-niña" type="button" data-team="1">
                            <h3>Team Niña 💖</h3>
                            <p>Traer pañales (Huggies de preferencia o cualquier)</p>
                        </div>
                    </div>
                </div>

                <div class="evento">
                    <div class="evento-mes">
                        <span>Octubre</span>
                    </div>
                    <div class="evento-dia">
                        <svg width="100%" height="1" viewBox="10 0 100 1" style="overflow:visible; line-height: 0;">
                            <defs>
                                <!-- Curva suave y centrada -->
                                <path id="curva-dia" d="M10,40 Q50,0 110,40" />
                            </defs>
                            <text font-size="12" fill="#f0b988" font-family="'Playwrite AU QLD'" text-anchor="middle">
                                <textPath xlink:href="#curva-dia" startOffset="50%">
                                    SÁBADO
                                </textPath>
                            </text>
                        </svg>
                        <p>18</p>
                    </div>
                    <div class="evento-hora">
                        <span>4:00 PM</span>
                    </div>
                </div>

                <div class="tiempo-container">
                    <p class="">¿Cuanto falta?</p>
                    <div class="tiempo-row">
                        <div class="flip-clock down day">
                            <div class="digital front"></div>
                            <div class="digital back"></div>
                        </div>
                        <div class="flip-clock down hour">
                            <div class="digital front"></div>
                            <div class="digital back"></div>
                        </div>
                        <div class="flip-clock down minute">
                            <div class="digital front"></div>
                            <div class="digital back"></div>
                        </div>
                        <div class="flip-clock down second">
                            <div class="digital front"></div>
                            <div class="digital back"></div>
                        </div>
                    </div>
                </div>

                <div class="acciones">
                    <a class="btn-ubicacion">
                        <span class="icono">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 2C7.59 2 4 5.58 4 10.01c0 4.42 6.16 10.97 7.13 11.89a1 1 0 0 0 1.37 0C13.84 20.98 20 14.43 20 10.01 20 5.58 16.41 2 12 2zm0 17.54C10.13 17.09 6 12.7 6 10.01 6 6.69 8.69 4 12 4s6 2.69 6 6.01c0 2.69-4.13 7.08-6 9.53z"
                                    fill="#f0b988" />
                                <circle cx="12" cy="10" r="2.5" fill="#f0b988" />
                            </svg>
                        </span>
                        <span class="texto">
                            <span>Ubicación</span>
                            <small>Ver mapa</small>
                        </span>
                    </a>
                    <a class="btn-asistencia" id="openModalBtn">
                        <span class="icono">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 11.5L10 15.5L16 7.5" stroke="#f0b988" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="texto">
                            <span>Confirmar</span>
                            <small>Asistencia</small>
                        </span>
                    </a>
                    <h3>Te Esperamos</h3>
                </div>
            </div>
        </div>
        <img src="{{secure_asset('front/revelacion/img/carta/flores.png')}}" alt="" class="flores-carta">
        <img src="{{secure_asset('front/revelacion/img/carta/osito.png')}}" alt="" class="osito-carta">
    </div>

    <!-- Modal Confirmacion -->
    <div class="modal" id="myModal">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label for="nombres">Nombres</label>
                    <input type="text" id="nombres">
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" id="apellidos">
                </div>
                <div class="buttons">
                    <button class="btn-close" id="closeBtn">Cerrar</button>
                    <button class="btn-confirm" id="confirmBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Alerta -->
    <div class="modal alerta" id="myModalAlerta">
        <div class="modal-content">
            <div class="modal-body">
                <div id="mensajeAlerta" class="modal-mensaje"></div>
                <div class="buttons">
                    <button class="btn-close" id="closeBtnAlerta">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ secure_asset('front/revelacion/temporizador/temporizador.js') }}"></script>
    <script src="{{ secure_asset('front/revelacion/carta/script.js') }}"></script>
</body>

</html>