@extends('layout.app')
@section('title', 'Sub Problemas')

@section('cabecera')
    <!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/registradas.css')}}"> -->
@endsection
@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Listado de Sub Problemas</strong>
                </h6>
                <div>
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#modal_subproblemas">
                        <i class="fas fa-plus"></i>
                        Nuevo Sub Problema
                    </button>
                    <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_subproblemas" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-bg-primary text-center">
                                    <th>Cod. Problema</th>
                                    <th>Prioridad</th>
                                    <th>Descripcion</th>
                                    <th>Registrado</th>
                                    <th>Actualizado</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                        <script>
                            const tb_subproblemas = new DataTable('#tb_subproblemas', {
                                autoWidth: true,
                                scrollX: true,
                                scrollY: 400,
                                fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
                                ajax: {
                                    url: `${__url}/soporte/mantenimiento/problemas/subproblemas/index`,
                                    dataSrc: "",
                                    error: function (xhr, error, thrown) {
                                        boxAlert.table();
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'codigo_problema' },
                                    { data: 'prioridad', render: function (data, type, row) {
                                            return getBadgePrioridad(data, .75);
                                        }
                                    },
                                    { data: 'descripcion' },
                                    { data: 'created_at' },
                                    { data: 'updated_at' },
                                    { data: 'estado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).find('td:eq(0), td:eq(1), td:eq(3), td:eq(4), td:eq(5), td:eq(6)').addClass('text-center');
                                    $(row).find('td:eq(6)').addClass(`td-acciones`);
                                },
                                processing: true
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_subproblemas" tabindex="-1" aria-labelledby="modal_subproblemasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-subproblema">
                <div class="modal-header  bg-primary text-white">
                    <h6 class="modal-title" id="modal_subproblemasLabel">REGISTRAR SUB PROBLEMA</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <div class="col-lg-8 mb-2">
                            <label class="form-label mb-0" for="problema">Problema</label>
                            <select class="select-clear" id="problema">
                                <option value="">-- Seleccione --</option>
                                @foreach ($data['problemas'] as $v)
                                    <option value="{{ $v->codigo }}"
                                        {{ $v->estatus != 1 || $v->eliminado == 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{$v->codigo}} - {{ $v->descripcion }}
                                        {{ $v->eliminado == 1 ? '<label class="badge badge-danger ms-2">Elim.</label>' : ($v->estatus != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label mb-0" for="prioridad">Prioridad</label>
                            <select class="select" id="prioridad">
                                <option value="">-- Seleccione --</option>
                                <option value="P1">{{ '<label class="badge badge-dark me-2">P1</label>' }}CRITICA</option>
                                <option value="P2">{{ '<label class="badge badge-danger me-2">P2</label>' }}ALTA</option>
                                <option value="P3">{{ '<label class="badge badge-warning me-2">P3</label>' }}MEDIA</option>
                                <option value="P4">{{ '<label class="badge badge-success me-2">P4</label>' }}BAJA</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-2">
                            <label class="form-label mb-0" for="descripcion">Descripcion</label>
                            <input class="form-control" id="descripcion">
                        </div>
                        <div class="col-lg-4 mb-2">
                            <label class="form-label mb-0" for="estado">Estado</label>
                            <select class="select" id="estado">
                                <option selected value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
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
    <script src="{{secure_asset('front/js/soporte/mantenimiento/problemas/subproblemas.js')}}?v={{ time() }}"></script>
@endsection