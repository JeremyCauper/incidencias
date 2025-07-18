@extends('layout.app')
@section('title', 'Visitas')

@section('cabecera')
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{secure_asset('front/vendor/daterangepicker/daterangepicker.css')}}">
    <!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/registradas.css')}}"> -->
    <style>
        #tb_visitas thead tr * {
            font-size: 12px;
            /* padding-top: ; */
        }

        #tb_visitas_filter.dataTables_filter label {
            width: 100% !important;
        }
    </style>
    <script>
        let cod_ordenv = "{{$data['cod_ordenv']}}";
        let empresas = <?php echo json_encode($data['company']); ?>;
        let sucursales = <?php echo json_encode($data['scompany']); ?>;
        let usuarios = <?php echo json_encode($data['usuarios']); ?>;
    </script>
@endsection
@section('content')

    <div class="row ">
        <div class="col-xl-6 mb-4">
            <div class="row panel-view">
                <div class="col-6 grid-margin">
                    <div class="card">
                        <div class="card-body text-danger">
                            <h6 class="card-title title-count mb-2"><i class="fas fa-xmark"></i></i> Visitas Sin Asignar
                            </h6>
                            <h4 class="subtitle-count"><b data-panel="vSinAsignar">0</b></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 grid-margin">
                    <div class="card">
                        <div class="card-body text-info">
                            <h6 class="card-title title-count mb-2"><i class="fas fa-clock"></i> Visitas Asignadas</h6>
                            <h4 class="subtitle-count"><b data-panel="vAsignadas">0</b></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title col-form-label-sm text-primary mb-3">
                        <strong>Visitas a Programar</strong>
                    </h6>
                    <div>
                        <button class="btn btn-primary px-2" onclick="updateTableVisitas()" data-mdb-ripple-init
                            role="button">
                            <i class="fas fa-rotate-right"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table id="tb_visitas" class="table table-hover text-nowrap" style="width:100%">
                                <thead>
                                    <tr class="text-bg-primary text-center">
                                        <th>Ruc - Sucursal</th>
                                        <th>Visitas Realizadas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                            <script>
                                const tb_visitas = new DataTable('#tb_visitas', {
                                    autoWidth: true,
                                    scrollX: true,
                                    scrollY: 400,
                                    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
                                    dom: `<"row"
                                                    <"col-lg-12 mb-2"B>>
                                                <"row"
                                                    <"col-sm-4 text-xsm-start text-center my-1"l>
                                                    <"col-sm-3 col-xsm-4 text-xsm-end text-center my-1 selectFiltroEstado">
                                                    <"col-sm-5 col-xsm-8 text-xsm-end text-center my-1"f>>
                                                <"contenedor_tabla my-2"tr>
                                                <"row"
                                                    <"col-md-5 text-md-start text-center my-1"i>
                                                    <"col-md-7 text-md-end text-center my-1"p>>`,
                                    ajax: {
                                        url: `${__url}/soporte/visitas/sucursales/index`,
                                        dataSrc: function (json) {
                                            $.each(json.conteo, function (panel, count) {
                                                $(`b[data-panel="${panel}"]`).html(count);
                                            });
                                            return json.data;
                                        },
                                        error: function (xhr, error, thrown) {
                                            boxAlert.table(updateTableVisitas);
                                            console.log('Respuesta del servidor:', xhr);
                                        }
                                    },
                                    columns: [
                                        {
                                            data: 'ruc', render: function (data, type, row) {
                                                return `${data} - ${row.sucursal}`;
                                            }
                                        },
                                        {
                                            data: 'visita', render: function (data, type, row) {
                                                badgeOptions = data == 'completado'
                                                    ? { t: 'Completado', c: 'primary' }
                                                    : (data ? { 'c': 'info', 't': `${data} Visita${(data > 1) ? 's' : ''}` } : { 'c': 'warning', 't': 'Sin Visitas' });

                                                return `<label class="badge badge-${badgeOptions.c}" style="font-size: .7rem;">${badgeOptions.t}</label>`;
                                            }
                                        },
                                        { data: 'acciones' }
                                    ],
                                    createdRow: function (row, data, dataIndex) {
                                        $(row).find('td:eq(1), td:eq(2)').addClass('text-center');
                                    },
                                    ordering: false,
                                    processing: true
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="row panel-view">
                <div class="col-6 grid-margin">
                    <div class="card">
                        <div class="card-body text-primary">
                            <h6 class="card-title title-count mb-2"><i class="fas fa-business-time"></i> Visitas En Proceso
                            </h6>
                            <h4 class="subtitle-count"><b data-panel="vEnProceso">0</b></h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 grid-margin">
                    <div class="card">
                        <div class="card-body text-warning">
                            <h6 class="card-title title-count mb-2"><i class="fas fa-clipboard-check"></i> Visitas Sin
                                Iniciar</h6>
                            <h4 class="subtitle-count"><b data-panel="vSinIniciar">0</b></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title col-form-label-sm text-primary mb-3">
                        <strong>Visitas Programadas</strong>
                    </h6>
                    <div>
                        <button class="btn btn-primary px-2" onclick="updateTableVProgramadas()" data-mdb-ripple-init
                            role="button">
                            <i class="fas fa-rotate-right"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table id="tb_vprogramadas" class="table table-hover text-nowrap" style="width:100%">
                                <thead>
                                    <tr class="text-bg-primary text-center">
                                        <th>Estado</th>
                                        <th>Sucursal</th>
                                        <th>Técnico</th>
                                        <th>Fecha Visita</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                            <script>
                                const tb_vprogramadas = new DataTable('#tb_vprogramadas', {
                                    autoWidth: true,
                                    scrollX: true,
                                    scrollY: 400,
                                    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
                                    ajax: {
                                        url: `${__url}/soporte/visitas/programadas/index`,
                                        dataSrc: function (json) {
                                            $.each(json.conteo, function (panel, count) {
                                                $(`b[data-panel="${panel}"]`).html(count);
                                            });
                                            return json.data;
                                        },
                                        error: function (xhr, error, thrown) {
                                            boxAlert.table(updateTableVProgramadas);
                                            console.log('Respuesta del servidor:', xhr);
                                        }
                                    },
                                    columns: [
                                        { data: 'estado' },
                                        {
                                            data: 'sucursal', render: function (data, type, row) {
                                                let sucursal = sucursales[data];
                                                return `${sucursal.ruc} - ${sucursal.nombre}`;
                                            }
                                        },
                                        { data: 'tecnicos' },
                                        { data: 'fecha' },
                                        { data: 'acciones' }
                                    ],
                                    createdRow: function (row, data, dataIndex) {
                                        $(row).find('td:eq(0), td:eq(3), td:eq(4)').addClass('text-center');
                                        $(row).find('td:eq(4)').addClass(`td-acciones`);
                                    },
                                    processing: true
                                });
                            </script>
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
                    <h6 class="modal-title" id="modal_visitasLabel">Asignar Personal Visita
                        <span aria-item="contrato"></span>
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
                                <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                    aria-item="sucursal">E/S INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Dir. Sucursal: </label><span style="font-size: .75rem;"
                                    aria-item="dir_sucursal">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                            </div>
                        </div>
                    </div>
                    <div class="fieldset">
                        <input type="hidden" id="idSucursal" name="idSucursal">
                        <div class="col-md-4">
                            <label class="form-label mb-0" for="fecha_visita">Fecha Visita</label>
                            <div class="input-group" role="button">
                                <label class="input-group-text ps-0 pe-1 border-0"><i class="far fa-calendar"></i></label>
                                <input type="text" class="form-control rounded" id="fecha_visita" name="fecha_visita"
                                    role="button" readonly>
                            </div>
                        </div>
                        <label class="form-label mb-0">Asignar Personal</label>
                        <div id="createPersonal"></div>
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
                    <h6 class="modal-title" id="modal_detalle_visitasLabel">Asignar Personal Visita
                        <span aria-item="contrato"></span>
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                        <div class="list-group list-group-light">
                            <div class="list-group-item">
                                <p aria-item="razon_social" class="font-weight-semibold mb-2" style="font-size: .92rem;">
                                    20506467854 - CORPORACION JULCAN S.A.</p>
                                <p class="mb-0" style="font-size: .75rem;" aria-item="direccion">AV. GERARDO UNGER
                                    N° 3689 MZ D LT 26 INDEPENDENCIA</p>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                    aria-item="sucursal">E/S INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Dir. Sucursal: </label><span style="font-size: .75rem;"
                                    aria-item="dir_sucursal">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <div class="row col-12">
                                    <div class="col-md-3 col-6">
                                        <label class="form-label me-2">Dias / Visitas: </label><span
                                            style="font-size: .75rem;" aria-item="rDias">0</span>
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
                                <label class="form-label me-2 text-danger">Nota: </label><span style="font-size: .75rem;"
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
                    <h6 class="modal-title">Seguimiento de la visita</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group list-group-light">
                        <div class="list-group-item">
                            <p aria-item="razon_social" class="font-weight-semibold mb-2" style="font-size: .92rem;">
                                20506467854 - CORPORACION JULCAN S.A.</p>
                            <p class="mb-0" style="font-size: .75rem;" aria-item="direccion">AV. GERARDO UNGER
                                N° 3689 MZ D LT 26 INDEPENDENCIA</p>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                aria-item="sucursal">E/S INDEPENDENCIA</span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Dir. Sucursal: </label><span style="font-size: .75rem;"
                                aria-item="dir_sucursal">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <h6 class="font-weight-semibold text-primary tt-upper m-0" style="font-size: smaller;">Seguimiento
                            Visita</h6>
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
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group list-group-light">
                        <div class="list-group-item">
                            <p aria-item="razon_social" class="font-weight-semibold mb-2" style="font-size: .92rem;">
                                20506467854 - CORPORACION JULCAN S.A.</p>
                            <p class="mb-0" style="font-size: .75rem;" aria-item="direccion">AV. GERARDO UNGER
                                N° 3689 MZ D LT 26 INDEPENDENCIA</p>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                aria-item="sucursal">E/S INDEPENDENCIA</span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Dir. Sucursal: </label><span style="font-size: .75rem;"
                                aria-item="dir_sucursal">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <h6 class="font-weight-semibold text-primary tt-upper m-0" style="font-size: smaller;">Asignar
                            Personal</h6>
                        <span aria-item="estado"></span>
                    </div>
                    <div class="p-3 pb-0 fieldset">
                        <input type="hidden" id="id_visitas_asign">
                        <div id="createPersonal1"></div>
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
                            aria-item="codigo">{{$data['cod_ordenv']}}</span></h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <!-- INICIO CABECERA -->
                        <div class="text-end cabecera-orden">
                            <div>
                                <label class="form-label me-2">Fecha Inicio: </label>
                                <span style="font-size: small;" aria-item="registrado"></span>
                            </div>
                        </div>

                        <!-- TER CABECERA -->
                        <div class="col-12 mt-2">
                            <h6 class="tittle text-primary"> TECNICOS</h6>
                        </div>

                        <div class="col-12 mt-1 mb-3">
                            <span aria-item="tecnicos"></span>
                        </div>


                        <div class="col-12 mt-2">
                            <h6 class="tittle text-primary"> DATOS DEL CLIENTE </h6>
                        </div>

                        <div class="col-12 mt-1 mb-3">
                            <div class="list-group list-group-light">
                                <div class="list-group-item">
                                    <p aria-item="razon_social" class="font-weight-semibold mb-2"
                                        style="font-size: .92rem;">
                                        20506467854 - CORPORACION JULCAN S.A.</p>
                                    <p class="mb-0" style="font-size: .75rem;" aria-item="direccion">AV. GERARDO UNGER
                                        N° 3689 MZ D LT 26 INDEPENDENCIA</p>
                                </div>
                                <div class="list-group-item">
                                    <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                        aria-item="sucursal">E/S INDEPENDENCIA</span>
                                </div>
                                <div class="list-group-item">
                                    <label class="form-label me-2">Dir. Sucursal: </label><span style="font-size: .75rem;"
                                        aria-item="dir_sucursal">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <h6 class="tittle text-primary"> REVISION DEL GABINETE </h6>
                        </div>

                        <input type="hidden" name="cod_ordenv" value="{{$data['cod_ordenv']}}">
                        <input type="hidden" name="id_visita_orden">

                        <div id="contendor-filas">
                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>UPS</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des1" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-3" style="font-size: 11px; color: #757575"><label
                                        class="item-isla">BATERIAS UPS</label></div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des2" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-3" style="font-size: 11px; color: #757575"><label
                                        class="item-isla">SALIDA DE ENERGIA</label></div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des3" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>ESTABILIZADOR</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des4" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-3" style="font-size: 11px; color: #757575"><label
                                        class="item-isla">INGRESO DE ENERGIA</label></div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des5" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-3" style="font-size: 11px; color: #757575"><label
                                        class="item-isla">SALIDA DE ENERGIA</label></div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des6" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>INTERFACE</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des7" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>MONITOR</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des8" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>TARJETA MULTIPUERTOS</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des9" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>SWITCH</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des10" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <h6 class="tittle text-primary"> REVISION DEL SERVIDOR</h6>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>SISTEMA OPERATIVO</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des11" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>VENCIMIENTO DE ANTIVIRUS</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des12" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>DISCO DURO</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des13" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                    <strong>REALIZAR BACKUP</strong>
                                </div>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 ps-0"><i
                                                class="fas fa-circle-check"></i></span>
                                        <input type="text" name="des14" class="form-control rounded"
                                            onchange="changeCheck(this)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-10 my-1">
                                <h6 class="tittle text-primary"> REVISION DEL POS, LECTORES, JACK TOOLS IMPRESORAS Y
                                    CONEXIONES </h6>
                            </div>
                            <div class="col-lg-2 my-1 d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-danger btn-sm px-2 me-2 text-nowrap"
                                    onclick="MRevision.deleteAll()">Limpiar Islas</button>
                                <strong class="me-2" style="white-space: nowrap;" id="conteo-islas">Cant. 1</strong>
                                <button type="button" class="btn btn-secondary btn-sm px-1" onclick="MRevision.create()"><i
                                        class="far fa-square-plus"></i></button>
                            </div>
                        </div>

                        <div id="content-islas" class="mt-3">
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
    <script src="{{secure_asset('front/js/app/RevisionMananger.js')}}?v={{ time() }}"></script>
    <script src="{{secure_asset('front/js/soporte/visitas/visitas.js')}}?v={{ time() }}"></script>
    <script src="{{secure_asset('front/js/soporte/visitas/programadas.js')}}?v={{ time() }}"></script>
@endsection