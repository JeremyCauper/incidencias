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

    #tb_incidencia thead, #tb_incidencia tbody {
        font-size: 12px !important;
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
            <div class="row">
                <div class="col-12">
                    <table id="tb_incidencia" class="table table-hover text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Empresa</th>
                                <th>Sucursal</th>
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
                        <span class="badge badge-warning badge-lg" aria-item="cod">INC-00000001</span>
                    </div>
                    <div class="list-group-item">
                        <span class="font-weight-semibold" aria-item="empresa">20345774042 - SERVICENTRO AGUKI SA.</span>
                    </div>
                    <div class="list-group-item">
                        <span class="font-weight-semibold">Direccion:</span><span aria-item="direccion"> AV. ELMER FAUCETT NRO. 5482 (ULTIMA CDRA. AV. ELMER FAUCETT)</span>
                    </div>
                    <div class="list-group-item">
                        <span class="font-weight-semibold">Sucursal:</span><span aria-item="sucursal"> E/S PRINCIPAL</span>
                    </div>
                </div>
                <h6 class="font-weight-semibold col-form-label-sm text-primary mt-2">Seguimiento Incidencia</h6>
                <div class="">
                    <ul class="list-group list-group-light">
                        <li class="list-group-item">
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
                        </li>
                        <li class="list-group-item">
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
                        </li>
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
                        <span class="badge badge-warning badge-lg" aria-item="cod"></span>
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
@endsection

@section('scripts')
<script>
    let cod_incidencia = '<?= $dataInd['cod_inc'] ?>';
    const sucursales = <?php echo json_encode($dataInd['sucursales']); ?>;
    const obj_problem = <?php echo json_encode($dataInd['problema']); ?>;
    const obj_subproblem = <?php echo json_encode($dataInd['subproblema']); ?>;

    document.getElementById('form-incidencias').addEventListener('submit', function (event) {
        event.preventDefault();
        $('#modal_incidencias .modal-dialog .modal-content').append(`<div class="loader-of-modal" style="position: absolute;height: 100%;width: 100%;z-index: 999;background: #dadada60;border-radius: inherit;align-content: center;"><div class="loader-rc"></div></div>`);

        var elementos = this.querySelectorAll('[name]');
        var dataForm = {};

        let cad_require = "";
        elementos.forEach(function (elemento) {
            if (elemento.getAttribute("require") && elemento.value == "") {
                cad_require += `<b>${elemento.getAttribute("require")}</b>, `;
            }
            dataForm[elemento.name] = elemento.value;
        });
        dataForm['personal_asig'] = tecnicoAsigManenger('extract', $('[name="cod_inc"]').val(), 'content_asig_personal');
        console.log(dataForm);
        if (cad_require) {
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
            return boxAlert.box('info', 'Faltan datos', `<h6 class="text-secondary">El campo ${cad_require} es requerido.</h6>`);
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
            success: function (data) {
                $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
                if (data.success) {
                    cod_incidencia = data.data.cod_inc;
                    $('#modal_incidencias').modal('hide');
                    boxAlert.minbox('success', data.message, { background: "#3b71ca", color: "#ffffff" }, "top");
                    updateTable();
                    return true;
                }
                boxAlert.box('error', '¡Ocurrio un error!', data.message);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                const obj_error = jqXHR.responseJSON;
                boxAlert.box('error', '¡Ocurrio un error!', obj_error.message);
                console.log(obj_error);
                $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
            }
        });
    });

</script>
<script src="{{asset('front/js/app/panel.js')}}"></script>
@endsection