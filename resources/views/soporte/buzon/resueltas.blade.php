@extends('layout.app')
@section('title', 'INC RESUELTAS')

@section('cabecera')
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{secure_asset('front/vendor/daterangepicker/daterangepicker.css')}}">

    <script>
        let empresas = <?php echo json_encode($data['empresas']); ?>;
        let sucursales = <?php echo json_encode($data['sucursales']); ?>;
        let tipo_incidencia = <?php echo json_encode($data['tIncidencia']); ?>;
        let tipo_soporte = <?php echo json_encode($data['tSoporte']); ?>;
        let obj_problem = <?php echo json_encode($data['problema']); ?>;
        let obj_subproblem = <?php echo json_encode($data['sproblema']); ?>;
    </script>
@endsection
@section('content')

    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body form-container">
                <h6 class="text-primary">Filtros de Busqueda</h6>
                <div class="row">
                    <div class="col-xxl-5 my-1">
                        <label class="form-label mb-0" for="empresa">Empresa</label>
                        <select id="empresa" name="empresa" class="select-clear form-control">
                            <option value=""></option>
                            @foreach ($data['empresas'] as $key => $val)
                                @if ($val->status)
                                    <option value="{{$val->ruc}}" id-empresa="{{$val->id}}">
                                        {{$val->ruc . ' - ' . $val->razon_social}}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-3 col-md-8 my-1">
                        <label class="form-label mb-0" for="idGrupo">Sucursal</label>
                        <select id="sucursal" name="sucursal" class="select-clear" disabled="true">
                            <option value="">-- Seleccione --</option>
                        </select>
                    </div>
                    <div class="col-xxl-2 col-md-4 my-1">
                        <label class="form-label mb-0" for="dateRango">Rango</label>
                        <input type="text" class="form-control" id="dateRango" name="dateRango" role="button" readonly>
                    </div>
                    <div class="align-items-end col-xxl-2 d-flex my-1 justify-content-end">
                        <div>
                            <button type="button" class="btn btn-primary" data-mdb-ripple-init onclick="filtroBusqueda()">
                                <i class="fas fa-magnifying-glass"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Incidencias / Visitas Resueltas</strong>
                </h6>
                <ul class="nav nav-tabs mb-3" id="myTab0" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button data-mdb-tab-init class="nav-link position-relative rounded-top-1 active" id="home-tab0"
                            data-mdb-target="#home0" type="button" role="tab" aria-controls="home" aria-selected="true"
                            data-mdb-ripple-init onclick="resetTable(false)">
                            Incidencias
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button data-mdb-tab-init class="nav-link position-relative rounded-top-1" id="profile-tab0"
                            data-mdb-target="#profile0" type="button" role="tab" aria-controls="profile"
                            aria-selected="false" data-mdb-ripple-init onclick="resetTable(true)">
                            Visitas
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent0">
                    <div class="tab-pane fade show active" id="home0" role="tabpanel" aria-labelledby="home-tab0">
                        <div>
                            <button class="btn btn-primary px-2" onclick="updateTableInc()" data-mdb-ripple-init
                                role="button">
                                <i class="fas fa-rotate-right"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table id="tb_incidencias" class="table table-hover text-nowrap w-100">
                                    <thead>
                                        <tr class="text-bg-primary text-center">
                                            <th>Incidencia</th>
                                            <th>Fecha Incidencia</th>
                                            <th>N° Orden</th>
                                            <th>Empresa</th>
                                            <th>Sucursal</th>
                                            <th>Iniciada</th>
                                            <th>Terminada</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                                <script>
                                    const tb_incidencias = new DataTable('#tb_incidencias', {
                                        autoWidth: true,
                                        scrollX: true,
                                        scrollY: 400,
                                        ajax: {
                                            url: `${__url}/soporte/buzon-personal/incidencias/resueltas/index?ruc=&sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}`,
                                            dataSrc: function (json) {
                                                return json;
                                            },
                                            error: function (xhr, error, thrown) {
                                                boxAlert.table(updateTableInc);
                                                console.log('Respuesta del servidor:', xhr);
                                            }
                                        },
                                        columns: [
                                            { data: 'cod_inc' },
                                            { data: 'fecha_inc' },
                                            { data: 'cod_orden' },
                                            {
                                                data: 'id_sucursal', render: function (data, type, row) {
                                                    var ruc = sucursales[data].ruc;
                                                    return `${empresas[ruc].ruc} - ${empresas[ruc].razon_social}`;
                                                }
                                            },
                                            {
                                                data: 'id_sucursal', render: function (data, type, row) {
                                                    return sucursales[data].nombre;
                                                }
                                            },
                                            { data: 'iniciado' },
                                            { data: 'finalizado' },
                                            { data: 'acciones' }
                                        ],
                                        order: [[1, 'desc']],
                                        createdRow: function (row, data, dataIndex) {
                                            $(row).find('td:eq(0), td:eq(1), td:eq(2), td:eq(5), td:eq(6), td:eq(7)').addClass('text-center');
                                            $(row).find('td:eq(7)').addClass(`td-acciones`);
                                        },
                                        processing: true
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile0" role="tabpanel" aria-labelledby="profile-tab0">
                        <div>
                            <button class="btn btn-primary px-2" onclick="updateTableVis()" data-mdb-ripple-init
                                role="button">
                                <i class="fas fa-rotate-right"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table id="tb_visitas" class="table table-hover text-nowrap" style="width:100%">
                                    <thead>
                                        <tr class="text-bg-primary">
                                            <th>N° Orden</th>
                                            <th>Fecha Visita</th>
                                            <th>Empresa</th>
                                            <th>Sucursal</th>
                                            <th>Iniciada</th>
                                            <th>Terminada</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                                <script>
                                    const tb_visitas = new DataTable('#tb_visitas', {
                                        autoWidth: true,
                                        scrollX: true,
                                        scrollY: 400,
                                        ajax: {
                                            url: `${__url}/soporte/buzon-personal/visitas/resueltas/index?ruc=&sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}`,
                                            dataSrc: function (json) {
                                                return json;
                                            },
                                            error: function (xhr, error, thrown) {
                                                boxAlert.table(updateTableVis);
                                                console.log('Respuesta del servidor:', xhr);
                                            }
                                        },
                                        columns: [
                                            { data: 'cod_orden' },
                                            { data: 'fecha_vis' },
                                            {
                                                data: 'id_sucursal', render: function (data, type, row) {
                                                    var ruc = sucursales[data].ruc;
                                                    return `${empresas[ruc].ruc} - ${empresas[ruc].razon_social}`;
                                                }
                                            },
                                            {
                                                data: 'id_sucursal', render: function (data, type, row) {
                                                    return sucursales[data].nombre;
                                                }
                                            },
                                            { data: 'iniciado' },
                                            { data: 'finalizado' },
                                            { data: 'acciones' }
                                        ],
                                        order: [[1, 'desc']],
                                        createdRow: function (row, data, dataIndex) {
                                            $(row).find('td:eq(0), td:eq(1), td:eq(4), td:eq(5), td:eq(6)').addClass('text-center');
                                            $(row).find('td:eq(6)').addClass(`td-acciones`);
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
    </div>

    <button class="d-none" data-mdb-modal-init data-mdb-target="#modal_seguimiento_visitasp"></button>
    <div class="modal fade" id="modal_seguimiento_visitasp" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Seguimiento de la visita</h6>
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
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <h6 class="font-weight-semibold text-primary tt-upper m-0" style="font-size: smaller;">Seguimiento Visita</h6>
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

    <div class="modal fade" id="modal_detalle" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Detalle de incidencia -
                        <span class="badge badge-success badge-lg" aria-item="codigo"></span>
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
                                <label class="form-label me-2">Tipo Incidencia:</label>
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

@endsection

@section('scripts')
    <script src="{{secure_asset('front/js/soporte/buzon/resueltas.js')}}"></script>
@endsection