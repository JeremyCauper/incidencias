@extends('layout.app')
@section('title', 'INC RESUELTAS')

@section('cabecera')
    <script type="text/javascript" src="{{secure_asset($ft_js->daterangepicker_moment)}}"></script>
    <script type="text/javascript" src="{{secure_asset($ft_js->daterangepicker)}}"></script>
    <link rel="stylesheet" type="text/css" href="{{secure_asset($ft_css->daterangepicker)}}">

    <script>
        let empresas = @json($data['empresas']);
        let sucursales = @json($data['sucursales']);
        let tipo_incidencia = @json($data['tIncidencia']);
        let tipo_soporte = @json($data['tSoporte']);
        let obj_problem = @json($data['problema']);
        let obj_subproblem = @json($data['sproblema']);
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
                            <option value="">Seleccione...</option>
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


    <div class="nav nav-tabs my-3 gap-2" id="myTab0" role="tablist">
        <div class="nav-item" role="presentation">
            <button data-mdb-tab-init class="nav-link position-relative rounded-top-1 active" id="home-tab0"
                data-mdb-target="#home0" type="button" role="tab" aria-controls="home" aria-selected="true"
                data-mdb-ripple-init onclick="resetTable(false)">
                Incidencias
            </button>
        </div>
        <div class="nav-item" role="presentation">
            <button data-mdb-tab-init class="nav-link position-relative rounded-top-1" id="profile-tab0"
                data-mdb-target="#profile0" type="button" role="tab" aria-controls="profile" aria-selected="false"
                data-mdb-ripple-init onclick="resetTable(true)">
                Visitas
            </button>
        </div>
    </div>

    <div class="tab-content" id="myTabContent0">
        <div class="tab-pane fade show active" id="home0" role="tabpanel" aria-labelledby="home-tab0">
            <div id="contenedor_registros_incidencias"></div>
            <script src="{{ secure_asset('front/js/soporte/buzon/vista-registros-resueltas-incidencias.js') }}"></script>
            <!-- <div>
                    <button class="btn btn-primary" onclick="updateTableInc()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_incidencias" class="table table-hover text-nowrap w-100">
                            <thead>
                                <tr class="text-center">
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
                </div> -->
        </div>
        <div class="tab-pane fade" id="profile0" role="tabpanel" aria-labelledby="profile-tab0">
            <div id="contenedor_registros_visitas"></div>
            <script src="{{ secure_asset('front/js/soporte/buzon/vista-registros-resueltas-visitas.js') }}"></script>
            <!-- <div>
                    <button class="btn btn-primary" onclick="updateTableVis()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_visitas" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>N° Orden</th>
                                    <th>Fecha Visita</th>
                                    <th>Empresa</th>
                                    <th>Sucursal</th>
                                    <th>Iniciada</th>
                                    <th>Terminada</th>
                                    <th>Acciones</th>
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
                </div> -->
        </div>
    </div>

    <button class="d-none" data-mdb-modal-init data-mdb-target="#modal_seguimiento_visitasp"></button>
    <div class="modal fade" id="modal_seguimiento_visitasp" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(59 113 202 / 25%);">
                    <h5 class="modal-title">Detalle de la visita</h5>
                    <div class="align-items-center d-flex gap-2">
                        <span aria-item="estado"></span>
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

    <div class="modal fade" id="modal_detalle" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(59 113 202 / 25%);">
                    <h5 class="modal-title">Detalle de incidencia
                        <span class="text-nowrap">
                            <span class="badge badge-lg rounded-pill" style="background-color: #5a8bdb"
                                aria-item="codigo"></span>
                            <span class="badge badge-lg rounded-pill ms-1" style="background-color: #5a8bdb"
                                aria-item="codigo_orden"></span>
                        </span>
                    </h5>
                    <div class="align-items-center d-flex gap-2">
                        <span aria-item="estado"></span>
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

                            <h6 class="text-uppercase my-4 title_detalle">
                                <i class="fas fa-circle-exclamation me-2"></i> Detalles del Soporte
                            </h6>
                            <div class="detalle_body d-flex align-items-center justify-content-between mb-3">
                                <span class="detalle_text mb-0">Tipo Soporte</span>
                                <span class="detalle_text text-uppercase fw-bolder mb-0" aria-item="soporte"></span>
                            </div>
                            <div class="detalle_body mb-3">
                                <p class="detalle_label mb-1 text-uppercase fw-bolder text-muted">Problema Reportado</p>
                                <p class="detalle_text fw-bold mb-0" aria-item="problema"></p>
                                <p class="detalle_text text-muted fst-italic mb-0 mt-2" aria-item="subproblema"></p>
                            </div>

                            <div class="detalle_body"
                                style="background-color: rgb(123 126 255 / 19%);border-color: rgb(99 102 241 / 0.1);">
                                <label class="form-label text-uppercase me-2" style="color: rgb(99 102 241 / 1);"><i
                                        class="fas fa-stopwatch"></i> Nivel de Incidencia:</label>
                                <div aria-item="incidencia"></div>
                            </div>

                            <h6 class="text-uppercase mt-4 mb-2 title_detalle">Observación</h6>
                            <div class="detalle_body datalle_observacion" aria-item="observacion">
                            </div>
                        </div>
                        <div class="col-lg-7 p-4 modal-col-scrollable personalized-scroll">
                            <div class="align-items-center d-flex mt-2 mb-4">
                                <h4 class="mb-0 text-nowrap text-uppercase title_detalle">
                                    <i class="fas fa-clock-rotate-left" style="color: rgb(99 102 241 / 1)"></i>
                                    SEGUIMIENTO INCIDENCIA
                                </h4>
                                <div class="ms-2 rounded-pill"
                                    style="height: .35rem;width: 100%;background-color: rgb(148 163 184 / 11%)"></div>
                            </div>
                            <div class="content_seguimiento" aria-item="contenedor-seguimiento"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
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