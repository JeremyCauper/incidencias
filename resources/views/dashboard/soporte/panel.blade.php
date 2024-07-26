@extends('layout.app')
@section('title', 'Panel de Control')

@section('style')
<link rel="stylesheet" href="{{asset('front/css/app/panel.css')}}">
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-12 grid-margin">               
                <div class="card">
                    <div class="card-body text-primary" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="far fa-clock"></i> Incidencias Registradas</h6>
                        <h4 class="subtitle-count"><b>17944</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin">
                <div class="card">
                    <div class="card-body text-info" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-user-check"></i> Incidencias Asignadas</h6>
                        <h4 class="subtitle-count"><b>6</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin">
                <div class="card">
                    <div class="card-body text-warning" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-business-time"></i> Incidencias En Proceso</h6>
                        <h4 class="subtitle-count"><b>5</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin">
                <div class="card">
                    <div class="card-body text-success" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clipboard-check"></i> Incidencias Resueltas</h6>
                        <h4 class="subtitle-count"><b>16708</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin">
                <div class="card">
                    <a class="card-body text-secondary" href="{{url('/soport-empresa/empresas')}}" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-database"></i> Clientes Registrados</h6>
                        <h4 class="subtitle-count"><b>{{$resumenInc['cEmpresa']}}</b></h4>
                    </a>
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
                    <table id="tb_incidencia" class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Empresa</th>
                                <th>Sucursal</th>
                                <th>Contacto</th>
                                <th>Registrado</th>
                                <th>Tecnico</th>
                                <th>Estacion</th>
                                <th>Atencion</th>
                                <th>Informe</th>
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
    <form id="form-incidencias">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="position: relative;">
                <div class="modal-body">
                    <h6 class="card-title mb-4 text-primary"><b>CREAR NUEVA INCIDENCIA -</b><b class="ms-2" id="cod_inc">{{$resumenInc['cod_inc']}}</b></h6>
                    <input type="text" class="d-none" name="cod_inc" value="{{$resumenInc['cod_inc']}}">
                    <div class="col-12 mb-2">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
                    </div>
                    <div class="mt-4 p-3 rounded-3 fieldset mb-3 shadow-1-strong">
                        <h6 class="legend text-primary">Datos Empresa</h6>
                        <div class="row">
                            <div class="col-lg-8 mb-1">
                                <label class="form-label mb-0" for="id_empresa"><b>Empresa <span class="text-danger">*</span></b></label>
                                <select id="id_empresa" class="select-clear" name="id_empresa" require="Area">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($resumenInc['empresas'] as $e)
                                        <option value="{{$e['id']}}" select-ruc="{{$e['ruc']}}">{{$e['empresa']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-1">
                                <label class="form-label mb-0" for="id_sucursal"><b>Sucursal <span class="text-danger">*</span></b></label>
                                <select id="id_sucursal" class="select" name="id_sucursal" require="Area" disabled="true">
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 rounded-3 fieldset mb-3 shadow-1-strong">
                        <h6 class="legend text-primary">Datos Contacto</h6>
                        <div class="row">
                            <div class="col-lg-3 col-7 mb-1">
                                <label class="form-label mb-0" for="car_contac"><b>Cargo <span class="text-danger">*</span></b></label>
                                <select id="car_contac" class="select-clear" name="car_contac" require="Cargo">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($resumenInc['cargo_contaco'] as $cc)
                                        <option value="{{$cc->descripcion}}">{{$cc->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-5 mb-1">
                                <label class="form-label mb-0" for="tel_contac"><b>Telefono</b></label>
                                <input type="text" class="form-control" id="tel_contac" name="tel_contac">
                            </div>
                            <div class="col-lg-3 col-5 mb-1">
                                <label class="form-label mb-0" for="nom_contac"><b>Nombre</b></label>
                                <input type="text" class="form-control" id="nom_contac" name="nom_contac">
                            </div>
                            <div class="col-lg-4 col-7 mb-1">
                                <label class="form-label mb-0" for="cor_contac"><b>Correo</b></label>
                                <input type="text" class="form-control" id="cor_contac" name="cor_contac">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 rounded-3 fieldset mb-3 shadow-1-strong">
                        <h6 class="legend text-primary">Datos Incidencia</h6>
                        <div class="row">
                            <div class="col-sm-4 mb-1">
                                <label class="form-label mb-0" for="tipe_inc"><b>Tipo Estación <span class="text-danger">*</span></b></label>
                                <select class="select-clear" id="tipe_inc" name="tipe_inc">
                                    <option value="">-- Seleccione --</option>
                                    <option value="GNV">GNV</option>
                                    <option value="GLP Y LIQUIDOS">GLP Y LIQUIDOS</option>
                                    <option value="GNC">GNC</option>
                                    <option value="OFICINA">OFICINA</option>
                                </select>
                            </div>
                            <div class="col-sm-4 col-6 mb-1">
                                <label class="form-label mb-0" for="priori_inc"><b>Prioridad <span class="text-danger">*</span></b></label>
                                <select class="select" id="priori_inc" name="priori_inc" require="Prioridad">
                                    <option value="Alta">Alta</option>
                                    <option value="Media">Media</option>
                                    <option value="Baja">Baja</option>
                                    <option value="Critica">Critica</option>
                                </select>
                            </div>
                            <div class="col-sm-4 col-6 mb-1">
                                <label class="form-label mb-0" for="soport_inc"><b>Soporte <span class="text-danger">*</span></b></label>
                                <select class="select" id="soport_inc" name="soport_inc" require="Soporte">
                                    <option value="Remoto">Remoto</option>
                                    <option value="Presencial">Presencial</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-1">
                            <label class="form-label mb-0" for="tipo_acceso"><b>Observacion</b></label>
                            <textarea class="form-control" id="observasion" name="observasion" style="height: 70px;resize: none;"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 rounded-3 fieldset mb-3 shadow-1-strong">
                        <h6 class="legend text-primary">Asignar Personal</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mt-2 mb-1">
                                    <span class="input-group-text border-0" id="search-addon"><i class="fas fa-chalkboard-user"></i></span>
                                    <select class="select-clear" id="selectPersonal" aria-describedby="search-addon">
                                        <option value=""></option>
                                        @foreach ($resumenInc['usuarios'] as $u)
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
@endsection

@section('scripts')
<script>
    const sucursales = <?php echo json_encode($resumenInc['sucursales']); ?>;

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
        // if (cad_require) {
        //     $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
        //     return boxAlert.box('info', 'Faltan datos', `<h6 class="text-secondary">El campo ${cad_require} es requerido.</h6>`);
        // }

        // url = [
        //     `/register`, `/editusu/${$('#form-usuario').attr('idu')}`
        // ];
        // $.ajax({
        //     type: 'POST',
        //     url: __url + url[$('#form-usuario').attr('frm-accion')],
        //     contentType: 'application/json',
        //     headers: {
        //         'X-CSRF-TOKEN': __token,
        //     },
        //     data: JSON.stringify(dataForm),
        //     success: function (response) {
        //         boxAlert.minbox('success', response.message, { background: "#3b71ca", color: "#ffffff" }, "top");
        //         updateTable();
        //         $('[data-mdb-dismiss="modal"]').click();
        //         console.log(response);
        //         $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
        //     },
        //     error: function (jqXHR, textStatus, errorThrown) {
        //         boxAlert.box('error', '¡Ocurrio un error!', 'Error al registrar el usuario');
        //         console.log(jqXHR.responseJSON);
        //         $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
        //     }
        // });
    });

</script>
<script src="{{asset('front/js/app/panel.js')}}"></script>
@endsection