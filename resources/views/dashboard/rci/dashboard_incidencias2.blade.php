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

        .no-sombrear {
            user-select: none;
            /* estándar */
            -webkit-user-select: none;
            /* Safari */
            -moz-user-select: none;
            /* Firefox */
            -ms-user-select: none;
            /* IE/Edge */
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
                            <button type="button" class="btn btn-primary" data-mdb-ripple-init id="btnFiltroBusqueda">
                                <i class="fas fa-magnifying-glass"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12" id="chart-container">
        <div class="row" id="list-estado">
        </div>
        <div class="row">
            <div class="col-12 mb-2">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-12"
                            style="color: #9fa6b2; font-family: Arial, sans-serif; font-size: 13.8px; font-weight: 700;">
                            Actividades del personal por Tecnico</div>
                        <div class="col-xl-2 col-lg-3 align-content-center mb-2 py-2" id="list-fechas">
                        </div>
                        <div class="col-xl-10 col-lg-9 mb-2">
                            <div id="chart-actividades"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-8 col-xl-7 col-lg-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div id="chart-incidencias-fechas"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-5 col-lg-6 mb-2">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-12"
                            style="color: #9fa6b2; font-family: Arial, sans-serif; font-size: 13.8px; font-weight: 700;">
                            Niveles de incidencia y su frecuencia total</div>
                        <div class="col-5 mb-2" id="list-niveles">
                        </div>
                        <div class="col-7 mb-2">
                            <div id="chart-niveles"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-2">
                <div class="card" style="height: 100%;">
                    <div class="card-body">
                        <div class="col-12"
                            style="color: #9fa6b2; font-family: Arial, sans-serif; font-size: 13.8px; font-weight: 700;"
                            id="title-contable"></div>
                        <div style="height: 38.1vh;">
                            <div class="col-xxl-7 col-xl-6 ms-auto pt-3 pb-2">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0" id="search-addon">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input class="form-control rounded" id="filterContable" type="search" placeholder="Buscar"
                                        aria-label="Buscar" aria-describedby="search-addon">
                                </div>
                            </div>
                            <div class="py-2" id="list-contable"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div id="chart-problemas"></div>
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
                    <div id="chart-subproblemas"></div>
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
    <script src="{{secure_asset('front/js/app/ChartMananger2.js')}}"></script>
    <script src="{{secure_asset('front/js/dashboard/rci/dashboard_incidencias2.js')}}?v={{ time() }}"></script>
@endsection