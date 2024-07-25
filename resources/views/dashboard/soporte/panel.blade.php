@extends('layout.app')
@section('title', 'Panel de Control')

@section('style')
    <style>
        .title-count {
            font-weight: bold;
            font-size: .8rem;
        }

        .subtitle-count {
            margin-bottom: 0px;
        }

        .grid-margin .card .card-body {
            cursor: pointer !important;
        }
    </style>
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
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="card-title mb-4 text-primary"><b>CREAR NUEVA INCIDENCIA</b></h6>
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
                                    <option value="Jefe de Playa"> Jefe de Playa</option>
                                    <option value="Islero"> Islero</option>
                                    <option value="Jefe de Planta"> Jefe de Planta</option>
                                    <option value="Administrador(a)"> Administrador(a)</option>
                                    <option value="Supervisor"> Supervisor</option>
                                    <option value="Contadora"> Contadora</option>
                                    <option value="Asistente Contable"> Asistente Contable</option>
                                    <option value="Encargado"> Encargado</option>
                                    <option value="Cajero"> Cajero</option>
                                    <option value="Jefe de Sistemas"> Jefe de Sistemas</option>
                                    <option value="Asistente de Sistemas"> Asistente de Sistemas</option>
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
                                    <option value="1">Alta</option>
                                    <option value="2">Media</option>
                                    <option value="3">Baja</option>
                                    <option value="4">Critica</option>
                                </select>
                            </div>
                            <div class="col-sm-4 col-6 mb-1">
                                <label class="form-label mb-0" for="soport_inc"><b>Soporte <span class="text-danger">*</span></b></label>
                                <select class="select" id="soport_inc" name="soport_inc" require="Soporte">
                                    <option value="1">Remoto</option>
                                    <option value="2">Presencial</option>
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
        $(document).ready(function() {
            $('#id_empresa').on('change', function() {
                const sucursales = <?php echo json_encode($resumenInc['sucursales']); ?>;
                $('#id_sucursal').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
                if (!$(this).val()) return false;
                console.log($(this));
                var option = $('#id_empresa option[value="' + $(this).val() + '"]').attr('select-ruc');
                sucursales[option].forEach(s => {
                    $('#id_sucursal').append($('<option>').val(s.id).html(s.sucursal));
                });
                $('#id_sucursal').attr('disabled', false);
            });
        });

        const tb_incidencia = new DataTable('#tb_incidencia', {
            scrollX: true,
            scrollY: 300,
            ajax: {
                url: "{{ url('/DataTableUser') }}",
                dataSrc: "",
                error: function(xhr, error, thrown) {
                    boxAlert.box('error', 'Ocurrio un error', 'Error en la solicitud Ajax: ' + error);
                    console.log('Respuesta del servidor:', xhr);
                }
            },
            columns: [
                { data: 'ndoc_usuario' },
                { data: 'nombres', render: function (data, type, row) {
                        return `${row.nombres} ${row.apellidos}`;
                    }
                },
                { data: 'descripcion' },
                { data: 'usuario' },
                { data: 'pass_view' },
                { data: 'estatus' },
                { data: 'id_usuario' }
            ],
            processing: true
        });

        function updateTable() {
            tb_usuario.ajax.reload();
        }

        function tecnicoAsigManenger(accion, row) {
            switch (accion) {
                case 'create':
                    const personal = $('#selectPersonal').val();
                    if (!personal)
                        return false;
                    if (!$(`#content_asig_personal table`).length) {
                        const tabla = $('<table>', { class: 'table w-100 text-nowrap' });
                        const thead = $('<thead>').html($('<tr>').html('<th>#</th><th>Nro. Documento</th><th>Nombres y Apellidos</th><th>Acciones</th>'));
                        $('#content_asig_personal').html(tabla.append(thead).append($('<tbody>')));
                    }
                    const obj = personal.split('|');
                    const tr = $('<tr>', { 'aria-row': `reg${obj[0]}` }).html(`<td>${obj[0]}</td><td>${obj[1]}</td><td>${obj[2]}</td><td><button type="button" class="btn btn-danger btn-sm px-2"  onclick="tecnicoAsigManenger('delete', 'reg${obj[0]}')"><i class="far fa-trash-can"></i></button></td>`);

                    if ($(`#content_asig_personal table tbody tr[aria-row="reg${obj[0]}"]`).length)
                        return boxAlert.minbox('info', '<h6 class="mb-0" style="font-size:.75rem">NO PUEDO INGRESAR EL MISMO PERSONAL DOS VECES</h6>', {background:"#628acc", color:"#ffffff"}, "top");
                    $(`#content_asig_personal table tbody`).append(tr);
                    $('#selectPersonal').val('').trigger('change.select2');
                    break;

                case 'delete':
                    $(`#content_asig_personal table tbody tr[aria-row="${row}"]`).remove();
                    if (!$(`#content_asig_personal table tbody tr`).length) {
                        $('#content_asig_personal table').remove();
                    }
                    break;
            }
        }
    </script>
@endsection