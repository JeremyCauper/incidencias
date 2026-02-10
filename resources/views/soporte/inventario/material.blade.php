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
                        <i class="far fa-square-plus me-2"></i>
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
                                <tr class="text-center">
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="form-asignar">
                <div class="modal-header  bg-primary text-white">
                    <h6 class="modal-title" id="modal_asignarLabel">Asignar Material</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-9">
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
                        <div class="col-md-9 mt-4 mb-2">
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
                                        <tr class="text-center">
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
                    <button id="guardar_asignacion" type="button" class="btn btn-primary d-none" disabled="true"
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
            let ct_material_asignado = $('#ct_material_asignado');
            let tb_material_asignado = $('#tb_material_asignado');
            let select_tecnico = $('#tecnico');
            let select_materiales = $('#materiales');
            let btnGuardar = $('#guardar_asignacion');
            let xhr_tecnico = null; // 🔁 Variable para guardar la petición actual
            let xhr_asignar = null;

            $('.modal').on('hidden.bs.modal', function () {
                ct_material_asignado.addClass('d-none');
                tb_material_asignado.find('tbody').html('');
                select_tecnico.val('').trigger('change');
                btnGuardar.html('Guardar').prop('disabled', true).addClass('d-none');
                if (xhr_asignar && xhr_asignar.readyState !== 4) {
                    xhr_asignar.abort();
                }
            });

            select_tecnico.on('change', function () {
                if (xhr_tecnico && xhr_tecnico.readyState !== 4) {
                    xhr_tecnico.abort();
                }
                $('#buscar_tecnico').click();
            });

            $('#buscar_tecnico').on('click', function () {
                const _this = $(this);
                const id = $('#tecnico').val();

                ct_material_asignado.addClass('d-none');
                tb_material_asignado.find('tbody').html('');
                if (!id) return false;

                if (xhr_tecnico && xhr_tecnico.readyState !== 4) {
                    xhr_tecnico.abort();
                }

                _this.html('<span class="spinner-border" role="status" style="width: 1.4rem; height: 1.4rem;"><span class="visually-hidden"></span></span>');

                xhr_tecnico = $.ajax({
                    type: 'GET',
                    url: `${__url}/soporte/inventario/tecnicos/index?id=${id}`,
                    success: function (data) {
                        _this.html('<i class="fas fa-magnifying-glass"></i>');
                        if (data.success) {
                            ct_material_asignado.removeClass('d-none');
                            tb_material_asignado.find('tbody').html('');
                            CS_materiales.llenar(materiales);
                            data.data.forEach(e => {
                                select_materiales.find(`option[value="${e.id_material}"]`).attr({ 'data-hidden': true, 'data-nosearch': true });
                                llenarTabla({
                                    id: e.id_material,
                                    min: 0,
                                    value: e.cantidad,
                                    delet: true
                                });
                            });
                            btnGuardar.removeClass('d-none').prop('disabled', false);
                        }
                    },
                    error: function (xhr, status) {
                        _this.html('<i class="fas fa-magnifying-glass"></i>');
                        if (status === 'abort') {
                            console.log('Petición abortada');
                            return;
                        }
                        const res = xhr.responseJSON;
                        boxAlert.box({ i: res?.icon || 'error', t: res?.title || 'Error', h: res?.message || 'Error al obtener datos' });
                    }
                });
            });

            $('#agregar_material').on('click', function () {
                const idm = select_materiales.val();
                if (!idm) return;
                if ($(`#producto-${idm}`).length) {
                    return boxAlert.box({ i: 'info', t: 'Material ya agregado', h: 'Este material ya está en la lista' });
                }
                llenarTabla({ id: idm, nuevo: true });

                select_materiales.val('').trigger('change')
                    .find(`option[value="${idm}"]`).attr({ 'data-hidden': true, 'data-nosearch': true });
                btnGuardar.removeClass('d-none').prop('disabled', false);
            });

            tb_material_asignado.on('click', '.eliminar-item', async function () {
                const row = $(this).closest('tr');
                const id_material = row.attr('id').replace('producto-', '');
                const id_usuario = select_tecnico.val();
                const btn = $(this);

                if (!eval(row.attr('data-nuevo'))) {
                    if (!await boxAlert.confirm({ h: `Esta apunto de eliminar el material asignado.` })) return true;

                    btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);

                    xhr_asignar = $.ajax({
                        type: 'POST',
                        url: `${__url}/soporte/inventario/tecnicos/asignar`,
                        data: {
                            id_usuario: id_usuario,
                            id_material: id_material,
                            cantidad: 0,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            boxAlert.box({ i: 'success', t: 'Eliminado', h: res.success });
                            row.remove();
                            updateTable();
                            CS_materiales.llenar(materiales);
                            tb_material_asignado.find('tbody').find('tr').each((i, e) => {
                                const id = $(e).attr('id').replace('producto-', '');
                                select_materiales.find(`option[value="${id}"]`).attr({ 'data-hidden': true, 'data-nosearch': true });
                            });
                        },
                        error: function (xhr) {
                            const res = xhr.responseJSON;
                            boxAlert.box({ i: 'error', t: 'Error', h: res?.error || 'Problema al eliminar' });
                            if (status === 'abort') {
                                console.log('Petición abortada');
                                return;
                            }
                        }
                    });
                } else {
                    row.remove();
                    CS_materiales.llenar(materiales);
                    tb_material_asignado.find('tbody').find('tr').each((i, e) => {
                        const id = $(e).attr('id').replace('producto-', '');
                        select_materiales.find(`option[value="${id}"]`).attr({ 'data-hidden': true, 'data-nosearch': true });
                    });
                }
            });

            tb_material_asignado.on('click', '.guardar-item', function () {
                const row = $(this).closest('tr');
                const btn = $(this);
                const id_material = row.attr('id').replace('producto-', '');
                const cantidad = parseInt(row.find('input.input-asignado').val());
                const cantidadAnterior = parseInt(row.attr('data-anterior'));
                const id_usuario = select_tecnico.val();

                if (!cantidad || cantidad <= 0) {
                    return boxAlert.box({ i: 'warning', t: 'Cantidad inválida', h: 'Ingresa una cantidad válida' });
                }
                const diferencia = cantidad - cantidadAnterior;

                btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);

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
                        row.find('.guardar-item').addClass('d-none');
                        row.find('.input-cantidad').addClass('d-none');
                        if (eval(row.attr('data-nuevo'))) {
                            row.find('label[data-badge="true"]').remove();
                            row.attr('data-nuevo', false);
                        }
                        updateTable();
                        btn.html('<i class="fas fa-floppy-disk"></i>').prop('disabled', false);
                    },
                    error: function (xhr) {
                        btn.html('<i class="fas fa-floppy-disk"></i>').prop('disabled', false);
                        const res = xhr.responseJSON;
                        boxAlert.box({ i: 'error', t: 'Error', h: res?.error || 'Problema al asignar' });
                    }
                });
            });

            tb_material_asignado.on('input', 'input.input-asignado', function () {
                const input = $(this);
                const row = input.closest('tr');
                const guardarBtn = row.find('.guardar-item');
                const asignadoInput = row.find('.input-asignado');
                const cantidadInput = row.find('.input-cantidad');

                const valorInicial = parseInt(input.attr('data-inicial'));
                const valorActual = parseInt(input.val());

                if (valorInicial !== valorActual) {
                    guardarBtn.removeClass('d-none');
                    cantidadInput.removeClass('d-none');
                    cantidadInput.val(asignadoInput.attr('max') - (valorActual - valorInicial));
                } else {
                    guardarBtn.addClass('d-none');
                    cantidadInput.addClass('d-none');
                }
            });

            btnGuardar.on('click', function () {
                const id_usuario = select_tecnico.val();
                const rows = tb_material_asignado.find('tbody tr:not(:has(:disabled))');

                if (!id_usuario) {
                    return boxAlert.box({ i: 'warning', t: 'Selecciona un técnico', h: 'No se seleccionó ningún técnico.' });
                }

                if (rows.length === 0) {
                    return boxAlert.box({ i: 'warning', t: 'No hay materiales', h: 'Agrega al menos un material.' });
                }

                btnGuardar.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...').prop('disabled', true);

                rows.each(function () {
                    $(this).find('.guardar-item').click();
                });

                setTimeout(() => {
                    $('#modal_asignar').modal('hide');
                    btnGuardar.html('Guardar').prop('disabled', false);
                }, 1000);
            });

            function llenarTabla({ id, min = 1, value = 1, delet = true, nuevo = false } = {}) {
                const producto = materiales.find(m => m.id == id);
                if (!producto) return;

                tb_material_asignado.find('tbody').append(
                    $('<tr>', { id: 'producto-' + id, 'data-anterior': value, 'data-nuevo': nuevo }).append(
                        $('<td>').append(producto.producto, nuevo ? '<label class="badge badge-info ms-2" data-badge="true" style="font-size: small;">Nuevo</label>' : ''),
                        $('<td>', { style: 'width: 280px;' }).append(
                            $('<div>', { class: 'd-flex', style: 'width: 280px;' }).append(
                                $('<input>', {
                                    class: 'form-control input-asignado',
                                    type: 'number',
                                    style: 'width: 100px',
                                    min: min,
                                    max: producto.cantidad,
                                    'data-inicial': value,
                                    value: value
                                }),
                                $('<input>', {
                                    class: 'form-control input-cantidad d-none',
                                    type: 'text',
                                    style: 'width: 80px',
                                    disabled: true,
                                    value: producto.cantidad
                                }),
                                $('<button>', {
                                    class: 'btn btn-dark btn-sm ms-1 px-2 guardar-item d-none'
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