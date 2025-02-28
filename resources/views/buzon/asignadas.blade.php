@extends('layout.app')
@section('title', 'INC RESUELTAS')

@section('style')
    <script type="text/javascript" src="{{asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('front/vendor/daterangepicker/daterangepicker.css')}}">
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
@endsection
@section('content')

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Incidencias / Visitas Resueltas</strong>
                </h6>

                <ul class="nav nav-tabs mb-3" id="myTab0" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button data-mdb-tab-init class="nav-link position-relative active" id="home-tab0"
                            data-mdb-target="#home0" type="button" role="tab" aria-controls="home" aria-selected="true"
                            onclick="resetTable(false)">
                            Incidencias
                            <span id="count_asig"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-danger"></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button data-mdb-tab-init class="nav-link position-relative" id="profile-tab0"
                            data-mdb-target="#profile0" type="button" role="tab" aria-controls="profile"
                            aria-selected="false" onclick="resetTable(true)">
                            Visitas
                            <span id="count_vis"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-danger"></span>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent0">
                    <div class="tab-pane fade show active" id="home0" role="tabpanel" aria-labelledby="home-tab0">
                        <div>
                            <button class="btn btn-primary px-2" onclick="updateTableInc()" data-mdb-ripple-init
                                role="button">
                                <i class="fas fa-rotate-right"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table id="tb_incidencias" class="table table-hover text-nowrap" style="width:100%">
                                    <thead>
                                        <tr class="text-bg-primary">
                                            <th>Incidencia</th>
                                            <th class="text-center">Estado</th>
                                            <th>Registrado</th>
                                            <th>Asignado</th>
                                            <th>Empresa</th>
                                            <th>Sucursal</th>
                                            <th>Estacion</th>
                                            <th>Problema / Sub Problema</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile0" role="tabpanel" aria-labelledby="profile-tab0">
                        <div>
                            <button class="btn btn-primary btn-sm px-1" onclick="updateTableVis()" data-mdb-ripple-init
                                role="button">
                                <i class="fas fa-rotate-right"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table id="tb_visitas" class="table table-hover text-nowrap" style="width:100%">
                                    <thead>
                                        <tr class="text-bg-primary">
                                            <th class="text-center">Estado</th>
                                            <th>Registrado</th>
                                            <th>Empresa</th>
                                            <th>Sucursal</th>
                                            <th>Asignado</th>
                                            <th>Programado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Para Incidencias -->
    <button class="d-none" data-mdb-modal-init data-mdb-target="#modal_detalle"></button>
    <div class="modal fade" id="modal_detalle" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title"><i class="fas fa-book-open"></i> Detalle de incidencia
                        <span class="ms-2 badge badge-success badge-lg" aria-item="codigo"></span>
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-end" aria-item="estado"></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                        <div class="list-group list-group-light">
                            <div class="list-group-item">
                                <span aria-item="empresa">20506467854 - CORPORACION JULCAN S.A.</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                    aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                    aria-item="sucursal">E/S INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Problema / Sub Problema:</label>
                                <span style="font-size: .75rem;" aria-item="problema / sub problema"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Observación:</label>
                                <span style="font-size: .75rem;" aria-item="observasion"></span>
                            </div>
                        </div>
                    </div>
                    <h6 class="font-weight-semibold col-form-label text-primary mt-2">Seguimiento Incidencia</h6>
                    <div class="">
                        <ul class="list-group list-group-light" id="content-seguimiento">
                        </ul>
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
        <div class="modal-dialog modal-lg" style="display: flex;">
            <form class="modal-content" id="form-orden">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">ORDEN DE SERVICIO <span class="badge badge-success badge-lg"
                            aria-item="codigo"></span></h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-lg-12 col-xs-12">
                        <!-- INICIO CABECERA -->
                        <div class="text-end cabecera-orden">
                            <div>
                                <label class="form-label me-2">Fecha Inicio: </label>
                                <span style="font-size: small;" aria-item="registrado"></span>
                            </div>
                        </div>
                        <div class="mb-1 cabecera-orden">
                            <input type="hidden" name="codInc" id="codInc">
                            <div class="col-lg-5 col-10">
                                <label class="form-label" for="n_orden">N° de Orden </label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="n_orden" />
                                    <button class="btn btn-secondary" type="button" id="button-cod-orden" check-cod="false"
                                        data-mdb-ripple-init data-mdb-ripple-color="dark">
                                        Cod. Tecnito
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- TER CABECERA -->
                        <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                            <h6 class="tittle text-primary"> TECNICOS</h6>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                            <span aria-item="tecnicos"></span>
                        </div>


                        <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                            <h6 class="tittle text-primary"> DATOS DEL CLIENTE </h6>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                            <div class="list-group list-group-light">
                                <div class="list-group-item">
                                    <span aria-item="empresa">20506467854 - CORPORACION JULCAN S.A.</span>
                                </div>
                                <div class="list-group-item">
                                    <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                        aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                                </div>
                                <div class="list-group-item">
                                    <div class="row col-12">
                                        <div class="col-sm-6">
                                            <label class="form-label me-2">Sucursal: </label><span
                                                style="font-size: .75rem;" aria-item="sucursal">E/S INDEPENDENCIA</span>
                                        </div>
                                        <div class="col-sm-6 text-end">
                                            <label class="form-label me-2">Atencion: </label><span
                                                style="font-size: .75rem;" aria-item="atencion">Remoto</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                            <h6 class="tittle text-primary"> TRABAJO REALIZADO </h6>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                            <div class="col-md-12">
                                <label class="form-label me-2">Clasificacion Error:</label><span style="font-size: .75rem;"
                                    aria-item="problema / sub problema">PROBLEMA DE LECTURA / VALIDACION DE
                                    JACKTOOL</span>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label me-2">Obersevaciones Incidencia:</label>
                                <span style="font-size: .75rem;" aria-item="observacion"></span>
                            </div>
                            <div class="row justify-content-md-center">
                                <div class="col-md-6">
                                    <div class="form-group pt-2">
                                        <label class="form-label" for="observacion">Observaciones</label>
                                        <textarea class="form-control" id="observacion"
                                            style="height: 80px;resize: none;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group pt-2">
                                        <label class="form-label" for="recomendacion">Recomendaciones</label>
                                        <textarea class="form-control" id="recomendacion" name="recomendacion"
                                            style="height: 80px;resize: none;" require="Recomendaciones"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group pt-2">
                                        <label class="form-label" for="fecha_f">Fecha Fin </label>
                                        <input class="form-control form-control-sm" id="fecha_f">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group pt-2">
                                        <label class="form-label" for="hora_f">Hora Fin </label>
                                        <input class="form-control form-control-sm" id="hora_f">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                            <h6 class="tittle text-primary">MATERIALES UTILIZADOS</h2>
                        </div>

                        <div class="col-12">
                            <div class="row" id="content-material">
                                <div class="col-lg-9 my-1">
                                    <div class="input-group">
                                        <select class="select-clear" id="createMaterial">
                                            <option value=""></option>
                                            @foreach ($data['materiales'] as $m)
                                                <option value="{{$m['value']}}" data-value="{{$m['dValue']}}">{{$m['text']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6 my-1 ps-lg-0">
                                    <div class="input-group disabled" style="max-width: 300px;" id="content-cantidad">
                                        <button class="btn btn-secondary px-2" type="button" data-mdb-ripple-init
                                            onclick="manCantidad('-')">
                                            <i class="fas fa-minus" style="font-size: .75rem;"></i>
                                        </button>
                                        <input type="number" class="form-control" input-cantidad=""
                                            oninput="manCantidad('press')" />
                                        <button class="btn btn-secondary px-2" type="button" data-mdb-ripple-init
                                            onclick="manCantidad('+')">
                                            <i class="fas fa-plus" style="font-size: .75rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6 my-1 ps-0 d-none" id="content-codAviso">
                                    <input type="text" class="form-control" id="codAviso" placeholder="Cod. Aviso">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 my-2 px-4">
                            <div class="row justify-content-between firmas-orden">
                                <div class="col-lg-5 text-center my-2">
                                    <img class="border rounded-1" {{Auth::user()->firma_digital ? 'src=' . asset('front/images/firms/' . Auth::user()->firma_digital) . '' : ''}} height="130"
                                        width="160">
                                    <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">
                                        Firma Tecnico
                                    </p>
                                    <p class="mb-1" style="font-size: 13.4px;">RICARDO CALDERON INGENIEROS SAC</p>
                                    <p class="mb-0" style="font-size: 12.5px;">
                                        {{Auth::user()->ndoc_usuario . ' - ' . Auth::user()->nombres . ' ' . Auth::user()->apellidos}}
                                    </p>
                                </div>

                                <div class="col-lg-5 text-center my-2">
                                    <div class="text-center content-image">
                                        <div class="overlay">
                                            <button class="btn-img removeImgButton" style="display: none;"
                                                id="removeImgFirma" type="button" button-reset><i
                                                    class="fas fa-xmark"></i></button>
                                            <button class="btn-img mx-1 uploadImgButton" id="uploadImgFirma"
                                                type="button"><i class="fas fa-arrow-up-from-bracket"></i></button>
                                            <button class="btn-img mx-1 uploadImgButton" id="createFirma" type="button"><i
                                                    class="fas fa-pencil"></i></button>
                                            <button class="btn-img expandImgButton" type="button"
                                                onclick="PreviImagenes(PreviFirma.src);"><i
                                                    class="fas fa-expand"></i></button>
                                        </div>
                                        <input type="file" class="d-none" id="firma_digital">
                                        <input type="text" class="d-none" name="firma_digital" id="textFirmaDigital">
                                        <img id="PreviFirma" class="visually-hidden" height="130" width="160">
                                    </div>
                                    <!-- <img class="border rounded-1" id="" alt="" > -->
                                    <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma
                                        Cliente</p>
                                    <p class="mb-1" style="font-size: 13.4px;" aria-item="empresaFooter">COESTI S.A.</p>
                                    <div class="search_signature_group">
                                        <input type="text" id="search_signature" placeholder="Buscar cliente">
                                        <span class="search_signature_text rounded" type="button" data-mdb-ripple-init>
                                            <i class="fas fa-magnifying-glass"></i>
                                        </span>
                                    </div>
                                    <input type="hidden" name="id_firmador" id="id_firmador">
                                    <input type="hidden" name="nomFirmaDigital" id="nomFirmaDigital">
                                    <input type="hidden" name="n_doc" id="n_doc">
                                    <input type="hidden" name="nom_cliente" id="nom_cliente">
                                </div>
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
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Añadir Codigo Aviso -
                        <span class="badge badge-success badge-lg" aria-item="codigo"></span>
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-end" aria-item="estado"></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                        <div class="list-group list-group-light">
                            <div class="list-group-item">
                                <span aria-item="empresa">20506467854 - CORPORACION JULCAN S.A.</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                    aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <div class="row col-12">
                                    <div class="col-sm-6">
                                        <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                            aria-item="sucursal">E/S INDEPENDENCIA</span>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <label class="form-label me-2">Atención: </label><span style="font-size: .75rem;"
                                            aria-item="atencion">Remoto</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 pb-0 fieldset mb-3">
                        <input type="hidden" id="cod_incidencia" name="cod_incidencia">
                        <input type="hidden" id="cod_orden_ser" name="cod_orden_ser">
                        <div class="col-md-6 mb-2">
                            <label class="form-label mb-0" for="codigo_aviso">Codigo Aviso</label>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title"><i class="fas fa-book-open"></i> Seguimiento de la visita</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-end" aria-item="estado"></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                        <div class="list-group list-group-light">
                            <div class="list-group-item">
                                <span aria-item="empresa">20506467854 - CORPORACION JULCAN S.A.</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                    aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                    aria-item="sucursal">E/S INDEPENDENCIA</span>
                            </div>
                        </div>
                    </div>
                    <h6 class="font-weight-semibold col-form-label text-primary mt-2">Seguimiento Visita</h6>
                    <div class="">
                        <ul class="list-group list-group-light" id="content-seguimiento-vis">
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link " data-mdb-ripple-init
                        data-mdb-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_orden_visita" aria-labelledby="modal_orden_visita" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="display: flex;">
            <form class="modal-content" id="form-orden-visita">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">ORDEN DE SERVICIO <span class="badge badge-success badge-lg"
                            aria-item="codigo">{{$data['cod_ordenv']}}</span></h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <!-- INICIO CABECERA -->
                        <div class="text-end cabecera-orden">
                            <div>
                                <label class="form-label me-2">Fecha Inicio: </label>
                                <span style="font-size: small;" aria-item="registrado"></span>
                            </div>
                        </div>

                        <!-- TER CABECERA -->
                        <div class="col-12 mt-2">
                            <h6 class="tittle text-primary"> TECNICOS</h6>
                        </div>

                        <div class="col-12 mt-1 mb-3">
                            <span aria-item="tecnicos"></span>
                        </div>


                        <div class="col-12 mt-2">
                            <h6 class="tittle text-primary"> DATOS DEL CLIENTE </h6>
                        </div>

                        <div class="col-12 mt-1 mb-3">
                            <div class="list-group list-group-light">
                                <div class="list-group-item">
                                    <span aria-item="empresa">20506467854 - CORPORACION JULCAN S.A.</span>
                                </div>
                                <div class="list-group-item">
                                    <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                        aria-item="sucursal">E/S INDEPENDENCIA</span>
                                </div>
                                <div class="list-group-item">
                                    <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                        aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <h6 class="tittle text-primary"> REVISION DEL GABINETE </h6>
                        </div>

                        <input type="hidden" name="cod_ordenv" value="{{$data['cod_ordenv']}}">
                        <input type="hidden" name="id_visita_orden">

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>UPS</strong></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des1" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px;"><label>• BATERIAS UPS</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des2" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px;"><label>• SALIDA DE ENERGIA</label></div>
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
                                <strong>ESTABILIZADOR</strong></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des4" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px;"><label>• INGRESO DE ENERGIA</label></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des5" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-lg-3" style="font-size: 11px;"><label>• SALIDA DE ENERGIA</label></div>
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
                                <strong>INTERFACE</strong></div>
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
                                <strong>MONITOR</strong></div>
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
                                <strong>TARJETA MULTIPUERTOS</strong></div>
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
                                <strong>SWITCH</strong></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des10" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="tittle text-primary"> REVISION DEL SERVIDOR</h6>
                        </div>

                        <div class="row my-2">
                            <div class="col-lg-3 d-flex align-items-center" style="font-size: 11px; color: #757575">
                                <strong>SISTEMA OPERATIVO</strong></div>
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
                                <strong>VENCIMIENTO DE ANTIVIRUS</strong></div>
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
                                <strong>DISCO DURO</strong></div>
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
                                <strong>REALIZAR BACKUP</strong></div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text border-0 ps-0"><i class="fas fa-circle-check"></i></span>
                                    <input type="text" name="des14" class="form-control rounded"
                                        onchange="changeCheck(this)">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-10 my-1">
                                <h6 class="tittle text-primary"> REVISION DEL POS, LECTORES, JACK TOOLS IMPRESORAS Y
                                    CONEXIONES </h6>
                            </div>
                            <div class="col-lg-2 my-1 d-flex align-items-center justify-content-end">
                                <strong class="me-2" style="white-space: nowrap;" id="conteo-islas">Cant. 1</strong>
                                <button type="button" class="btn btn-secondary px-2" onclick="MRevision.create()"><i
                                        class="far fa-square-plus"></i></button>
                            </div>
                        </div>

                        <div id="content-islas" class="mt-3"></div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary px-2" onclick="MRevision.create()"><i
                                    class="far fa-square-plus"></i></button>
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
@endsection

@section('scripts')
    <script>
        let cod_ordenv = "{{$data['cod_ordenv']}}";
        let cod_orden = '<?= $data['cod_orden'] ?>';
        const empresas = <?php echo json_encode($data['empresas']); ?>;
        const sucursales = <?php echo json_encode($data['sucursales']); ?>;
        const tipo_estacion = <?php echo json_encode($data['tipo_estacion']); ?>;
        const problemas = <?php echo json_encode($data['problemas']); ?>;
        const subproblemas = <?php echo json_encode($data['subproblemas']); ?>;
    </script>
    <script src="{{asset('front/js/RevisionMananger,js')}}"></script>
    <script src="{{asset('front/vendor/signature/signature_pad.js')}}"></script>
    <script src="{{asset('front/js/app/buzon/asignadas.js')}}"></script>
@endsection