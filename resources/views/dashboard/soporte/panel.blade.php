@extends('layout.app')
@section('title', 'Panel de Control')

@section('style')
<link rel="stylesheet" href="{{asset('front/css/app/panel.css')}}">
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

<div class="modal fade" id="modal_incidencias" aria-labelledby="modal_incidencias" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="position: relative;">
            <form id="form-incidencias">
                <input type="hidden" name="id_inc" id="id_inc">
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
                                <label class="form-label mb-0" for="id_empresa">Empresa <span class="text-danger">*</span></label>
                                <select id="id_empresa" class="select-clear" name="id_empresa" require="Empresa">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['empresas'] as $e)
                                    <option value="{{$e['id']}}" select-ruc="{{$e['ruc']}}">{{$e['empresa']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label mb-0" for="id_sucursal">Sucursal <span class="text-danger">*</span></label>
                                <select id="id_sucursal" class="select" name="id_sucursal" require="Sucursal" disabled="true">
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 pb-0 fieldset mb-3">
                        <h6 class="legend text-primary">Datos Contacto</h6>
                        <input type="hidden" name="cod_contact" id="cod_contact">
                        <div class="row">
                            <div class="col-lg-4 col-6 mb-3">
                                <label class="form-label mb-0" for="tel_contac">Telefono</label>
                                <input type="text" class="form-control" id="tel_contac" name="tel_contac">
                            </div>
                            <div class="col-lg-4 col-6 mb-3">
                                <label class="form-label mb-0" for="nro_doc">Dni</label>
                                <input type="text" class="form-control" id="nro_doc" name="nro_doc">
                            </div>
                            <div class="col-lg-4 col-12 mb-3">
                                <label class="form-label mb-0" for="nom_contac">Nombre</label>
                                <input type="text" class="form-control" id="nom_contac" name="nom_contac">
                            </div>
                            <div class="col-lg-6 col-5 mb-3">
                                <label class="form-label mb-0" for="car_contac">Cargo</label>
                                <select id="car_contac" class="select-clear" name="car_contac">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['cargo_contaco'] as $cc)
                                    <option value="{{$cc->id}}">{{$cc->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-7 mb-3">
                                <label class="form-label mb-0" for="cor_contac">Correo</label>
                                <input type="text" class="form-control" id="cor_contac" name="cor_contac">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 pb-0 fieldset mb-3">
                        <h6 class="legend text-primary">Datos Incidencia</h6>
                        <div class="row">
                            <div class="col-lg-4 col-7 mb-3">
                                <label class="form-label mb-0" for="tip_estacion">Tipo Estación <span class="text-danger">*</span></label>
                                <select class="select-clear" id="tip_estacion" name="tip_estacion" require="Tipo Estación">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['tipo_estacion'] as $v)
                                    <option value="{{$v->id}}">{{$v->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-5 mb-3">
                                <label class="form-label mb-0" for="priori_inc">Prioridad <span class="text-danger">*</span></label>
                                <select class="select" id="priori_inc" name="priori_inc" require="Prioridad">
                                    <option value="Alta">Alta</option>
                                    <option value="Media">Media</option>
                                    <option value="Baja">Baja</option>
                                    <option value="Critica">Critica</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-7 mb-3">
                                <label class="form-label mb-0" for="tip_soport">Tipo Soporte <span class="text-danger">*</span></label>
                                <select class="select" id="tip_soport" name="tip_soport" require="Tipo Soporte">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['tipo_soporte'] as $v)
                                    <option value="{{$v->id}}">{{$v->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-5 mb-3">
                                <label class="form-label mb-0" for="tip_incidencia">Tipo Incidencia <span class="text-danger">*</span></label>
                                <select class="select" id="tip_incidencia" name="tip_incidencia" require="Tipo Incidencia">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($dataInd['tipo_incidencia'] as $v)
                                    <option value="{{$v->id}}">{{$v->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0" for="inc_problem">Problema <span class="text-danger">*</span></label>
                                <select class="select-clear" id="inc_problem" name="inc_problem" require="Problema" disabled>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0" for="inc_subproblem">Sub Problema <span class="text-danger">*</span></label>
                                <select class="select-clear" id="inc_subproblem" name="inc_subproblem" require="Sub Problema" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-0" for="fecha_imforme">Fecha de Informe <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha_imforme" name="fecha_imforme" require="Fecha de Informe">
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label mb-0" for="hora_informe">Hora de Informe <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="hora_informe" name="hora_informe" min="00:00" max="23:59" step="1">
                                </div>
                            </div>
                            <div class="col-sm-8 mb-3">
                                <label class="form-label mb-0" for="tipo_acceso">Observacion</label>
                                <textarea class="form-control" id="observasion" name="observasion" style="height: 106px;resize: none;"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 pb-0 fieldset mb-3" id="contenedor-personal">
                        <h6 class="legend text-primary">Asignar Personal</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mt-2 mb-3">
                                    <span class="input-group-text border-0"><i class="fas fa-chalkboard-user"></i></span>
                                    <select class="select-clear">
                                        <option value=""></option>
                                        @foreach ($dataInd['usuarios'] as $u)
                                        <option value="{{$u['value']}}" data-value="{{$u['dValue']}}">{{$u['text']}}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary px-2" id="createPersonal" data-mdb-ripple-init><i class="fas fa-plus" style="pointer-events: none;"></i></button>
                                </div>
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
                        <div class="col-md-9">
                            <div class="input-group mt-2 mb-3">
                                <span class="input-group-text border-0"><i class="fas fa-chalkboard-user"></i></span>
                                <select class="select-clear">
                                    <option value=""></option>
                                    @foreach ($dataInd['usuarios'] as $u)
                                    <option value="{{$u['value']}}" data-value="{{$u['dValue']}}">{{$u['text']}}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-primary px-2" id="createPersonal1" data-mdb-ripple-init><i class="fas fa-plus" style="pointer-events: none;"></i></button>
                            </div>
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

<div class="modal fade" id="modal_ordens" aria-labelledby="modal_ordens"  tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="form-ordenes">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Registrar orden de servicio</h6>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-lg-12 col-xs-12">
                            <!-- INICIO CABECERA -->
                            <div class="text-end mb-2 cabecera-orden">
                                <h6><label>Fecha Inicio: </label><span aria-item="registrado"></span></h6>
                            </div>
                            <div class="mb-2 cabecera-orden">
                                <input type="hidden" name="codInc" id="codInc">
                                <h6>
                                    <label>N° Orden *: </label>
                                    <span><input class="form-control form-control-sm" style="display: inline; width:auto; max-width: 130px;" type="text" name="n_orden" id="n_orden" require="N° Orden"></span>
                                    <span>
                                        <input type="checkbox" name="check_cod" id="check_cod" style="display: inline; width:auto" onchange="setChangeCodOrden(this)">
                                        <label for="check_cod">Cod. del Sistema</label>
                                    </span>
                                </h6>
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
                                        <div class="row col-12">
                                            <div class="col-sm-6">
                                                <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;" aria-item="sucursal">E/S INDEPENDENCIA</span>
                                            </div>
                                            <div class="col-sm-6 text-end">
                                                <label class="form-label me-2">Atencion: </label><span style="font-size: .75rem;" aria-item="atencion">Remoto</span>
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
                                    <label class="form-label me-2">Clasificacion Error:</label><span style="font-size: .75rem;" aria-item="error">PROBLEMA DE LECTURA / VALIDACION DE JACKTOOL</span>
                                </div>
                                <div class="row justify-content-md-center">
                                    <div class="col-md-6">
                                        <div class="form-group pt-2">
                                            <label class="form-label" for="exampleInputEmail1">Observaciones *</label>
                                            <textarea class="form-control" id="obs" name="obs" style="height: 80px;resize: none;" require="Observaciones"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group pt-2">
                                            <label class="form-label" for="exampleInputEmail1">Recomendaciones *</label>
                                            <textarea class="form-control" id="rec" name="rec" style="height: 80px;resize: none;" require="Recomendaciones"></textarea>
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

                            <div class="col-md-12 col-sm-12 col-xs-12 my-2 pb-2">
                                <div class="row" id="content-material">
                                    <div class="col-lg-12">
                                        <div class="input-group mt-2 mb-3">
                                            <span class="input-group-text border-0"><i class="fas fa-diagram-successor"></i></span>
                                            <select class="select-clear" id="selector-material">
                                                <option value=""></option>
                                                @foreach ($dataInd['materiales'] as $m)
                                                <option value="{{$m['value']}}" data-value="{{$m['dValue']}}">{{$m['text']}}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group mx-2 disabled" id="content-cantidad" style="width: auto;">
                                                <button type="button" onclick="manCantidad('minus')" class="btn btn-primary px-2" data-mdb-ripple-init><i class="fas fa-minus" style="font-size: .75rem;"></i></button>
                                                <input type="number" class="form-control" style="width: 80px; flex: none;" value="0" input-cantidad="" oninput="manCantidad('press')">
                                                <button type="button" onclick="manCantidad('plus')" class="btn btn-primary px-2" data-mdb-ripple-init><i class="fas fa-plus" style="font-size: .75rem;"></i></button>
                                            </div>
                                            <button type="button" class="btn btn-primary px-2" id="createMaterial" data-mdb-ripple-init><i class="fas fa-plus" style="pointer-events: none;"></i></button>
                                        </div>
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
                                        <div class="text-center content-image">
                                            <div class="overlay">
                                                <button class="btn-img removeImgButton" style="display: none;" id="removeImgFirma" type="button" button-reset><i class="fas fa-xmark"></i></button>
                                                <button class="btn-img mx-1 uploadImgButton" id="uploadImgFirma" type="button"><i class="fas fa-arrow-up-from-bracket"></i></button>
                                                <button class="btn-img mx-1 uploadImgButton" id="createFirma" type="button"><i class="fas fa-pencil"></i></button>
                                                <button class="btn-img expandImgButton" type="button" onclick="PreviImagenes(PreviFirma.src);"><i class="fas fa-expand"></i></button>
                                            </div>
                                            <input type="file" class="d-none" id="firma_digital">
                                            <input type="text" class="d-none" name="firma_digital" id="textFirmaDigital">
                                            <img id="PreviFirma" class="visually-hidden" height="130" width="160">
                                        </div>
                                        <!-- <img class="border rounded-1" id="" alt="" > -->
                                        <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma Cliente</p>
                                        <p>COESTI S.A.</p>
                                        <p id="doc_clienteFirma" class="doc-fsearch"></p>
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
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('front/vendor/signature/signature_pad.min.js')}}"></script>
<script src="{{asset('front/js/FormMananger.js')}}"></script>
<script>
    let cod_incidencia = '<?= $dataInd['cod_inc'] ?>';
    let cod_ordenSer = '<?= $dataInd['cod_ordenS'] ?>';
    const sucursales = <?php echo json_encode($dataInd['sucursales']); ?>;
    const obj_problem = <?php echo json_encode($dataInd['problema']); ?>;
    const obj_subproblem = <?php echo json_encode($dataInd['subproblema']); ?>;
</script>
<script src="{{asset('front/js/app/panel.js')}}"></script>
@endsection