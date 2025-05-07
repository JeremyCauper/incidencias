@extends('layout.app')
@section('title', 'INC RESUELTAS')

@section('cabecera')
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{secure_asset('front/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/resueltas.css')}}?v={{ time() }}">
    <script src="{{secure_asset('front/vendor/multiselect/bootstrap.bundle.min.js')}}"></script>
    <script src="{{secure_asset('front/vendor/multiselect/bootstrap_multiselect.js')}}"></script>
    <script src="{{secure_asset('front/vendor/multiselect/form_multiselect.js')}}"></script>

    <script src="{{secure_asset('front/vendor/chartjs/chart.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        let empresas = <?php echo json_encode($data['company']); ?>;
        let sucursales = <?=json_encode($data['scompany'])?>;
        let tipo_soporte = <?php echo json_encode($data['tSoporte']); ?>;
        let tipo_incidencia = <?=json_encode($data['tIncidencia'])?>;
        let obj_problem = <?=json_encode($data['problema'])?>;
        let obj_subproblem = <?=json_encode($data['sproblema'])?>;
        let usuarios = <?=json_encode($data['usuarios'])?>;
    </script>
@endsection
@section('content')

<div class="col-12 mb-4">
        <div class="card">
            <div class="card-body form-container">
                <h6 class="text-primary">Filtros de Busqueda</h6>
                <div class="row">
                    <div class="col-lg-7 my-1">
                        <label class="form-label mb-0" for="empresa">Empresa</label>
                        <select id="empresa" name="empresa" class="select-clear">
                            <option value=""></option>
                            @foreach ($data['company'] as $key => $val)
                                @if ($val->status)
                                    <option value="{{$val->ruc}}">
                                        {{$val->ruc . ' - ' . $val->razon_social}}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5 my-1">
                        <label class="form-label mb-0" for="sucursal">Sucursal</label>
                        <select id="sucursal" name="sucursal" class="select-search" disabled="true">
                            <option selected value="0">Todos</option>
                            @foreach ($data['scompany'] as $key => $val)
                                <option value="{{$val->id}}">{{$val->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-6 my-1">
                        <label class="form-label mb-0" for="tIncidencia">Tipo Soporte</label>
                        <select id="tIncidencia" multiple="multiple" class="multiselect-select-all">
                            @foreach ($data['tIncidencia'] as $v)
                                <option value="{{ $v->id }}" selected>
                                    {{ '<span class="custom-control-label w-100 font-weight-semibold"><label class="badge badge-' . $v->color . ' me-2">' . $v->tipo . '</label><span>' . $v->descripcion . '</span>' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 my-1">
                        <label class="form-label mb-0" for="dateRango">Rango</label>
                        <input type="text" class="form-control" id="dateRango" name="dateRango" role="button" readonly>
                    </div>
                    <div class="col-12 align-items-end d-flex my-1 justify-content-end">
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
                    <strong>Incidencias Resueltas</strong>
                </h6>
                <div>
                    <button type="button" class="d-none" data-mdb-modal-init data-mdb-target="#modal_detalle"></button>
                    <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-4 align-content-center">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="col-8">
                        <table id="tb_orden" class="table table-hover text-nowrap w-100">
                            <thead>
                                <tr class="text-bg-primary text-center">
                                    <th>Incidencia</th>
                                    <th>Estado</th>
                                    <th>Fecha Incidencia</th>
                                    <th>N° Orden</th>
                                    <th>Tecnico</th>
                                    <!-- <th>Empresa</th> -->
                                    <th>Sucursal</th>
                                    <th>Nivel Incidencia</th>
                                    <th>Soporte</th>
                                    <th>Problema / Sub Problema</th>
                                    <th>Iniciada</th>
                                    <th>Terminada</th>
                                    <!-- <th>Acciones</th> -->
                                </tr>
                            </thead>
                        </table>
                        <script>
                            const tb_orden = new DataTable('#tb_orden', {
                                scrollX: true,
                                scrollY: 400,
                                ajax: {
                                    url: `${__url}/soporte/reportes/reporte-incidencias/index?sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}&tIncidencia=${$('#tIncidencia').val()}`,
                                    dataSrc: function (json) {
                                        return json.data;
                                    },
                                    error: function (xhr, error, thrown) {
                                        boxAlert.table();
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'cod_incidencia' },
                                    { data: 'estado' },
                                    { data: 'fecha_inc' },
                                    { data: 'cod_orden' },
                                    {
                                        data: 'asignados', render: function (data, type, row) {
                                            return (data.map(usu => usuarios[usu].nombre)).join(", ");
                                        }
                                    },
                                    {
                                        data: 'sucursal', render: function (data, type, row) {
                                            return sucursales[data].nombre;
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
                                        }
                                    },
                                    { data: 'iniciado' },
                                    { data: 'finalizado' },
                                    // { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).find('td:eq(0), td:eq(1), td:eq(2), td:eq(3), td:eq(7), td:eq(9), td:eq(10)').addClass('text-center');
                                    // $(row).find('td:eq(11)').addClass(`td-acciones`);
                                },
                                order: [[2, 'desc']],
                                processing: true
                            });
                        </script>
                    </div>
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
                        <h6 class="font-weight-semibold text-primary tt-upper m-0" style="font-size: smaller;">Seguimiento
                            Incidencia</h6>
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
    <script src="{{secure_asset('front/js/reporte/incidencias.js')}}?v={{ time() }}"></script>
    <script>
        const data = {
            labels: [
                'Red',
                'Blue',
                'Yellow'
            ],
            datasets: [{
                label: 'Total de Inicidencias',
                data: [200, 50, 100],
                backgroundColor: [
                    'rgb(255, 129, 156)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 20
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                layout: {
                    padding: 20
                },
                plugins: {
                    legend: {
                        position: 'left',
                        align: 'start',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            padding: 10,
                            color: 'rgb(112, 112, 112)',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                        },
                        onHover: (event, legendItem, legend) => {
                            const chart = legend.chart;
                            const index = legendItem.index;
                            // Aplica hover manual al elemento del gráfico
                            chart.setActiveElements([{ datasetIndex: 0, index: index }]);
                            chart.update();
                        },
                        onLeave: (event, legendItem, legend) => {
                            const chart = legend.chart;
                            chart.setActiveElements([]); // Quita el hover
                            chart.update();
                        }
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Doughnut Chart'
                    },
                    datalabels: {
                        color: 'rgb(255, 255, 255)',
                        formatter: (value, ctx) => {
                            const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total * 100).toFixed(1) + "%";
                            return percentage;
                        },
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        };

        new Chart($('#myChart'), config);
    </script>
@endsection