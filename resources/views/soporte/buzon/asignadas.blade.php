@extends('layout.app')
@section('title', 'INC RESUELTAS')

@section('cabecera')
    <script type="text/javascript" src="{{secure_asset($ft_js->daterangepicker_moment)}}"></script>
    <script type="text/javascript" src="{{secure_asset($ft_js->daterangepicker)}}"></script>
    <link rel="stylesheet" type="text/css" href="{{secure_asset($ft_css->daterangepicker)}}">
    <style>
        /*////////////////////////////////////////
                                                                                                                /        SCRIPT CSS FIRMA DIGITAL        /
                                                                                                                ////////////////////////////////////////*/

        .content-image {
            margin: auto;
            position: relative;
            border: 1px solid #e0e0e0;
            border-radius: 0.25rem;
            width: 160px;
            height: 130px;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            transition: .5s;
            opacity: 0;
            border: 1px solid #e0e0e0;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .removeImgButton {
            position: absolute;
            top: 4px;
            right: 4px;
        }

        .expandImgButton {
            position: absolute;
            bottom: 4px;
            right: 4px;
            display: none;
        }

        .content-image:hover .overlay,
        .content-image:hover .uploadImgButton {
            opacity: 1;
            transition: .5s;
        }

        .btn-img {
            border: none;
            font-size: .9rem;
            border-radius: 50px;
            width: 35px;
            height: 35px;
            padding: 0;
            background: #ffffff;
            color: #1F3BB3;
        }

        @media (max-width: 576px) {

            .content-image .overlay,
            .content-image .uploadImgButton {
                opacity: 1;
                transition: .5s;
            }

            .content-image img {
                min-height: 140px !important;
                height: 140px;
            }

            .btn-img {
                font-size: .7rem;
                width: 30px;
                height: 30px;
            }

            .expandImgButton {
                display: block;
            }

            .cabecera-orden h6 {
                font-size: .7rem;
            }
        }

        .content-signature-pad {
            position: relative;
        }

        .content-signature-pad::before {
            content: "";
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 35%;
            height: 2px;
            background: #000000;
        }

        /*////////////////////////////////////////
                                                                                                                /          SCRIPT CSS Doc Firma          /
                                                                                                                ////////////////////////////////////////*/

        .search_signature_group {
            flex-wrap: nowrap;
            position: relative;
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            width: 100%;
        }

        .search_signature_text {
            display: flex;
            align-items: center;
            width: 27.56px;
            height: auto;
            cursor: pointer;
            padding-left: 5px;
        }

        .search_signature_text i,
        .search_signature_text i::before {
            line-height: 1.3;
            min-width: auto;
            width: 19px;
            height: 19px;
        }

        #search_signature {
            display: block;
            width: calc(100% - 28px);
            padding: .2rem .4rem;
            font-size: .65rem;
            height: auto;
            font-weight: 400;
            line-height: 1.6;
            color: var(--mdb-surface-color);
            appearance: none;
            background-color: var(--mdb-body-bg);
            border: var(--mdb-border-width) solid var(--mdb-border-color);
            border-radius: var(--mdb-border-radius);
        }

        #search_signature:focus-visible {
            outline: none;
            transition: all .1s linear;
            border-color: #3b71ca;
            box-shadow: inset 0px 0px 0px 1px #3b71ca;
        }

        .text-normal {
            white-space: pre-line;
        }
    </style>
    <script>
        let cod_ordenv = "{{$data['cod_ordenv']}}";
        let cod_orden = "{{$data['cod_orden']}}";
        let empresas = @json($data['empresas']);
        let sucursales = @json($data['sucursales']);
        let tipo_estacion = @json($data['tEstacion']);
        let tipo_soporte = @json($data['tSoporte']);
        let tipo_incidencia = @json($data['tIncidencia']);
        let obj_problem = @json($data['problema']);
        let obj_subproblem = @json($data['sproblema']);
        let materiales = @json($data['materiales']);
    </script>
@endsection
@section('content')

    <div class="nav nav-tabs my-3 gap-2" id="myTab0" role="tablist">
        <div class="nav-item" role="presentation">
            <button data-mdb-tab-init class="nav-link position-relative active" id="home-tab0" data-mdb-target="#home0"
                type="button" role="tab" aria-controls="home" aria-selected="true" onclick="resetTable()">
                Incidencias
                <span id="count_asig"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-danger"></span>
            </button>
        </div>
        <div class="nav-item" role="presentation">
            <button data-mdb-tab-init class="nav-link position-relative" id="profile-tab0" data-mdb-target="#profile0"
                type="button" role="tab" aria-controls="profile" aria-selected="false" onclick="resetTable()">
                Visitas
                <span id="count_vis"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-danger"></span>
            </button>
        </div>
    </div>

    <div>
        <div class="tab-content" id="myTabContent0">
            <div class="tab-pane fade show active" id="home0" role="tabpanel" aria-labelledby="home-tab0">
                <div id="contenedor_registros_incidencias"></div>
                <script
                    src="{{ secure_asset('front/js/soporte/buzon/vista-registros-asignadas-incidencias.js') }}"></script>
            </div>


            <div class="tab-pane fade" id="profile0" role="tabpanel" aria-labelledby="profile-tab0">
                <div id="contenedor_registros_visitas"></div>
                <script src="{{ secure_asset('front/js/soporte/buzon/vista-registros-asignadas-visitas.js') }}"></script>
            </div>
        </div>
    </div>

    <!-- Modals Para Incidencias -->
    <button class="d-none" data-mdb-modal-init data-mdb-target="#modal_detalle"></button>
    <div class="modal fade" id="modal_detalle" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de incidencia
                        <span class="badge badge-lg rounded-pill" style="background-color: #5a8bdb"
                            aria-item="codigo"></span>
                    </h5>
                    <div class="align-items-center d-flex gap-2">
                        <span aria-item="estado"></span>
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body modal-body-scrollable px-1 p-0">
                    <div class="row">
                        <div class="col-lg-5 p-4 modal-col-scrollable personalized-scroll"
                            style="background-color: rgb(59 113 202 / 5%);">
                            <h6 class="text-uppercase mt-2 mb-4 title_detalle">
                                <i class="fas fa-city me-2"></i> Información del Cliente
                            </h6>
                            <div class="detalle_body mb-2">
                                <div class="border-bottom mb-4">
                                    <h5><span aria-item=" razon_social"></span></h5>
                                    <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                                </div>
                                <div>
                                    <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                                    <p class="detalle_text" aria-item="sucursal"></p>
                                </div>
                                <div>
                                    <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                        Sucursal</p>
                                    <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                                </div>
                            </div>

                            <h6 class="text-uppercase my-4 title_detalle">
                                <i class="fas fa-circle-exclamation me-2"></i> Detalles del Soporte
                            </h6>
                            <div class="detalle_body d-flex align-items-center justify-content-between mb-3">
                                <span class="detalle_text mb-0">Tipo Soporte</span>
                                <span class="detalle_text text-uppercase fw-bolder mb-0" aria-item="soporte"></span>
                            </div>
                            <div class="detalle_body mb-3">
                                <p class="detalle_label mb-1 text-uppercase fw-bolder text-muted">Problema Reportado</p>
                                <p class="detalle_text fw-bold mb-0" aria-item="problema"></p>
                                <p class="detalle_text text-muted fst-italic mb-0 mt-2" aria-item="subproblema"></p>
                            </div>

                            <div class="detalle_body"
                                style="background-color: rgb(123 126 255 / 19%);border-color: rgb(99 102 241 / 0.1);">
                                <label class="form-label text-uppercase me-2" style="color: rgb(99 102 241 / 1);"><i
                                        class="fas fa-stopwatch"></i> Nivel de Incidencia:</label>
                                <div aria-item="incidencia"></div>
                            </div>

                            <h6 class="text-uppercase mt-4 mb-2 title_detalle">Observación</h6>
                            <div class="detalle_body datalle_observacion" aria-item="observacion">
                            </div>
                        </div>
                        <div class="col-lg-7 p-4 modal-col-scrollable personalized-scroll">
                            <div class="align-items-center d-flex mt-2 mb-4">
                                <h4 class="mb-0 text-nowrap text-uppercase title_detalle">
                                    <i class="fas fa-clock-rotate-left" style="color: rgb(99 102 241 / 1)"></i>
                                    SEGUIMIENTO INCIDENCIA
                                </h4>
                                <div class="ms-2 rounded-pill"
                                    style="height: .35rem;width: 100%;background-color: rgb(148 163 184 / 11%)"></div>
                            </div>
                            <div class="content_seguimiento" aria-item="contenedor-seguimiento"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link " data-mdb-ripple-init
                        data-mdb-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_orden" aria-labelledby="modal_orden" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-scrollable modal-xl">
            <form class="modal-content" id="form-orden">
                <div class="modal-header">
                    <h5 class="modal-title">ORDEN DE SERVICIO
                        <span class="ms-2 badge badge-lg rounded-pill" style="background-color: #5a8bdb"
                            aria-item="codigo"></span>
                    </h5>
                    <div class="align-items-center d-flex gap-2">
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="text-end cabecera-orden">
                        <div style="color: rgb(148 163 184 / 1)">
                            <label class="form-label me-2">Fecha Inicio: </label>
                            <span style="font-size: small;" aria-item="registrado"></span>
                        </div>
                    </div>
                    <div class="mb-1 cabecera-orden">
                        <input type="hidden" name="codInc" id="codInc">
                        <div class="col-lg-5 col-10">
                            <label class="form-label" style="color: rgb(148 163 184 / 1)" for="n_orden">N° de Orden</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="n_orden" name="n_orden" requested="N° de Orden">
                                <button class="btn btn-secondary" type="button" id="button-cod-orden" check-cod="false"
                                    data-mdb-ripple-init data-mdb-ripple-color="dark">
                                    Cod. Tecnito
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- TER CABECERA -->
                    <h6 class="text-uppercase mb-3 title_detalle">
                        <i class="fas fa-user-gear"></i> Tecnicos
                    </h6>

                    <div class="mb-3">
                        <span aria-item="tecnicos"></span>
                    </div>

                    <h6 class="text-uppercase mb-3 title_detalle">
                        <i class="fas fa-city me-2"></i> Información del Cliente
                    </h6>
                    <div class="detalle_body mb-2">
                        <div class="border-bottom mb-4">
                            <h5><span aria-item="razon_social"></span></h5>
                            <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                            <p class="detalle_text" aria-item="sucursal"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                Sucursal</p>
                            <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                        </div>
                    </div>

                    <h6 class="text-uppercase my-3 title_detalle">
                        <i class="fas fa-circle-exclamation me-2"></i> Detalles del Soporte
                    </h6>
                    <div class="detalle_body d-flex align-items-center justify-content-between mb-3">
                        <span class="detalle_text mb-0">Tipo Soporte</span>
                        <span class="detalle_text text-uppercase fw-bolder mb-0" aria-item="soporte"></span>
                    </div>
                    <div class="detalle_body mb-3">
                        <p class="detalle_label mb-1 text-uppercase fw-bolder text-muted">Problema Reportado</p>
                        <p class="detalle_text fw-bold mb-0" aria-item="problema"></p>
                        <p class="detalle_text text-muted fst-italic mb-0 mt-2" aria-item="subproblema"></p>
                    </div>

                    <h6 class="text-uppercase mt-4 mb-2 title_detalle">Observación</h6>
                    <div class="detalle_body datalle_observacion" aria-item="observacion">
                    </div>

                    <div class="row justify-content-md-center">
                        <div class="col-md-6">
                            <div class="form-group pt-2">
                                <textarea class="form-control" id="observaciones"
                                    style="height: 80px;resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pt-2">
                                <textarea class="form-control" id="recomendacion" style="height: 80px;resize: none;"
                                    require="Recomendaciones"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="form-group pt-2">
                                <input class="form-control form-control-sm" id="fecha_f">
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="form-group pt-2">
                                <input class="form-control form-control-sm" id="hora_f">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                        <h6 class="tittle text-primary">MATERIALES UTILIZADOS</h2>
                    </div>

                    <div class="col-md-4 col-6 my-1 ps-0 d-none" id="content-codAviso">
                        <input type="text" class="form-control" id="codAviso" placeholder="Cod. Aviso">
                    </div>
                    <div class="col-12">
                        <div id="createMaterial"></div>
                    </div>

                    <div class="col-12 my-2 px-4">
                        <div class="row justify-content-between firmas-orden">
                            <div class="col-sm-5 text-center my-2">
                                <img class="border rounded-1" {{ Auth::user()->firma_digital ? 'src=' . asset('front/images/firms/' . Auth::user()->firma_digital) . '?v=' . time() : '' }}
                                    height="130" width="160">
                                <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">
                                    Firma Tecnico
                                </p>
                                <p class="mb-1" style="font-size: 13.4px;">RICARDO CALDERON INGENIEROS SAC</p>
                                <p class="mb-0" style="font-size: 12.5px;">
                                    {{ Auth::user()->ndoc_usuario . ' - ' . Auth::user()->nombres . ' ' . Auth::user()->apellidos }}
                                </p>
                            </div>

                            <div class="col-sm-5 text-center my-2">
                                <div class="text-center content-image">
                                    <div class="overlay">
                                        <button class="btn-img removeImgButton" style="display: none;" id="removeImgFirma"
                                            type="button" button-reset><i class="fas fa-xmark"></i></button>
                                        <button class="btn-img mx-1 uploadImgButton" id="uploadImgFirma" type="button"><i
                                                class="fas fa-arrow-up-from-bracket"></i></button>
                                        <button class="btn-img mx-1 uploadImgButton" id="createFirma" type="button"><i
                                                class="fas fa-pencil"></i></button>
                                        <button class="btn-img expandImgButton" type="button"
                                            onclick="PreviImagenes(PreviFirma.src);"><i class="fas fa-expand"></i></button>
                                    </div>
                                    <input type="file" class="d-none" id="firma_digital">
                                    <input type="text" class="d-none" name="firma_digital" id="textFirmaDigital">
                                    <img id="PreviFirma" class="visually-hidden" height="130" width="160">
                                </div>
                                <!-- <img class="border rounded-1" id="" alt="" > -->
                                <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma
                                    Cliente</p>
                                <p class="mb-1" style="font-size: 13.4px;" aria-item="empresaFooter">COESTI S.A.
                                </p>
                                <div class="search_signature_group">
                                    <input type="text" id="search_signature" placeholder="Buscar cliente">
                                    <span class="search_signature_text rounded" type="button" data-mdb-ripple-init>
                                        <i class="fas fa-magnifying-glass"></i>
                                    </span>
                                </div>
                                <input type="hidden" name="id_firmador" id="id_firmador">
                                <input type="hidden" name="nomFirmaDigital" id="nomFirmaDigital">
                                <input type="hidden" name="n_doc" id="n_doc"> <input type="hidden" name="nom_cliente"
                                    id="nom_cliente">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init onclick="">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal_addcod" aria-labelledby="modal_addcod" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-addcod">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Codigo Aviso
                        <span class="ms-2 badge badge-lg rounded-pill" style="background-color: #5a8bdb"
                            aria-item="codigo"></span>
                    </h5>
                    <div class="align-items-center d-flex gap-2">
                        <span aria-item="estado"></span>
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="detalle_body mb-2">
                        <div class="border-bottom mb-4">
                            <h5><span aria-item="razon_social"></span></h5>
                            <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                            <p class="detalle_text" aria-item="sucursal"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                Sucursal</p>
                            <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                        </div>
                    </div>


                    <h6 class="text-uppercase mt-4 title_detalle text-primary" style="font-size: 14px">
                        <i class="fas fa-laptop-code me-2"></i>INGRESAR CODIGO AVISO
                    </h6>

                    <div>
                        <input type="hidden" id="cod_incidencia" name="cod_incidencia">
                        <input type="hidden" id="cod_orden_ser" name="cod_orden_ser">
                        <div class="col-md-6 mb-2">
                            <input class="form-control" id="codigo_aviso">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init>Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals Para Visitas -->
    <div class="modal fade" id="modal_seguimiento_visitasp" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de la visita</h5>
                    <div class="align-items-center d-flex gap-2">
                        <span aria-item="estado"></span>
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body modal-body-scrollable px-1 p-0">
                    <div class="row">
                        <div class="col-lg-5 p-4 modal-col-scrollable personalized-scroll"
                            style="background-color: rgb(59 113 202 / 5%);">
                            <h6 class="text-uppercase mt-2 mb-4 title_detalle">
                                <i class="fas fa-city me-2"></i> Información del Cliente
                            </h6>
                            <div class="detalle_body mb-2">
                                <div class="border-bottom mb-4">
                                    <h5><span aria-item="razon_social"></span></h5>
                                    <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                                </div>
                                <div>
                                    <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                                    <p class="detalle_text" aria-item="sucursal"></p>
                                </div>
                                <div>
                                    <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                        Sucursal</p>
                                    <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 p-4 modal-col-scrollable personalized-scroll">
                            <div class="align-items-center d-flex mt-2 mb-4">
                                <h4 class="mb-0 text-nowrap text-uppercase title_detalle">
                                    <i class="fas fa-clock-rotate-left" style="color: rgb(99 102 241 / 1)"></i>
                                    SEGUIMIENTO VISITA
                                </h4>
                                <div class="ms-2 rounded-pill"
                                    style="height: .35rem;width: 100%;background-color: rgb(148 163 184 / 11%)"></div>
                            </div>
                            <div class="content_seguimiento" aria-item="contenedor-seguimiento"></div>
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

    <div class="modal fade" id="modal_orden_visita" aria-labelledby="modal_orden_visita" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-scrollable modal-xl">
            <form class="modal-content" id="form-orden-visita">
                <div class="modal-header">
                    <h5 class="modal-title">ORDEN DE SERVICIO
                        <span class="ms-2 badge badge-lg rounded-pill" style="background-color: #5a8bdb"
                            aria-item="codigo">{{$data['cod_ordenv']}}</span>
                    </h5>
                    <div class="align-items-center d-flex gap-2">
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <!-- INICIO CABECERA -->
                    <div class="text-end cabecera-orden">
                        <div style="color: rgb(148 163 184 / 1)">
                            <label class="form-label me-2">Fecha Inicio: </label>
                            <span style="font-size: small;" aria-item="registrado"></span>
                        </div>
                    </div>

                    <!-- TER CABECERA -->
                    <h6 class="text-uppercase mb-3 title_detalle">
                        <i class="fas fa-user-gear"></i> Tecnicos
                    </h6>

                    <div class="mb-3">
                        <span aria-item="tecnicos"></span>
                    </div>


                    <h6 class="text-uppercase mb-3 title_detalle">
                        <i class="fas fa-city me-2"></i> Información del Cliente
                    </h6>
                    <div class="detalle_body mb-2">
                        <div class="border-bottom mb-4">
                            <h5><span aria-item="razon_social"></span></h5>
                            <p class="detalle_text text-muted mb-3" aria-item="direccion"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Sucursal</p>
                            <p class="detalle_text" aria-item="sucursal"></p>
                        </div>
                        <div>
                            <p class="detalle_label mb-0 text-uppercase fw-bolder text-muted">Dirección
                                Sucursal</p>
                            <p class="detalle_text mb-0" aria-item="dir_sucursal"></p>
                        </div>
                    </div>


                    <h6 class="text-uppercase mt-4 mb-3 title_detalle">
                        <i class="fas fa-boxes-stacked me-2"></i> Revisión del gabinete
                    </h6>

                    <input type="hidden" name="cod_ordenv" value="{{$data['cod_ordenv']}}">
                    <input type="hidden" name="id_visita_orden">

                    <div class="detalle_body mb-2" id="contendor-filas">
                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>UPS</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des1" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px; color: #757575"><label class="item-isla">BATERIAS
                                    UPS</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des2" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px; color: #757575"><label class="item-isla">SALIDA DE
                                    ENERGIA</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des3" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>ESTABILIZADOR</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des4" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px; color: #757575"><label class="item-isla">INGRESO
                                    DE ENERGIA</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des5" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px; color: #757575"><label class="item-isla">SALIDA DE
                                    ENERGIA</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des6" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>INTERFACE</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des7" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>MONITOR</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des8" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>TARJETA MULTIPUERTOS</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des9" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>SWITCH</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des10" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-uppercase mt-4 mb-3 title_detalle">
                        <i class="fas fa-server me-2"></i> Revisión del servidor
                    </h6>

                    <div class="detalle_body mb-2">
                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>SISTEMA OPERATIVO</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des11" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>VENCIMIENTO DE ANTIVIRUS</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des12" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>DISCO DURO</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des13" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>REALIZAR BACKUP</strong>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des14" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-5 mb-3">
                        <h6 class="text-uppercase title_detalle mb-0">
                            Revisión de equipos
                        </h6>
                        <div class="d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm px-2 me-2 text-nowrap"
                                onclick="MRevision.deleteAll()">Limpiar Islas</button>
                            <strong class="me-2" style="white-space: nowrap;" id="conteo-islas">Cant. 1</strong>
                            <button type="button" class="btn btn-secondary btn-sm px-1" onclick="MRevision.create()"><i
                                    class="far fa-square-plus"></i></button>
                        </div>
                    </div>

                    <div id="content-islas" class="detalle_body mb-2 mt-3">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init onclick="">Registrar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{secure_asset($ft_js->RevisionMananger)}}"></script>
    <script src="{{secure_asset('front/vendor/signature/signature_pad.js')}}"></script>
    <script src="{{secure_asset('front/js/soporte/buzon/asignadas.js')}}"></script>
@endsection