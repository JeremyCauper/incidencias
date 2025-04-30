@extends('layout.app')
@section('title', 'Panel de Control')

@section('cabecera')
    <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/registradas.css')}}?v={{ time() }}">
    <!-- Include stylesheet -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" /> -->
    <!-- Include the Quill library -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script> -->

    <script>
        let cod_incidencia = '<?= $data['cod_inc'] ?>';
        let cod_orden = '<?= $data['cod_orden'] ?>';
        let empresas = <?php echo json_encode($data['company']); ?>;
        let sucursales = <?php echo json_encode($data['scompany']); ?>;
        let cargo_estacion = <?php echo json_encode($data['CargoEstacion']); ?>;
        let tipo_estacion = <?php echo json_encode($data['tEstacion']); ?>;
        let tipo_soporte = <?php echo json_encode($data['tSoporte']); ?>;
        let tipo_incidencia = <?php echo json_encode($data['tIncidencia']); ?>;
        let obj_problem = <?php echo json_encode($data['problema']); ?>;
        let obj_subproblem = <?php echo json_encode($data['sproblema']); ?>;
        let obj_eContactos = <?php echo json_encode($data['eContactos']); ?>;
        let usuarios = <?php echo json_encode($data['usuarios']); ?>;
    </script>
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
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Incidencias Registradas</strong>
                </h6>
                <div>
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#modal_incidencias">
                        <i class="fas fa-book-medical"></i>
                        Nueva Incidencia
                    </button>
                    <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_incidencia" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-bg-primary text-center">
                                    <th>Codigo</th>
                                    <th>Estado</th>
                                    <th>Tecnicos</th>
                                    <th>Empresa</th>
                                    <th>Sucursal</th>
                                    <th>Registrado</th>
                                    <th>Estacion</th>
                                    <th>Nivel Incidencia</th>
                                    <th>Soporte</th>
                                    <th>Problema / Sub Problema</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                        <script>
                            function fillSelectContac(data) {
                                $('#tel_contac').html('<option value=""></option>');
                                Object.entries(data).forEach(([key, e]) => {
                                    $('#tel_contac').append($('<option>').val(e.telefono).text(e.telefono));
                                });
                                obj_eContactos = data;
                            }
                            const tb_incidencia = new DataTable('#tb_incidencia', {
                                scrollX: true,
                                scrollY: 400,
                                ajax: {
                                    url: `${__url}/soporte/incidencias/registradas/index`,
                                    dataSrc: function (json) {
                                        $.each(json.conteo_data, function (panel, count) {
                                            $(`b[data-panel="${panel}"]`).html(count);
                                        });
                                        fillSelectContac(json.contact);
                                        return json.data;
                                    },
                                    error: function (xhr, error, thrown) {
                                        boxAlert.table();
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'incidencia' },
                                    { data: 'estado' },
                                    {
                                        data: 'tecnicos', render: function (data, type, row) {
                                            return (data.map(usu => usuarios[usu].nombre)).join(", ");
                                        }
                                    },
                                    {
                                        data: 'empresa', render: function (data, type, row) {
                                            let empresa = empresas[data];
                                            return `${empresa.ruc} - ${empresa.razon_social}`;
                                        }
                                    },
                                    {
                                        data: 'sucursal', render: function (data, type, row) {
                                            return sucursales[data].nombre;
                                        }
                                    },
                                    { data: 'registrado' },
                                    {
                                        data: 'tipo_estacion', render: function (data, type, row) {
                                            return tipo_estacion[data].descripcion;
                                        }
                                    },
                                    {
                                        data: 'tipo_incidencia', render: function (data, type, row) {
                                            let tipo = tipo_incidencia[data[data.length - 1]];
                                            return `<label class="badge badge-${tipo.color} me-2" style="font-size: 0.75rem;">${tipo.tipo}</label>${tipo.descripcion}`;
                                        }
                                    },
                                    {
                                        data: 'tipo_soporte', render: function (data, type, row) {
                                            return tipo_soporte[data].descripcion;
                                        }
                                    },
                                    {
                                        data: 'problema', render: function (data, type, row) {
                                            return `${getBadgePrioridad(obj_subproblem[row.subproblema].prioridad, .75)} ${obj_problem[data].descripcion} / ${obj_subproblem[row.subproblema].descripcion}`;
                                            // return `${getBadgePrioridad(obj_subproblem[row.subproblema].prioridad, .75)} ${data} / ${row.subproblema}`;
                                        }
                                    },
                                    { data: 'acciones' }
                                ],
                                order: [[5, 'desc']],
                                createdRow: function (row, data, dataIndex) {
                                    const row_bg = ['row-bg-warning', 'row-bg-info', 'row-bg-primary', '', 'row-bg-danger'];
                                    $(row).find('td:eq(0), td:eq(1), td:eq(4), td:eq(5), td:eq(6), td:eq(10)').addClass('text-center');
                                    $(row).find('td:eq(10)').addClass(`td-acciones`);
                                    $(row).addClass(row_bg[data.estado_informe]);
                                },
                                processing: true
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_incidencias" aria-labelledby="modal_incidencias" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form class="modal-content" id="form-incidencias" style="position: relative;">
                <input type="hidden" name="id_inc" id="id_inc">
                <input type="hidden" name="estado_info" id="estado_info">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">
                        NUEVA INCIDENCIA
                        <span class="mx-2 badge badge-success badge-lg" id="cod_inc_text">{{$data['cod_inc']}}</span>
                        <span class="badge badge-lg" aria-item="contrato"></span>
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
                    <h6 class="tittle text-primary">Datos Empresa</h6>
                    <div class="row">
                        <div class="col-lg-8 mb-2">
                            <label class="form-label mb-0" for="empresa">Empresa</label>
                            <select class="select-clear" id="empresa">
                                <option value="">-- Seleccione --</option>
                                @foreach ($data['company'] as $e)
                                    <option value="{{$e->ruc}}"
                                        {{ $e->status != 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{$e->ruc}} - {{$e->razon_social}} {{ $e->status != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 mb-2">
                            <label class="form-label mb-0" for="sucursal">Sucursal</label>
                            <select class="select-clear" id="sucursal">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="tittle text-primary">Datos Contacto</h6>
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
                                @foreach ($data['CargoEstacion'] as $cc)
                                    <option value="{{ $cc['id'] }}"
                                        {{ ($cc['selected'] == 1 && $cc['estatus'] == 1) ? 'selected' : '' }}
                                        {{ $cc['estatus'] != 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ $cc['descripcion'] }} {{ $cc['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-7 mb-2">
                            <label class="form-label mb-0" for="cor_contac">Correo</label>
                            <input type="text" class="form-control" id="cor_contac" name="cor_contac"
                                onkeyup="validContac(this)">
                        </div>
                    </div>

                    <h6 class="tittle text-primary">Datos Incidencia</h6>
                    <div class="row">
                        <div class="col-lg-4 mb-2">
                            <label class="form-label mb-0" for="tEstacion">Tipo Estación</label>
                            <select class="select-clear" id="tEstacion">
                                <option value="">-- Seleccione --</option>
                                @foreach ($data['tEstacion'] as $k => $v)
                                    <option value="{{ $v['id'] }}"
                                        {{ ($v['selected'] == 1 && $v['estatus'] == 1) ? 'selected' : '' }}
                                        {{ $v['estatus'] != 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ $v['descripcion'] }} {{ $v['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 mb-2">
                            <label class="form-label mb-0" for="tIncidencia">Nivel Incidencia</label>
                            <select class="select" id="tIncidencia">
                                <option value="">-- Seleccione --</option>
                                @foreach ($data['tIncidencia'] as $v)
                                    <option value="{{ $v['id'] }}"
                                        {{ ($v['selected'] == 1 && $v['estatus'] == 1) ? 'selected' : '' }}
                                        {{ $v['estatus'] != 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ '<label class="badge badge-' . $v['color'] . ' me-2">' . $v['tipo'] . '</label>' }} {{ $v['descripcion'] }} {{ $v['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 mb-2">
                            <label class="form-label mb-0" for="tSoporte">Tipo Soporte</label>
                            <select class="select" id="tSoporte">
                                <option value="">-- Seleccione --</option>
                                @foreach ($data['tSoporte'] as $v)
                                    <option value="{{ $v['id'] }}"
                                        {{ ($v['selected'] == 1 && $v['estatus'] == 1) ? 'selected' : '' }}
                                        {{ $v['estatus'] != 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ $v['descripcion'] }} {{ $v['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '' }}
                                    </option>
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
                            <select class="select-icons" id="sproblema">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-12 col-6 mb-2">
                                    <label class="form-label mb-0" for="fecha_imforme">Fecha de Informe</label>
                                    <input class="form-control" id="fecha_imforme">
                                </div>
                                <div class="col-sm-12 col-6 mb-2">
                                    <label class="form-label mb-0" for="hora_informe">Hora de Informe</label>
                                    <input class="form-control" id="hora_informe" min="00:00" max="23:59" step="1">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 mb-2">
                            <label class="form-label mb-0" for="observacion">Observacion</label>
                            <textarea class="form-control" id="observacion" style="height: 106px;resize: none;"></textarea>
                        </div>
                        <!-- <div class="col-12">
                            <div id="editor">
                                <p>Hello World!</p>
                                <p>Some initial <strong>bold</strong> text</p>
                                <p><br /></p>
                            </div>
                        </div>
                        <script>
                            const quill = new Quill('#editor', {
                                theme: 'snow'
                            });
                        </script> -->
                    </div>

                    <div id="contenedor-personal">
                        <h6 class="tittle text-primary">Asignar Personal
                        </h6>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="input-group mt-2 mb-2">
                                    <span class="input-group-text border-0"><i class="fas fa-chalkboard-user"></i></span>
                                    <select class="select-clear" id="createPersonal">
                                        <option value=""></option>
                                        @foreach ($data['usuarios'] as $u)
                                            <option value="{{$u['value']}}"
                                                data-value="{{$u['dValue']}}"
                                                {{ $u['estatus'] != 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                                {{$u['text']}} {{ $u['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '' }}
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
                    <h6 class="modal-title">Detalle de incidencia
                        <span class="ms-2 badge badge-success badge-lg" aria-item="codigo"></span>
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="list-group list-group-light">
                            <div class="list-group-item">
                                <p aria-item="razon_social" class="font-weight-semibold mb-2" style="font-size: .92rem;">
                                    20506467854 - CORPORACION JULCAN S.A.</p>
                                <p class="mb-0" style="font-size: .75rem;" aria-item="direccion">AV. GERARDO UNGER
                                    N° 3689 MZ D LT 26 INDEPENDENCIA</p>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Sucursal: </label><span
                                    style="font-size: .75rem;" aria-item="sucursal">E/S INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Dir. Sucursal: </label><span style="font-size: .75rem;"
                                    aria-item="dir_sucursal">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <div>
                                    <label class="form-label me-2">Tipo Soporte:</label>
                                    <span style="font-size: .75rem;" aria-item="soporte"></span>
                                </div>
                                <div>
                                    <label class="form-label me-2">Problema:</label>
                                    <span style="font-size: .75rem;" aria-item="problema"></span>
                                </div>
                                <div>
                                    <label class="form-label me-2">Sub Problema:</label>
                                    <span style="font-size: .75rem;" aria-item="subproblema"></span>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Nivel Incidencia:</label>
                                <div aria-item="incidencia"></div>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Observación:</label>
                                <span style="font-size: .75rem;" aria-item="observacion"></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <h6 class="font-weight-semibold text-primary tt-upper m-0" style="font-size: smaller;">Seguimiento Incidencia</h6>
                        <span aria-item="estado"></span>
                    </div>
                    <div class="fieldset" aria-item="contenedor-seguimiento">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
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
                    <h6 class="modal-title">Asignar Personal
                        <span class="ms-2 badge badge-success badge-lg" aria-item="codigo"></span>
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="list-group list-group-light">
                            <div class="list-group-item">
                                <p aria-item="razon_social" class="font-weight-semibold mb-2" style="font-size: .92rem;">
                                    20506467854 - CORPORACION JULCAN S.A.</p>
                                <p class="mb-0" style="font-size: .75rem;" aria-item="direccion">AV. GERARDO UNGER
                                    N° 3689 MZ D LT 26 INDEPENDENCIA</p>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Sucursal: </label><span
                                    style="font-size: .75rem;" aria-item="sucursal">E/S INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Dir. Sucursal: </label><span style="font-size: .75rem;"
                                    aria-item="dir_sucursal">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <div>
                                    <label class="form-label me-2">Tipo Soporte:</label>
                                    <span style="font-size: .75rem;" aria-item="soporte"></span>
                                </div>
                                <div>
                                    <label class="form-label me-2">Problema:</label>
                                    <span style="font-size: .75rem;" aria-item="problema"></span>
                                </div>
                                <div>
                                    <label class="form-label me-2">Sub Problema:</label>
                                    <span style="font-size: .75rem;" aria-item="subproblema"></span>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Observación:</label>
                                <span style="font-size: .75rem;" aria-item="observacion"></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <h6 class="font-weight-semibold text-primary tt-upper m-0" style="font-size: smaller;">Asignar Personal</h6>
                        <span aria-item="estado"></span>
                    </div>
                    <div class="p-3 pb-0 fieldset mb-3">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="input-group mt-2 mb-3">
                                    <span class="input-group-text border-0"><i class="fas fa-chalkboard-user"></i></span>
                                    <select class="select-clear" id="createPersonal1">
                                        <option value=""></option>
                                        @foreach ($data['usuarios'] as $u)
                                            <option value="{{$u['value']}}"
                                                data-value="{{$u['dValue']}}"
                                                {{ $u['estatus'] != 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                                {{$u['text']}} {{ $u['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '' }}
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
                            <div class="col-lg-5 col-10">
                                <label class="form-label" for="n_orden">N° de Orden </label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="n_orden">
                                    <button class="btn btn-secondary" type="button" id="button-cod-orden" check-cod="false"
                                        data-mdb-ripple-init data-mdb-ripple-color="dark">
                                        Cod. Tecnito
                                    </button>
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
                                    <p aria-item="razon_social" class="font-weight-semibold mb-2" style="font-size: .92rem;">
                                        20506467854 - CORPORACION JULCAN S.A.</p>
                                    <p class="mb-0" style="font-size: .75rem;" aria-item="direccion">AV. GERARDO UNGER
                                        N° 3689 MZ D LT 26 INDEPENDENCIA</p>
                                </div>
                                <div class="list-group-item">
                                    <label class="form-label me-2">Sucursal: </label><span
                                        style="font-size: .75rem;" aria-item="sucursal">E/S INDEPENDENCIA</span>
                                </div>
                                <div class="list-group-item">
                                    <label class="form-label me-2">Dir. Sucursal: </label><span style="font-size: .75rem;"
                                        aria-item="dir_sucursal">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                            <h6 class="tittle text-primary"> TRABAJO REALIZADO </h6>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                            <div>
                                <label class="form-label me-2">Tipo Soporte:</label>
                                <span style="font-size: .75rem;" aria-item="soporte"></span>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label me-2">Problema:</label>
                                <span style="font-size: .75rem;" aria-item="problema"></span>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label me-2">Sub Problema:</label>
                                <span style="font-size: .75rem;" aria-item="subproblema"></span>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label me-2">Obersevaciones Incidencia:</label>
                                <span style="font-size: .75rem;" aria-item="observacion"></span>
                            </div>
                            <div class="row justify-content-md-center">
                                <div class="col-md-6">
                                    <div class="form-group pt-2">
                                        <label class="form-label" for="observaciones">Observaciones</label>
                                        <textarea class="form-control" id="observaciones"
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

                        <div class="col-12">
                            <div class="row" id="content-material">
                                <div class="col-lg-9 my-1">
                                    <div class="input-group">
                                        <select class="select-clear" id="createMaterial">
                                            <option value=""></option>
                                            @foreach ($data['materiales'] as $m)
                                                <option value="{{$m['value']}}"
                                                    data-value="{{$m['dValue']}}"
                                                    {{ $m['estatus'] != 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                                    {{$m['text']}} {{ $m['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6 my-1 ps-lg-0">
                                    <div class="input-group disabled" style="max-width: 300px;" id="content-cantidad">
                                        <button class="btn btn-secondary px-2" type="button" data-mdb-ripple-init
                                            onclick="manCantidad('-')">
                                            <i class="fas fa-minus" style="font-size: .75rem;"></i>
                                        </button>
                                        <input type="number" class="form-control" input-cantidad=""
                                            oninput="manCantidad('press')" />
                                        <button class="btn btn-secondary px-2" type="button" data-mdb-ripple-init
                                            onclick="manCantidad('+')">
                                            <i class="fas fa-plus" style="font-size: .75rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6 my-1 ps-0 d-none" id="content-codAviso">
                                    <input type="text" class="form-control" id="codAviso" placeholder="Cod. Aviso">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 my-2 px-4">
                            <div class="row justify-content-between firmas-orden">
                                <div class="col-lg-5 text-center my-2">
                                    <img class="border rounded-1" {{Auth::user()->firma_digital ? 'src=' . asset('front/images/firms/' . Auth::user()->firma_digital) . '?v=' . time() : ''}} height="130"
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

    <div class="modal fade" id="modal_addcod" aria-labelledby="modal_addcod" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-addcod">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Añadir Codigo Aviso
                        <span class="badge badge-success badge-lg" aria-item="codigo"></span>
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="list-group list-group-light">
                            <div class="list-group-item">
                                <p aria-item="razon_social" class="font-weight-semibold mb-2" style="font-size: .92rem;">
                                    20506467854 - CORPORACION JULCAN S.A.</p>
                                <p class="mb-0" style="font-size: .75rem;" aria-item="direccion">AV. GERARDO UNGER
                                    N° 3689 MZ D LT 26 INDEPENDENCIA</p>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Sucursal: </label><span
                                    style="font-size: .75rem;" aria-item="sucursal">E/S INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Dir. Sucursal: </label><span style="font-size: .75rem;"
                                    aria-item="dir_sucursal">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <h6 class="font-weight-semibold text-primary tt-upper m-0" style="font-size: smaller;">Agregar codigo aviso</h6>
                        <span aria-item="estado"></span>
                    </div>
                    <div class="p-3 pb-0 fieldset mb-3">
                        <input type="hidden" id="cod_incidencia" name="cod_incidencia">
                        <input type="hidden" id="cod_orden_ser" name="cod_orden_ser">
                        <!-- <h6 class="tittle text-primary">Asignar Personal</h6> -->
                        <div class="col-md-6 mb-2">
                            <label class="form-label mb-0" for="codigo_aviso">Codigo Aviso</label>
                            <input class="form-control" id="codigo_aviso">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init>Guardar</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // 
    </script>
    <script src="{{secure_asset('front/js/app/SelectManeger.js')}}?v={{ time() }}"></script>
    <script src="{{secure_asset('front/vendor/signature/signature_pad.js')}}?v={{ time() }}"></script>
    <script src="{{secure_asset('front/js/soporte/incidencia/registradas.js')}}?v={{ time() }}"></script>
@endsection