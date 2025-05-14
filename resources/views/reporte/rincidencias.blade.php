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

    <!-- <script src="{{secure_asset('front/vendor/chartjs/chart.js')}}"></script>
                                                                                        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/echarts@5.6.0/dist/echarts.min.js"></script> -->
    <script src="https://echarts.apache.org/en/js/vendors/echarts/dist/echarts.min.js"></script>

    <script>
        let empresas = <?php echo json_encode($data['company']); ?>;
        let sucursales = <?=json_encode($data['scompany'])?>;
        let tipo_soporte = <?php echo json_encode($data['tSoporte']); ?>;
        let tipo_incidencia = <?=json_encode($data['tIncidencia'])?>;
        let obj_problem = <?=json_encode($data['problema'])?>;
        let obj_subproblem = <?=json_encode($data['sproblema'])?>;
        let usuarios = <?=json_encode($data['usuarios'])?>;
    </script>
    <style>
        #chart-container {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }
    </style>
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
                        <label class="form-label mb-0" for="tIncidencia">Nivel Incidencia</label>
                        <select id="tIncidencia" multiple="multiple" class="multiselect-select-all">
                            @foreach ($data['tIncidencia'] as $v)
                                <option value="{{ $v->id }}" selected>
                                    {{ '<span class="custom-control-label w-100"><label class="badge badge-' . $v->color . ' ms-2 me-1">' . $v->tipo . '</label><span>' . $v->descripcion . '</span>' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-6 my-1">
                        <label class="form-label mb-0" for="tSoporte">Tipo Soporte</label>
                        <select id="tSoporte" multiple="multiple" class="multiselect-select-all">
                            @foreach ($data['tSoporte'] as $v)
                                <option value="{{ $v->id }}" selected>
                                    {{ $v->descripcion }}
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
                    <strong>Reporte Incidencias</strong>
                </h6>
                <div class="row justify-content-center">
                    <div class="col-xl-12">
                        <div id="chart-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{secure_asset('front/js/reporte/incidencias.js')}}?v={{ time() }}"></script>
    <script>
        var dom = document.getElementById('chart-container');
        var myChart = echarts.init(dom, 'null', {
            renderer: 'canvas',
            useDirtyRect: false
        });
        var app = {};

        var option = {}

        const data_nivel = [
            { value: 120, name: 'A' },
            { value: 80, name: 'B' },
            { value: 150, name: 'C' }
        ];
        const total_nivel = data_nivel.reduce((sum, item) => sum + item.value, 0);

        option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            title: [
                {
                    text: 'Estado de Incidencias',
                    // subtext: '总计 ' + builderJson.all,
                    left: '25%',
                    top: '5%',
                    textAlign: 'center'
                },
                {
                    text: 'Nivel de Incidencias',
                    left: '75%',
                    top: '5%',
                    textAlign: 'center'
                },
                {
                    text: 'Problemas',
                    left: '25%',
                    top: '55%',
                    textAlign: 'center'
                },
                {
                    text: 'Personal',
                    left: '75%',
                    top: '55%',
                    textAlign: 'center'
                }
            ],
            grid: [
                {
                    top: '15%',
                    width: '45%',
                    bottom: '50%',
                    left: '50%',
                    containLabel: true
                },
                {
                    top: '62%',
                    width: '45%',
                    bottom: 0,
                    left: 10,
                    containLabel: true
                }
            ],
            xAxis: [
                {
                    type: 'category',
                    data: ['N1 - REMOTO', 'N2 - PRESENCIAL', 'N3 - PROVEEDOR'],
                    splitLine: {
                        show: false
                    }
                },
                {
                    gridIndex: 1,
                    type: 'value'
                }
            ],
            yAxis: [
                {
                    type: 'value'
                },
                {
                    gridIndex: 1,
                    type: 'category',
                    data: ['N1 - REMOTO', 'N2 - PRESENCIAL', 'N3 - PROVEEDOR'],
                    splitLine: {
                        show: false
                    }
                }
            ],
            series: [
                {
                    name: 'Access From',
                    type: 'pie',
                    radius: ['13%', '27%'],
                    center: ['26%', '30%'],
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 4,
                        borderColor: '#ffffff',
                        borderWidth: 1
                    },
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    },
                    label: {
                        formatter: '{name|{b}}\n{time|{c} ({d}%)}',
                        lineHeight: 13,
                        rich: {
                            time: { fontSize: 10, color: '#999' }
                        }
                    },
                    data: [
                        {
                            value: 80,
                            name: 'Sin Asignar',
                            itemStyle: { color: 'rgb(228, 161, 27)' }
                        },
                        {
                            value: 35,
                            name: 'Asignada',
                            itemStyle: { color: 'rgb(84, 180, 211)' }
                        },
                        {
                            value: 15,
                            name: 'En Proceso',
                            itemStyle: { color: 'rgb(59, 113, 202)' }
                        },
                        {
                            value: 40,
                            name: 'Faltan Datos',
                            itemStyle: { color: 'rgb(220, 76, 100)' }
                        },
                        {
                            value: 284,
                            name: 'Finalizado',
                            itemStyle: { color: 'rgb(20, 164, 77)' }
                        },
                        {
                            value: 100,
                            name: 'Cierre Sistemas',
                            itemStyle: { color: 'rgb(159, 166, 178)' }
                        }
                    ]
                },
                {
                    name: 'Nivel',
                    type: 'bar',
                    barWidth: '40%',
                    itemStyle: {
                        borderRadius: 3,
                        borderColor: '#ffffff',
                        borderWidth: 1
                    },
                    label: {
                        show: true,
                        position: 'top', // o 'insideTop', 'top'
                        align: 'center',     // ← usa esto, no textAlign
                        verticalAlign: 'middle',
                        formatter: function (params) {
                            const percent = ((params.value / total_nivel) * 100).toFixed(1);
                            return `${params.value} (${percent}%)`;
                        },
                        rich: {
                            value: {
                                fontSize: 12,
                                color: '#fff'
                            }
                        }
                    },
                    data: [
                        { value: 120, itemStyle: { color: '#e74c3c' } },  // rojo
                        { value: 50, itemStyle: { color: '#3498db' } },   // azul
                        { value: 205, itemStyle: { color: '#2ecc71' } }   // verde
                    ]
                },
                {
                    name: 'Nivel 2',
                    type: 'bar',
                    barWidth: '40%',
                    xAxisIndex: 1,
                    yAxisIndex: 1,
                    itemStyle: {
                        borderRadius: 3,
                        borderColor: '#ffffff',
                        borderWidth: 1
                    },
                    label: {
                        show: true,
                        position: 'top', // o 'insideTop', 'top'
                        align: 'center',     // ← usa esto, no textAlign
                        verticalAlign: 'middle',
                        formatter: function (params) {
                            const percent = ((params.value / total_nivel) * 100).toFixed(1);
                            return `${params.value} (${percent}%)`;
                        },
                        rich: {
                            value: {
                                fontSize: 12,
                                color: '#fff'
                            }
                        }
                    },
                    data: [
                        { value: 12, itemStyle: { color: '#e74c3c' } },  // rojo
                        { value: 50, itemStyle: { color: '#3498db' } },   // azul
                        { value: 205, itemStyle: { color: '#2ecc71' } }   // verde
                    ]
                }
            ]
        };

        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

        window.addEventListener('resize', myChart.resize);
    </script>
@endsection