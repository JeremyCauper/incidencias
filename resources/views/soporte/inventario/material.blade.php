@extends('layout.app')
@section('title', 'Materiales')

@section('cabecera')
    <!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/usuario/usuarios.css')}}?v={{ time() }}"> -->
@endsection
@section('content')

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Listado de Usuarios</strong>
                </h6>
                <div class="mb-3">
                    <!-- <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                                                        data-mdb-target="#modal_material">
                                                        <i class="fas fa-user-plus me-2"></i>
                                                        Nuevo Material
                                                    </button> -->
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#modal_asignar">
                        <i class="fas fa-user-plus me-2"></i>
                        Asignar Material
                    </button>
                    <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_material" class="table text-nowrap" style="width: 100%;">
                            <thead>
                                <tr class="text-bg-primary text-center">
                                    <th>Id</th>
                                    <th>Codigo</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                        <script>
                            const tb_material = new DataTable('#tb_material', {
                                scrollX: true,
                                scrollY: 300,
                                ajax: {
                                    url: `${__url}/soporte/inventario/materiales/index`,
                                    dataSrc: "data",
                                    error: function (xhr, error, thrown) {
                                        boxAlert.table();
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'id_materiales' },
                                    { data: 'codigo' },
                                    { data: 'producto' },
                                    { data: 'cantidad' },
                                    { data: 'estado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).find('td:eq(0), td:eq(1), td:eq(4), td:eq(5)').addClass('text-center');
                                    $(row).find('td:eq(5)').addClass(`td-acciones`);
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
    <div class="modal fade" id="modal_asignar" tabindex="-1" aria-labelledby="modal_asignarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-asignar">
                <div class="modal-header  bg-primary text-white">
                    <h6 class="modal-title" id="modal_asignarLabel">Asignar Material</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 mb-2">
                            <label class="form-label mb-0" for="tecnico">Tecnicos</label>
                            <div class="input-group">
                                <select class="select-clear" id="tecnico">
                                    <option value=""></option>
                                    @foreach ($data['usuarios'] as $u)
                                        <option value="{{$u['value']}}">{{$u['text']}}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary btn-sm px-2" type="button"><i
                                        class="fas fa-magnifying-glass"></i></button>
                            </div>
                        </div>
                        <div class="col-md-10 mb-4">
                            <label class="form-label mb-0" for="materiales">Materiales</label>
                            <div class="input-group">
                                <select class="select-clear" id="materiales">
                                    <option value=""></option>
                                    @foreach ($data['materiales'] as $m)
                                        <option value="{{$m['id']}}">{{$m['producto']}}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary btn-sm px-2" type="button"><i
                                        class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="overflow-auto">
                                <table id="tb_material_asignado" class="table table-hover text-nowrap" style="width:100%">
                                    <thead>
                                        <tr class="text-bg-primary text-center">
                                            <th>Material</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
        $('.modal').on('shown.bs.modal', function () {
            switch ($(this).attr('id')) {
                case 'modal_asignar':
                    // tb_material_asignado.columns.adjust().draw();
                    break;

                default:
                    break;
            }
        });
    </script>
    <!-- <script src="{{secure_asset('front/js/soporte/usuario/usuarios.js')}}?v={{ time() }}"></script> -->
@endsection