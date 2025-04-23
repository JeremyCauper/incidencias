<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
    <style>
        @page {
            size: 80mm auto;
        }

        html,
        body {
            margin: 0px;
        }

        body {
            font-family: Arial, sans-serif;
            /* border: 1px solid #000; */
            width: calc(80mm - 18mm);
            padding: 2rem;
        }

        .t-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mt-2 {
            margin-top: .175rem !important;
        }

        .mb-2 {
            margin-bottom: .175rem !important;
        }

        .ticket-header {
            text-align: center;
        }

        .ticket-header .logo {
            width: 45mm;
            margin-bottom: 0px;
        }

        .ticket-content {
            margin-top: 6px 3px 3px 3px;
        }

        .ticket-content * {
            margin: 0px;
            padding: 0px;
            font-size: .53rem;
        }

        .ticket-content .tittle {
            font-weight: bold;
            font-size: .6rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .tb-material tr td:nth-child(1) {
            text-align: center;
            width: 40px;
        }

        .tb-material tr td:nth-child(3) {
            text-align: center;
            width: 60px;
        }

        .content-firmas {
            position: relative;
            width: 100px;
            height: 100px;
            margin: auto;
            margin-bottom: 5px;
        }

        .content-firmas::after {
            content: "";
            position: absolute;
            width: 75%;
            top: 65%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid black;
        }

        .content-firmas .firmas {
            margin: 0;
            padding: 0;
            width: inherit;
            height: inherit;
            /* border: 1px solid #466497; */
        }

        .hidden {
            visibility: hidden;
        }

        .linea {
            border-top: .1rem solid black;
            width: 100%;
            height: 1px;
            margin: 3px 0px;
            padding: 0px;
        }
    </style>
</head>

<body>
    <div class="ticket-header">
        <img src="{{public_path() . '/front/images/app/logo_tittle_rc_white.png'}}?v={{ time() }}" class="logo">
        <p style="font-weight: bold; font-size: 11px; margin: 0px">ORDEN DE SERVICIO</p>
        <p style="font-size: 11px; margin: 0px">{{$cod_ordens}}</p>
        <p style="font-size: 9px; margin: 9px 0px 0px 100px;"><span style="font-weight: bold;">FECHA :</span> {{$fecha}}
        </p>
    </div>
    <div class="linea"></div>
    <div class="ticket-content">
        <p class="tittle">CLIENTE</p>
        <p>{{$empresa}}</p>

        <p class="tittle mt-2">DIRECCION</p>
        <p>{{$sucursal['direccion']}}</p>

        <p class="tittle mt-2">CONTACTO</p>
        <p>{{$contacto}}</p>

        <p class="tittle mt-2">TELEFONO</p>
        <p>{{$telefono}}</p>

        <p class="tittle mt-2">CORREO</p>
        <p>{{$correo}}</p>
    </div>
    <div class="linea"></div>
    <div class="ticket-content">
        <p class="tittle">TECNICO(s)</p>
        <div>
            {{ implode(", ", $asignados) }}
        </div>
    </div>
    <div class="linea"></div>
    <div class="ticket-content">
        <p class="tittle mb-2">DETALLE</p>

        <p>
            <span class="fw-bold">Tipo Soporte: </span>
            {{$tipoSoporte}}
        </p>

        <p>
            <table>
                <tr>
                    <td style="width: 50%;">
                        <span class="fw-bold">Hora Inicio: </span>
                        {{$horaIni}}
                    </td>
                    <td style="width: 50%; padding: 0px 0px 0px 39px;">
                        <span class="fw-bold">Hora Fin: </span>
                        {{$horaFin}}
                    </td>
                </tr>
            </table>
        </p>

        <p>
            <span class="fw-bold">Informe: </span>
            {{$problema}}
        </p>
    </div>
    <div class="linea"></div>
    <div class="ticket-content">
        <p class="tittle mb-2">TRABAJO REALIZADO</p>

        <p class="fw-bold">OBSERVACIONES:</p>
        <p>{{$observacion}}</p>

        <p class="fw-bold mt-2">RECOMENDACIONES:</p>
        <p>{{$recomendacion}}</p>
    </div>
    <div class="linea"></div>
    <div class="ticket-content">
        <p class="tittle mb-2">MATERIALES UTILIZADOS</p>

        @if ($eCodAviso)
        <p class="mb-2">
            <span class="fw-bold">Codigo Aviso :</span> {{$codigo_aviso}}
        </p>
        @endif

        <div style="border-radius: .4rem; border: 1px solid black; padding-top: 3px; padding-bottom: 3px;">
            <table class="tb-material">
                <tr>
                    <td>#</td>
                    <td>PRODUCTO / MATERIAL</td>
                    <td>CANT.</td>
                </tr>
            </table>
        </div>
        <table class="tb-material">
            <!-- 8 tr se pueden añadir -->
            @if (count($materiales))
                @foreach ($materiales as $mat)
                    <tr>
                        <td>{{$mat['i']}}</td>
                        <td>{{$mat['p']}}</td>
                        <td>{{$mat['c']}}</td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
    <div class="linea"></div>
    <div class="ticket-content">
        <table>
            <tr>
                <td class="t-center">
                    <div class="content-firmas">
                        <img class="firmas {{$firmaA ? '' : 'hidden'}}" src="{{$firmaA}}?v={{ time() }}">
                    </div>
                    <h4>Firma Tecnico</h4>
                </td>
                <td class="t-center">
                    <div class="content-firmas">
                        <img class="firmas {{$firmaC ? '' : 'hidden'}}" src="{{$firmaC}}?v={{ time() }}">
                    </div>
                    <h4>Firma Cliente</h4>
                </td>
            </tr>
        </table>
    </div>
    <div class="linea"></div>
    <div class="ticket-content t-center">
        <p>Documento Impreso : {{now()->format('Y-m-d H:i:s')}}</p>
    </div>
</body>

</html>