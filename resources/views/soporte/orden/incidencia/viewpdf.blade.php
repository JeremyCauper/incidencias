<!DOCTYPE html>
<html>

<head>
    <title>{{ $titulo }}</title>
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
            padding: .4rem;
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
                    <h3 class="mb-5 fs-1p6rem">ORDEN DE SERVICIO</h3>
                    <p class="mb-5 fs-1p3rem">ELECTRÓNICA</p>
                    <p class="mb-4 fs-1p3rem">N° : {{$cod_ordens}}</p>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="mb-2">
                    <table>
                        <tbody>
                            <td>
                                <h5>Tecnico(s) :</h5>
                            </td>
                            <td style="text-align: right;">
                                <h5>Fecha Incidencia : {{$fecha}}</h5>
                            </td>
                        </tbody>
                    </table>
                    <div>
                        {{ implode(", ", $asignados) }}
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="8">
                <div class="card bg-primary mt-1">
                    <h5>DATOS DEL CLIENTE</h5>
                </div>
            </td>
        </tr>
        <tr>
            <td class="w-80px fw-bold">Razón Social :</td>
            <td colspan="4">{{$empresa}}</td>
            <td class="w-60px fw-bold">Sucursal :</td>
            <td colspan="2">{{$sucursal['sucursal']}}</td>
        </tr>
        <tr>
            <td class="w-80px fw-bold">Dirección :</td>
            <td colspan="7">{{$sucursal['direccion']}}</td>
        </tr>
        <tr>
            <td class="w-80px fw-bold">Contacto :</td>
            <td colspan="2">{{$contacto}}</td>
            <td class="w-60px fw-bold">Telefono :</td>
            <td>{{$telefono}}</td>
            <td class="w-60px fw-bold">Correo :</td>
            <td colspan="2">{{$correo}}</td>
        </tr>
        <tr>
            <td colspan="8">
                <div class="card bg-primary mt-1">
                    <h5> TRABAJO REALIZADO</h5>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <span class="fw-bold">Clasificacion del Error :</span> {{$problema}}
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <span class="fw-bold">Tipo Soporte :</span> {{$tipoSoporte}}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="w-35px">
                <span class="fw-bold">Observaciones</span>
                <div class="card h-08rem">
                    {{$observacion}}
                </div>
            </td>
            <td colspan="4" class="w-35px">
                <span class="fw-bold">Recomendaciones</span>
                <div class="card h-08rem">
                    {{$recomendacion}}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <span class="fw-bold">Hora Inicio :</span> {{$horaIni}}
                <span class="fw-bold"> - Hora Fin :</span> {{$horaFin}}
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <div class="card bg-primary mt-1">
                    <h5>MATERIALES UTILIZADOS</h5>
                </div>
            </td>
        </tr>
        
        @if ($eCodAviso)
        <tr>
            <td colspan="8">
                <span class="fw-bold">Codigo Aviso :</span> {{$codigo_aviso}}
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="8">
                <div style="border-radius: .4rem; border: 1px solid #466497;">
                    <table class="tb-material">
                        <tr>
                            <td>#</td>
                            <td>PRODUCTO / MATERIAL</td>
                            <td>CANTIDAD</td>
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
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="t-center">
                <div class="content-firmas {{$firmaA ? '' : 'content-firmas-line'}}">
                    <img class="firmas {{$firmaA ? '' : 'hidden'}}" src="{{$firmaA}}">
                </div>
                <h6 class="mb-2 mt-2">Firma Tecnico</h6>
                <h5 class="mb-2">RICARDO CALDERON INGENIEROS</h5>
                <p class="mb-2">{{$NomFirma}}</p>
            </td>
            <td style="width: 120;"></td>
            <td class="t-center">
                <div class="content-firmas {{$firmaC ? '' : 'content-firmas-line'}}">
                    <img class="firmas {{$firmaC ? '' : 'hidden'}}" src="{{$firmaC}}?v={{ time() }}">
                    <!-- src="{{public_path() . '/front/images/client/fdc_61505130.png'}}"> -->
                </div>
                <h6 class="mb-2 mt-2">Firma Cliente</h6>
                <h5 class="mb-2">{{$empresa}}</h5>
                <p class="mb-2">{{$contacOrden}}</p>
            </td>
        </tr>
    </table>
</body>

</html>