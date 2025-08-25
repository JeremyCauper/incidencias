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
        #chart-niveles,
        #chart-contable {
            position: relative;
            height: 40vh;
            overflow: hidden;
        }

        #chart-subproblemas {
            position: relative;
            height: 60vh;
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
                        <input type="text" class="form-control" id="empresa"
                            value="{{ session('config_layout')->nombre_perfil }}" readonly role="button">
                    </div>
                    <div class="col-lg-4 col-sm-8 my-1">
                        <label class="form-label mb-0" for="sucursal">Sucursal</label>
                        <select id="sucursal" name="sucursal" class="select-search">
                            <option selected value="">Todos</option>
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
            <div class="mb-3 py-3 rounded-top-2 text-bg-primary chart-container-header"> <!-- chart-header -->
                <div class="logo_rci_white position-absolute d-none" style="left: 0;"></div>
                <h2 class="mb-0 text-nowrap">ANALISIS DE INCIDENCIAS</h2>
            </div>
            <div class="card-body pt-0 chart-container-body">
                <div class="card chart-info d-none mb-4">
                    <div class="card-body py-2">
                        <div class="row">
                            <div class="col-md-8">
                                <div>
                                    <label class="form-label me-2">Empresa: </label>
                                    <span style="font-size: .75rem;" aria-item="empresa">...</span>
                                </div>
                                <div>
                                    <label class="form-label me-2">Sucursal: </label>
                                    <span style="font-size: .75rem;" aria-item="sucursal">...</span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <label class="form-label me-2">Fecha: </label>
                                <span style="font-size: .75rem;" aria-item="fechas">...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-4">
                    <div class="row panel-view justify-content-center">
                        <div class="col-xl-6 grid-margin">
                            <div class="card chart-contenedor chart-loading">
                                <div class="chart-title text-secondary">ESTADO DE INCIDENCIAS</div>
                                <div class="card-body">
                                    <div class="chart-descripcion">Muestra la distribución de las incidencias según su
                                        estado.</div>
                                    <div class="col-12">
                                        <div id="chart-estado"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 grid-margin">
                            <div class="card chart-contenedor chart-loading">
                                <div class="chart-title text-secondary">NIVELES DE INCIDENCIAS</div>
                                <div class="card-body">
                                    <div class="chart-descripcion">Muestra la distribución del nivel de atención alcanzado.
                                    </div>
                                    <div class="col-12">
                                        <div id="chart-niveles"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8 grid-margin">
                            <div class="card chart-contenedor chart-loading">
                                <div class="chart-title text-secondary">PROBLEMAS DE INCIDENCIAS
                                </div>
                                <div class="card-body">
                                    <div class="chart-descripcion">Muestra los 10 problemas más frecuentes registrados.
                                    </div>
                                    <div class="col-12">
                                        <div id="chart-problemas"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8 grid-margin">
                            <div class="card chart-contenedor chart-loading">
                                <div class="chart-title text-secondary">SUCURSALES</div>
                                <div class="card-body">
                                    <!-- <div class="chart-descripcion">Muestra la distribución del nivel de atención alcanzado.</div> -->
                                    <div class="col-12">
                                        <div id="chart-contable"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="d-none" data-mdb-modal-init data-mdb-target="#modal_subproblema"></button>
    <div class="modal fade" id="modal_subproblema" aria-labelledby="modal_subproblema" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">SUB PROBLEMAS DE INCIDENCIAS</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card chart-contenedor chart-loading">
                        <div class="chart-title text-secondary"></div>
                        <div class="card-body">
                            <div class="chart-descripcion"></div>
                            <div class="col-12">
                                <div id="chart-subproblemas"></div>
                            </div>
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

    <div class="capturar-btn-wrapper" aria-label="Guardar Imagen de Graficos" title="Guardar Imagen de Graficos"
        onclick="capturar()">
        <div>Guardar Imagen</div>
        <button class="capturar-btn btn-floating d-flex align-items-center justify-content-center"
            aria-label="Guardar Imagen de Graficos" title="Guardar Imagen de Graficos">
            <i class="fas fa-camera fa-1x text-white"></i>
        </button>
    </div>

@endsection

@section('scripts')
    <script src="{{secure_asset('front/js/app/ChartMananger.js')}}"></script>
    <script src="{{secure_asset('front/js/dashboard/empresa/dashboard_incidencias.js')}}?v={{ time() }}"></script>
@endsection