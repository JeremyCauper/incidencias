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

    <!-- <script src=""></script> -->
    <script src="{{secure_asset('front/vendor/echartjs/echarts.min.js')}}"></script>
    <script src="{{secure_asset('front/vendor/dom-to-image/dom-to-image.min.js')}}"></script>


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
        #chart-estado,
        #chart-personal,
        #chart-problemas,
        #chart-niveles {
            position: relative;
            height: 40vh;
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
                    <div class="col-lg-6 my-1">
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
                    <div class="col-lg-4 col-sm-8 my-1">
                        <label class="form-label mb-0" for="sucursal">Sucursal</label>
                        <select id="sucursal" name="sucursal" class="select-search" disabled="true">
                            <option selected value="0">Todos</option>
                            @foreach ($data['scompany'] as $key => $val)
                                <option value="{{$val->id}}">{{$val->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- <div class="col-md-4 col-6 my-1">
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
                        </div> -->
                    <div class="col-lg-2 col-sm-4 my-1">
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

    <div class="col-12" id="chart-container">
        <div class="card">
            <div class="mb-3 py-3 rounded-top-2 text-bg-primary chart-container-title"> <!-- chart-title -->
                <div class="logo_rci position-absolute d-none" style="left: 0;"></div>
                <h2 class="mb-0 text-nowrap">ANALISIS DE INCIDENCIAS</h2>
            </div>
            <div class="card-body pt-0">
                <div class="col-12 mb-4">
                    <div class="row panel-view">
                        <div class="col-xl-6 grid-margin">
                            <div class="card shadow-2-strong grid-estadisticas grid-loading">
                                <div class="title-estadisticas text-secondary shadow-2-strong">ESTADO DE INCIDENCIAS</div>
                                <div class="card-body">
                                    <div class="col-12">
                                        <div id="chart-estado"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 grid-margin">
                            <div class="card shadow-2-strong grid-estadisticas grid-loading">
                                <div class="title-estadisticas text-secondary shadow-2-strong">ACTIVIDADES DEL PERSONAL
                                </div>
                                <div class="card-body">
                                    <div class="col-12">
                                        <div id="chart-personal"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 grid-margin">
                            <div class="card shadow-2-strong grid-estadisticas grid-loading">
                                <div class="title-estadisticas text-secondary shadow-2-strong">PROBLEMAS DE INCIDENCIAS
                                </div>
                                <div class="card-body">
                                    <div class="col-12">
                                        <div id="chart-problemas"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 grid-margin">
                            <div class="card shadow-2-strong grid-estadisticas grid-loading">
                                <div class="title-estadisticas text-secondary shadow-2-strong">NIVELES DE INCIDENCIAS</div>
                                <div class="card-body">
                                    <div class="col-12">
                                        <div id="chart-niveles"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="{{secure_asset('front/js/app/ChartMananger.js')}}"></script>
    <script src="{{secure_asset('front/js/reporte/rci/incidencias.js')}}?v={{ time() }}"></script>
@endsection