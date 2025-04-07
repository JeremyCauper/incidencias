@extends('layout.appEmpresa')
@section('title', 'INC RESUELTAS')

@section('cabecera')
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{secure_asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{secure_asset('front/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/resueltas.css')}}">
    <script src="{{secure_asset('front/vendor/multiselect/bootstrap.bundle.min.js')}}"></script>
    <script src="{{secure_asset('front/vendor/multiselect/bootstrap_multiselect.js')}}"></script>
    <script src="{{secure_asset('front/vendor/multiselect/form_multiselect.js')}}"></script>
    <script>
        let empresa = <?php echo json_encode(session('empresa')); ?>;
        let sucursales = <?=json_encode($data['scompany'])?>;
        let tipo_incidencia = <?=json_encode($data['tIncidencia'])?>;
        let obj_problem = <?=json_encode($data['problema'])?>;
        let obj_subproblem = <?=json_encode($data['sproblema'])?>;
        let usuarios = <?=json_encode($data['usuarios'])?>;
    </script>
@endsection
@section('content')

    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body form-container">
                <h6 class="text-primary"><i class="fas fa-filter"></i> Filtros de Busqueda</h6>
                <div class="row">
                    <div class="col-xxl-5 my-1">
                        <label class="form-label mb-0" for="empresa">Empresa</label>
                        <select id="empresa" name="empresa" class="select" disabled="true">
                            <option selected value="{{ session('empresa')->ruc }}">
                                {{ session('config_layout')->nombre_perfil }}</option>
                        </select>
                        <!-- <input type="text" class="form-control" id="empresa" name="empresa" value="{{ session('config_layout')->nombre_perfil }}" disabled> -->
                    </div>
                    <div class="col-xxl-3 col-md-8 my-1">
                        <label class="form-label mb-0" for="sucursal">Sucursal</label>
                        <select id="sucursal" name="sucursal" class="select-clear">
                            <option value="">-- Seleccione --</option>
                            <option selected value="0">Todos</option>
                            @foreach ($data['scompany'] as $key => $val)
                                <option value="{{$val->id}}">{{$val->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-2 col-md-4 my-1">
                        <label class="form-label mb-0" for="tIncidencia">Tipo Incidencia</label>
                        <select class="select" id="tIncidencia">
                            <option value="">-- Seleccione --</option>
                            <option selected value="0">Todos</option>
                            @foreach ($data['tIncidencia'] as $v)
                                <option value="{{$v['id']}}">{{$v['descripcion']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-2 col-md-4 my-1">
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
                    <div class="col-xxl-2 col-md-4 my-1">
                        <label class="form-label mb-0" for="dateRango">Rango</label>
                        <input type="text" class="form-control" id="dateRango" name="dateRango" role="button" readonly>
                    </div>
                    <div class="align-items-end col-xxl-2 d-flex my-1 justify-content-end">
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
                    <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_orden" class="table table-hover text-nowrap w-100">
                            <thead>
                                <tr class="text-bg-primary text-center">
                                    <th>Incidencia</th>
                                    <th>Estado</th>
                                    <th>Fecha Incidencia</th>
                                    <th>N° Orden</th>
                                    <th>Tecnico</th>
                                    <th>Empresa</th>
                                    <th>Sucursal</th>
                                    <th>Tipo Incidencia</th>
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
                                    url: `${__url}/empresa/incidencias/index?sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}`,
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
                                        data: 'empresa', render: function (data, type, row) {
                                            return `${empresa.ruc} - ${empresa.razon_social}`;
                                        }
                                    },
                                    {
                                        data: 'sucursal', render: function (data, type, row) {
                                            return sucursales[data].nombre;
                                        }
                                    },
                                    {
                                        data: 'tipo_incidencia', render: function (data, type, row) {
                                            return tipo_incidencia[data].descripcion;
                                        }
                                    },
                                    {
                                        data: 'problema', render: function (data, type, row) {
                                            return `${obj_problem[data].text} / ${obj_subproblem[row.subproblema].text}`;
                                        }
                                    },
                                    { data: 'iniciado' },
                                    { data: 'finalizado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).find('td:eq(0), td:eq(1), td:eq(2), td:eq(3), td:eq(7), td:eq(9), td:eq(10), td:eq(11)').addClass('text-center');
                                    $(row).find('td:eq(11)').addClass(`td-acciones`);
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title"><i class="fas fa-book-open"></i> Detalle de incidencia -
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
                            <div class="list-group-item">
                                <label class="form-label me-2">Problema / Sub Problema:</label>
                                <span style="font-size: .75rem;" aria-item="problema / sub problema"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label me-2">Observación:</label>
                                <span style="font-size: .75rem;" aria-item="observacion"></span>
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
                    <div class="col-12 text-center">
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