@extends('layout.app')
@section('title', 'Panel de Control')

@section('cabecera')
    <link rel="stylesheet" href="{{ secure_asset($ft_css->mdtp) }}">
    <script src="{{ secure_asset($ft_js->mdtp) }}"></script>

    <link rel="stylesheet" href="{{ secure_asset('front/css/app/incidencias/registradas.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('front/vendor/selectize/selectize.default.min.css') }}">
    <script src="{{ secure_asset('front/vendor/selectize/selectize.min.js') }}"></script>

    <script>
        let personal = {{ Auth::user()->id_usuario }};
        let cod_incidencia = "{{ $data['cod_inc'] }}";
        let cod_orden = "{{ $data['cod_orden'] }}";
        let empresas = @json($data['company']);
        let sucursales = @json($data['scompany']);
        let cargo_estacion = @json($data['CargoEstacion']);
        let tipo_estacion = @json($data['tEstacion']);
        let tipo_soporte = @json($data['tSoporte']);
        let tipo_incidencia = @json($data['tIncidencia']);
        let obj_problem = @json($data['problema']);
        let obj_subproblem = @json($data['sproblema']);
        let obj_eContactos = @json($data['eContactos']);
        let materiales = @json($data['materiales']);
        let usuarios = @json($data['usuarios']);
    </script>

    <style>
        .mi-animacion-modal .modal-dialog {
            animation: aparecerDesdeAbajo 0.5s ease-out;
        }

        @keyframes aparecerDesdeAbajo {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
@endsection
@section('content')

    <section class="row">
        <div class="col-md-3 col-6 mb-2">
            <div class="card" style="cursor: pointer;" data-mdb-ripple-init onclick="searchTable(0)">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="card-icon rounded-7" style="background-color: #14a44d40;">
                                <i class="fa-solid fa-clock fa-fw fs-4 text-success"></i>
                            </div>
                        </div>
                        <div class="content-text flex-grow-1 ms-2">
                            <p class="text-nowrap fw-bold mb-1" style="color: var(--color-surface)">Registradas</p>
                            <p class="fw-bold mb-0 fs-4" data-panel="totales">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3 col-6 mb-2">
            <div class="card" style="cursor: pointer;" data-mdb-ripple-init onclick="searchTable(1)">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="card-icon rounded-7" style="background-color: #54b4d340;">
                                <i class="fa-solid fa-user-check fa-fw fs-4 text-info"></i>
                            </div>
                        </div>
                        <div class="content-text flex-grow-1 ms-2">
                            <p class="text-nowrap fw-bold mb-1" style="color: var(--color-surface)">Asignadas</p>
                            <p class="fw-bold mb-0 fs-4" data-panel="tAsignadas">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-2">
            <div class="card" style="cursor: pointer;" data-mdb-ripple-init onclick="searchTable(2)">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="card-icon rounded-7" style="background-color: #e4a11b40;">
                                <i class="fa-solid fa-user-xmark fa-fw fs-4 text-warning"></i>
                            </div>
                        </div>
                        <div class="content-text flex-grow-1 ms-2">
                            <p class="text-nowrap fw-bold mb-1" style="color: var(--color-surface)">Sin Asignar</p>
                            <p class="fw-bold mb-0 fs-4" data-panel="tSinAsignar">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-2">
            <div class="card" style="cursor: pointer;" data-mdb-ripple-init onclick="searchTable(3)">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="card-icon rounded-7" style="background-color: #549cea40;">
                                <i class="fa-solid fa-hourglass-half fa-fw fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div class="content-text flex-grow-1 ms-2">
                            <p class="text-nowrap fw-bold mb-1" style="color: var(--color-surface)">En Proceso</p>
                            <p class="fw-bold mb-0 fs-4" data-panel="tEnProceso">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function fillSelectContac(data) {
            $('#tel_contac').html('<option value=""></option>');
            Object.entries(data).forEach(([key, e]) => {
                $('#tel_contac').append($('<option>').val(e.telefono).text(e.telefono));
            });
            obj_eContactos = data;
        }
    </script>

    <div class="col-12" id="contenedor_registros"></div>
    <script src="{{ secure_asset('front/js/soporte/incidencia/vista-registros-registradas.js') }}"></script>

    <div class="modal fade" id="modal_incidencias" aria-labelledby="modal_incidencias" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down modal-xl">
            <form class="modal-content" id="form-incidencias" style="position: relative;">
                <input type="hidden" name="id_inc" id="id_inc">
                <input type="hidden" name="estado_info" id="estado_info">
                <div class="modal-header">
                    <h5 class="modal-title">NUEVA INCIDENCIA
                        <span class="ms-2 badge badge-lg rounded-pill" style="background-color: #5a8bdb"
                            id="cod_inc_text">{{ $data['cod_inc'] }}</span>
                        <span class="badge badge-lg" aria-item="contrato"></span>
                    </h5>
                    <div class="align-items-center d-flex gap-2">
                        <span aria-item="estado"></span>
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <input type="text" class="d-none" name="cod_inc" id="cod_inc" value="{{ $data['cod_inc'] }}">
                    <div class="col-12 mb-2">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios
                            (<span class="text-danger">*</span>)</span>
                    </div>
                    <h6 class="text-uppercase mt-4 mb-2 title_detalle"><i class="fas fa-city me-2"></i>Datos Empresa</h6>
                    <div class="row detalle_body">
                        <div class="col-lg-8 mb-2">
                            <select class="select-clear" id="empresa">
                                <option value="">Seleccione...</option>
                                @foreach ($data['company'] as $e)
                                    <option value="{{ $e->ruc }}"
                                        {{ $e->status != 1 || $e->eliminado == 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ $e->ruc }} - {{ $e->razon_social }}
                                        {{ $e->eliminado == 1 ? '<label class="badge badge-danger ms-2">Elim.</label>' : ($e->status != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 mb-2">
                            <select class="select-clear" id="sucursal">
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-uppercase mt-4 mb-2 title_detalle"><i class="far fa-address-book me-2"></i>Datos Contacto</h6>
                    <div class="row detalle_body">
                        <input type="hidden" name="cod_contact" id="cod_contact">
                        <input type="hidden" name="consultado_api" id="consultado_api">
                        <div class="col-lg-3 col-4 mb-2">
                            <input class="form-control" id="nro_doc">
                        </div>
                        <div class="col-lg-5 col-8 mb-2">
                            <select id="nom_contac">
                                <option value=""></option>
                                @foreach ($data['eContactos'] as $ct)
                                    <option value="{{ $ct->nombres }}">{{ $ct->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-5 mb-2">
                            <select class="select-tags" id="tel_contac" minlength="9" maxlength="9"
                                onchange="validContac(this)">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-7 mb-2">
                            <select class="select-clear" id="car_contac" onchange="validContac(this)">
                                <option value="">Seleccione...</option>
                                @foreach ($data['CargoEstacion'] as $cc)
                                    <option value="{{ $cc['id'] }}"
                                        {{ $cc['selected'] == 1 && $cc['estatus'] == 1 ? 'selected' : '' }}
                                        {{ $cc['estatus'] != 1 || $cc['eliminado'] == 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ $cc['descripcion'] }}
                                        {{ $cc['eliminado'] == 1 ? '<label class="badge badge-danger ms-2">Elim.</label>' : ($cc['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-12 mb-2">
                            <input class="form-control" id="cor_contac" onkeyup="validContac(this)">
                        </div>
                    </div>

                    <h6 class="text-uppercase mt-4 mb-2 title_detalle"><i class="fas fa-book-skull me-2"></i>Datos Incidencia</h6>
                    <div class="row detalle_body">
                        <div class="col-lg-4 mb-2">
                            <select class="select-clear" id="tEstacion">
                                <option value="">Seleccione...</option>
                                @foreach ($data['tEstacion'] as $k => $v)
                                    <option value="{{ $v['id'] }}"
                                        {{ $v['selected'] == 1 && $v['estatus'] == 1 ? 'selected' : '' }}
                                        {{ $v['estatus'] != 1 || $v['eliminado'] == 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ $v['descripcion'] }}
                                        {{ $v['eliminado'] == 1 ? '<label class="badge badge-danger ms-2">Elim.</label>' : ($v['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 mb-2">
                            <select class="select" id="tIncidencia">
                                <option value="">Seleccione...</option>
                                @foreach ($data['tIncidencia'] as $v)
                                    <option value="{{ $v['id'] }}"
                                        {{ $v['selected'] == 1 && $v['estatus'] == 1 ? 'selected' : '' }}
                                        {{ $v['estatus'] != 1 || $v['eliminado'] == 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ '<label class="badge badge-' . $v['color'] . ' me-2">' . $v['tipo'] . '</label>' }}{{ $v['descripcion'] }}
                                        {{ $v['eliminado'] == 1 ? '<label class="badge badge-danger ms-2">Elim.</label>' : ($v['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 mb-2">
                            <select class="select" id="tSoporte">
                                <option value="">Seleccione...</option>
                                @foreach ($data['tSoporte'] as $v)
                                    <option value="{{ $v['id'] }}"
                                        {{ $v['selected'] == 1 && $v['estatus'] == 1 ? 'selected' : '' }}
                                        {{ $v['estatus'] != 1 || $v['eliminado'] == 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ $v['descripcion'] }}
                                        {{ $v['eliminado'] == 1 ? '<label class="badge badge-danger ms-2">Elim.</label>' : ($v['estatus'] != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            <select class="select-clear" id="problema">
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <select class="select-clear" id="sproblema">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-12 col-6 mb-2">
                                    <input class="form-control" id="fecha_imforme">
                                </div>
                                <div class="col-sm-12 col-6 mb-2">
                                    <input class="form-control" id="hora_informe">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 mb-2">
                            <textarea class="form-control" id="observacion" style="height: 106px;resize: none;"></textarea>
                        </div>
                    </div>
                    <script>
                        const fecha_imforme = new MaterialDateTimePicker({
                            inputId: 'fecha_imforme',
                            mode: 'date',
                            format: 'MMMM DD de YYYY'
                        });
                        fecha_imforme.val("{{ date('Y-m-d') }}");

                        const hora_informe = new MaterialDateTimePicker({
                            inputId: 'hora_informe',
                            mode: 'time',
                            format: 'HH:mm',
                            format24h: true,
                        });
                        hora_informe.val("{{ date('H:i') }}");
                    </script>

                    <div id="contenedor-personal">
                        <h6 class="tittle text-primary mt-4"><i class="fas fa-user-plus me-2"></i>Asignar Personal
                        </h6>
                        <div id="createPersonal"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init
                        data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal_detalle" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de incidencia
                        <span class="ms-2 badge badge-lg rounded-pill" style="background-color: #5a8bdb"
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

    <div class="modal fade" id="modal_assign" aria-labelledby="modal_assign" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar
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

                    <div class="detalle_body d-flex align-items-center justify-content-between mb-2">
                        <span class="detalle_text mb-0">Tipo Soporte</span>
                        <span class="detalle_text text-uppercase fw-bolder mb-0" aria-item="soporte"></span>
                    </div>
                    <div class="detalle_body mb-3">
                        <p class="detalle_label mb-1 text-uppercase fw-bolder text-muted">Problema Reportado</p>
                        <p class="detalle_text fw-bold mb-0" aria-item="problema"></p>
                        <p class="detalle_text text-muted fst-italic mb-0 mt-2" aria-item="subproblema"></p>
                    </div>

                    <h6 class="text-uppercase mt-4 mb-2 title_detalle">Observación</h6>
                    <div class="detalle_body datalle_observacion" aria-item="observacion"></div>

                    <h6 class="text-uppercase mt-4 title_detalle text-primary" style="font-size: 14px"><i
                            class="fas fa-user-plus ms-2"></i>Asignar Personal</h6>

                    <div class="">
                        <div id="createPersonal1"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init
                        data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init
                        onclick="AssignPer()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_orden" aria-labelledby="modal_orden" tabindex="-1" aria-hidden="true" style="overflow: clip;">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-scrollable modal-xl" style="display: flex;">
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
                                <input type="text" class="form-control" id="n_orden" name="n_orden"
                                    requested="N° de Orden">
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
                                <textarea class="form-control" id="observaciones" style="height: 80px;resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pt-2">
                                <textarea class="form-control" id="recomendacion" style="height: 80px;resize: none;" require="Recomendaciones"></textarea>
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
                                <img class="border rounded-1"
                                    {{ Auth::user()->firma_digital ? 'src=' . asset('front/images/firms/' . Auth::user()->firma_digital) . '?v=' . time() : '' }}
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
                                <input type="hidden" name="n_doc" id="n_doc">
                                <input type="hidden" name="nom_cliente" id="nom_cliente">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init
                        data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init
                        onclick="">Registrar</button>
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

@endsection

@section('scripts')
    <script>
        // 
    </script>
    <script src="{{ secure_asset($ft_js->SelectManeger) }}"></script>
    <script src="{{ secure_asset('front/vendor/signature/signature_pad.js') }}"></script>
    <script src="{{ secure_asset('front/js/soporte/incidencia/registradas.js') }}"></script>
@endsection
