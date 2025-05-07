@extends('layout.app')
@section('title', 'Problemas')

@section('cabecera')
    <!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/registradas.css')}}"> -->
    <script>
        let tipo_soporte = <?php echo json_encode($data['tSoporte']); ?>;
    </script>
@endsection
@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Listado de Problemas</strong>
                </h6>
                <div>
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#modal_problemas">
                        <i class="fas fa-plus"></i>
                        Nuevo Problema
                    </button>
                    <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_problemas" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-bg-primary text-center">
                                    <th>Codigo</th>
                                    <th>Descripcion</th>
                                    <th>Tipo</th>
                                    <th>Registrado</th>
                                    <th>Actualizado</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                    <!-- <th class="text-bg-primary px-2 th-acciones">Acciones</th> -->
                                </tr>
                            </thead>
                        </table>
                        <script>
                            const tb_problemas = new DataTable('#tb_problemas', {
                                autoWidth: true,
                                scrollX: true,
                                scrollY: 400,
                                fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
                                ajax: {
                                    url: `${__url}/soporte/mantenimiento/problemas/problemas/index`,
                                    dataSrc: "",
                                    error: function (xhr, error, thrown) {
                                        boxAlert.table();
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'codigo' },
                                    { data: 'descripcion' },
                                    { data: 'tipo_soporte', render: function(data, type, row) {
                                            return tipo_soporte[data].descripcion;
                                        } 
                                    },
                                    { data: 'created_at' },
                                    { data: 'updated_at' },
                                    { data: 'estado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).addClass('text-center');
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
    <div class="modal fade" id="modal_problemas" tabindex="-1" aria-labelledby="modal_problemasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-problema">
                <div class="modal-header  bg-primary text-white">
                    <h6 class="modal-title" id="modal_problemasLabel">REGISTRAR PROBLEMA</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <div class="col-lg-4 mb-2">
                            <label class="form-label mb-0" for="codigo">Codigo</label>
                            <input class="form-control" id="codigo">
                        </div>
                        <div class="col-lg-8 mb-2">
                            <label class="form-label mb-0" for="descripcion">Descripcion</label>
                            <input class="form-control" id="descripcion">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label mb-0" for="tipo">Tipo</label>
                            <select class="select" id="tipo">
                                <option value="">-- Seleccione --</option>
                                @foreach ($data['tSoporte'] as $v)
                                    <option value="{{ $v->id }}"
                                        {{ ($v->selected == 1 && $v->estatus == 1) ? 'selected' : '' }}
                                        {{ $v->estatus != 1 ? 'data-hidden="true" data-nosearch="true"' : '' }}>
                                        {{ $v->descripcion }} {{ $v->estatus != 1 ? '<label class="badge badge-danger ms-2">Inac.</label>' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 mb-2">
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
    <script src="{{secure_asset('front/js/soporte/mantenimiento/problemas/problemas.js')}}?v={{ time() }}"></script>
@endsection