@extends('layout.app')
@section('title', 'Panel de Control')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Incidencias Registradas</h6>
                        <h4 class="rate-percentage"><b>17944</b></h4>
                        <div class="d-flex justify-content-between">
                            <p class="text-muted text-danger mb-0"><i class="mdi mdi-menu-down"></i><span>3% Todos los dias</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Incidencias Asignadas</h6>
                        <h4 class="rate-percentage"><b>6</b></h4>
                        <div class="d-flex justify-content-between">
                            <p class="text-muted text-success mb-0"><i class="mdi mdi-menu-up"></i><span>34% Todos los dias</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Incidencias En Proceso</h6>
                        <h4 class="rate-percentage"><b>5</b></h4>
                        <div class="d-flex justify-content-between">
                            <p class="text-muted text-danger mb-0"><i class="mdi mdi-menu-down"></i><span>12% Todos los dias</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Incidencias Resueltas</h6>
                        <h4 class="rate-percentage"><b>16708</b></h4>
                        <div class="d-flex justify-content-between">
                            <p class="text-muted text-success mb-0"><i class="mdi mdi-menu-down"></i><span>34% Todos los dias</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Clientes Registrados</h6>
                        <h4 class="rate-percentage"><b>222</b></h4>
                        <div class="d-flex justify-content-between">
                            <p class="text-muted text-danger mb-0"><i class="mdi mdi-menu-down"></i><span>34% Todos los dias</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Incidencias Registradas</h4>
            <div>
                <button class="btn btn-primary btn-sm" onclick="$('#modal_frm_usuarios').modal('show')">
                    <i class="mdi mdi-book-plus me-2"></i>
                    Nueva Incidencia
                </button>
                <button class="btn btn-primary btn-sm">
                    <i class="mdi mdi-autorenew"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Empresa</th>
                                <th>Sucursal</th>
                                <th>Contacto</th>
                                <th>Registrada</th>
                                <th>Tecnico</th>
                                <th>Estacion</th>
                                <th>Atencion</th>
                                <th>Informe</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection