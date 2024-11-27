@extends('layout.app')
@section('title', 'Panel de Control')

@section('style')
<link rel="stylesheet" href="{{asset('front/css/app/mantenimiento/problemas/problemas.css')}}">
@endsection
@section('content')

<div class="row panel-view">
    <div class="col-12">
        <div class="row">
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-success" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="far fa-clock"></i> Incidencias Registradas
                        </h6>
                        <h4 class="subtitle-count"><b data-panel="totales">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-info" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-user-check"></i> Incidencias Asignadas
                        </h6>
                        <h4 class="subtitle-count"><b data-panel="tAsignadas">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-warning" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clipboard-check"></i> Incidencias Sin
                            Asignar</h6>
                        <h4 class="subtitle-count"><b data-panel="tSinAsignar">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-primary" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-business-time"></i> Incidencias En
                            Proceso</h6>
                        <h4 class="subtitle-count"><b data-panel="tEnProceso">0</b></h4>
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
                <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#modal_incidencias">
                    <i class="fas fa-book-medical"></i>
                    Nueva Incidencia
                </button>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_incidencia" class="table table-hover text-nowrap w-100">
                        <thead class="w-100">
                            <tr>
                                <th>Codigo</th>
                                <th>Empresa</th>
                                <th>Sucursal</th>
                                <th>Direccion</th>
                                <th>Registrado</th>
                                <th>Estacion</th>
                                <th>Atencion</th>
                                <th>Problema</th>
                                <th>Estado</th>
                                <th class="bg-white px-2 th-acciones">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{asset('front/js/app/mantenimiento/problemas/problemas.js')}}"></script>
@endsection