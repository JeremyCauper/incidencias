@extends('layout.app')
@section('title', 'Tipo Soporte')

@section('cabecera')
    <!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/registradas.css')}}"> -->

    <style>
    </style>
@endsection
@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-primary mb-3">
                    <strong>Listado de Tipo Soportes</strong>
                </h6>
                <div>
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#modal_tipo_soporte">
                        <i class="fas fa-plus"></i>
                        Nuevo Tipo Soporte
                    </button>
                    <button class="btn btn-primary" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_tipo_soporte" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Descripcion</th>
                                    <th>Registrado</th>
                                    <th>Actualizado</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                        <script>
                            const tb_tipo_soporte = new DataTable('#tb_tipo_soporte', {
                                autoWidth: true,
                                scrollX: true,
                                scrollY: 400,
                                fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
                                ajax: {
                                    url: `${__url}/soporte/mantenimiento/tiposoporte/tiposoporte/index`,
                                    dataSrc: function (json) {
                                        return json;
                                    },
                                    error: function (xhr, error, thrown) {
                                        boxAlert.table();
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'descripcion' },
                                    { data: 'created_at' },
                                    { data: 'updated_at' },
                                    { data: 'estado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).addClass('text-center');
                                    $(row).find('td:eq(4)').addClass(`td-acciones`);
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
    <div class="modal fade" id="modal_tipo_soporte" tabindex="-1" aria-labelledby="modal_tipo_soporteLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-tipo-soporte">
                <div class="modal-header  bg-primary text-white">
                    <h6 class="modal-title" id="modal_tipo_soporteLabel">REGISTRAR TIPO SOPORTE</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <div class="col-md-6 mb-2">
                            <input class="form-control" id="descripcion">
                        </div>
                        <div class="col-md-6 mb-2">
                            <select class="select" id="estado">
                                <option value="1" selected>Activo</option>
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
    <script>
    </script>
    <script src="{{secure_asset('front/js/soporte/mantenimiento/tiposoporte/tiposoporte.js')}}"></script>
@endsection