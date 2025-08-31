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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
        .chart-estado-title {
            font-size: .75rem;
        }

        @media (max-width: 550px) {
            .chart-estado-title {
                font-size: .6rem;
            }
        }
    </style>

@endsection
@section('content')

    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body form-container py-3">
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
        <div class="row">
            <div class="col-xxl-2 col-md-4 col-6 mb-2">
                <div class="card">
                    <div class="card-body row text-secondary">
                        <div>
                            <h6 class="card-title chart-estado-title mb-1">TOTAL INCIDENCIAS</h6>
                            <h4 class="subtitle-count"><b data-panel="total">0</b></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 col-6 mb-2">
                <div class="card">
                    <div class="card-body row text-warning">
                        <div class="col-7">
                            <h6 class="card-title chart-estado-title mb-1">SIN ASIGNAR</h6>
                            <h4 class="subtitle-count"><b data-panel="sinasignar">0</b></h4>
                        </div>
                        <div class="col-5 p-0">
                            <div id="chart-estado0"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 col-6 mb-2">
                <div class="card">
                    <div class="card-body row text-info">
                        <div class="col-7">
                            <h6 class="card-title chart-estado-title mb-1">ASIGNADOS</h6>
                            <h4 class="subtitle-count"><b data-panel="asignados">0</b></h4>
                        </div>
                        <div class="col-5 p-0">
                            <div id="chart-estado1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 col-6 mb-2">
                <div class="card">
                    <div class="card-body row text-primary">
                        <div class="col-7">
                            <h6 class="card-title chart-estado-title mb-1">EN PROCESO</h6>
                            <h4 class="subtitle-count"><b data-panel="enproceso">0</b></h4>
                        </div>
                        <div class="col-5 p-0">
                            <div id="chart-estado2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 col-6 mb-2">
                <div class="card">
                    <div class="card-body row text-success">
                        <div class="col-7">
                            <h6 class="card-title chart-estado-title mb-1">FINALIZADOS</h6>
                            <h4 class="subtitle-count"><b data-panel="finalizados">0</b></h4>
                        </div>
                        <div class="col-5 p-0">
                            <div id="chart-estado3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 col-6 mb-2">
                <div class="card">
                    <div class="card-body row text-danger">
                        <div class="col-7">
                            <h6 class="card-title chart-estado-title mb-1">FALTAN DATOS</h6>
                            <h4 class="subtitle-count"><b data-panel="faltandatos">0</b></h4>
                        </div>
                        <div class="col-5 p-0">
                            <div id="chart-estado4"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-2">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-12" style="color: #9fa6b2; font-family: Arial, sans-serif; font-size: 13.8px; font-weight: 700;">Cantidad de incidencias por fecha</div>
                        <div class="col-xl-2 col-lg-3 align-content-center mb-2 py-2">
                            <div type="button" class="btn border text-lg-start d-lg-block my-2 py-lg-4 text-nowrap text-bg-primary" data-mdb-ripple-init
                                data-mdb-ripple-color="dark" onclick="toggleSeries('INCIDENCIAS', this)">
                                <i class="fas fa-file-invoice me-1"></i>INCIDENCIAS
                            </div>
                            <div type="button" class="btn border text-lg-start d-lg-block my-2 py-lg-4 text-nowrap text-bg-success" data-mdb-ripple-init
                                data-mdb-ripple-color="dark" onclick="toggleSeries('VISITAS', this)">
                                <i class="fas fa-van-shuttle me-1"></i>VISITAS
                            </div>
                            <div type="button" class="btn border text-lg-start d-lg-block my-2 py-lg-4 text-nowrap text-bg-warning" data-mdb-ripple-init
                                data-mdb-ripple-color="dark" onclick="toggleSeries('MANTENIMIENTOS', this)">
                                <i class="fas fa-screwdriver-wrench me-1"></i>MANTENIMIENTOS
                            </div>
                        </div>
                        <div class="col-xl-10 col-lg-9 mb-2">
                            <div id="chart-actividades"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-2">
            <div class="card">
                <div class="card-body">
                    <div id="chart-incidencias"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-2">
            <div class="card">
                <div class="card-body">
                    <div id="chart-tipo-incidencias"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script src="{{secure_asset('front/js/app/ChartMananger2.js')}}"></script>
    <script src="{{secure_asset('front/js/dashboard/rci/dashboard_incidencias2.js')}}?v={{ time() }}"></script>
@endsection