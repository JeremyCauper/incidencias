let contenedor_registros_programadas = $('#contenedor_registros_programadas');
let listado_vprogramadas;
let getUrlListarProgramados = () => generateUrl(`${__url}/soporte/visitas/programadas/index`, {});
let dataSetProgramados = (json) => {
    $.each(json.conteo, function (panel, count) {
        $(`b[data-panel="${panel}"]`).html(count);
    });
    return json.data;
}


let acciones_programadas = $('<div>').append(
    $('<button>', {
        class: 'btn btn-primary',
        onclick: 'updateTableVProgramadas()',
        'data-mdb-ripple-init': '',
        role: 'button'
    }).append($('<i>', { class: 'fas fa-rotate-right' })),
);

if (esCelular()) {
    contenedor_registros_programadas.append($('<div>', { id: 'listado_vprogramadas' }));

    listado_vprogramadas = new CardTable('listado_vprogramadas', {
        ajax: {
            url: getUrlListarProgramados(),
            dataSrc: dataSetProgramados,
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'estado', title: 'Estado' },
            { data: 'sucursal', title: 'Sucursal' },
            { data: 'tecnicos', title: 'Técnico' },
            { data: 'fecha', title: 'Fecha Visita' }
        ],
        cardTemplate: (data, index) => {
            let sucursal = sucursales[data.sucursal];
            return $('<div>').append(
                $('<div>', { class: 'd-flex align-items-center justify-content-between pb-1' }).append(
                    $('<div>', { class: 'fw-medium mb-0', style: 'overflow: hidden;font-size: 3.2vw;' }).append(data.estado),
                    $('<div>', { class: 'btn-acciones-movil' }).append(data.acciones)
                ),
                $('<div>', { class: 'mt-2 mb-3 d-flex align-items-center gap-2' }).append(
                    $('<div>', { class: 'p-3 rounded-7', style: 'background-color: rgb(228 161 27 / 15%)' }).append(
                        '<i class="fas fa-store text-warning"></i>'
                    ),
                    $('<div>').append(
                        $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 3.25vw;' }).html(sucursal.ruc),
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 2.5vw;' }).text(sucursal.nombre),
                    ),
                ),
                $('<div>', { class: 'my-2 d-flex align-items-center justify-content-between text-muted', style: 'font-size: 2.5vw;' }).append(
                    $('<div>').append(
                        '<i class="fas fa-user me-2"></i>' + data.tecnicos
                    ),
                    $('<div>').append(
                        '<i class="fas fa-calendar me-2"></i>' + data.fecha
                    ),
                ),
            ).get(0).outerHTML;
        },
        scrollY: '600px',
        perPage: 100,
        searchPlaceholder: 'Buscar por ruc o sucursal...',
        order: ['personal', 'asc'],
        drawCallback: function () {
            if (typeof mdb !== 'undefined') {
                document.querySelectorAll('[data-mdb-dropdown-init]').forEach(el => {
                    new mdb.Dropdown(el);
                });
            }
        }
    });

    $('#listado_vprogramadas .botones-accion').append(acciones_programadas);
} else {
    contenedor_registros_programadas.append($('<div>', { class: 'card' })
        .append($('<div>', { class: 'card-body' })
            .append(
                $('<h6>', { class: 'card-title col-form-label-sm text-primary mb-3' }).append($('<strong>').text('Visitas a Programar')),
                acciones_programadas,
                $('<div>', { class: 'row' })
                    .append($('<div>', { class: 'col-12' })
                        .append(
                            $('<table>', { class: 'table table-hover text-nowrap', id: 'listado_vprogramadas', style: 'width: 100%' })
                                .append($('<thead>', { class: 'text-center' })
                                    .append($('<tr>')
                                        .append($('<th>').text('Estado'))
                                        .append($('<th>').text('Sucursal'))
                                        .append($('<th>').text('Técnico'))
                                        .append($('<th>').text('Fecha Visita'))
                                        .append($('<th>').text('Acciones'))
                                    )
                                )
                                .append($('<tbody>'))
                        )
                    )
            )
        )
    );

    listado_vprogramadas = new DataTable('#listado_vprogramadas', {
        autoWidth: true,
        scrollX: true,
        scrollY: 400,
        fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
        ajax: {
            url: getUrlListarProgramados(),
            dataSrc: dataSetProgramados,
            error: function (xhr, error, thrown) {
                boxAlert.table(updateTableVProgramadas);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'estado' },
            {
                data: 'sucursal', render: function (data, type, row) {
                    let sucursal = sucursales[data];
                    return `${sucursal.ruc} - ${sucursal.nombre}`;
                }
            },
            { data: 'tecnicos' },
            { data: 'fecha' },
            { data: 'acciones' }
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(0), td:eq(3), td:eq(4)').addClass('text-center');
            $(row).find('td:eq(4)').addClass(`td-acciones`);
        },
        processing: true
    });
    mostrar_acciones(listado_vprogramadas);
}

function updateTableVProgramadas() {
    if (esCelular()) {
        return listado_vprogramadas.reload();
    }
    listado_vprogramadas.ajax.reload();
}