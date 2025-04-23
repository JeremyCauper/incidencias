@extends('layout.app')
@section('title', 'Empresas')

@section('content')

    <style>
    </style>

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Listado de Empresas</strong>
                </h6>
                <div class="mb-3">
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#modal_empresas">
                        <i class="fas fa-plus me-1"></i>
                        Nueva Empresa
                    </button>
                    <button class="btn btn-primary px-2" onclick="updateTable()">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_empresas" class="table text-nowrap" style="width: 100%;">
                            <thead>
                                <tr class="text-bg-primary text-center">
                                    <th>Grupo</th>
                                    <th>Ruc</th>
                                    <th>Empresa</th>
                                    <th>Contrato</th>
                                    <th>Fecha Registro</th>
                                    <th>Actualizado</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                        <script>
                            const tb_empresas = new DataTable('#tb_empresas', {
                                scrollX: true,
                                scrollY: 300,
                                ajax: {
                                    url: `${__url}/soporte/empresas/empresas/index`,
                                    dataSrc: "",
                                    error: function (xhr, error, thrown) {
                                        boxAlert.table();
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'grupo' },
                                    { data: 'ruc' },
                                    { data: 'razonSocial' },
                                    { data: 'contrato' },
                                    { data: 'created_at' },
                                    { data: 'updated_at' },
                                    { data: 'estado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).find('td:eq(1), td:eq(3), td:eq(4), td:eq(5), td:eq(6), td:eq(7)').addClass('text-center');
                                    $(row).find('td:eq(7)').addClass(`td-acciones`);
                                },
                                processing: true
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_empresas" class="modal fade" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl">
            <form class="modal-content" id="form-empresa">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title" id="modal_empresasLabel">REGISTRAR EMPRESA</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12 mb-2">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
                    </div>
                    <div class="row">
                        <h6 class="tittle text-primary my-3">Datos Empresa</h6>
                        <input type="hidden" name="id" id="id">
                        <div class="col-lg-4 col-7 mb-2">
                            <label class="form-label mb-0" for="idGrupo">Grupo</label>
                            <select id="idGrupo" class="select-clear">
                                <option value=""></option>
                                @foreach ($data['grupos'] as $key => $val)
                                    @if ($val->status)
                                        <option value="{{$val->id}}">{{$val->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-5 mb-2">
                            <label class="form-label mb-0" for="ruc">Ruc</label>
                            <input class="form-control" id="ruc">
                        </div>
                        <div class="col-lg-5 col-12 mb-2">
                            <label class="form-label mb-0" for="razonSocial">Razon Social</label>
                            <input class="form-control" id="razonSocial">
                        </div>

                        <div class="col-lg-7 mb-2">
                            <label class="form-label mb-0" for="direccion">Dirección</label>
                            <input class="form-control" id="direccion">
                        </div>
                        <div class="col-lg-5 mb-2">
                            <label class="form-label mb-0" for="ubigeo">Ubigeo</label>
                            <select id="ubigeo" class="select-clear">
                                <option value=""></option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-6 mb-2">
                            <label class="form-label mb-0" for="contrato">Contrato</label>
                            <select id="contrato" class="select">
                                <option value=""></option>
                                <option value="1">Si</option>
                                <option selected value="0">No</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-6 mb-2">
                            <label class="form-label mb-0" for="facturacion">Facturacion</label>
                            <select id="facturacion" class="select">
                                <option value=""></option>
                                <option value="1">Si</option>
                                <option selected value="0">No</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-6 mb-2">
                            <label class="form-label mb-0" for="prico">Prico</label>
                            <select id="prico" class="select">
                                <option value=""></option>
                                <option value="1">Ose</option>
                                <option selected value="0">Sunat</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-6 mb-2">
                            <label class="form-label mb-0" for="eds">Eds</label>
                            <select id="eds" class="select">
                                <option value=""></option>
                                <option selected value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-4 mb-2">
                            <label class="form-label mb-0" for="idNube">Id Nube</label>
                            <input class="form-control" id="idNube" name="idNube">
                        </div>

                        <div class="col-lg-2 col-4 mb-2">
                            <label class="form-label mb-0" for="visitas">Visitas</label>
                            <input class="form-control" id="visitas" name="visitas">
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <label class="form-label mb-0" for="diasVisita">Dias Visita</label>
                            <input class="form-control" id="diasVisita" name="diasVisita">
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <label class="form-label mb-0" for="mantenimientos">Mantenimientos</label>
                            <input class="form-control" id="mantenimientos" name="mantenimientos">
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <label class="form-label mb-0" for="estado">Estado</label>
                            <select class="select" id="estado">
                                <option selected value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <label class="form-label mb-0" for="codVisita">Cod. Visita</label>
                            <select class="select" id="codVisita">
                                <option selected value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                        </div>

                        <h6 class="tittle text-primary my-3">Datos Contacto</h6>

                        <div class="col-lg-3 mb-2">
                            <label class="form-label mb-0" for="cargo">Cargo</label>
                            <select class="select-clear" id="cargo">
                                <option value=""></option>
                                @foreach ($data['cargos'] as $key => $val)
                                    @if ($val['estatus'])
                                        <option value="{{$val['id']}}">{{$val['descripcion']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mb-2">
                            <label class="form-label mb-0" for="encargado">Encargado</label>
                            <input class="form-control" id="encargado">
                        </div>
                        <div class="col-lg-2 col-4 mb-2">
                            <label class="form-label mb-0" for="telefono">Telefono</label>
                            <input class="form-control" id="telefono">
                        </div>
                        <div class="col-lg-4 col-8">
                            <label class="form-label mb-0" for="correo">Correo</label>
                            <input class="form-control" id="correo">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('scripts')
    <!-- jQuery Mask Plugin CDN -->
    <script src="{{secure_asset('front/vendor/ubigeos-peru/ubigeo.js')}}?v={{ time() }}"></script>
    <script src="{{secure_asset('front/js/soporte/empresas/empresas.js')}}?v={{ time() }}"></script>
@endsection