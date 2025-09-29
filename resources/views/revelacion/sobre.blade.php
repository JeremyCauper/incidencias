<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ secure_asset('front/revelacion/fonts/fonts_local.css') }}">
  <link rel="stylesheet" href="{{ secure_asset('front/revelacion/sobre/style.css') }}">
  <link rel="shortcut icon" href="{{ secure_asset('front/revelacion/img/teddy-bear.ico') }}" type="image/x-icon">
  <title>Sobre</title>
</head>

<body>
  <div class="contenedor">
    <div class="sobre">
      <img src="{{ secure_asset('front/revelacion/img/sobre/lazo.png') }}" alt="" class="lazo">
      <div class="sobre-title">
        <h1>Niño</h1>
        <h1>o</h1>
        <h1>Niña</h1>
      </div>
      <div class="sobre-body">
        <div class="envelope-3d" id="btnIrCarta">
          <script>
            const url_base = "{{ url('/') }}";
            document.getElementById("btnIrCarta").addEventListener("click", () => {
              // Marcamos en localStorage que ya pasó por aquí
              localStorage.setItem("carta_acceso", "true");
              // Redirigimos a carta.html
              window.location.href = "{{ url('revelacion/carta-invitacion') }}";
            });
          </script>
          <div class="envelope-flap-top"></div>
          <div class="envelope-flap-bottom"></div>
          <div class="envelope-sello">
            <svg width="120" height="120" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
              <!-- Contorno derretido más pronunciado -->
              <path
                d="M25,85 Q5,65 35,55 Q10,40 45,25 Q35,10 65,18 Q85,8 95,32 Q115,42 98,65 Q115,85 82,95 Q92,115 60,105 Q40,115 28,95 Q5,105 25,85 Z"
                fill="#703705" opacity="0.95" />

              <!-- Base interna del sello -->
              <ellipse cx="60" cy="60" rx="46" ry="44" fill="#703705" />
              <ellipse cx="60" cy="60" rx="40" ry="38" fill="#703705" opacity="0.85" />
              <ellipse cx="60" cy="60" rx="34" ry="32" fill="#93531b" opacity="0.7" />

              <!-- Letras doradas -->
              <text x="50%" y="50%" text-anchor="middle" dominant-baseline="central" font-family="'Mea Culpa', cursive"
                font-size="28" fill="#e6c97b" stroke="#bfa23a" stroke-width="1.5" paint-order="stroke"
                style="letter-spacing: 2px;">
                M J
              </text>
            </svg>
          </div>
        </div>
        <h3>DALE CLICK PARA VER LA INVITACION</h3>
      </div>
    </div>
  </div>
</body>

</html>