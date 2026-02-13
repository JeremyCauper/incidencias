@extends('layout.app')
@section('title', 'Panel de Control')

@section('cabecera')
    <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/registradas.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/vendor/selectize/selectize.default.min.css')}}">
    <script src="{{secure_asset('front/vendor/selectize/selectize.min.js')}}"></script>

    <script src="{{secure_asset('front/vendor/echartjs/echarts.min.js')}}"></script>

    <script>
        // let cod_incidencia = '< ?= $data['cod_inc'] ?>';
        // let empresas = < ?php echo json_encode($data['company']); ?>;
    </script>

    <style>
        .text-capitalize {
            font-weight: 400;
            font-size: .76rem;
        }

        .text-sm {
            font-size: .75rem;
        }
    </style>
@endsection
@section('content')

    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
            <p class="mb-4">
                Check the sales, value and bounce rate by country.
            </p>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header border-0 p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1 text-capitalize">Today's Money</p>
                            <h4 class="mb-0">53</h4>
                        </div>
                        <div class="text-center position-relative bg-dark rounded" style="width: 48px; height: 48px;">
                            <i class="far fa-address-card" style="top: 30%;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 p-2 ps-3">
                    <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+55% </span>than last week</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header border-0 p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1 text-capitalize">Today's Users</p>
                            <h4 class="mb-0">2300</h4>
                        </div>
                        <div class="text-center position-relative bg-dark rounded" style="width: 48px; height: 48px;">
                            <i class="material-symbols-rounded" style="top: 30%;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 p-2 ps-3">
                    <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>than last month</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header border-0 p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1 text-capitalize">Ads Views</p>
                            <h4 class="mb-0">3462</h4>
                        </div>
                        <div class="text-center position-relative bg-dark rounded" style="width: 48px; height: 48px;">
                            <i class="material-symbols-rounded" style="top: 30%;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 p-2 ps-3">
                    <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">-2% </span>than yesterday</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-header border-0 p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="mb-1 text-capitalize">Sales</p>
                            <h4 class="mb-0">103430</h4>
                        </div>
                        <div class="text-center position-relative bg-dark rounded" style="width: 48px; height: 48px;">
                            <i class="material-symbols-rounded" style="top: 30%;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 p-2 ps-3">
                    <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+5% </span>than yesterday</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6>Website Views</h6>
                    <p class="text-sm">Rendimiento de la última campaña</p>
                    <div class="pe-2">
                        <div class="chart">
                            <canvas id="chart-bars" class="chart-canvas" height="170"
                                style="display: block; box-sizing: border-box; height: 170px; width: 333px;"
                                width="333"></canvas>
                        </div>
                    </div>
                    <hr class="dark horizontal">
                    <p class="mb-0 text-sm"><i class="fas fa-clock me-1"></i>campaña enviada hace 2 días</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
            <div class="card ">
                <div class="card-body">
                    <h6> Daily Sales </h6>
                    <p class="text-sm"> (<span class="font-weight-bolder">+15%</span>) aumento en las ventas de hoy. </p>
                    <div class="pe-2">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="170"
                                style="display: block; box-sizing: border-box; height: 170px; width: 333px;"
                                width="333"></canvas>
                        </div>
                    </div>
                    <hr class="dark horizontal">
                    <p class="mb-0 text-sm"><i class="fas fa-clock me-1"></i>actualizado hace 4 min</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Completed Tasks</h6>
                    <p class="text-sm">Rendimiento de la última campaña</p>
                    <div class="pe-2">
                        <div class="chart">
                            <div id="chart-line-tasks" style="height: 170px;"></div>
                        </div>
                    </div>
                    <hr class="dark horizontal">
                    <p class="mb-0 text-sm"><i class="fas fa-clock me-1"></i>recién actualizado</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{secure_asset($ft_js->ChartMananger)}}"></script>
    <script>
        var myChart_peronal = new ChartMananger({
            id: '#chart-line-tasks',
            type: 'bar',
            config: {
                xAxis: 'category',
                yAxis: 'value'
            }
        });
    </script>
    <!-- <script src="{{secure_asset('front/js/soporte/incidencia/registradas.js')}}"></script> -->
@endsection