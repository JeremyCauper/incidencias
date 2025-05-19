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

        .capturar-btn-wrapper {
            cursor: pointer;
            position: fixed;
            right: 40px;
            bottom: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            background-color: #3b72ca;
            color: #fff;
            z-index: 1045;
            border-radius: 6px 50% 50% 6px;
            border-radius: 6px;
        }

        .capturar-btn-wrapper>div {
            padding: 0 .85rem;
        }

        @media only screen and (max-width: 512px) {
            .capturar-btn-wrapper>div {
                display: none;
            }
        }

        .capturar-btn {
            padding: .6rem;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            background-color: #6691d6;
            transform: scale(1.3);
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
                <!-- <div class="card-title text-primary mb-3">
                                                                                    </div> -->
                <div class="row justify-content-center">
                    <div class="col-xl-12">
                        <div id="chart-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="capturar-btn-wrapper" aria-label="Guardar Imagen de Graficos" title="Guardar Imagen de Graficos"
        onclick="capturar()">
        <div>Guardar Imagen</div>
        <button class="capturar-btn btn-floating d-flex align-items-center justify-content-center"
            aria-label="Guardar Imagen de Graficos" title="Guardar Imagen de Graficos">
            <i class="fas fa-camera fa-1x"></i>
        </button>
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

        const data_estado = [
            { value: 80, name: 'Sin Asignar', itemStyle: { color: 'rgb(228, 161, 27)' } },
            { value: 35, name: 'Asignada', itemStyle: { color: 'rgb(84, 180, 211)' } },
            { value: 15, name: 'En Proceso', itemStyle: { color: 'rgb(59, 113, 202)' } },
            { value: 40, name: 'Faltan Datos', itemStyle: { color: 'rgb(220, 76, 100)' } },
            { value: 284, name: 'Finalizado', itemStyle: { color: 'rgb(20, 164, 77)' } },
            { value: 100, name: 'Cierre Sistemas', itemStyle: { color: 'rgb(159, 166, 178)' } }
        ];

        const data_personal = [
            { name: 'RENZO VIGO', count: { incidencias: 12, visitas: 120 } },
            { name: 'Soporte01 Tecnico', count: { incidencias: 51, visitas: 71 } },
            { name: 'Soporte02 Tecnico', count: { incidencias: 24, visitas: 88 } },
            { name: 'OMAR SAENZ', count: { incidencias: 41, visitas: 40 } },
            { name: 'ALVARO HUERTA', count: { incidencias: 54, visitas: 65 } },
            { name: 'JHERSON VILCAPOMA', count: { incidencias: 21, visitas: 74 } },
            { name: 'GIANFRANCO ESTEBAN', count: { incidencias: 31, visitas: 58 } },
            { name: 'KHESNIL CANCHARI', count: { incidencias: 12, visitas: 34 } },
            { name: 'DAYSI MENDOZA', count: { incidencias: 75, visitas: 45 } },
            { name: 'SAMUEL VELARDE', count: { incidencias: 41, visitas: 53 } },
            { name: 'RODRIGO ALVAREZ', count: { incidencias: 72, visitas: 67 } },
            { name: 'OWEN TRUJILLO', count: { incidencias: 100, visitas: 83 } },
            { name: 'SEBASTIAN INCIO', count: { incidencias: 41, visitas: 92 } },
            { name: 'EDUARDO ESCOBAR', count: { incidencias: 27, visitas: 61 } },
        ];

        const data_problema = [
            { value: 423, name: 'PI-0001' }, // , itemStyle: { color: 'rgb(45, 45, 45)' }
            { value: 845, name: 'PI-0002' }, // , itemStyle: { color: 'rgb(84, 84, 84)' }
            { value: 784, name: 'PI-0003' }, // , itemStyle: { color: 'rgb(12, 12, 12)' }
            { value: 659, name: 'PI-0004' }, // , itemStyle: { color: 'rgb(24, 24, 24)' }
            { value: 243, name: 'PI-0005' }, // , itemStyle: { color: 'rgb(32, 32, 32)' }
            { value: 219, name: 'PI-0006' }, // , itemStyle: { color: 'rgb(26, 26, 26)' }
            { value: 321, name: 'PI-0007' }, // , itemStyle: { color: 'rgb(56, 56, 56)' }
            { value: 156, name: 'PI-0008' }, // , itemStyle: { color: 'rgb(75, 75, 75)' }
            { value: 357, name: 'PS-0001' }, // , itemStyle: { color: 'rgb(64, 64, 64)' }
            { value: 456, name: 'PS-0002' } // , itemStyle: { color: 'rgb(90, 90, 90)' }
        ];
        const total_nivel = data_problema.reduce((sum, item) => sum + item.value, 0);
        data_problema.sort((a, b) => a.value - b.value);

        const data_nivel = [
            { value: 80, name: 'N1 - REMOTO', itemStyle: { color: 'rgb(159, 166, 178)' } },
            { value: 35, name: 'N2 - PRESENCIAL', itemStyle: { color: 'rgb(84, 180, 211)' } },
            { value: 15, name: 'N3 - PROVEEDOR', itemStyle: { color: 'rgb(51, 45, 45)' } }
        ];


        let config_title = {
            textStyle: {
                color: '#999',
                fontWeight: 'normal',
                fontSize: 14
            }
        }

        const waterMarkText = 'RC ING.';
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = canvas.height = 100;

        // 🟨 Fondo blanco
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Marca de agua encima
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.globalAlpha = 0.08;
        ctx.font = '20px Microsoft Yahei';
        ctx.translate(50, 50);
        ctx.rotate(-Math.PI / 4);
        ctx.fillStyle = '#9e9e9e'; // color del texto
        ctx.fillText(waterMarkText, 0, 0);

        const labelOptionBar = {
            show: true,
            position: 'top',
            distance: 2,
            align: 'left',
            verticalAlign: 'middle',
            rotate: 90,
            formatter: function (params) {
                const percent = ((params.value / total_nivel) * 100).toFixed(1);
                return `${params.value} (${percent}%)`;
            },
            color: 'rgb(154, 158, 165)'
        };

        const resizeOption = {
            serie1: {
                legend: {
                    '2000': {
                        top: '12%',
                        left: '3%',
                        width: '40%'
                    },
                    '1350': {
                        top: '11%',
                        left: '10%',  // o '0%', o una distancia como '10px'
                        orient: 'vertical'  // o 'vertical', según cómo la quieras distribuir
                    }
                },
                title: {
                    '2000': {
                        top: '8%',
                        left: '25%',
                        textAlign: 'center',
                    },
                    '1350': {
                        top: '6.5%',
                        left: 'center',
                    }
                },
                radius: {
                    '2000': ['13%', '23%'],
                    '1350': ['5%', '11%'],
                },
                center: {
                    '2000': ['25%', '35%'],
                    '1350': ['60%', '20%'],
                },
            },
            serie2: {
                legend: {
                    '2000': {
                        top: '12%',
                        left: '56%',
                    },
                    '1350': {
                        top: '32%',
                        left: 'center',
                    }
                },
                title: {
                    '2000': {
                        top: '8%',
                        left: '75%',
                        textAlign: 'center',
                    },
                    '1350': {
                        top: '30%',
                        left: 'center',
                    }
                },
                grid: {
                    '2000': {
                        top: '25%',
                        left: '52%',
                        bottom: '47%',
                        width: '45%',
                    },
                    '1350': {
                        top: '34%',
                        bottom: '47%',
                    }
                },
            },
            serie3: {
                title: {
                    '2000': {
                        top: '55%',
                        left: '25%',
                        textAlign: 'center',
                    },
                    '1350': {
                        top: '50%',
                        left: 'center',
                    }
                },
                grid: {
                    '2000': {
                        top: '60%',
                        left: '3%',
                        bottom: '5%',
                        width: '45%',
                    },
                    '1350': {
                        top: '52%',
                        bottom: '28%',
                    }
                },
            },
            serie4: {
                legend: {
                    '2000': {
                        top: '60%',
                        left: '56%',
                        width: '35%'
                    },
                    '1350': {
                        top: '83%',
                        left: '10%',  // o '0%', o una distancia como '10px'
                        orient: 'vertical'  // o 'vertical', según cómo la quieras distribuir
                    }
                },
                title: {
                    '2000': {
                        top: '55%',
                        left: '75%',
                        textAlign: 'center',
                    },
                    '1350': {
                        top: '76%',
                        left: 'center',
                    }
                },
                radius: {
                    '2000': ['13%', '23%'],
                    '1350': ['5%', '11%'],
                },
                center: {
                    '2000': ['75%', '80%'],
                    '1350': ['60%', '90%'],
                },
            }
        };

        option = {
            backgroundColor: {
                type: 'pattern',
                image: canvas,
                repeat: 'repeat'
            },
            legend: [
                {
                    show: true,
                    data: data_estado.map(item => item.name),
                    ...resizeOption.serie1.legend['1350']
                },
                {
                    show: true,
                    data: ['Incidencias', 'Visitas'],
                    ...resizeOption.serie2.legend['1350']
                },
                {
                    show: false
                },
                {
                    show: true,
                    data: data_nivel.map(item => item.name),
                    ...resizeOption.serie4.legend['1350']
                }
            ],
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                },
            },
            graphic: [
                {
                    type: 'line',
                    left: 'center',
                    top: 40,
                    shape: {
                        x1: -120,
                        y1: 0,
                        x2: 120,
                        y2: 0
                    },
                    style: {
                        stroke: 'rgb(159, 166, 178)',
                        lineWidth: 2
                    }
                },
            ],
            title: [
                {
                    text: 'ANALISIS DE INCIDENCIAS',
                    left: '50%',
                    top: '2%',
                    textAlign: 'center',
                    textStyle: {
                        fontSize: 20,
                        color: 'rgb(159, 166, 178)'
                    }
                },
                {
                    text: 'Estado de Incidencias',
                    ...resizeOption.serie1.title['1350'],
                    ...config_title
                },
                {
                    text: 'Actividades del Personal',
                    ...resizeOption.serie2.title['1350'],
                    ...config_title
                },
                {
                    text: 'Estadisticas Problemas',
                    ...resizeOption.serie3.title['1350'],
                    ...config_title
                },
                {
                    text: 'Nivel de Incidencias',
                    ...resizeOption.serie4.title['1350'],
                    ...config_title
                },
            ],
            grid: [
                {
                    ...resizeOption.serie2.grid['1350'],
                    containLabel: true
                },
                {
                    ...resizeOption.serie3.grid['1350'],
                    containLabel: true
                }
            ],
            xAxis: [
                {
                    type: 'category',
                    axisTick: { show: false },
                    data: data_personal.map(item => item.name),
                    axisLabel: {
                        interval: 0,
                        rotate: 30
                    },
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
                    data: data_problema.map(item => item.name),
                    splitLine: {
                        show: false
                    }
                }
            ],

            series: [
                {
                    name: 'Estado Total',
                    type: 'pie',
                    radius: resizeOption.serie1.radius['1350'],
                    center: resizeOption.serie1.center['1350'],
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
                    data: data_estado
                },
                {
                    name: 'Incidencias',
                    type: 'bar',
                    barGap: 0,
                    label: labelOptionBar,
                    itemStyle: {
                        borderRadius: 3,
                        color: 'rgb(84, 180, 211)'
                    },
                    data: data_personal.map(item => item.count.incidencias)
                },
                {
                    name: 'Visitas',
                    type: 'bar',
                    barGap: 0,
                    label: labelOptionBar,
                    itemStyle: {
                        borderRadius: 3,
                        color: 'rgb(228, 161, 27)'
                    },
                    data: data_personal.map(item => item.count.visitas)
                },
                {
                    name: 'Problema Total',
                    type: 'bar',
                    xAxisIndex: 1,
                    yAxisIndex: 1,
                    itemStyle: {
                        borderRadius: 3,
                        color: 'rgb(59, 113, 202)'
                    },
                    label: {
                        show: true,
                        position: 'right',
                        formatter: function (params) {
                            const percent = ((params.value / total_nivel) * 100).toFixed(1);
                            return `${params.value} (${percent}%)`;
                        },
                        color: 'rgb(159, 166, 178)',
                    },
                    data: data_problema
                },
                {
                    name: 'Nivel Total',
                    type: 'pie',
                    radius: resizeOption.serie4.radius['1350'],
                    center: resizeOption.serie4.center['1350'],
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
                    data: data_nivel
                }
            ]
        };

        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }


        // window.addEventListener('resize', myChart.resize);

        fObservador('.content-wrapper', () => {
            myChart.resize();
            console.log($('#chart-container').width());
        });

        function capturar() {
            let imgData = myChart.getDataURL({
                type: 'png',
                pixelRatio: 2,
            });

            let link = document.createElement('a');
            link.href = imgData;
            link.download = 'Aanálisis de Incidencias.png';
            link.click();
        }
    </script>
@endsection