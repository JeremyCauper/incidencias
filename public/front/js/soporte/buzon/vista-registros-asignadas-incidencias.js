let contenedor_registros_incidencias = $('#contenedor_registros_incidencias');
let listado_incidencia;
let base_url_incidencia = `${__url}/soporte/buzon-personal/incidencias/asignadas/index`;
let dataSet_incidencia = (json) => {
    if (json.count_asig) {
        $('#count_asig').removeClass('d-none').html(json.count_asig);
    } else {
        $('#count_asig').addClass('d-none');
    }
    return json.data.map(item => {
        let sucursal = sucursales[item.id_sucursal];
        let nombre_empresa = `${empresas[sucursal.ruc].ruc} - ${empresas[sucursal.ruc].razon_social}`;
        let nombre_sucursal = sucursal.nombre;
        item.empresa = nombre_empresa;
        item.sucursal = nombre_sucursal;
        return item;
    });
}

let acciones = $('<div>').append(
    $('<button>', {
        class: 'btn btn-primary',
        onclick: 'updateTableInc()',
        'data-mdb-ripple-init': '',
        role: 'button'
    }).append($('<i>', { class: 'fas fa-rotate-right' })),
);

if (esCelular()) {
    contenedor_registros_incidencias.append($('<div>', { id: 'listado_incidencia' }));

    listado_incidencia = new CardTable('listado_incidencia', {
        ajax: {
            url: base_url_incidencia,
            dataSrc: dataSet_incidencia,
            error: function (xhr, error, thrown) {
                boxAlert.table(updateTableInc);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'cod_inc', title: 'Codigo' },
            { data: 'estado', title: 'Estado' },
            { data: 'registrado', title: 'Registrado' },
            { data: 'iniciado', title: 'Iniciado' },
            { data: 'empresa', title: 'Empresa' },
            { data: 'sucursal', title: 'Sucursal' },
            { data: 'tipo_estacion', title: 'Estacion' },
            { data: 'tipo_incidencia', title: 'Nivel Incidencia' },
            { data: 'tipo_soporte', title: 'Soporte' },
            { data: 'problema', title: 'Problema' },
            { data: 'subproblema', title: 'Sub Problema' }
        ],
        cardTemplate: (data, index) => {
            let tipoIncidencia = tipo_incidencia[data.tipo_incidencia[data.tipo_incidencia.length - 1]];

            return $('<div>').append(
                $('<div>', { class: 'd-flex align-items-center justify-content-between pb-1' }).append(
                    $('<div>', { class: 'fw-medium mb-0', style: 'overflow: hidden;font-size: 3.25vw;' }).append(
                        $('<span>').html(`<span class="badge rounded-pill me-1" style="background-color: rgb(90 139 219 / 10%);color: rgb(90 139 219);font-size: 0.7rem;" aria-item="codigo">${data.cod_inc}</span>`)
                    ),
                    $('<div>', { class: 'btn-acciones-movil' }).append(data.acciones)
                ),
                $('<div>', { class: 'mb-3' }).append(
                    $('<div>', { style: 'font-size: 3.25vw;' }).html('<i class="fas fa-building me-1"></i>' + data.empresa),
                    $('<div>', { class: 'text-muted mt-2', style: 'font-size: 2.5vw;' }).html(data.sucursal),
                ),
                $('<div>', { class: 'mb-2 d-flex align-items-center justify-content-between' }).append(
                    $('<div>').append(
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 1.7vw;' }).text('Asignado'),
                        $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 2.2vw;' }).html('<i class="fas fa-user-clock me-2" style="font-size: 2.25vw;"></i>' + data.iniciado),
                    ),
                ),
                $('<div>', { class: 'mb-2 rounded-7 p-2', style: 'background-color: rgb(101 138 177 / 8%);' }).append(
                    $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 1.7vw;' }).text('Problema Reportado'),
                    $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 2.2vw;' }).text(obj_problem[data.problema].descripcion),
                    $('<p>', { class: 'text-muted fst-italic mb-0', style: 'font-size: 2.1vw;' }).html(getBadgePrioridad(obj_subproblem[data.subproblema].prioridad, .65) + obj_subproblem[data.subproblema].descripcion),
                ),
                $('<div>', { class: 'mb-2 d-flex align-items-center justify-content-between' }).append(
                    $('<div>').append(
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 1.7vw;' }).text('Estación'),
                        $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 2.2vw;' }).html(tipo_estacion[data.tipo_estacion].descripcion),
                    ),
                    $('<div>').append(
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 1.7vw;' }).text('Nivel Incidencia'),
                        $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 2.2vw;' }).html(
                            `<label class="badge badge-${tipoIncidencia.color} me-2" style="font-size: 0.75rem;">${tipoIncidencia.tipo}</label>${tipoIncidencia.descripcion}`
                        ),
                    ),
                    $('<div>').append(
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 1.7vw;' }).text('Soporte'),
                        $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 2.2vw;' }).html(tipo_soporte[data.tipo_soporte].descripcion),
                    ),
                ),
                $('<div>', { class: 'd-flex align-items-center justify-content-between pt-1', style: 'font-size: 2.85vw;color: #909090;' }).append(
                    `<span><i class="fas fa-clock me-2"></i>${data.registrado}</span>`,
                    data.estado
                )
            ).get(0).outerHTML;
        },
        scrollY: '600px',
        perPage: 100,
        searchPlaceholder: 'Buscar por nombre...',
        order: ['personal', 'asc'],
        drawCallback: function () {
            if (typeof mdb !== 'undefined') {
                document.querySelectorAll('[data-mdb-dropdown-init]').forEach(el => {
                    new mdb.Dropdown(el);
                });
            }
        }
    });

    $('#listado_incidencia .botones-accion').append(acciones);
} else {
    contenedor_registros_incidencias.append($('<div>', { class: 'card' })
        .append($('<div>', { class: 'card-body' })
            .append(
                $('<h6>', { class: 'card-title col-form-label-sm text-primary mb-3' }).append($('<strong>').text('Incidencias Registradas')),
                acciones,
                $('<div>', { class: 'row' })
                    .append($('<div>', { class: 'col-12' })
                        .append(
                            $('<table>', { class: 'table table-hover text-nowrap', id: 'listado_incidencia', style: 'width: 100%' })
                                .append($('<thead>', { class: 'text-center' })
                                    .append($('<tr>')
                                        .append($('<th>').text('Incidencia'))
                                        .append($('<th>').text('Estado'))
                                        .append($('<th>').text('Registrado'))
                                        .append($('<th>').text('Asignado'))
                                        .append($('<th>').text('Empresa'))
                                        .append($('<th>').text('Sucursal'))
                                        .append($('<th>').text('Estacion'))
                                        .append($('<th>').text('Nivel Incidencia'))
                                        .append($('<th>').text('Soporte'))
                                        .append($('<th>').text('Problema / Sub Problema'))
                                        .append($('<th>').text('Acciones'))
                                    )
                                )
                                .append($('<tbody>'))
                        )
                    )
            )
        )
    );

    listado_incidencia = new DataTable('#listado_incidencia', {
        autoWidth: true,
        scrollX: true,
        scrollY: 400,
        ajax: {
            url: base_url_incidencia,
            dataSrc: dataSet_incidencia,
            error: function (xhr, error, thrown) {
                boxAlert.table(updateTableInc);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'cod_inc' },
            { data: 'estado' },
            { data: 'registrado' },
            { data: 'iniciado' },
            { data: 'empresa' },
            { data: 'sucursal' },
            {
                data: 'tipo_estacion', render: function (data, type, row) {
                    return tipo_estacion[data].descripcion;
                }
            },
            {
                data: 'tipo_incidencia', render: function (data, type, row) {
                    let tipo = tipo_incidencia[data[data.length - 1]];
                    return `<label class="badge badge-${tipo.color} me-2" style="font-size: 0.75rem;">${tipo.tipo}</label>${tipo.descripcion}`;
                }
            },
            {
                data: 'tipo_soporte', render: function (data, type, row) {
                    return tipo_soporte[data].descripcion;
                }
            },
            {
                data: 'problema', render: function (data, type, row) {
                    return `${getBadgePrioridad(obj_subproblem[row.subproblema].prioridad, .75)} ${obj_problem[data].descripcion} / ${obj_subproblem[row.subproblema].descripcion}`;
                }
            },
            { data: 'acciones' }
        ],
        createdRow: function (row, data, dataIndex) {
            const row_bg = ['row-bg-warning', 'row-bg-info', 'row-bg-primary', '', 'row-bg-danger'];
            $(row).find('td:eq(0), td:eq(1), td:eq(2), td:eq(3), td:eq(6), td:eq(8), td:eq(10)').addClass('text-center');
            $(row).find('td:eq(10)').addClass('td-acciones');
            $(row).addClass(row_bg[data.estado_informe]);
        },
        order: [[2, 'desc']],
        processing: true,
    });
    mostrar_acciones(listado_incidencia);
}

function updateTableInc() {
    if (esCelular()) {
        return listado_incidencia.reload();
    }
    listado_incidencia.ajax.reload();
}