@extends('layout.appEmpresa')
@section('title', 'INC RESUELTAS')

@section('cabecera')
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{secure_asset('front/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/resueltas.css')}}?v={{ time() }}">

    <!-- <script src=""></script> -->
    <script src="{{secure_asset('front/vendor/echartjs/echarts.min.js')}}"></script>
    <script src="{{secure_asset('front/vendor/dom-to-image/dom-to-image.min.js')}}"></script>

    <script>
        let empresa = <?php echo json_encode(session('empresa')); ?>;
        let sucursales = <?=json_encode($data['scompany'])?>;
    </script>
    <style>
        #chart-estado,
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
                        <input type="text" class="form-control" value="{{ session('config_layout')->nombre_perfil }}" readonly role="button">
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
            <div class="text-center mb-3 py-3 text-bg-primary rounded-top-2">
                <h2 class="mb-0">ANALISIS DE INCIDENCIAS</h2>
            </div>
            <div class="card-body pt-0">
                <div class="col-12 mb-4">
                    <div class="row justify-content-center panel-view">
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
                                <div class="title-estadisticas text-secondary shadow-2-strong">NIVELES DE INCIDENCIAS</div>
                                <div class="card-body">
                                    <div class="col-12">
                                        <div id="chart-niveles"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8 grid-margin">
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
    <script src="{{secure_asset('front/js/reporte/cliente/crincidencias.js')}}?v={{ time() }}"></script>
@endsection