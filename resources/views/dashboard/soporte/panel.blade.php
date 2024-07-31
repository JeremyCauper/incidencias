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
                <div class="modal-body">
                    <h6 class="card-title mb-4 text-primary"><b>CREAR NUEVA INCIDENCIA -</b><b class="ms-2" id="cod_inc_text">{{$dataInd['cod_inc']}}</b></h6>
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
                <h7 class="modal-title">Detalle del Comprobante  :  <span class="label form-control-sm" id="recibo">B001-00049917</span>  <span class="badge badge-success " id="tipo">BOLETA - EXO.</span>  </h7>
                    
            </div>
            <center id="imgCargando" style="display: none;">
                <img src="http://cpe.apufact.com/portal/public/img/sistema/cargando.gif" width="80px" style="z-index:3;" class="pt-4"> <label class="pt-4">  Consultando Comprobante... </label>
            </center>
            <form id="frmdatos" style=""> 
                <div class="modal-body" style="font-size:11px">
                    <table class="table table-xs" width="100%" style="margin-top:-15px">
                        <thead>
                          <tr>
                            <th width="8%"></th><th width="30%"></th><th width="35%"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="3">  <span class="font-weight-semibold" id="Empresa">20605809686 - GRIFOS ESSA PUCALLPA S.A.C.</span> </td>
                          </tr>
                          <tr>
                            <td colspan="2"> <span id="direccion" class="">Direccion : CARRETERA FEDERICO BASADRE NRO. 298 UCAYALI PADRE ABAD PADRE ABAD</span> </td>
                            <td> <span id="sucursal" class="">Sucursal : E/S PRINCIPAL</span> </td>
                          </tr>
                          <tr>
                            <td><span class="font-weight-semibold">Cliente : </span></td>
                            <td id="cliente">00000000 - Clientes  Varios</td>
                            <td><span class="font-weight-semibold">Emitido :</span> <span id="emitido" class="font-weight-semibold col-form-label-sm">2024-07-31 14:29:00</span> </td>
                          </tr>
                          <tr>
                            <td><span class="font-weight-semibold ">Direccion :</span></td>
                            <td id="dircli" class=" text-left">-</td>
                            <td><span class="font-weight-semibold ">Moneda :</span> <span id="moneda" class="font-weight-semibold col-form-label-sm">SOLES</span> </td>
                          </tr>
                          <tr id="DatosRefNc" style="display:none">
                            <td><span class="font-weight-semibold">Doc. Ref. :</span></td>
                            <td id="docref" class="text-left"></td>
                            <td> <span class="font-weight-semibold">Motivo :</span> <span id="motivo" class="font-weight-semibold col-form-label-sm"></span>  </td>
                          </tr>
                        </tbody>
                    </table>
                    <div class="table-responsive table-scrollable">
                        <tfooter class="">
                              </tfooter><table class="table table-xs" width="100%">
                            <thead class="bg-primary text-white text-center">
                              <tr class="">
                                <th width="10%">Codigo</th><th width="60%">Descripcion</th><th width="10%">Cantidad</th><th width="10%">Precio</th><th width="10%">Importe</th>
                              </tr>
                            </thead>
                            <tbody id="ItemsT" class=""><tr><td>2</td><td>DIESEL B5</td><td class="text-right">22.69</td><td class="text-right">12.99</td><td class="text-right">294.78</td></tr></tbody>
                            <tbody><tr>
                                <td colspan="3" id="leyenda" class="text-left">SON : DOSCIENTOS NOVENTA Y CUATRO CON 78/100 SOLES</td>
                                <td class="">  SubTotal </td>
                                <td id="subtot" class="text-right">0.00</td>
                              </tr>
                              <tr>
                                <td colspan="3"></td>
                                <td class="">  Igv </td>
                                <td id="igv" class="text-right">0.00</td>
                              </tr>
                              <tr>
                                <td colspan="3"></td>
                                <td class="">  Descuento </td>
                                <td id="dsct" class="text-right">0.00</td>
                              </tr>
                              <tr>
                                <td colspan="3"> </td>
                                <td class="">  Total </td>
                                <td id="total" class="text-right">294.78</td>
                              </tr>
                            
                        </tbody></table>
                    </div>
                    <h6 class="font-weight-semibold col-form-label-sm">Seguimiento Sunat </h6>
                    <table class="table table-xs text-center" width="100%">
                        <thead class="bg-primary text-white">
                            <tr class="">
                            <th width="35%">Recepcionado</th>
                            <th width="35%">Enviado</th>
                            <th width="30%">Codigo Sunat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="">
                            <td id="recepcionado">2024-07-31 14:29:49</td>
                            <td id="enviado">2024-07-31 14:29:55</td>
                            <td id="estadosnt">0</td>
                            </tr>
                            <tr class="">
                            <td colspan="3" id="rsptsunat">La Boleta de Venta numero B001-49917, ha sido aceptado</td>
                            </tr>
                            <tr>
                            <td colspan="3" id="docbaja"></td>
                            </tr>
                            
                        </tbody>
                    </table>
                      
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
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