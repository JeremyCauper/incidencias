@extends('layout.app')
@section('title', 'Visitas')

@section('cabecera')
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{secure_asset('front/vendor/daterangepicker/daterangepicker.css')}}">
    <!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/registradas.css')}}"> -->
    <style>
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
            <div class="row">
                <div class="col-6 mb-2">
                    <div class="card" style="cursor: pointer;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="card-icon rounded-7" style="background-color: #dc4c6440;">
                                        <i class="fa-solid fa-xmark fa-fw fs-4 text-danger"></i>
                                    </div>
                                </div>
                                <div class="content-text flex-grow-1 ms-2">
                                    <p class="text-muted text-nowrap text-secondary fw-bold mb-1">Sin Asignar</p>
                                    <p class="fw-bold mb-0 fs-4" data-panel="vSinAsignar">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    <div class="card" style="cursor: pointer;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="card-icon rounded-7" style="background-color: #54b4d340;">
                                        <i class="fa-solid fa-clock fa-fw fs-4 text-info"></i>
                                    </div>
                                </div>
                                <div class="content-text flex-grow-1 ms-2">
                                    <p class="text-muted text-nowrap text-secondary fw-bold mb-1">Asignadas</p>
                                    <p class="fw-bold mb-0 fs-4" data-panel="vAsignadas">0</p>
                                </div>
                            </div>
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
                                    <tr class="text-center">
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
                <div class="col-6 mb-2">
                    <div class="card" style="cursor: pointer;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="card-icon rounded-7" style="background-color: #3b71ca40;">
                                        <i class="fa-solid fa-business-time fa-fw fs-4 text-primary"></i>
                                    </div>
                                </div>
                                <div class="content-text flex-grow-1 ms-2">
                                    <p class="text-muted text-nowrap text-secondary fw-bold mb-1">En Proceso</p>
                                    <p class="fw-bold mb-0 fs-4" data-panel="vEnProceso">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="card" style="cursor: pointer;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="card-icon rounded-7" style="background-color: #e4a11b40;">
                                        <i class="fa-solid fa-clipboard-check fa-fw fs-4 text-warning"></i>
                                    </div>
                                </div>
                                <div class="content-text flex-grow-1 ms-2">
                                    <p class="text-muted text-nowrap text-secondary fw-bold mb-1">Sin Iniciar</p>
                                    <p class="fw-bold mb-0 fs-4" data-panel="vSinIniciar">0</p>
                                </div>
                            </div>
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
                                    <tr class="text-center">
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
                <div class="modal-header" style="background-color: rgb(59 113 202 / 25%);">
                    <h5 class="modal-title" id="modal_visitasLabel">Asignar Personal Visita
                        <span aria-item="contrato"></span>
                    </h5>
                    <div class="align-items-center d-flex gap-2">
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="detalle_body mb-2">
                        <div class="border-bottom mb-4">
                            <h5><span aria-item="razon_social"></span></h5>
                            <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                            <p class="detalle_text" aria-item="sucursal"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                Sucursal</p>
                            <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                        </div>
                    </div>
                    <div class="mt-3">
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
                <div class="modal-header" style="background-color: rgb(59 113 202 / 25%);">
                    <h6 class="modal-title" id="modal_detalle_visitasLabel">Asignar Personal Visita
                        <span aria-item="contrato"></span>
                    </h6>
                    <div class="align-items-center d-flex gap-2">
                        <span aria-item="estado"></span>
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="detalle_body mb-2">
                        <div class="border-bottom mb-4">
                            <h5><span aria-item="razon_social"></span></h5>
                            <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                            <p class="detalle_text" aria-item="sucursal"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                Sucursal</p>
                            <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-6">
                            <div class="detalle_body mb-2">
                                <p class="form-label text-nowrap mb-2 me-2">Dias / Visitas</p>
                                <p class="mb-0 fw-bold" style="font-size: .95rem;">
                                    <span aria-item="rDias">0</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detalle_body mb-2">
                                <p class="form-label text-nowrap mb-2 me-2">Visitas Totales</p>
                                <p class="mb-0 fw-bold" style="font-size: .95rem;">
                                    <i class="fas fa-list-check text-primary me-2"></i>
                                    <span aria-item="vTotal">0</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detalle_body mb-2">
                                <p class="form-label text-nowrap mb-2 me-2">Visitas Realizadas</p>
                                <p class="mb-0 fw-bold" style="font-size: .95rem;">
                                    <i class="far fa-circle-check text-success me-2"></i>
                                    <span aria-item="vRealizada">0</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="detalle_body mb-2">
                                <p class="form-label text-nowrap mb-2 me-2">Visitas Pendientes</p>
                                <p class="mb-0 fw-bold" style="font-size: .95rem;">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <span aria-item="vPendiente">0</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="detalle_body mb-2 d-none">
                        <table class="table mb-0" id="tb_visitas_asignadas">
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

                    <div class="detalle_body"
                        style="background-color: rgb(255 0 0 / 6%);border: 1px solid rgb(255 0 0 / 30%);">
                        <p class="form-label mb-2 me-2 text-danger"><i class="fas fa-circle-exclamation me-3"></i>Nota
                            Importante</p>
                        <p class="mb-0" style="font-size: .75rem;color: rgb(255 0 0 / 70%);" aria-item="mensaje"></p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_seguimiento_visitasp" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(59 113 202 / 25%);">
                    <h5 class="modal-title">Detalle de la visita</h5>
                    <div class="align-items-center d-flex gap-2">
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body modal-body-scrollable px-1 p-0">
                    <div class="row">
                        <div class="col-lg-5 p-4 modal-col-scrollable personalized-scroll"
                            style="background-color: rgb(29 49 69 / 5%);">
                            <h6 class="text-uppercase mt-2 mb-4 title_detalle">
                                <i class="fas fa-city me-2"></i> Información del Cliente
                            </h6>
                            <div class="detalle_body mb-2">
                                <div class="border-bottom mb-4">
                                    <h5><span aria-item="razon_social"></span></h5>
                                    <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                                </div>
                                <div>
                                    <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                                    <p class="detalle_text" aria-item="sucursal"></p>
                                </div>
                                <div>
                                    <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                        Sucursal</p>
                                    <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 p-4 modal-col-scrollable personalized-scroll">
                            <div class="align-items-center d-flex mt-2 mb-4">
                                <h4 class="mb-0 text-nowrap text-uppercase title_detalle">
                                    <i class="fas fa-clock-rotate-left" style="color: rgb(99 102 241 / 1)"></i>
                                    SEGUIMIENTO VISITA
                                </h4>
                                <div class="ms-2 rounded-pill"
                                    style="height: .35rem;width: 100%;background-color: rgb(148 163 184 / 11%)"></div>
                            </div>
                            <div class="content_seguimiento" aria-item="contenedor-seguimiento"></div>
                        </div>
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
                <div class="modal-header" style="background-color: rgb(59 113 202 / 25%);">
                    <h5 class="modal-title">Asignar</h5>
                    <div class="align-items-center d-flex gap-2">
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="detalle_body mb-2">
                        <div class="border-bottom mb-4">
                            <h5><span aria-item="razon_social"></span></h5>
                            <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                            <p class="detalle_text" aria-item="sucursal"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                Sucursal</p>
                            <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                        </div>
                    </div>

                    <h6 class="text-uppercase mt-4 title_detalle text-primary" style="font-size: 14px"><i
                            class="fas fa-user-plus me-2"></i>Asignar Personal</h6>
                    <div class="">
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
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-scrollable modal-xl">
            <form class="modal-content" id="form-orden">
                <div class="modal-header" style="background-color: rgb(59 113 202 / 25%);">
                    <h5 class="modal-title">ORDEN DE SERVICIO
                        <span class="ms-2 badge badge-lg rounded-pill px-3" style="background-color: #5a8bdb"
                            aria-item="codigo">{{$data['cod_ordenv']}}</span>
                    </h5>
                    <div class="align-items-center d-flex gap-2">
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <!-- INICIO CABECERA -->
                    <div class="text-end cabecera-orden">
                        <div style="color: rgb(148 163 184 / 1)">
                            <label class="form-label me-2">Fecha Inicio: </label>
                            <span style="font-size: small;" aria-item="registrado"></span>
                        </div>
                    </div>

                    <!-- TER CABECERA -->
                    <h6 class="text-uppercase mb-3 title_detalle">
                        <i class="fas fa-user-gear"></i> Tecnicos
                    </h6>

                    <div class="mb-3">
                        <span aria-item="tecnicos"></span>
                    </div>


                    <h6 class="text-uppercase mb-3 title_detalle">
                        <i class="fas fa-city me-2"></i> Información del Cliente
                    </h6>
                    <div class="detalle_body mb-2">
                        <div class="border-bottom mb-4">
                            <h5><span aria-item="razon_social"></span></h5>
                            <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                            <p class="detalle_text" aria-item="sucursal"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                Sucursal</p>
                            <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                        </div>
                    </div>


                    <h6 class="text-uppercase mt-4 mb-3 title_detalle">
                        <i class="fas fa-server me-2"></i> Revisión del gabinete
                    </h6>

                    <input type="hidden" name="cod_ordenv" value="{{$data['cod_ordenv']}}">
                    <input type="hidden" name="id_visita_orden">

                    <div class="detalle_body mb-2" id="contendor-filas">
                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>UPS</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des1" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px; color: #757575"><label class="item-isla">BATERIAS
                                    UPS</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des2" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px; color: #757575"><label class="item-isla">SALIDA DE
                                    ENERGIA</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
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
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des4" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px; color: #757575"><label class="item-isla">INGRESO
                                    DE ENERGIA</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des5" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px; color: #757575"><label class="item-isla">SALIDA DE
                                    ENERGIA</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
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
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
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
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
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
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
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
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
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
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
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
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
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
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
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
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des14" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detalle_body row mb-2 mt-3">
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

                    <div id="content-islas" class="detalle_body mb-2 mt-3">
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