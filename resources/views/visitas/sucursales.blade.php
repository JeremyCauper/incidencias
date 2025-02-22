@extends('layout.app')
@section('title', 'Visitas')

@section('style')
<script type="text/javascript" src="{{asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('front/vendor/daterangepicker/daterangepicker.css')}}">
<!-- <link rel="stylesheet" href="{{asset('front/css/app/incidencias/registradas.css')}}"> -->
<style>
    #tb_visitas thead tr * {
        font-size: 12px;
        /* padding-top: ; */
    }
</style>
@endsection
@section('content')

<div class="row panel-view">
    <div class="col-12">
        <div class="row">
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-danger" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-xmark"></i></i> Visitas Sin Programar
                        </h6>
                        <h4 class="subtitle-count"><b data-panel="tAsignadas">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-warning" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clock"></i> Visitas Programadas</h6>
                        <h4 class="subtitle-count"><b data-panel="tSinAsignar">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-info" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-business-time"></i> Visitas En Proceso
                        </h6>
                        <h4 class="subtitle-count"><b data-panel="tEnProceso">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-success" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clipboard-check"></i> Visitas
                            Realizadas</h6>
                        <h4 class="subtitle-count"><b data-panel="tEnProceso">0</b></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Visitas a Programar</strong>
                </h6>
                <div>
                    <button class="btn btn-primary btn-sm px-1" onclick="updateTableVisitas()" data-mdb-ripple-init
                        role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_visitas" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-bg-primary">
                                    <th>Ruc</th>
                                    <th>Sucursal</th>
                                    <th class="text-center">Visitas Realizadas</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Visitas Programadas</strong>
                </h6>
                <div>
                    <button class="btn btn-primary btn-sm px-1" onclick="updateTableVProgramadas()" data-mdb-ripple-init
                        role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_vprogramadas" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-bg-primary">
                                    <th class="text-center">Estado</th>
                                    <th>Sucursal</th>
                                    <th>Técnico</th>
                                    <th class="text-center">Fecha Visita</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<button class="d-none" data-mdb-modal-init data-mdb-target="#modal_visitas"></button>
<div class="modal fade" id="modal_visitas" tabindex="-1" aria-labelledby="modal_visitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="form-visita">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="modal_visitasLabel">Asignar Personal Visita <span
                        class="badge badge-success" aria-item="contrato">En Contrato</span></h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                    <div class="list-group list-group-light">
                        <div class="list-group-item">
                            <span aria-item="empresa"></span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                aria-item="direccion"></span>
                        </div>
                        <div class="list-group-item">
                            <div class="row col-12">
                                <div class="col-sm-6">
                                    <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                        aria-item="sucursal"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-3 pb-0 fieldset mb-3">
                    <input type="hidden" id="idSucursal" name="idSucursal">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label mb-0" for="createPersonal">Asignar Personal</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text border-0 ps-0"><i
                                        class="fas fa-chalkboard-user"></i></span>
                                <select class="select-clear" id="createPersonal">
                                    <option value=""></option>
                                    @foreach ($data['usuarios'] as $u)
                                        <option value="{{$u['value']}}" data-value="{{$u['dValue']}}">{{$u['text']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0" for="fecha_visita">Fecha Visita</label>
                            <div class="input-group" role="button">
                                <label class="input-group-text ps-0 pe-1 border-0"><i
                                        class="far fa-calendar"></i></label>
                                <input type="text" class="form-control rounded" id="fecha_visita" name="fecha_visita"
                                    role="button" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_detalle_visitas" tabindex="-1" aria-labelledby="modal_detalle_visitasLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="modal_detalle_visitasLabel">Asignar Personal Visita <span
                        class="badge badge-success" aria-item="contrato">En Contrato</span></h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                    <div class="list-group list-group-light">
                        <div class="list-group-item">
                            <span aria-item="empresa"></span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                aria-item="direccion"></span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                aria-item="sucursal"></span>
                        </div>
                        <div class="list-group-item">
                            <div class="row col-12">
                                <div class="col-md-3 col-6">
                                    <label class="form-label me-2">Limitacion: </label><span style="font-size: .75rem;"
                                        aria-item="rDias">0</span>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label me-2">Visitas Totales: </label><span
                                        style="font-size: .75rem;" aria-item="vTotal">0</span>
                                </div>
                                <div class="col-md-3 col-6 text-center">
                                    <label class="form-label me-2">Visitas Realizadas: </label><span
                                        style="font-size: .75rem;" aria-item="vRealizada">0</span>
                                </div>
                                <div class="col-md-3 col-6 text-end">
                                    <label class="form-label me-2">Visitas Pendientes: </label><span
                                        style="font-size: .75rem;" aria-item="vPendiente">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <table class="table mb-0 d-none" id="tb_visitas_asignadas">
                                <thead>
                                    <tr class="text-bg-primary">
                                        <th>Asignado Por</th>
                                        <th class="text-center">Fecha Visita</th>
                                        <th class="text-center">Registrado</th>
                                        <th class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Nota: </label><span style="font-size: .75rem;"
                                aria-item="mensaje"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_seguimiento_visitasp" aria-labelledby="modal_detalle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title"><i class="fas fa-book-open"></i> Seguimiento de la visita</h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-end" aria-item="estado"></div>
                <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                    <div class="list-group list-group-light">
                        <div class="list-group-item">
                            <span aria-item="empresa">20506467854 - CORPORACION JULCAN S.A.</span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                aria-item="sucursal">E/S INDEPENDENCIA</span>
                        </div>
                    </div>
                </div>
                <h6 class="font-weight-semibold col-form-label text-primary mt-2">Seguimiento Visita</h6>
                <div class="">
                    <ul class="list-group list-group-light" id="content-seguimiento">
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link " data-mdb-ripple-init
                    data-mdb-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_assign" aria-labelledby="modal_assign" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">Asignar Personal -
                    <span class="badge badge-success badge-lg" aria-item="codigo"></span>
                </h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-end" aria-item="estado"></div>
                <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                    <div class="list-group list-group-light">
                        <div class="list-group-item">
                            <span aria-item="empresa">20506467854 - CORPORACION JULCAN S.A.</span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                aria-item="sucursal">E/S INDEPENDENCIA</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-3 pb-0 fieldset mb-3">
                    <input type="hidden" id="id_visitas_asign">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label mb-0" for="createPersonal1">Asignar Personal</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text border-0 ps-0"><i
                                        class="fas fa-chalkboard-user"></i></span>
                                <select class="select-clear" id="createPersonal1">
                                    <option value=""></option>
                                    @foreach ($data['usuarios'] as $u)
                                        <option value="{{$u['value']}}" data-value="{{$u['dValue']}}">{{$u['text']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0" for="fecha_visita_asign">Fecha Visita</label>
                            <div class="input-group" role="button">
                                <label class="input-group-text ps-0 pe-1 border-0"><i
                                        class="far fa-calendar"></i></label>
                                <input type="text" class="form-control rounded" id="fecha_visita_asign" name="fecha_visita_asign"
                                    role="button" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init
                    onclick="AssignPer()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_orden" aria-labelledby="modal_orden" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="display: flex;">
        <form class="modal-content" id="form-orden">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">ORDEN DE SERVICIO <span class="badge badge-success badge-lg"
                        aria-item="codigo">{{$data['cod_ordenv']}}</span></h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <!-- INICIO CABECERA -->
                    <div class="text-end cabecera-orden">
                        <div>
                            <label class="form-label me-2">Fecha Inicio: </label>
                            <span style="font-size: small;" aria-item="registrado"></span>
                        </div>
                    </div>

                    <!-- TER CABECERA -->
                    <div class="col-12 mt-2">
                        <h6 class="tittle text-primary"> TECNICOS</h6>
                    </div>

                    <div class="col-12 mt-1 mb-3">
                        <span aria-item="tecnicos"></span>
                    </div>


                    <div class="col-12 mt-2">
                        <h6 class="tittle text-primary"> DATOS DEL CLIENTE </h6>
                    </div>

                    <div class="col-12 mt-1 mb-3">
                        <div class="list-group list-group-light">
                            <div class="list-group-item">
                                <span aria-item="empresa">20506467854 - CORPORACION JULCAN S.A.</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Sucursal: </label><span
                                    style="font-size: .75rem;" aria-item="sucursal">E/S INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                    aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <h6 class="tittle text-primary"> REVISION DEL GABINETE </h6>
                    </div>

                    <input type="hidden" name="cod_ordenv" value="{{$data['cod_ordenv']}}">
                    <input type="hidden" name="id_visita_orden">

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>UPS</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des1" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-lg-3" style="font-size: 11px;"><label>• BATERIAS UPS</label></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des2" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3" style="font-size: 11px;"><label>• SALIDA DE ENERGIA</label></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des3" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>ESTABILIZADOR</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des4" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-lg-3" style="font-size: 11px;"><label>• INGRESO DE ENERGIA</label></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des5" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3" style="font-size: 11px;"><label>• SALIDA DE ENERGIA</label></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des6" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>INTERFACE</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des7" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>MONITOR</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des8" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>TARJETA MULTIPUERTOS</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des9" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>SWITCH</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des10" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <h6 class="tittle text-primary"> REVISION DEL SERVIDOR</h6>
                    </div>

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>SISTEMA OPERATIVO</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des11" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>VENCIMIENTO DE ANTIVIRUS</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des12" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>DISCO DURO</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des13" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>REALIZAR BACKUP</strong></div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                <input type="text" name="des14" class="form-control rounded" onchange="changeCheck(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-10 my-1">
                            <h6 class="tittle text-primary"> REVISION DEL POS, LECTORES, JACK TOOLS IMPRESORAS Y CONEXIONES </h6>
                        </div>
                        <div class="col-lg-2 my-1 d-flex align-items-center justify-content-end">
                            <strong class="me-2" style="white-space: nowrap;" id="conteo-islas">Cant. 1</strong>
                            <button type="button" class="btn btn-secondary px-2" onclick="MRevision.create()"><i class="far fa-square-plus"></i></button>
                        </div>
                    </div>

                    <div id="content-islas" class="mt-3">
                        <!--<div class="islas-item py-2">
                            <div class="row my-2">
                                <div class="col-lg-3 col-sm-4 col-5">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0" style="font-size: small;">ISLA</span>
                                        <input type="text" class="form-control rounded"/>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-4 col-5">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0" style="font-size: small;">POS</span>
                                        <input type="text" class="form-control rounded"/>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-4 col-2 text-end">
                                    <button type="button" class="btn btn-danger px-2"><i class="far fa-trash-can"></i></button>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>IMPRESORAS</strong></div>
                                <div class="col-lg-9">
                                    <input type="text" name="des7" class="form-control">
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>RED DE LECTORES</strong></div>
                                <div class="col-lg-9">
                                    <input type="text" name="des8" class="form-control">
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>JACK TOOLS</strong></div>
                                <div class="col-lg-9">
                                    <input type="text" name="des7" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-3" style="font-size: 11px;"><label>• VOLTAJE DE MANGUERAS</label></div>
                                <div class="col-lg-9">
                                    <input type="text" name="des2" class="form-control">
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>CAUCHO PROTECTOR DE LECTORES</strong></div>
                                <div class="col-lg-9">
                                    <input type="text" name="des8" class="form-control">
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>MUEBLE DE POS</strong></div>
                                <div class="col-lg-9">
                                    <input type="text" name="des9" class="form-control">
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575"><strong>MR 350 / DTI / TERMINAL</strong></div>
                                <div class="col-lg-9">
                                    <input type="text" name="des10" class="form-control">
                                </div>
                            </div>
                        </div>-->
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary px-2" onclick="MRevision.create()"><i class="far fa-square-plus"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init onclick="">Registrar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let cod_ordenv = "{{$data['cod_ordenv']}}";
</script>
<script src="{{asset('front/js/RevisionMananger,js')}}"></script>
<script src="{{asset('front/js/app/visitas/visitas.js')}}"></script>
<script src="{{asset('front/js/app/visitas/programadas.js')}}"></script>
@endsection