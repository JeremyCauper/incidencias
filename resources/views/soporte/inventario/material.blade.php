@extends('layout.app')
@section('title', 'Materiales')

@section('cabecera')
    <!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/usuario/usuarios.css')}}?v={{ time() }}"> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                scrollY: 400,
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
                    <button id="guardar_asignacion" type="button" class="btn btn-primary" disabled="true"
                        data-mdb-ripple-init>Guardar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ secure_asset('front/js/app/SelectManeger.js') }}"></script>
    <script>
        $(document).ready(function () {
            formatSelect('modal_asignar');

            let ct_material_asignado = $('#ct_material_asignado');
            let tb_material_asignado = $('#tb_material_asignado');
            let select_tecnico = $('#tecnico');
            let select_materiales = $('#materiales');

            $('#buscar_tecnico').on('click', function () {
                const id = select_tecnico.val();
                if (!id) {
                    ct_material_asignado.addClass('d-none');
                    tb_material_asignado.find('tbody').html('');
                    return;
                }

                $.ajax({
                    type: 'GET',
                    url: `${__url}/soporte/inventario/tecnicos/index?id=${id}`,
                    success: function (data) {
                        if (data.success) {
                            ct_material_asignado.removeClass('d-none');
                            CS_materiales.llenar(materiales); // rellenamos el select de materiales disponibles

                            tb_material_asignado.find('tbody').html('');
                            data.data.forEach(e => {
                                // Removemos del select si ya lo tiene
                                select_materiales.find(`option[value="${e.id_material}"]`).remove();

                                // Insertamos en la tabla como ya asignado
                                llenarTabla({
                                    id: e.id_material,
                                    min: 0,
                                    value: e.cantidad,
                                    delet: true
                                });
                            });
                        }
                    },
                    error: function (jqXHR) {
                        const res = jqXHR.responseJSON;
                        boxAlert.box({ i: res.icon, t: res.title, h: res.message });
                    }
                });
            });

            select_tecnico.on('change', function () {
                if (!$(this).val()) {
                    ct_material_asignado.addClass('d-none');
                    tb_material_asignado.find('tbody').html('');
                }
            });

            $('#agregar_material').on('click', function () {
                const idm = select_materiales.val();
                if (!idm) return;

                if ($(`#producto-${idm}`).length) {
                    return boxAlert.box({ i: 'info', t: 'Material ya agregado', h: 'Este material ya está en la lista' });
                }
                llenarTabla({ id: idm })

                // ✅ Eliminar del select
                select_materiales.find(`option[value="${idm}"]`).remove();
                select_materiales.val('').trigger('change'); // limpia el selector
            });

            tb_material_asignado.on('click', '.eliminar-item', async function () {
                if (!await boxAlert.confirm({ h: `Esta apunto de eliminar el material asignado.` })) return true;
                const row = $(this).closest('tr');
                const id_material = row.attr('id').replace('producto-', '');
                const cantidadAnterior = parseInt(row.attr('data-anterior'));
                const id_usuario = select_tecnico.val();

                $.ajax({
                    type: 'POST',
                    url: `${__url}/soporte/inventario/tecnicos/asignar`,
                    data: {
                        id_usuario: id_usuario,
                        id_material: id_material,
                        cantidad: 0, // Esto indica eliminación
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        boxAlert.box({ i: 'success', t: 'Eliminado', h: res.success });
                        row.remove(); // ✅ 1. Eliminar la fila

                        const producto = materiales.find(m => m.id == id_material); // ✅ 2. Agregar nuevamente al select
                        if (producto) {
                            select_materiales.append(
                                $('<option>', {
                                    value: producto.id,
                                    text: producto.producto
                                })
                            );
                            // Reordenar y refrescar si usas select2 u otro plugin
                            select_materiales.trigger('change');
                        }
                        updateTable();
                    },
                    error: function (xhr) {
                        const res = xhr.responseJSON;
                        boxAlert.box({ i: 'error', t: 'Error', h: res?.error || 'Problema al eliminar' });
                    }
                });
            });

            tb_material_asignado.on('click', '.guardar-item', function () {
                const row = $(this).closest('tr');
                const id_material = row.attr('id').replace('producto-', '');
                const cantidad = parseInt(row.find('input[type="number"]').val());
                const cantidadAnterior = parseInt(row.attr('data-anterior'));
                const id_usuario = select_tecnico.val();

                if (!cantidad || cantidad <= 0) {
                    return boxAlert.box({ i: 'warning', t: 'Cantidad inválida', h: 'Ingresa una cantidad válida' });
                }

                // Diferencia real que se va a descontar o devolver
                const diferencia = cantidad - cantidadAnterior;

                $.ajax({
                    type: 'POST',
                    url: `${__url}/soporte/inventario/tecnicos/asignar`,
                    data: {
                        id_usuario: id_usuario,
                        id_material: id_material,
                        cantidad: cantidad,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        boxAlert.box({ i: 'success', t: 'Asignado', h: res.success });
                        row.attr('data-anterior', cantidad);
                        updateTable();
                    },
                    error: function (xhr) {
                        const res = xhr.responseJSON;
                        boxAlert.box({ i: 'error', t: 'Error', h: res?.error || 'Problema al asignar' });
                    }
                });
            });

            $('#guardar_asignacion').on('click', function () {
                const id_usuario = select_tecnico.val();
                const rows = tb_material_asignado.find('tbody tr:not(:has(:disabled))');

                if (!id_usuario) {
                    return boxAlert.box({ i: 'warning', t: 'Selecciona un técnico', h: 'No se seleccionó ningún técnico.' });
                }
                if (rows.length === 0) {
                    return boxAlert.box({ i: 'warning', t: 'No hay materiales', h: 'Agrega al menos un material.' });
                }

                rows.each(function () {
                    $(this).find('.guardar-item').click();
                });

                $('#modal_asignar').modal('hide');
            });

            function llenarTabla({ id, min = 1, value = 1, delet = true } = {}) {
                const producto = materiales.find(m => m.id == id);
                if (!producto) return;

                tb_material_asignado.find('tbody').append(
                    $('<tr>', { id: 'producto-' + id, 'data-anterior': value }).append(
                        $('<td>').text(producto.producto),
                        $('<td>', { class: 'text-center' }).append(
                            $('<div>', { class: 'd-flex justify-content-center' }).append(
                                $('<input>', {
                                    class: 'form-control',
                                    type: 'number',
                                    style: 'width: 100px',
                                    min: min,
                                    value: value
                                }),
                                $('<button>', {
                                    class: 'btn btn-dark btn-sm ms-1 px-2 guardar-item'
                                }).html('<i class="fas fa-floppy-disk"></i>'),
                                delet ? $('<button>', {
                                    class: 'btn btn-danger btn-sm ms-1 px-2 eliminar-item',
                                    'data-id': id
                                }).html('<i class="fas fa-trash-can"></i>') : null
                            )
                        )
                    )
                );
            }
        });

        function updateTable() {
            tb_material.ajax.reload();
        }

        const CS_materiales = new CSelect(['#materiales'], {
            dataSet: materiales,
            filterField: 'id',
            optionText: 'producto'
        });
    </script>
@endsection