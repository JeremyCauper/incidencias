@extends('layout.app')
@section('title', 'INC RESUELTAS')

@section('style')
    <script type="text/javascript" src="{{asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('front/vendor/daterangepicker/daterangepicker.css')}}">
@endsection
@section('content')

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Incidencias / Visitas Resueltas</strong>
                </h6>
                <!-- Tabs navs
                <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link position-relative active" id="ex1-tab-1" href="#ex1-tabs-1"
                            role="tab" aria-controls="ex1-tabs-1" aria-selected="true">
                            Incidencias
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-danger"
                                style="z-index: 999 !important;">
                                99+
                            </span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a data-mdb-tab-init class="nav-link position-relative" id="ex1-tab-2" href="#ex1-tabs-2" role="tab"
                            aria-controls="ex1-tabs-2" aria-selected="false">
                            Visitas
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-danger"
                                style="z-index: 999 !important;">
                                99+
                            </span>
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content" id="ex1-content">
                    <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
                        Incidencias content
                    </div>
                    <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
                        Visitas content
                    </div>
                </div>
                Tabs content -->

                <ul class="nav nav-tabs mb-3" id="myTab0" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button data-mdb-tab-init class="nav-link position-relative active" id="home-tab0" data-mdb-target="#home0"
                            type="button" role="tab" aria-controls="home" aria-selected="true">
                            Incidencias
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-danger">
                                99+
                            </span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button data-mdb-tab-init class="nav-link position-relative" id="profile-tab0" data-mdb-target="#profile0"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">
                            Visitas
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-danger">
                                99+
                            </span>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent0">
                    <div class="tab-pane fade show active" id="home0" role="tabpanel" aria-labelledby="home-tab0">
                        Tab 1 content.
                    </div>
                    <div class="tab-pane fade" id="profile0" role="tabpanel" aria-labelledby="profile-tab0">
                        Tab 2 content
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const empresas = <?php echo json_encode($data['empresas']); ?>;
        const sucursales = <?php echo json_encode($data['sucursales']); ?>;
    </script>
    <script src="{{asset('front/vendor/signature/signature_pad.js')}}"></script>
    <!-- <script src="{{asset('front/js/app/incidencia/resueltas.js')}}"></script> -->
@endsection