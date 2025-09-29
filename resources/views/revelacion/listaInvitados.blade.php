<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ secure_asset('front/revelacion/invitados/style.css') }}">
    <link rel="shortcut icon" href="{{ secure_asset('front/revelacion/img/teddy-bear.ico') }}" type="image/x-icon">
    <title>Listado de Invitados</title>
</head>

<body style="display: none;">
    <script>
        const CORRECT_CODE = '4321';
        let acceso_lista = localStorage.getItem("acceso_lista");
        const url_base = "{{ secure_url('/') }}";

        // Verificar si han pasado más de 30 minutos (1800000 ms)
        let tiempoTranscurrido = new Date().getTime() - parseInt(acceso_lista);
        if (tiempoTranscurrido > 7200000 || !acceso_lista) {
            localStorage.removeItem("acceso_lista");
            solicitarCodigo();
        } else {
            document.body.removeAttribute('style');
        }
        // Función para solicitar el código con 3 intentos
        function solicitarCodigo() {
            let intentos = 3;
            while (intentos > 0) {
                let input = prompt('Ingrese el código para ver la página:');
                if (input !== null && input === CORRECT_CODE) {
                    document.body.removeAttribute('style');
                    localStorage.setItem("acceso_lista", new Date().getTime());
                    return;
                } else {
                    intentos--;
                    alert('Código incorrecto. Te quedan ' + intentos + ' intentos.');
                }
            }
            alert('No tienes acceso a esta página.');
        }
    </script>
    <h1>Listado de Invitados</h1>

    <div class="link-container">
        <h3></h3>
        <button class="btn btn-info" id="btnAbrirVinculo" title="Abrir el enlace en una nueva pestaña">
            <img src="{{ secure_asset('front/revelacion/img/listar/link.svg') }}" alt="">
            Abrir Vinculo
        </button>
        <button class="btn btn-primary" id="copiar-link" title="Copiar enlace al portapapeles">
            <img src="{{ secure_asset('front/revelacion/img/listar/share.svg') }}" alt="">
        </button>
        <button class="btn btn-whatsapp" id="btnWhatsapp" title="Enviar enlace por WhatsApp">
            <img src="{{ secure_asset('front/revelacion/img/listar/whatsapp.svg') }}" alt="">
        </button>
    </div>

    <!-- Filtro de búsqueda -->
    <div class="acciones">
        <input type="text" id="buscar" placeholder="Buscar por nombre o apellido...">
        <button class="btn btn-primary" onclick="cargarInvitados()">Cargar Invitados</button>
    </div>

    <div class="table-conteiner">
        <!-- Tabla de invitados -->
        <table id="tabla-invitados">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Team</th>
                    <th>Fecha Confirmación</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se insertan las filas con JS -->
                <tr>
                    <td colspan="5">No se encontraron invitados.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="{{ secure_asset('front/revelacion/invitados/script.js') }}"></script>
</body>

</html>