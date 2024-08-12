@extends('layout.app')
@section('title', 'Panel de Control')

@section('style')
<link rel="stylesheet" href="{{asset('front/css/app/panel.css')}}">
<style>
    .th-acciones {
        right: 0px;
        position: sticky !important;
        width: 90px !important;
    }

    .td-acciones {
        position: sticky;
        right: 0;
        text-align: center !important;
        background-color: #fff !important;
    }

    .list-group .list-group-item {
        font-size: .9rem;
        padding: .8rem 0;
    }

    #tb_incidencia thead,
    #tb_incidencia tbody {
        font-size: 12px !important;
    }

    .tittle {
        font-weight: bold;
        font-size: .9rem;
    }

    .firmas-orden div p {
        font-size: .7rem;
        padding: .4rem;
        margin-bottom: .1rem;
        border-radius: .5rem;
    }

    #doc_clienteFirma {
        cursor: pointer;
        position: relative;
    }

    .doc-fsearch {
        height: 26.78px;
        border: 0.1rem solid #cdcdcd;
    }

    .doc-fsearch:hover {
        background-color: #f3f3f3;
    }

    .doc-fsearch::before,
    .doc-fsearch::after {
        position: absolute;
        color: inherit;
        display: block;
        top: 55%;
        font-size: .75rem;
        margin-top: -.4375rem;
        line-height: 1;
        opacity: .6;
        -webkit-font-smoothing: antialiased;
    }

    .doc-fsearch::before {
        content: "\f002";
        font-family: "Font Awesome 6 Free";
        left: 2.25rem;
    }

    .doc-fsearch::after {
        content: "Buscar cliente";
        font-family: "Roboto";
        left: 3.65rem;
        font-weight: 500;
    }

    .doc-fclear {
        padding-right: 1.7rem !important;
    }

    .doc-fclear::before {
        content: "\f00d";
        font-family: "Font Awesome 6 Free";
        position: absolute;
        right: .65rem;
        top: 50%;
        font-size: .9rem;
        margin-top: -.4375rem;
        line-height: 1;
        opacity: .6;
        -webkit-font-smoothing: antialiased;
    }
</style>
@endsection
@section('content')

<div class="row panel-view">
    <div class="col-12">
        <div class="row">
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-success" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="far fa-clock"></i> Incidencias Registradas</h6>
                        <h4 class="subtitle-count"><b data-panel="_count">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-info" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-user-check"></i> Incidencias Asignadas</h6>
                        <h4 class="subtitle-count"><b data-panel="_inc_a">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-warning" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clipboard-check"></i> Incidencias Sin Asignar</h6>
                        <h4 class="subtitle-count"><b data-panel="_inc_s">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-primary" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-business-time"></i> Incidencias En Proceso</h6>
                        <h4 class="subtitle-count"><b data-panel="_inc_p">0</b></h4>
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
                <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modal_incidencias">
                    <i class="fas fa-book-medical"></i>
                    Nueva Incidencia
                </button>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <!-- <div style="height: 200px;">
                <div class="gear">
                    <div>
                        <label></label>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div> -->
            <div class="row">
                <div class="col-12">
                    <table id="tb_incidencia" class="table table-hover text-nowrap w-100">
                        <thead>
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

<div class="modal fade" id="modal_incidencias" aria-labelledby="modal_incidencias" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="position: relative;">
            <form id="form-incidencias" frm-accion="0" idu="">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title"><b>CREAR NUEVA INCIDENCIA: </b><b id="cod_inc_text">{{$dataInd['cod_inc']}}</b><b class="ms-3 badge badge-success" id="contrato"></b></h6>
                </div>
                <div class="modal-body">
                    <input type="text" class="d-none" name="cod_inc" id="cod_inc" value="{{$dataInd['cod_inc']}}">
                    <div class="col-12 mb-2">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
                    </div>
                    <div class="mt-4 p-3 pb-0 fieldset mb-3">
                        <h6 class="legend text-primary">Datos Empresa</h6>
                        <div class="row">
                            <div class="col-lg-8 mb-3">
                                <label class="form-label mb-0" for="id_empresa"><b>Empresa <span class="text-danger">*</span></b></label>
                                <select id="id_empresa" class="select-clear" name="id_empresa" require="Empresa">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['empresas'] as $e)
                                    <option value="{{$e['id']}}" select-ruc="{{$e['ruc']}}">{{$e['empresa']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label mb-0" for="id_sucursal"><b>Sucursal <span class="text-danger">*</span></b></label>
                                <select id="id_sucursal" class="select" name="id_sucursal" require="Sucursal" disabled="true">
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 pb-0 fieldset mb-3">
                        <h6 class="legend text-primary">Datos Contacto</h6>
                        <div class="row">
                            <div class="col-lg-4 col-6 mb-3">
                                <label class="form-label mb-0" for="tel_contac"><b>Telefono</b></label>
                                <select id="tel_contac" class="select-tags" name="tel_contac"></select>
                            </div>
                            <div class="col-lg-4 col-6 mb-3">
                                <label class="form-label mb-0" for="nro_doc"><b>Dni</b></label>
                                <input type="text" class="form-control" id="nro_doc" name="nro_doc">
                            </div>
                            <div class="col-lg-4 col-12 mb-3">
                                <label class="form-label mb-0" for="nom_contac"><b>Nombre</b></label>
                                <input type="text" class="form-control" id="nom_contac" name="nom_contac">
                            </div>
                            <div class="col-lg-6 col-5 mb-3">
                                <label class="form-label mb-0" for="car_contac"><b>Cargo</b></label>
                                <select id="car_contac" class="select-clear" name="car_contac">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['cargo_contaco'] as $cc)
                                    <option value="{{$cc->id}}">{{$cc->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-7 mb-3">
                                <label class="form-label mb-0" for="cor_contac"><b>Correo</b></label>
                                <input type="text" class="form-control" id="cor_contac" name="cor_contac">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 pb-0 fieldset mb-3">
                        <h6 class="legend text-primary">Datos Incidencia</h6>
                        <div class="row">
                            <div class="col-lg-4 col-7 mb-3">
                                <label class="form-label mb-0" for="tip_estacion"><b>Tipo Estación <span class="text-danger">*</span></b></label>
                                <select class="select-clear" id="tip_estacion" name="tip_estacion" require="Tipo Estación">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['tipo_estacion'] as $v)
                                    <option value="{{$v->id}}">{{$v->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-5 mb-3">
                                <label class="form-label mb-0" for="priori_inc"><b>Prioridad <span class="text-danger">*</span></b></label>
                                <select class="select" id="priori_inc" name="priori_inc" require="Prioridad">
                                    <option value="Alta">Alta</option>
                                    <option value="Media">Media</option>
                                    <option value="Baja">Baja</option>
                                    <option value="Critica">Critica</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-7 mb-3">
                                <label class="form-label mb-0" for="tip_soport"><b>Tipo Soporte <span class="text-danger">*</span></b></label>
                                <select class="select" id="tip_soport" name="tip_soport" require="Tipo Soporte">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['tipo_soporte'] as $v)
                                    <option value="{{$v->id}}">{{$v->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-5 mb-3">
                                <label class="form-label mb-0" for="tip_incidencia"><b>Tipo Incidencia <span class="text-danger">*</span></b></label>
                                <select class="select" id="tip_incidencia" name="tip_incidencia" require="Tipo Incidencia">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['tipo_incidencia'] as $v)
                                    <option value="{{$v->id}}">{{$v->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0" for="inc_problem"><b>Problema <span class="text-danger">*</span></b></label>
                                <select class="select-clear" id="inc_problem" name="inc_problem" require="Problema" disabled>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0" for="inc_subproblem"><b>Sub Problema <span class="text-danger">*</span></b></label>
                                <select class="select-clear" id="inc_subproblem" name="inc_subproblem" require="Sub Problema" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-0" for="fecha_imforme"><b>Fecha de Informe <span class="text-danger">*</span></b></label>
                                    <input type="date" class="form-control input-date" id="fecha_imforme" name="fecha_imforme" require="Fecha de Informe">
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-0" for="hora_informe"><b>Hora de Informe <span class="text-danger">*</span></b></label>
                                    <input type="time" class="form-control input-time" id="hora_informe" name="hora_informe" require="Hora de Informe">
                                </div>
                            </div>
                            <div class="col-sm-8 mb-3">
                                <label class="form-label mb-0" for="tipo_acceso"><b>Observacion</b></label>
                                <textarea class="form-control" id="observasion" name="observasion" style="height: 106px;resize: none;"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 pb-0 fieldset mb-3">
                        <h6 class="legend text-primary">Asignar Personal</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mt-2 mb-3">
                                    <span class="input-group-text border-0"><i class="fas fa-chalkboard-user"></i></span>
                                    <select class="select-clear" id="selectPersonal">
                                        <option value=""></option>
                                        @foreach ($dataInd['usuarios'] as $u)
                                        <option value="{{$u['value']}}">{{$u['text']}}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary px-2" onclick="tecnicoAsigManenger('create', 'selectPersonal', 'content_asig_personal')" data-mdb-ripple-init><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-12" id="content_asig_personal" style="overflow: auto;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init>Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_viewdetalle" aria-labelledby="modal_viewdetalle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title"><i class="fas fa-book-open"></i> Detalle de incidencia</h6>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-light">
                    <div class="text-end" style="font-size: 1.4rem;">
                        <span class="badge badge-secondary badge-lg" aria-item="cod"></span>
                    </div>
                    <div class="list-group-item">
                        <span class="font-weight-semibold" aria-item="empresa"></span>
                    </div>
                    <div class="list-group-item">
                        <span class="font-weight-semibold">Direccion: </span><span aria-item="direccion"></span>
                    </div>
                    <div class="list-group-item">
                        <span class="font-weight-semibold">Sucursal: </span><span aria-item="sucursal"></span>
                    </div>
                </div>
                <h6 class="font-weight-semibold col-form-label-sm text-primary mt-2">Seguimiento Incidencia</h6>
                <div class="">
                    <ul class="list-group list-group-light" id="content-seguimiento">
                        <!-- <li class="list-group-item">
                            <div class="row">
                                <div style="width: 70px;">
                                    <img class="rounded-circle" style="width: 55px;" src="{{ asset('front/images/auth/user_auth.jpg') }}" alt="Profile image">
                                </div>
                                <div class="col">
                                    <h6 class="mb-2">Nombre usuario</h6>
                                    <p class="mb-1">Registro La Incidencia con codigo</p>
                                    <p class="mb-1"><i class="fab fa-whatsapp"></i> 987456321 / <i class="far fa-envelope"></i> jcauper@gmail.com</p>
                                </div>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link " data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_assign" aria-labelledby="modal_assign" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">Asignar Personal</h6>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-light">
                    <div class="text-end" style="font-size: 1.4rem;">
                        <span class="badge badge-secondary badge-lg" aria-item="cod"></span>
                    </div>
                    <div class="list-group-item">
                        <span class="font-weight-semibold" aria-item="empresa"></span>
                    </div>
                    <div class="list-group-item">
                        <span class="font-weight-semibold">Direccion: </span><span aria-item="direccion"></span>
                    </div>
                    <div class="list-group-item">
                        <span class="font-weight-semibold">Sucursal: </span><span aria-item="sucursal"></span>
                    </div>
                </div>
                <div class="mt-4 p-3 pb-0 fieldset mb-3">
                    <h6 class="legend text-primary">Asignar Personal</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mt-2 mb-3">
                                <span class="input-group-text border-0"><i class="fas fa-chalkboard-user"></i></span>
                                <select class="select-clear" id="selectPersonalAssign">
                                    <option value=""></option>
                                    @foreach ($dataInd['usuarios'] as $u)
                                    <option value="{{$u['value']}}">{{$u['text']}}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-primary px-2" onclick="tecnicoAsigManenger('create', 'selectPersonalAssign', 'content_asig_personalAssign')" data-mdb-ripple-init><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-12" id="content_asig_personalAssign" style="overflow: auto;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init onclick="createAssign()">Registrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_ordens" aria-labelledby="modal_ordens" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">Registrar orden de servicio</h6>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-lg-12 col-xs-12">
                    <form id="fupForm" method="post" enctype="multipart/form-data">

                        <!-- INICIO CABECERA -->
                        <div class="d-flex justify-content-between mb-2">
                            <input type="hidden" name="n_orden" id="n_orden" value="ST24-00000001">
                            <h6><strong>N° Orden: </strong><span>ST24-00000001</span></h6>
                            <h6><strong>Fecha Inicio: </strong><span aria-item="registrado"></span></h6>
                        </div>

                        <!-- TER CABECERA -->
                        <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                            <h6 class="tittle text-primary"> TECNICOS</h6>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                            <div class="list-group list-group-light" aria-item="tecnicos">
                                <!-- <div class="list-group-item">
                                    <span class="">JORGE LUIS HONORES OCAMPO</span>
                                </div> -->
                            </div>
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
                                    <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;" aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                                </div>
                                <div class="list-group-item">
                                    <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;" aria-item="sucursal">E/S INDEPENDENCIA</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                            <h6 class="tittle text-primary"> TRABAJO REALIZADO </h6>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                            <div class="col-md-12">
                                <label class="form-label me-2">Clasificacion Error:</label><span style="font-size: .75rem;" aria-item="error">PROBLEMA DE LECTURA / VALIDACION DE JACKTOOL</span>
                            </div>
                            <div class="row justify-content-md-center">
                                <div class="col-md-6">
                                    <div class="form-group pt-2">
                                        <label class="form-label" for="exampleInputEmail1">Observaciones</label>
                                        <textarea class="form-control" id="obs" name="obs" style="height: 80px;resize: none;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group pt-2">
                                        <label class="form-label" for="exampleInputEmail1">Recomendaciones</label>
                                        <textarea class="form-control" id="rec" name="rec" style="height: 80px;resize: none;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pt-2">
                                        <label class="form-label" for="exampleInputEmail1">Fecha Fin </label>
                                        <input type="text" class="form-control form-control-sm" id="fecha_f" name="fecha_f">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pt-2">
                                        <label class="form-label" for="exampleInputEmail1">Hora Fin </label>
                                        <input type="text" class="form-control form-control-sm" id="hora_f" name="hora_f">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                            <h6 class="tittle text-primary">MATERIALES UTILIZADOS</h2>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="input-group mt-2 mb-3">
                                        <span class="input-group-text border-0"><i class="fas fa-diagram-successor"></i></span>
                                        <select class="select-clear">
                                            <option value=""></option>
                                            @foreach ($dataInd['materiales'] as $m)
                                            <option value="{{$m->id}}">{{$m->producto}}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary px-2" onclick="" data-mdb-ripple-init><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-12" id="content_asig_personal" style="overflow: auto;">
                                    <!-- <table id="tabla1" class="table  table-striped table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>PRODUCTO / MATERIAL</th>
                                                <th>CANTIDAD</th>
                                                <th>MARCA</th>
                                                <th>MODELO</th>
                                                <th>ACCION</th>
                                            </tr>
                                        </thead>
                                    </table> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 my-2 px-4">
                            <div class="row justify-content-between firmas-orden">
                                <div class="col-lg-4 text-center">
                                    <img class="border rounded-1" {{Auth::user()->firma_digital ? 'src=' . asset('front/images/firms/' . Auth::user()->firma_digital) . '' : ''}} id="" alt="" height="130" width="160">
                                    <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma Tecnico</p>
                                    <p>RICARDO CALDERON INGENIEROS SAC</p>
                                    <p>{{Auth::user()->nombres . ' ' . Auth::user()->apellidos}}</p>
                                </div>

                                <div class="col-lg-4 text-center">
                                    <img class="border rounded-1" id="" alt="" height="130" width="160">
                                    <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma Cliente</p>
                                    <p>COESTI S.A.</p>
                                    <p id="doc_clienteFirma" class="doc-fsearch"></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init onclick="">Registrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let cod_incidencia = '<?= $dataInd['cod_inc'] ?>';
    const sucursales = <?php echo json_encode($dataInd['sucursales']); ?>;
    const obj_problem = <?php echo json_encode($dataInd['problema']); ?>;
    const obj_subproblem = <?php echo json_encode($dataInd['subproblema']); ?>;

    document.getElementById('form-incidencias').addEventListener('submit', function(event) {
        event.preventDefault();
        $('#modal_incidencias .modal-dialog .modal-content').append(`<div class="loader-of-modal"><div class="gear"><div><label></label><span></span><span></span><span></span><span></span></div></div></div>`);

        var elementos = this.querySelectorAll('[name]');
        var dataForm = {};

        let cad_require = "";
        elementos.forEach(function(elemento) {
            if (elemento.getAttribute("require") && elemento.value == "") {
                cad_require += `<b>${elemento.getAttribute("require")}</b>, `;
            }
            dataForm[elemento.name] = elemento.value;
        });
        dataForm['personal_asig'] = tecnicoAsigManenger('extract', $('[name="cod_inc"]').val(), 'content_asig_personal');
        console.log(dataForm);
        if (cad_require) {
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
            return boxAlert.box({
                i: 'info',
                t: 'Faltan datos',
                h: `<h6 class="text-secondary">El campo ${cad_require} es requerido.</h6>`
            });
        }

        url = [
            `/soporte/create`, `/soporte/edit/${$('#form-incidencias').attr('idu')}`
        ];
        $.ajax({
            type: 'POST',
            url: __url + url[$('#form-incidencias').attr('frm-accion')],
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': __token,
            },
            data: JSON.stringify(dataForm),
            success: function(data) {
                $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
                if (data.success) {
                    cod_incidencia = data.data.cod_inc;
                    $('#modal_incidencias').modal('hide');
                    boxAlert.minbox({
                        h: data.message
                    });
                    updateTable();
                    return true;
                }
                boxAlert.box('error', '¡Ocurrio un error!', data.message);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                const obj_error = jqXHR.responseJSON;
                boxAlert.box({
                    i: 'error',
                    t: 'Ocurrio un error en el processo',
                    h: obj_error.message
                });
                console.log(obj_error);
                $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
            }
        });
    });

    document.getElementById('doc_clienteFirma').addEventListener('click', async function(event) {
        
        var rect = this.getBoundingClientRect();
        var beforeWidth = 14;
        var beforeHeight = 14;
        var beforeElementRightOffset = 0.65 * parseFloat(getComputedStyle(document.documentElement).fontSize);
        var beforeElementTopOffset = rect.top + (rect.height / 2) - (beforeHeight / 2);

        if (event.clientX >= rect.right - beforeElementRightOffset - beforeWidth &&
            event.clientX <= rect.right - beforeElementRightOffset &&
            event.clientY >= beforeElementTopOffset &&
            event.clientY <= beforeElementTopOffset + beforeHeight) {
            this.innerHTML = "";
            this.classList.add("doc-fsearch");
            this.classList.remove("doc-fclear");
        } else {
            const bodyChildren = Array.from(document.body.children);
            bodyChildren.forEach(child => {
                if (!child.classList.contains('swal2-container')) {
                    child.setAttribute('inert', '');
                }
            });

            Swal.fire({
                title: '<h5 class="text-primary">Buscar cliente</h5>',
                html: `
                    <div class="form-group text-start mb-3">
                        <label class="form-label">Nro. de Documento</label>
                        <div class="input-group">
                            <input type="text" id="docNumber" class="form-control" placeholder="Número de Documento" onchange="search_doc()">
                            <button type="button" class="btn btn-primary px-2" id="btn-conDoc" data-mdb-ripple-init>
                                <span class="spinner-border spinner-border-sm visually-hidden" role="status" aria-hidden="true"></span>
                                <i class="fas fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group text-start">
                        <label class="form-label">Nom del Cliente</label>
                        <input type="text" id="clientName" class="form-control" placeholder="Nombre del Cliente">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                confirmButtonColor: "#3085d6",
                cancelButtonText: 'Cancelar',
                focusConfirm: false,
                didOpen: () => {
                    const docNumberInput = Swal.getPopup().querySelector('#docNumber');
                    if (docNumberInput) docNumberInput.focus();
                },
                willClose: () => {
                    bodyChildren.forEach(child => {
                        if (!child.classList.contains('swal2-container')) child.removeAttribute('inert');
                    });
                },
                preConfirm: () => {
                    const docNumber = Swal.getPopup().querySelector('#docNumber');
                    const clientName = Swal.getPopup().querySelector('#clientName');

                    if (!docNumber.value || !clientName.value) {
                        Swal.showValidationMessage(`Por favor ingresa ambos campos`);
                    }
                    const hideValid = () => { Swal.getPopup().querySelector('.swal2-validation-message').style.display = "none"; }
                    docNumber.addEventListener("focus", hideValid);
                    clientName.addEventListener("focus", hideValid);

                    return { docNumber: docNumber.value, clientName: clientName.value };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const docNumber = result.value.docNumber;
                    const clientName = result.value.clientName;
                    this.innerHTML = `${docNumber} - ${clientName}`;
                    
                    this.classList.remove("doc-fsearch");
                    this.classList.add("doc-fclear");
                }
            });
        }
    });
    

    function search_doc() {
        const docNumberI = Swal.getPopup().querySelector('#docNumber');
        const clientNameI = Swal.getPopup().querySelector('#clientName');
        const conDocB = Swal.getPopup().querySelector('#btn-conDoc');

        $.ajax({
            type: 'GET',
            url: `${__url}/ConsultaDni/${docNumberI.value}`,
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': __token,
            },
            beforeSend: function () {
                conDocB.querySelector('span').classList.remove('visually-hidden');
                conDocB.querySelector('i').classList.add('visually-hidden');
            },
            success: function (data) {
                if (data.success) {
                    clientNameI.value = data.data.complet;
                }
                else {
                    Swal.showValidationMessage(data.message);
                }
                conDocB.querySelector('span').classList.add('visually-hidden');
                conDocB.querySelector('i').classList.remove('visually-hidden');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                const obj_error = jqXHR.responseJSON;
                Swal.showValidationMessage(obj_error.message);
                //boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: obj_error.message });
                console.log(obj_error);
            }
        });
    }
</script>
<script src="{{asset('front/js/app/panel.js')}}"></script>
@endsection