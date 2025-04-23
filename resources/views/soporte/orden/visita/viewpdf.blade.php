<!DOCTYPE html>
<html>

<head>
    <title> {{ $titulo }} </title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            color: #616161;
            font-size: .85rem;
        }

        html {
            margin: 2rem 2.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            /* border: 1px solid black; */
            padding: .25rem;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .card {
            padding: .6rem;
            border-radius: .4rem;
            border: 1px solid #466497;
        }

        .bg-primary,
        .bg-primary * {
            background: #466497;
            color: #ffffff;
        }

        .t-center {
            text-align: center;
        }

        .t-white {
            color: #ffffff;
        }

        .w-50 {
            width: 50%;
        }

        .w-35px {
            width: 35px;
        }

        .w-80px {
            width: 80px;
        }

        .w-60px {
            width: 60px;
        }

        .h-08rem {
            height: 8rem;
        }

        .fs-1p6rem {
            font-size: 1.6rem;
        }

        .fs-1p3rem {
            font-size: 1.3rem;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mt-1 {
            margin-top: .25rem;
        }

        .mt-2 {
            margin-top: .5rem;
        }

        .mb-1 {
            margin-bottom: .2rem;
        }

        .mb-2 {
            margin-bottom: .5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-5 {
            margin-bottom: 1.25rem;
        }

        .tb-material tr td:nth-child(1) {
            text-align: center;
            width: 50px;
        }

        .tb-material tr td:nth-child(3) {
            text-align: center;
            width: 100px;
        }

        .content-firmas {
            position: relative;
            width: 200px;
            height: 120px;
            margin: auto;
        }

        .content-firmas-line::after {
            content: "";
            position: absolute;
            width: 75%;
            top: 65%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 0.01rem solid black;
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

        .div-checked {
            height: 12px;
            width: 12px;
            max-width: 12px;
            border: 1px solid black;
            border-radius: 2px;
            padding: 3px 2px 1px 2px;
        }

        .fa-check {
            height: 4px;
            width: 8px;
            margin: 0;
            padding: 0;
            border-bottom: 2px solid black;
            border-left:  2px solid black;
            transform: rotate(-45deg);
        }
        
        .icon-child {
            padding-left: 20px;
        }

        .icon-child::after {
            height: 0;
            width: 0;
            content: "";
            position: absolute;
            top: 3px;
            left: 5px;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-left: 10px solid #616161;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td class="w-50">
                <div>
                    <img src="{{public_path() . '/front/images/app/LogoRC.png'}}?v={{ time() }}" alt="Logo" width="60">
                    <img src="{{public_path() . '/front/images/app/tittle_login.png'}}?v={{ time() }}" alt="Logo" width="210">
                </div>
                <div class="card">
                    <p><b>Direccion :</b> Av. Augusto B. Leguia 307 - Coop. Policial Lima - Lima - SMP</p>
                    <p><b>Telefono :</b> 711-0747 / 711-0746</p>
                    <p><b>Correo :</b> ventas@rcingenieros.com / www.rcingenieros.com</p>
                </div>
            </td>
            <td>
                <div class="card t-center">
                    <h3 class="mb-5 fs-1p6rem">ORDEN DE VISITA</h3>
                    <p class="mb-5 fs-1p3rem">ELECTRÓNICA</p>
                    <p class="mb-4 fs-1p3rem">N° : {{ $cod_ordenv }}</p>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table>
                    <tbody>
                        <tr>
                            <td><h5>Tecnico(s) :</h5></td>
                            <td style="text-align: right;"><h5>Fecha de Servicio: {{ $fecha_visita }}</h5></td>
                        </tr>
                        <tr>
                            <td colspan="2">{{ implode(", ", $asignados) }}</td>
                        </tr>
                        <tr>
                            <td><h5>Hora Inicio: {{ $horaIni }}</h5></td>
                            <td><h5>Hora Fin: {{ $horaFin }}</h5></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="8">
                <div class="card bg-primary">
                    <h5>DATOS DEL CLIENTE</h5>
                </div>
            </td>
        </tr>
        <tr>
            <td class="w-80px fw-bold">Razón Social :</td>
            <td colspan="4">{{ $empresa }}</td>
            <td class="w-60px fw-bold">Sucursal :</td>
            <td colspan="2">{{ $sucursal['sucursal'] }}</td>
        </tr>
        <tr>
            <td class="w-80px fw-bold">Dirección :</td>
            <td colspan="7">{{ $sucursal['direccion'] }}</td>
        </tr>
        <tr>
            <td class="w-80px fw-bold">Contacto :</td>
            <td colspan="2">{{ $contacto }}</td>
            <td class="w-60px fw-bold">Telefono :</td>
            <td>{{ $telefono }}</td>
            <td class="w-60px fw-bold">Correo :</td>
            <td colspan="2">{{ $correo }}</td>
        </tr>
        <tr>
            <td colspan="8">
                <div class="card bg-primary mt-1">
                    <h5> REVISION DEL GABINETE</h5>
                </div>
            </td>
        </tr>
        @foreach ($ordenv_filas as $fila)
            @if ($fila->posicion == 11)
            <tr>
                <td colspan="8">
                    <div class="card bg-primary mt-1">
                        <h5> REVISION DEL SERVIDOR</h5>
                    </div>
                </td>
            </tr>
            @endif
            <tr>
                <td colspan="3">
                    <span class="fw-bold {{ $fila->config->child ? "icon-child" : "" }}" style="position: relative;">
                        {{ $fila->config->text }}
                    </span>
                </td>
                <td colspan="1">
                    <div class="div-checked">
                        @if ($fila->checked)
                            <div class="fa-check"></div>
                        @endif
                    </div>
                </td>
                <td colspan="4">
                    {{ $fila->descripcion }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="8">
                <div class="card bg-primary mt-1">
                    <h5> REVISION DEL POS, LECTORES, JACK TOOLS IMPRESORAS Y CONEXIONES</h5>
                </div>
            </td>
        </tr>
        @foreach ($ordenv_islas as $isla)
            <tr><td colspan="8" style="padding-top: 1.25rem;"></td></tr>
            <tr>
                <td colspan="2">
                    <span class="fw-bold">ISLA :</span> {{$isla->isla}}
                </td>
                <td colspan="2">
                    <span class="fw-bold">POS :</span> {{$isla->pos}}
                </td>
                <td colspan="4"></td>
            </tr>
            @foreach ($config_islas as $config)
            <tr>
                <td colspan="3">
                    <span class="fw-bold {{ $config->child ? "icon-child" : "" }}" style="position: relative;">{{ $config->text }}</span>
                </td>
                <td colspan="1">
                    <div class="div-checked">
                        @if ($isla->{$config->checked})
                            <div class="fa-check"></div>
                        @endif
                    </div>
                </td>
                <td colspan="4">
                    {{ $isla->{$config->descripcion} }}
                </td>
            </tr>
            @endforeach
        @endforeach
    </table>
</body>

</html>