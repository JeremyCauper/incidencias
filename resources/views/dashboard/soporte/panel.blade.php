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
                    <div class="card-body text-primary" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="far fa-clock"></i> Incidencias Registradas</h6>
                        <h4 class="subtitle-count"><b>{{$dataInd['count_panel']['count']}}</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-info" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-user-check"></i> Incidencias Asignadas</h6>
                        <h4 class="subtitle-count"><b>{{$dataInd['count_panel']['inc_a']}}</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-warning" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-business-time"></i> Incidencias En Proceso</h6>
                        <h4 class="subtitle-count"><b>{{$dataInd['count_panel']['inc_p']}}</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-success" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clipboard-check"></i> Incidencias Resueltas</h6>
                        <h4 class="subtitle-count"><b>{{$dataInd['count_panel']['inc_r']}}</b></h4>
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
                <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modal_frm_incidencias">
                    <i class="fas fa-book-medical"></i>
                    Nueva Incidencia
                </button>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_incidencia" class="table text-nowrap w-100">
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
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_frm_incidencias" aria-labelledby="modal_frm_incidencias" aria-hidden="true">
    <form id="form-incidencias" frm-accion="0" idu="">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="position: relative;">
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
                                    <span class="input-group-text border-0" id="search-addon"><i class="fas fa-chalkboard-user"></i></span>
                                    <select class="select-clear" id="selectPersonal" aria-describedby="search-addon">
                                        <option value=""></option>
                                        @foreach ($dataInd['usuarios'] as $u)
                                            <option value="{{$u['value']}}">{{$u['text']}}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary px-2" onclick="tecnicoAsigManenger('create')" data-mdb-ripple-init><i class="fas fa-plus"></i></button>
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
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modal_viewdetalle" aria-labelledby="modal_viewdetalle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title"><i class="fas fa-book-open"></i> Detalle de incidencia</h6> 
            </div>
            <div class="modal-body" style="font-size:11px">
                <table class="table table-xs" width="100%" style="margin-top:-15px">
                    <thead>
                        <tr><th width="8%"></th><th width="30%"></th><th width="35%"></th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3">  <span class="font-weight-semibold" id="Empresa">20345774042 - SERVICENTRO AGUKI SA.</span> </td>
                        </tr>
                        <tr>
                            <td colspan="2"><span class="font-weight-semibold">Direccion:</span><span id="direccion"> AV. ELMER FAUCETT NRO. 5482 (ULTIMA CDRA. AV. ELMER FAUCETT)</span></td>
                            <td><span class="font-weight-semibold">Sucursal:</span><span id="sucursal"> E/S PRINCIPAL</span> </td>
                        </tr>
                    </tbody>
                </table>
                <h6 class="font-weight-semibold col-form-label-sm text-primary">Seguimiento Incidencia</h6>
                <div class="">
                    <ul class="list-group list-group-light">
                        <li class="list-group-item">
                            <div class="row">
                                <div style="width: 70px;">
                                    <img class="rounded-circle" style="width: 55px;" src="http://localhost/incidencias/public/front/images/auth/user_auth.jpg" alt="Profile image">
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
                                    <img class="rounded-circle" style="width: 55px;" src="http://localhost/incidencias/public/front/images/auth/user_auth.jpg" alt="Profile image">
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
                                    <img class="rounded-circle" style="width: 55px;" src="http://localhost/incidencias/public/front/images/auth/user_auth.jpg" alt="Profile image">
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
                <h6 class="modal-title">Asignar Personal <span class="badge badge-success " id="tipo">INC-00000001</span></h6> 
            </div>
            <form id="frmdatos">
            <div class="card">
                <div class="card-title">
                  <h2><i class="fa fa-globe"></i> Incidencia
                    <small> Informada: 2024-07-31 11:15:50</small>
                  </h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>

                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="card-body">

                  <section class="content invoice">
                    <form id="frm_datos" name="frm_datos">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">

                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- info row -->
                      <div class="row invoice-info">
                        <input type="hidden" name="id_inci" id="id_inci" value="18128">
                        <div class="col-sm-4 invoice-col">
                          Informado

                          <address>
                            <strong>Empresa : 20127765279 - COESTI S.A.</strong>
                            <br>Sucursal : -
                            <br>Contacto :                             <br>Telefono :                             <br>Correo :                           </address>
                        </div>
                        <!-- /.col -->
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                          Problema
                          <address>
                            <strong></strong>
                          </address>
                        </div>
                        <div class="col-sm-4 invoice-col">
                          <p id="datos_asignacion"></p>
                          <div class="alert alert-danger" id="alerta_soporte" style="display: none;">
                            Debe Seleccionar El Tipo de Soporte!
                          </div>
                          <div class="alert alert-danger" id="alerta_personal" style="display: none;">
                            Debe Ingresar al menos un Personal!
                          </div>
                          <div class="alert alert-success" id="alerta_asignacion" style="display: none;">
                            Asignacion Existosa!
                          </div>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <!-- Table row -->
                      <div class="row ">
                        <!-- /.col -->
                        <div class="col-md-6">
                          <div class="col-md-3">
                            <label for=""> Tipo Soporte </label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="col-md-3">
                            <label for="personal">Personal</label>
                          </div>
                          <div class="col-md-7">
                            <input type="hidden" id="d_personal" class="form-control" value="">
                            <input type="text" id="n_personal" class="form-control" onchange="borrar()" autocomplete="off">
                          </div>
                          <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-primary" id="btn_per" onclick="llenar_tabla();"> <i class="fa fa-plus"></i> </button>
                          </div>
                        </div>
                        <div class="col-md-12 col-sm-12 table">
                          <br>
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Dni / Identificación</th>
                                <th>Nombres y Apellidos</th>
                                <th>Eliminar</th>
                              </tr>
                            </thead>
                            <tbody id="listado_per">

                            <tr><td> <input type="hidden" name="id_pers[]" id="id_pers[]" value="39"> 72050072</td><td>JOSE ALBERTO CABANILLAS RAMOS</td><td> <button type="button" class="btn btn-sm btn-danger borrar" title="Eliminar" onclick="borrar2(72050072)"> <i class="fa fa-trash"> </i> </button> </td></tr></tbody>
                          </table>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="col-md-12">
                            <label for="sad">Comentario :</label>
                          </div>
                          <div class="col-md-12 col-sm-12 col-xs-12">
                            <textarea class="form-control" name="comentario" id="comentario" cols="3"></textarea>
                            <br>
                          </div>
                        </div>
                      </div>

                      <div class="row no-print">
                        <div class="col-sm-12">
                          <button type="button" class="btn btn-success pull-right" id="btn_g" onclick="update_asignacion();"><i class="fa fa-edit"></i> Actualizar Asignación</button>
                        </div>
                      </div>
                    </form>
                  </section>
                </div>
              </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
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
        $('#form-incidencias .modal-dialog .modal-content').append(`<div class="loader-of-modal" style="position: absolute;height: 100%;width: 100%;z-index: 999;background: #dadada60;border-radius: inherit;align-content: center;"><div class="loader"></div></div>`);

        var elementos = this.querySelectorAll('[name]');
        var dataForm = {};

        let cad_require = "";
        elementos.forEach(function (elemento) {
            if (elemento.getAttribute("require") && elemento.value == "") {
                cad_require += `<b>${elemento.getAttribute("require")}</b>, `;
            }
            dataForm[elemento.name] = elemento.value;
        });
        dataForm['personal_asig'] = tecnicoAsigManenger('extract');
        console.log(dataForm);
        if (cad_require) {
            $('#form-incidencias .modal-dialog .modal-content .loader-of-modal').remove();
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
                $('#form-incidencias .modal-dialog .modal-content .loader-of-modal').remove();
                if (data.success) {
                    cod_incidencia = data.data.cod_inc;
                    $('#modal_frm_incidencias').modal('hide');
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
                $('#form-incidencias .modal-dialog .modal-content .loader-of-modal').remove();
            }
        });
    });

</script>
<script src="{{asset('front/js/app/panel.js')}}"></script>
@endsection