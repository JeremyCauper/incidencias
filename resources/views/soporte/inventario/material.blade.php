@extends('layout.app')
@section('title', 'Materiales')

@section('cabecera')
    <!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/usuario/usuarios.css')}}?v={{ time() }}"> -->
    <script>
        let materiales = <?php echo json_encode($data['materiales']); ?>;
    </script>
@endsection
@section('content')

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Listado de Usuarios</strong>
                </h6>
                <div class="mb-3">
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
                                    { data: 'codigo' },
                                    { data: 'producto' },
                                    { data: 'cantidad' },
                                    { data: 'estado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).find('td:eq(0), td:eq(1), td:eq(3), td:eq(4)').addClass('text-center');
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
    <div class="modal fade" id="modal_asignar" tabindex="-1" aria-labelledby="modal_asignarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="form-asignar">
                <div class="modal-header  bg-primary text-white">
                    <h6 class="modal-title" id="modal_asignarLabel">Asignar Material</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <label class="form-label mb-0" for="tecnico">Tecnicos</label>
                        <div class="input-group">
                            <select class="select-clear" id="tecnico">
                                <option value=""></option>
                                @foreach ($data['usuarios'] as $u)
                                    <option value="{{$u['value']}}">{{$u['text']}}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary btn-sm px-2" type="button" id="buscar_tecnico">
                                <i class="fas fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row d-none" id="ct_material_asignado">
                        <div class="col-md-10 mt-4 mb-2">
                            <label class="form-label mb-0" for="materiales">Materiales</label>
                            <div class="input-group">
                                <select class="select-clear" id="materiales"></select>
                                <button class="btn btn-primary btn-sm px-2" type="button" id="agregar_material">
                                    <i class="fas fa-plus"></i>
                                </button>
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
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{secure_asset('front/js/app/SelectManeger.js')}}"></script>
    <script>
        $(document).ready(function () {
            formatSelect('modal_asignar');

            $('.modal').on('shown.bs.modal', function () {
                switch ($(this).attr('id')) {
                    case 'modal_asignar':
                        // tb_material_asignado.columns.adjust().draw();
                        break;

                    default:
                        break;
                }
            });
            let ct_material_asignado = $('#ct_material_asignado');
            let tb_material_asignado = $('#tb_material_asignado');
            let select_tecnico = $('#tecnico');
            let select_materiales = $('#materiales');

            $('#buscar_tecnico').on('click', function () {
                const id = select_tecnico.val();
                if (id) {
                    $.ajax({
                        type: 'GET',
                        url: `${__url}/soporte/inventario/tecnicos/index?id=${id}`,
                        contentType: 'application/json',
                        success: function (data) {
                            console.log(data);
                            if (data.success) {
                                ct_material_asignado.removeClass('d-none');
                                CS_materiales.llenar();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            const datae = jqXHR.responseJSON;
                            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
                        }
                    });
                } else {
                    ct_material_asignado.addClass('d-none');
                    tb_material_asignado.find('body').html('');
                }
            });

            select_tecnico.on('change', function () {
                const id = $(this).val();
                if (!id) {
                    ct_material_asignado.addClass('d-none');
                    tb_material_asignado.find('body').html('');
                }
            });

            $('#agregar_material').on('click', function () {
                const idm = select_materiales.val();
                const producto = materiales.find(m => m.id == idm);
                tb_material_asignado.find('tbody').append(
                    $('<tr>', { id: 'producto-' + idm }).append(
                        $('<td>').text(producto.producto),
                        $('<td>', { class: 'text-center' }).append(
                            $('<div>', { class: 'd-flex justify-content-center' }).append(
                                $('<input>', { class: 'form-control', type: 'number', style: 'width: 100px' }),
                                $('<button>', { class: 'btn btn-dark btn-sm' }).text('Guardar')
                            )
                        )
                    )
                );
            });
        });

        const CS_materiales = new CSelect(['#materiales'], {
            dataSet: materiales,
            filterField: 'id',
            optionText: 'producto'
        });
    </script>
    <!-- <script src="{{secure_asset('front/js/soporte/usuario/usuarios.js')}}?v={{ time() }}"></script> -->
@endsection