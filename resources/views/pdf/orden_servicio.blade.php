<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
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
            width: 100%;
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
    </style>
</head>

<body>
    <table>
        <tr>
            <td class="w-50">
                <div>
                    <img src="{{public_path() . '/front/images/app/logo_pdf.png'}}" alt="Logo" class="logo">
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
                    <p class="mb-4 fs-1p3rem">N° : {{$cod_orden}}</p>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="mb-2">
                    <h5>Tecnico(s) :</h5>
                    <ul>
                        <li>Jeremy Patrick Cauper Silvano</li>
                    </ul>
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
            <td colspan="4"></td>
            <td class="w-60px fw-bold">Sucursal :</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td class="w-80px fw-bold">Dirección :</td>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td class="w-80px fw-bold">Contacto :</td>
            <td colspan="2"></td>
            <td class="w-60px fw-bold">Telefono :</td>
            <td></td>
            <td class="w-60px fw-bold">Correo :</td>
            <td colspan="2"></td>
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
                <span class="fw-bold">Clasificacion del Error :</span> L-PRE_01 PRESENCIAL
            </td>
        </tr>
        <tr>
            <td colspan="4" class="w-35px">
                <span class="fw-bold">Observaciones</span>
                <div class="card h-08rem">
                    <!-- observaciones -->
                </div>
            </td>
            <td colspan="4" class="w-35px">
                <span class="fw-bold">Recomendaciones</span>
                <div class="card h-08rem">
                    <!-- recomendaciones -->
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <span class="fw-bold">Hora Inicio :</span> 10:27:02
                <span class="fw-bold"> - Hora Fin :</span> 12:49:26
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <div class="card bg-primary mt-1">
                    <h5>MATERIALES UTILIZADOS</h5>
                </div>
            </td>
        </tr>
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
                <!-- 8 tr se pueden añadir -->
                <table class="tb-material">
                    <tr>
                        <td>1</td>
                        <td>Protector de Manguera Data (05 Mtrs)</td>
                        <td>1.00</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="t-center">
                <div class="content-firmas">
                    <img class="firmas" src="{{public_path() . '/front/images/firms/fd_jcauper.png'}}">
                </div>
                <h6 class="mb-2 mt-2">Firma Tecnico</h6>
                <h5 class="mb-2">RICARDO CALDERON INGENIEROS</h5>
                <p class="mb-2">Jeremy Patrick Cauper Silvano</p>
            </td>
            <td style="width: 120;"></td>
            <td class="t-center">
                <div class="content-firmas">
                    <img class="firmas">
                     <!-- src="{{public_path() . '/front/images/client/fdc_61505130.png'}}"> -->
                </div>
                <h6 class="mb-2 mt-2">Firma Cliente</h6>
                <h5 class="mb-2">COESTI S.A.</h5>
                <p class="mb-2">Admin</p>
            </td>
        </tr>
    </table>
</body>

</html>