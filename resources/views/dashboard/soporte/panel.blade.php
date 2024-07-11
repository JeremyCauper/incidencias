@extends('layout.app')
@section('title', 'Panel de Control')

@section('content')

<div class="col-sm-12">
    <div class="row">
        <div class="col-sm-12">
            <div class="statistics-details d-flex align-items-center justify-content-between">
                <div class="">
                    <p class="statistics-title">
                        Incidencias Registradas
                    </p>
                    <h2 class="rate-percentage"><b>17944</b></h2>
                    <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>3% Todos los dias</span></p>
                </div>
                <div class="">
                    <p class="statistics-title">
                        Incidencias Asignadas
                    </p>
                    <h2 class="rate-percentage"><b>6</b></h2>
                    <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>34% Todos los dias</span></p>
                </div>
                <div class="">
                    <p class="statistics-title">
                        Incidencias En Proceso
                    </p>
                    <h2 class="rate-percentage"><b>5</b></h2>
                    <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>12% Todos los dias</span></p>
                </div>
                <div class="">
                    <p class="statistics-title">
                        Incidencias Resueltas
                    </p>
                    <h2 class="rate-percentage"><b>16708</b></h2>
                    <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>34% Todos los dias</span></p>
                </div>
                <div class="">
                    <p class="statistics-title">
                        Clientes Registrados
                    </p>
                    <h2 class="rate-percentage"><b>222</b></h2>
                    <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>34% Todos los dias</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection