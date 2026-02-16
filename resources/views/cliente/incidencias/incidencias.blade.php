@extends('layout.appEmpresa')
@section('title', 'Incidencias Resueltas')

@section('cabecera')
    <script type="text/javascript" src="{{secure_asset($ft_js->daterangepicker_moment)}}"></script>
    <script type="text/javascript" src="{{secure_asset($ft_js->daterangepicker)}}"></script>
    <link rel="stylesheet" type="text/css" href="{{secure_asset($ft_css->daterangepicker)}}">
    <script src="{{secure_asset($ft_js->bootstrap_bundle)}}"></script>
    <script src="{{secure_asset($ft_js->bootstrap_multiselect)}}"></script>
    <script src="{{secure_asset($ft_js->form_multiselect)}}"></script>

    <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/resueltas.css')}}">
    <script>
        let empresa = @json(config('ajustes.empresa'));
        let sucursales = @json($data['scompany']);
        let tipo_soporte = @json($data['tSoporte']);
        let tipo_incidencia = @json($data['tIncidencia']);
        let obj_problem = @json($data['problema']);
        let obj_subproblem = @json($data['sproblema']);
        let usuarios = @json($data['usuarios']);
    </script>
@endsection
@section('content')

    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body form-container">
                <h6 class="text-primary">Filtros de Busqueda</h6>
                <div class="row">
                    <div class="col-lg-7 my-1">
                        <label class="form-label mb-0" for="empresa">Empresa</label>
                        <input type="text" class="form-control" value="{{ config('ajustes.config')->nombre_perfil }}"
                            readonly role="button">
                    </div>
                    <div class="col-lg-5 my-1">
                        <label class="form-label mb-0" for="sucursal">Sucursal</label>
                        <select id="sucursal" name="sucursal" class="select-search">
                            <option selected value="0">Todos</option>
                            @foreach ($data['scompany'] as $key => $val)
                                <option value="{{$val->id}}">{{$val->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-6 my-1">
                        <label class="form-label mb-0" for="tSoporte">Tipo Soporte</label>
                        <select id="tSoporte" multiple="multiple" class="multiselect-select-all">
                            @foreach ($data['tSoporte'] as $v)
                                <option selected value="{{$v['id']}}">{{$v['descripcion']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-6 my-1">
                        <label class="form-label mb-0" for="tEstado">Estado</label>
                        <select id="tEstado" multiple="multiple" class="multiselect-select-all">
                            <option selected value="0">Sin Asignar</option>
                            <option selected value="1">Asignada</option>
                            <option selected value="2">En Proceso</option>
                            <option selected value="3">Finalizado</option>
                            <option selected value="4">Faltan Datos</option>
                            <option selected value="5">Cierre Sistema</option>
                        </select>
                    </div>
                    <div class="col-md-4 my-1">
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

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Incidencias Resueltas</strong>
                </h6>
                <div>
                    <button type="button" class="d-none" data-mdb-modal-init data-mdb-target="#modal_detalle"></button>
                    <button class="btn btn-primary" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_orden" class="table table-hover text-nowrap w-100">
                            <thead>
                                <tr class="text-center">
                                    <th>Incidencia</th>
                                    <th>Estado</th>
                                    <th>Fecha Incidencia</th>
                                    <th>N° Orden</th>
                                    <th>Tecnico</th>
                                    <th>Sucursal</th>
                                    <th>Nivel Incidencia</th>
                                    <th>Soporte</th>
                                    <th>Prioridad</th>
                                    <th>Problema / Sub Problema</th>
                                    <th>Iniciada</th>
                                    <th>Terminada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                        <script>
                            const tb_orden = new DataTable('#tb_orden', {
                                scrollX: true,
                                scrollY: 400,
                                ajax: {
                                    url: `${__url}/empresa/incidencias/index?sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}&tSoporte=${$('#tSoporte').val()}&tEstado=${$('#tEstado').val()}`,
                                    dataSrc: function (json) {
                                        return json.data;
                                    },
                                    error: function (xhr, error, thrown) {
                                        boxAlert.table();
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'cod_incidencia' },
                                    { data: 'estado' },
                                    { data: 'fecha_inc' },
                                    { data: 'cod_orden' },
                                    {
                                        data: 'asignados', render: function (data, type, row) {
                                            return (data.map(usu => usuarios[usu].nombre)).join(", ");
                                        }
                                    },
                                    {
                                        data: 'sucursal', render: function (data, type, row) {
                                            return sucursales[data].nombre;
                                        }
                                    },
                                    {
                                        data: 'tipo_incidencia', render: function (data, type, row) {
                                            let tipo = tipo_incidencia[data[data.length - 1]];
                                            return `<label class="badge badge-${tipo.color} me-2" style="font-size: 0.75rem;">${tipo.tipo}</label>${tipo.descripcion}`;
                                        }
                                    },
                                    {
                                        data: 'tipo_soporte', render: function (data, type, row) {
                                            return tipo_soporte[data].descripcion;
                                        }
                                    },
                                    { data: 'subproblema', render: function (data, type, row) { return getBadgePrioridad(obj_subproblem[row.subproblema].prioridad, .75) } },
                                    {
                                        data: 'problema', render: function (data, type, row) {
                                            return `${obj_problem[data].descripcion} / ${obj_subproblem[row.subproblema].descripcion}`;
                                        }
                                    },
                                    { data: 'iniciado' },
                                    { data: 'finalizado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).find('td:eq(4), td:eq(5), td:eq(6), td:eq(8)').addClass('text-left');
                                    $(row).addClass('text-center');
                                    $(row).find('td:eq(12)').addClass(`td-acciones`);
                                },
                                order: [[2, 'desc']],
                                processing: true
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_detalle" aria-labelledby="modal_detalle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(59 113 202 / 25%);">
                    <h5 class="modal-title">Detalle de incidencia
                        <span class="text-nowrap">
                            <span class="badge badge-lg rounded-pill" style="background-color: #5a8bdb" aria-item="codigo"></span>
                            <span class="badge badge-lg rounded-pill ms-1" style="background-color: #5a8bdb" aria-item="codigo_orden"></span>
                        </span>
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
                            style="background-color: rgb(29 49 69 / 5%);">
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

    <div class="modal fade" id="modal_firmas" aria-labelledby="modal_firmas" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="form-firmas">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">ASIGNAR FIRMA
                        <span class="badge badge-success badge-lg" aria-item="codigo"></span>
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <h6 class="font-weight-semibold text-primary tt-upper m-0" style="font-size: smaller;">Agregar firma
                        </h6>
                        <span aria-item="estado"></span>
                    </div>
                    <div class="col-12 fieldset text-center">
                        <input type="hidden" name="cod_orden">
                        <div class="search_signature_group mb-2">
                            <input type="text" id="search_signature" placeholder="Buscar cliente">
                            <span class="search_signature_text rounded" type="button" data-mdb-ripple-init>
                                <i class="fas fa-magnifying-glass"></i>
                            </span>
                        </div>
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
                        <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma
                            Cliente</p>
                        <p class="mb-1" style="font-size: 13.4px;" aria-item="empresaFooter">COESTI S.A.</p>
                        <input type="hidden" name="id_firmador" id="id_firmador">
                        <input type="hidden" name="nomFirmaDigital" id="nomFirmaDigital">
                        <input type="hidden" name="n_doc" id="n_doc">
                        <input type="hidden" name="nom_cliente" id="nom_cliente">
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
    <script src="{{secure_asset('front/js/cliente/incidencia/incidencia.js')}}"></script>
@endsection