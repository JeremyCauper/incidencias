@extends('layout.appEmpresa')
@section('title', 'ANALISIS DE INCIDENCIAS')

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
            <div class="card-body form-container">
                <h6 class="text-primary">Filtro de Analisis</h6>
                <div class="row">
                    <div class="col-lg-6 my-1">
                        <label class="form-label mb-0" for="empresa">Empresa</label>
                        <input type="text" class="form-control" id="empresa"
                            value="{{ session('config_layout')->nombre_perfil }}" readonly role="button">
                    </div>
                    <div class="col-xxl-4 col-lg-3 col-sm-8 my-1">
                        <label class="form-label mb-0" for="sucursal">Sucursal</label>
                        <select id="sucursal" name="sucursal" class="select-search">
                            <option selected value="">Todos</option>
                            @foreach ($data['scompany'] as $key => $val)
                                <option value="{{$val->id}}">{{$val->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-sm-4 my-1">
                        <label class="form-label mb-0" for="dateRango">Fecha</label>
                        <div class="input-group" id="dateRango-group" style="display:none;">
                            <input type="text" class="form-control rounded" id="dateRango" name="dateRango" role="button"
                                readonly>
                            <span class="input-group-text border-0 rounded px-1 ms-1" type="button" id="btn-dateRango">
                                <i class="fas fa-xmark"></i>
                            </span>
                        </div>
                        <div id="contentFiltroMultiple">
                            <select class="select" id="filtroMultiple">
                                <option value="0">Hoy</option>
                                <option value="1">Avanzado</option>
                            </select>
                        </div>
                    </div>
                    <script>
                        const $select = $('#filtroMultiple');
                        const $contentSelect = $('#contentFiltroMultiple');
                        const $dateGroup = $('#dateRango-group');
                        const $dateInput = $('#dateRango');

                        $('#dateRango').daterangepicker({
                            showDropdowns: true,
                            startDate: date('Y-m-01'),
                            endDate: date('Y-m-d'),
                            maxDate: date('Y-m-d'),
                            opens: "center",
                            cancelClass: "btn-link",
                            locale: {
                                format: 'YYYY-MM-DD',
                                separator: ' a ',
                                applyLabel: 'Aplicar',
                                cancelLabel: 'Cerrar',
                                fromLabel: 'Desde',
                                toLabel: 'Hasta',
                                customRangeLabel: 'Rango personalizado',
                                daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                                monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                                firstDay: 1 // Comienza la semana en lunes
                            }
                        });

                        // Función para aplicar estado
                        function aplicarEstado(estado) {
                            if (estado === "0") { // Hoy
                                $contentSelect.show();
                                $dateGroup.hide();
                                $select.val("0").trigger("change");
                                $dateInput.data('daterangepicker').setStartDate(date('Y-m-d'));
                                $dateInput.data('daterangepicker').setEndDate(date('Y-m-d'));
                            } else if (estado === "1") { // Avanzado
                                $contentSelect.hide();
                                $dateGroup.show();
                                $select.val("1").trigger("change");
                                $dateInput.data('daterangepicker').setStartDate(date('Y-m-01'));
                                $dateInput.data('daterangepicker').setEndDate(date('Y-m-d'));
                            }
                            localStorage.setItem("estadoFecha", estado);
                        }

                        // Evento cambio select
                        $select.on('change', function () {
                            aplicarEstado($(this).val());
                        });

                        // Evento botón "X"
                        $('#btn-dateRango').on('click', function () {
                            aplicarEstado("0");
                        });

                        // Al cargar página, restaurar estado
                        $(document).ready(function () {
                            let estadoGuardado = localStorage.getItem("estadoFecha") || "0";
                            aplicarEstado(estadoGuardado);
                        });
                    </script>
                    <div class="col-12 align-items-end d-flex my-1 justify-content-end">
                        <div>
                            <button type="button" class="btn btn-primary" data-mdb-ripple-init id="btnFiltroAvanzado">
                                <i class="fas fa-filter"></i> Filtrar
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
                        <div>
                            <div class="col-xxl-7 col-xl-6 ms-auto pt-3 pb-2">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0" id="search-addon">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input class="form-control rounded" id="filterContable" type="search"
                                        placeholder="Buscar" aria-label="Buscar" aria-describedby="search-addon">
                                </div>
                            </div>
                            <div class="py-2" id="list-contable" style="height: 31.9vh;"></div>
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
    <script src="{{secure_asset('front/js/app/ChartMananger.js')}}"></script>
    <script src="{{secure_asset('front/js/dashboard/empresa/dashboard_incidencias.js')}}?v={{ time() }}"></script>
@endsection