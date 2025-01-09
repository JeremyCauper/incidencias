@extends('layout.app')
@section('title', 'Panel de Control')

@section('style')
<link rel="stylesheet" href="{{asset('front/css/app/incidencias/registradas.css')}}">
<style>
    .row-bg-warning {
        background-color:rgb(255, 246, 230);
    }
    .row-bg-info {
        background-color:rgb(239, 252, 255);
    }
    .row-bg-primary {
        background-color:rgb(236, 244, 255);
    }
    .row-bg-danger {
        background-color: #fae4e8;
    }
</style>
@endsection
@section('content')

<div class="row panel-view">
    <div class="col-12">
        <div class="row">
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-success" data-mdb-ripple-init onclick="searchTable(0)">
                        <h6 class="card-title title-count mb-2"><i class="far fa-clock"></i> Incidencias Registradas
                        </h6>
                        <h4 class="subtitle-count"><b data-panel="totales">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-info" data-mdb-ripple-init onclick="searchTable(1)">
                        <h6 class="card-title title-count mb-2"><i class="fas fa-user-check"></i> Incidencias Asignadas
                        </h6>
                        <h4 class="subtitle-count"><b data-panel="tAsignadas">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-warning" data-mdb-ripple-init onclick="searchTable(2)">
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clipboard-check"></i> Incidencias Sin
                            Asignar</h6>
                        <h4 class="subtitle-count"><b data-panel="tSinAsignar">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-primary" data-mdb-ripple-init onclick="searchTable(3)">
                        <h6 class="card-title title-count mb-2"><i class="fas fa-business-time"></i> Incidencias En
                            Proceso</h6>
                        <h4 class="subtitle-count"><b data-panel="tEnProceso">0</b></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
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
                    <table id="tb_incidencia" class="table table-hover text-nowrap" style="width:100%">
                        <thead>
                            <tr class="text-bg-primary">
                                <th>Codigo</th>
                                <th class="text-center">Estado</th>
                                <th>Empresa</th>
                                <th>Sucursal</th>
                                <th>Registrado</th>
                                <th>Estacion</th>
                                <th>Atencion</th>
                                <th>Problema / Sub Problema</th>
                                <th class="text-bg-primary px-2 th-acciones">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_incidencias" aria-labelledby="modal_incidencias" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" id="form-incidencias" style="position: relative;">
            <input type="hidden" name="id_inc" id="id_inc">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">
                    <b>NUEVA INCIDENCIA: </b>
                    <b id="cod_inc_text">{{$data['cod_inc']}}</b>
                    <b class="ms-3 badge badge-success" id="contrato"></b>
                </h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="d-none" name="cod_inc" id="cod_inc" value="{{$data['cod_inc']}}">
                <div class="col-12 mb-2">
                    <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios
                        (<span class="text-danger">*</span>)</span>
                </div>
                <h6 class="tittle text-primary my-3">Datos Empresa</h6>
                <div class="row">
                    <div class="col-lg-8 mb-2">
                        <label class="form-label mb-0" for="id_empresa">Empresa</label>
                        <select class="select-clear" id="id_empresa">
                            <option value="">-- Seleccione --</option>
                            @foreach ($data['company'] as $e)
                                <option value="{{$e->id}}" select-ruc="{{$e->ruc}}">{{$e->ruc}} - {{$e->razon_social}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 mb-2">
                        <label class="form-label mb-0" for="sucursal">Sucursal</label>
                        <select class="select" id="sucursal">
                            <option value="">-- Seleccione --</option>
                        </select>
                    </div>
                </div>

                <h6 class="tittle text-primary my-3">Datos Contacto</h6>
                <div class="row">
                    <input type="hidden" name="cod_contact" id="cod_contact">
                    <div class="col-lg-4 col-6 mb-2">
                        <label class="form-label mb-0" for="tel_contac">Telefono</label>
                        <select class="select-tags" id="tel_contac" onchange="validContac(this)">
                            <option value=""></option>
                            @foreach ($data['eContactos'] as $ct)
                                <option value="{{$ct->telefono}}">{{$ct->telefono}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-6 mb-2">
                        <label class="form-label mb-0" for="nro_doc">Dni</label>
                        <input class="form-control" id="nro_doc" onkeyup="validContac(this)">
                    </div>
                    <div class="col-lg-4 col-12 mb-2">
                        <label class="form-label mb-0" for="nom_contac">Nombre</label>
                        <input class="form-control" id="nom_contac" onkeyup="validContac(this)">
                    </div>
                    <div class="col-lg-6 col-5 mb-2">
                        <label class="form-label mb-0" for="car_contac">Cargo</label>
                        <select class="select-clear" id="car_contac" onchange="validContac(this)">
                            <option value="">-- Seleccione --</option>
                            @foreach ($data['CargoContacto'] as $cc)
                                <option value="{{$cc->id}}">{{$cc->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 col-7 mb-2">
                        <label class="form-label mb-0" for="cor_contac">Correo</label>
                        <input type="text" class="form-control" id="cor_contac" name="cor_contac" onkeyup="validContac(this)">
                    </div>
                </div>

                <h6 class="tittle text-primary my-3">Datos Incidencia</h6>
                <div class="row">
                    <div class="col-lg-4 col-7 mb-2">
                        <label class="form-label mb-0" for="tEstacion">Tipo Estación</label>
                        <select class="select-clear" id="tEstacion">
                            <option value="">-- Seleccione --</option>
                            @foreach ($data['tEstacion'] as $v)
                                <option value="{{$v->id}}">{{$v->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-5 mb-2">
                        <label class="form-label mb-0" for="prioridad">Prioridad</label>
                        <select class="select" id="prioridad">
                            <option value="Alta">Alta</option>
                            <option value="Media">Media</option>
                            <option value="Baja">Baja</option>
                            <option value="Critica">Critica</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-7 mb-2">
                        <label class="form-label mb-0" for="tSoporte">Tipo Soporte</label>
                        <select class="select" id="tSoporte">
                            <option value="">-- Seleccione --</option>
                            @foreach ($data['tSoporte'] as $v)
                                <option value="{{$v->id}}">{{$v->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-5 mb-2">
                        <label class="form-label mb-0" for="tIncidencia">Tipo Incidencia</label>
                        <select class="select" id="tIncidencia">
                            <option value="">-- Seleccione --</option>
                            @foreach ($data['tIncidencia'] as $v)
                                <option value="{{$v->id}}">{{$v->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="form-label mb-0" for="problema">Problema</label>
                        <select class="select-clear" id="problema">
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label mb-0" for="sproblema">Sub Problema</label>
                        <select class="select-clear" id="sproblema">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="col-sm-12 mb-2">
                            <label class="form-label mb-0" for="fecha_imforme">Fecha de Informe</label>
                            <input class="form-control" id="fecha_imforme">
                        </div>
                        <div class="col-sm-12 mb-2">
                            <label class="form-label mb-0" for="hora_informe">Hora de Informe</label>
                            <input class="form-control" id="hora_informe" min="00:00"
                                max="23:59" step="1">
                        </div>
                    </div>
                    <div class="col-sm-8 mb-2">
                        <label class="form-label mb-0" for="observasion">Observacion</label>
                        <textarea class="form-control" id="observasion"
                            style="height: 106px;resize: none;"></textarea>
                    </div>
                </div>

                <div id="contenedor-personal">
                    <h6 class="tittle text-primary my-3">Asignar Personal
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mt-2 mb-2">
                                <span class="input-group-text border-0"><i class="fas fa-chalkboard-user"></i></span>
                                <select class="select-clear" id="createPersonal">
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
                <button type="button" class="btn btn-danger btn-sm" data-mdb-ripple-init
                    data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init>Registrar</button>
            </div>
        </form>
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
                    </div>
                </div>
                <div class="mt-4 p-3 pb-0 fieldset mb-3">
                    <h6 class="tittle text-primary">Asignar Personal</h6>
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
    <div class="modal-dialog modal-lg" style="display: flex;">
        <form class="modal-content" id="form-orden">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">ORDEN DE SERVICIO <span class="badge badge-success badge-lg"
                        aria-item="codigo"></span></h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
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
                                <div class="form-group pt-2 required">
                                    <label class="form-label" for="observacion">Observaciones</label>
                                    <textarea class="form-control" id="observacion"
                                        style="height: 80px;resize: none;"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pt-2 required">
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

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row" id="content-material">
                            <div class="col-lg-8 my-1">
                                <select class="select-clear" id="createMaterial"> <!-- id="selector-material"> -->
                                    <option value=""></option>
                                    @foreach ($data['materiales'] as $m)
                                        <option value="{{$m['value']}}" data-value="{{$m['dValue']}}">{{$m['text']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-9 d-flex ps-lg-0 my-1 disabled" id="content-cantidad">
                                <div class="input-group">
                                    <button class="btn btn-secondary px-2" type="button" data-mdb-ripple-init
                                        onclick="manCantidad('minus')">
                                        <i class="fas fa-minus" style="font-size: .75rem;"></i>
                                    </button>
                                    <input type="number" class="form-control" input-cantidad=""
                                        oninput="manCantidad('press')" style="min-width: 65px;"/>
                                    <button class="btn btn-secondary px-2" type="button" data-mdb-ripple-init
                                        onclick="manCantidad('plus')">
                                        <i class="fas fa-plus" style="font-size: .75rem;"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control ms-2 d-none" id="codAviso" placeholder="Cod. Aviso">
                            </div>
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
                                <p class="mb-0" style="font-size: 12.5px;">{{Auth::user()->ndoc_usuario . ' - ' . Auth::user()->nombres . ' ' . Auth::user()->apellidos}}</p>
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
                                <p style="font-size: 12.5px;" id="doc_clienteFirma" class="doc-fsearch mb-0"></p>
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
<script>
    let cod_incidencia = '<?= $data['cod_inc'] ?>';
    let cod_orden = '<?= $data['cod_orden'] ?>';
    const sucursales = <?php echo json_encode($data['scompany']); ?>;
    const obj_problem = <?php echo json_encode($data['problema']); ?>;
    const obj_subproblem = <?php echo json_encode($data['sproblema']); ?>;
    let obj_eContactos = <?php echo json_encode($data['eContactos']); ?>;
</script>
<script src="{{asset('front/vendor/signature/signature_pad.js')}}"></script>
<script src="{{asset('front/js/app/incidencia/registradas.js')}}"></script>
@endsection