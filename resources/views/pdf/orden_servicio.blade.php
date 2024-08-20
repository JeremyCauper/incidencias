<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
        }

        html {
            margin: .7rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: .4rem;
        }

        .logo {
            width: 350px;
            height: auto;
        }

        .card {
            padding: .8rem;
            border-radius: .4rem;
            border: 1px solid #007bff;
        }

        .text-center {
            text-align: center;
        }

        .mb-1 {
            margin-bottom: .25rem;
        }

        .mb-2 {
            margin-bottom: .5rem;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td>
                <div>
                    <img src="{{public_path() . '/front/images/app/logo_pdf.png'}}" alt="Logo" class="logo">
                </div>
                <div class="card text-center">
                    <h4>ORDEN DE SERVICIO</h4>
                </div>
            </td>
            <td>
                <div class="card text-center">
                    <h4 class="mb-2">ORDEN DE SERVICIO</h4>
                    <p class="mb-2">ELECTRÓNICA</p>
                    <p class="">N° : 2024-ST00525</p>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>