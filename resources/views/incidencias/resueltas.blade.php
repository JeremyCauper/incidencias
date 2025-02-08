@extends('layout.app')
@section('title', 'INC RESUELTAS')

@section('style')
<script type="text/javascript" src="{{asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('front/vendor/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('front/css/app/incidencias/resueltas.css')}}">
@endsection
@section('content')

<div class="col-12 mb-4">
    <div class="card">
        <div class="card-body form-container">
            <h6 class="text-primary"><i class="fas fa-filter"></i> Filtros de Busqueda</h6>
            <div class="row">
                <div class="col-xxl-5 my-1">
                    <label class="form-label mb-0" for="empresa">Empresa</label>
                    <select id="empresa" name="empresa" class="select-clear">
                        <option value=""></option>
                        @foreach ($data['empresas'] as $key => $val)
                            @if ($val['status'])
                                <option value="{{$val['ruc']}}" id-empresa="{{$val['id']}}">
                                    {{$val['ruc'] . ' - ' . $val['razonSocial']}}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-xxl-3 col-md-8 my-1">
                    <label class="form-label mb-0" for="idGrupo">Sucursal</label>
                    <select id="sucursal" name="sucursal" class="select" disabled="true">
                        <option value="">-- Seleccione --</option>
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
                            <tr class="text-bg-primary">
                                <th>Codigo</th>
                                <th>Tipo Orden</th>
                                <th>Tecnico</th>
                                <th>Fecha Servicio</th>
                                <th>Empresa</th>
                                <th>Sucursal</th>
                                <th>Problema / Sub Problema</th>
                                <th>Iniciada</th>
                                <th>Terminada</th>
                                <th class="text-bg-primary px-2 th-acciones">Acciones</th>
                            </tr>
                        </thead>
                    </table>
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
                            <span style="font-size: small;" aria-item="iniciada"></span>
                        </div>
                    </div>
                    <!-- TER CABECERA -->
                    <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                        <h6 class="tittle text-primary"> TECNICOS</h6>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
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
                                            style="font-size: .75rem;" aria-item="tipo orden">Remoto</span>
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
                                    <label class="form-label" for="exampleInputEmail1">Observaciones *</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones"
                                        style="height: 80px;resize: none;" disabled></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="exampleInputEmail1">Recomendaciones *</label>
                                    <textarea class="form-control" id="recomendaciones" name="recomendaciones"
                                        style="height: 80px;resize: none;" disabled></textarea>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="exampleInputEmail1">Fecha Fin </label>
                                    <input type="text" class="form-control form-control-sm" id="fecha_f" disabled>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group pt-2">
                                    <label class="form-label" for="exampleInputEmail1">Hora Fin </label>
                                    <input type="text" class="form-control form-control-sm" id="hora_f" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 cabeceras">
                        <h6 class="tittle text-primary">MATERIALES UTILIZADOS</h2>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row" id="content-material">
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 my-2 px-4">
                        <div class="row justify-content-between firmas-orden">
                            <div class="col-lg-5 text-center my-2">
                                <div class="text-center content-image">
                                    <img id="firmaCreador" class="visually-hidden" height="130" width="160">
                                </div>
                                <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma Tecnico
                                </p>
                                <p class="mb-1" style="font-size: 13.4px;">RICARDO CALDERON INGENIEROS SAC</p>
                                <p class="mb-0" style="font-size: 12px;" id="nomCreador"></p>
                            </div>

                            <div class="col-lg-5 text-center my-2">
                                <div class="text-center content-image">
                                    <img id="PrevizualizarFirma" class="visually-hidden" height="130" width="160">
                                </div>
                                <p class="pt-1 text-secondary" style="font-weight: 600;font-size: .85rem;">Firma Cliente
                                </p>
                                <p class="mb-1" style="font-size: 13.4px;" aria-item="empresaFooter">COESTI S.A.</p>
                                <p class="mb-0" style="font-size: 12px;" id="doc_clienteFirma"></p>
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

<div class="modal fade" id="modal_firmas" aria-labelledby="modal_firmas" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init
                    onclick="AssignPer()">Guardar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const sucursales = <?php echo json_encode($data['sucursales']); ?>;
</script>
<script src="{{asset('front/vendor/signature/signature_pad.js')}}"></script>
<script src="{{asset('front/js/app/incidencia/resueltas.js')}}"></script>
@endsection