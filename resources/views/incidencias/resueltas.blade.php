@extends('layout.app')
@section('title', 'INC RESUELTAS')

@section('style')
<link rel="stylesheet" href="{{asset('front/css/app/incidencias/resueltas.css')}}">
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
            <h4 class="card-title">Incidencias Resueltas</h4>
            <div>
                <button type="button" class="d-none" data-mdb-modal-init data-mdb-target="#modal_detalle"></button>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_orden" class="table table-hover text-nowrap w-100">
                        <thead>
                            <tr class="text-bg-primary">
                                <th>Codigo</th>
                                <th>Tipo Orden</th>
                                <th>Tecnico</th>
                                <th>Fecha Servicio</th>
                                <th>Empresa</th>
                                <th>Sucursal</th>
                                <th>Problema / Sub Problema</th>
                                <th>Iniciada</th>
                                <th>Terminada</th>
                                <th class="text-bg-primary px-2 th-acciones">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_detalle" aria-labelledby="modal_detalle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title"><i class="fas fa-book-open"></i> Detalle de incidencia -
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
                            <div class="row col-12">
                                <div class="col-sm-6">
                                    <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                        aria-item="sucursal">E/S INDEPENDENCIA</span>
                                </div>
                                <div class="col-sm-6 text-end">
                                    <label class="form-label me-2">Atención: </label><span style="font-size: .75rem;"
                                        aria-item="atencion">Remoto</span>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Problema / Sub Problema:</label>
                            <span style="font-size: .75rem;" aria-item="problema / sub problema"></span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Observación:</label>
                            <span style="font-size: .75rem;" aria-item="observasion"></span>
                        </div>
                    </div>
                </div>
                <h6 class="font-weight-semibold col-form-label text-primary mt-2">Seguimiento Incidencia</h6>
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

<div class="modal fade" id="modal_orden" aria-labelledby="modal_orden" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="display: flex;">
        <form class="modal-content" id="form-orden">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">ORDEN DE SERVICIO <span class="badge badge-success badge-lg"
                        aria-item="codigo"></span></h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-lg-12 col-xs-12">
                    <!-- INICIO CABECERA -->
                    <div class="text-end cabecera-orden">
                        <div>
                            <label class="form-label me-2">Fecha Inicio: </label>
                            <span style="font-size: small;" aria-item="iniciada"></span>
                        </div>
                    </div>
                    <!-- TER CABECERA -->
                    <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                        <h6 class="tittle text-primary"> TECNICOS</h6>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                        <span aria-item="tecnicos"></span>
                    </div>


                    <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                        <h6 class="tittle text-primary"> DATOS DEL CLIENTE </h6>
                    </div>

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
                                <div class="row col-12">
                                    <div class="col-sm-6">
                                        <label class="form-label me-2">Sucursal: </label><span
                                            style="font-size: .75rem;" aria-item="sucursal">E/S INDEPENDENCIA</span>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <label class="form-label me-2">Atencion: </label><span
                                            style="font-size: .75rem;" aria-item="tipo orden">Remoto</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                        <h6 class="tittle text-primary"> TRABAJO REALIZADO </h6>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                        <div class="col-md-12">
                            <label class="form-label me-2">Clasificacion Error:</label><span style="font-size: .75rem;"
                                aria-item="problema / sub problema">PROBLEMA DE LECTURA / VALIDACION DE
                                JACKTOOL</span>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label me-2">Obersevaciones Incidencia:</label>
                            <span style="font-size: .75rem;" aria-item="observacion"></span>
                        </div>
                        <div class="row justify-content-md-center">
                            <div class="col-md-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="exampleInputEmail1">Observaciones *</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones"
                                        style="height: 80px;resize: none;" disabled></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="exampleInputEmail1">Recomendaciones *</label>
                                    <textarea class="form-control" id="recomendaciones" name="recomendaciones"
                                        style="height: 80px;resize: none;" disabled></textarea>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="exampleInputEmail1">Fecha Fin </label>
                                    <input type="text" class="form-control form-control-sm" id="fecha_f" disabled>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="exampleInputEmail1">Hora Fin </label>
                                    <input type="text" class="form-control form-control-sm" id="hora_f" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                        <h6 class="tittle text-primary">MATERIALES UTILIZADOS</h2>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row" id="content-material">
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 my-2 px-4">
                        <div class="row justify-content-between firmas-orden">
                            <div class="col-lg-5 text-center my-2">
                                <img class="border rounded-1" {{Auth::user()->firma_digital ? 'src=' . asset('front/images/firms/' . Auth::user()->firma_digital) . '' : ''}} height="130"
                                    width="160">
                                <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">
                                    Firma Tecnico
                                </p>
                                <p class="mb-1" style="font-size: 13.4px;">RICARDO CALDERON INGENIEROS SAC</p>
                                <p class="mb-0" style="font-size: 12.5px;">
                                    {{Auth::user()->ndoc_usuario . ' - ' . Auth::user()->nombres . ' ' . Auth::user()->apellidos}}
                                </p>
                            </div>

                            <div class="col-lg-5 text-center my-2">
                                <div class="text-center content-image">
                                    <img id="PreviFirma" class="visually-hidden" height="130" width="160">
                                </div>
                                <!-- <img class="border rounded-1" id="" alt="" > -->
                                <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma
                                    Cliente</p>
                                <p class="mb-1" style="font-size: 13.4px;" aria-item="                                              ">COESTI S.A.</p>
                                <p style="font-size: 12.5px;" id="doc_clienteFirma" class="doc-fsearch mb-0"></p>
                            </div>
                        </div>
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
</script>
<script src="{{asset('front/vendor/signature/signature_pad.js')}}"></script>
<script src="{{asset('front/js/app/incidencia/resueltas.js')}}"></script>
@endsection