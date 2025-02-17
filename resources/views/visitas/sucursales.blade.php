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
                    <h6 class="tittle text-primary">Asignar Personal</h6>
                    <input type="hidden" id="id_visitas_asign">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="input-group mt-2 mb-3">
                                <span class="input-group-text border-0"><i class="fas fa-chalkboard-user"></i></span>
                                <select class="select-clear" id="createPersonal1">
                                    <option value=""></option>
                                    @foreach ($data['usuarios'] as $u)
                                        <option value="{{$u['value']}}" data-value="{{$u['dValue']}}">{{$u['text']}}
                                        </option>
                                    @endforeach
                                </select>
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
                            <span style="font-size: small;" aria-item="registrado"></span>
                        </div>
                    </div>
                    <div class="mb-1 cabecera-orden">
                        <input type="hidden" name="codInc" id="codInc">
                        <div class="form-group pt-2">
                            <label class="form-label" for="n_orden">N° de Orden </label>
                            <div class="input-group mb-3" style="width: 250px;">
                                <input class="form-control form-control-sm rounded" id="n_orden">
                                <span class="input-group-text border-0">
                                    <input type="checkbox" class="me-1" name="check_cod" id="check_cod"
                                        onchange="setChangeCodOrden(this)">
                                    <label for="check_cod" style="font-size: .75rem;">Cod. Sistema</label>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- TER CABECERA -->
                    <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                        <h6 class="tittle text-primary"> TECNICOS</h6>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 my-2">
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
                                            style="font-size: .75rem;" aria-item="atencion">Remoto</span>
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
                                    <label class="form-label" for="observacion">Observaciones</label>
                                    <textarea class="form-control" id="observacion"
                                        style="height: 80px;resize: none;"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="recomendacion">Recomendaciones</label>
                                    <textarea class="form-control" id="recomendacion" name="recomendacion"
                                        style="height: 80px;resize: none;" require="Recomendaciones"></textarea>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="fecha_f">Fecha Fin </label>
                                    <input class="form-control form-control-sm" id="fecha_f">
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="hora_f">Hora Fin </label>
                                    <input class="form-control form-control-sm" id="hora_f">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                        <h6 class="tittle text-primary">MATERIALES UTILIZADOS</h2>
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
                                    <div class="overlay">
                                        <button class="btn-img removeImgButton" style="display: none;"
                                            id="removeImgFirma" type="button" button-reset><i
                                                class="fas fa-xmark"></i></button>
                                        <button class="btn-img mx-1 uploadImgButton" id="uploadImgFirma"
                                            type="button"><i class="fas fa-arrow-up-from-bracket"></i></button>
                                        <button class="btn-img mx-1 uploadImgButton" id="createFirma" type="button"><i
                                                class="fas fa-pencil"></i></button>
                                        <button class="btn-img expandImgButton" type="button"
                                            onclick="PreviImagenes(PreviFirma.src);"><i
                                                class="fas fa-expand"></i></button>
                                    </div>
                                    <input type="file" class="d-none" id="firma_digital">
                                    <input type="text" class="d-none" name="firma_digital" id="textFirmaDigital">
                                    <img id="PreviFirma" class="visually-hidden" height="130" width="160">
                                </div>
                                <!-- <img class="border rounded-1" id="" alt="" > -->
                                <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma
                                    Cliente</p>
                                <p class="mb-1" style="font-size: 13.4px;" aria-item="empresaFooter">COESTI S.A.</p>
                                <div class="search_signature_group">
                                    <input type="text" id="search_signature" placeholder="Buscar cliente">
                                    <span class="search_signature_text rounded" type="button" data-mdb-ripple-init>
                                        <i class="fas fa-magnifying-glass"></i>
                                    </span>
                                </div>
                                <input type="hidden" name="id_firmador" id="id_firmador">
                                <input type="hidden" name="nomFirmaDigital" id="nomFirmaDigital">
                                <input type="hidden" name="n_doc" id="n_doc">
                                <input type="hidden" name="nom_cliente" id="nom_cliente">
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
<script src="{{asset('front/js/app/visitas/visitas.js')}}"></script>
<script src="{{asset('front/js/app/visitas/programadas.js')}}"></script>
@endsection