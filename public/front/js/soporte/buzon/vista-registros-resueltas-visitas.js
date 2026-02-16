let contenedor_registros_visitas = $('#contenedor_registros_visitas');
let listado_orden_visitas;
let getUrlListarVisitasResueltas = () => generateUrl(`${__url}/soporte/buzon-personal/visitas/resueltas/index`, {
    ruc: $('#ruc').val(),
    sucursal: $('#sucursal').val(),
    fechaIni: $('#dateRango').val().split('  al  ')[0],
    fechaFin: $('#dateRango').val().split('  al  ')[1]
});
let dataSetVisitasResueltas = (json) => {
    return json.map((item) => {
        let sucursal = sucursales[item.id_sucursal];
        let nombre_empresa = `${empresas[sucursal.ruc].ruc} - ${empresas[sucursal.ruc].razon_social}`;
        item.nombre_empresa = nombre_empresa;
        item.nombre_sucursal = sucursal.nombre;
        return item;
    });
}

let accionesVisitasResueltas = $('<div>').append(
    $('<button>', {
        class: 'btn btn-primary',
        onclick: 'updateTableVis()',
        'data-mdb-ripple-init': '',
        role: 'button'
    }).append($('<i>', { class: 'fas fa-rotate-right' })),
);

if (esCelular()) {
    contenedor_registros_visitas.append($('<div>', { id: 'listado_orden_visitas' }));

    listado_orden_visitas = new CardTable('listado_orden_visitas', {
        ajax: {
            url: getUrlListarVisitasResueltas(),
            dataSrc: dataSetVisitasResueltas,
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'cod_orden', title: 'Código' },
            { data: 'fecha_vis', title: 'Fecha' },
            { data: 'nombre_sucursal', title: 'Sucursal' },
            { data: 'nombre_empresa', title: 'Empresa' },
            { data: 'iniciado', title: 'Hora Inicio' },
            { data: 'finalizado', title: 'Visitas Realizadas' }
        ],
        cardTemplate: (data, index) => {
            let sucursal = sucursales[data.id_sucursal];
            return $('<div>').append(
                $('<div>', { class: 'd-flex align-items-center justify-content-between pb-1' }).append(
                    $('<div>', { class: 'fw-medium mb-0' }).append(
                        `<span class="badge rounded-pill me-1" style="background-color: rgb(90 139 219 / 10%);color: rgb(90 139 219);font-size: 0.75rem;" aria-item="codigo">${data.cod_orden}</span>`
                    ),
                    $('<div>', { class: 'btn-acciones-movil' }).append(data.acciones)
                ),
                $('<div>', { class: 'mt-2 mb-3 d-flex align-items-center gap-2' }).append(
                    $('<div>', { class: 'p-3 rounded-7', style: 'background-color: rgb(33 81 159 / 15%)' }).append(
                        '<i class="fas fa-store text-primary"></i>'
                    ),
                    $('<div>').append(
                        $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 3.25vw;' }).html(data.nombre_empresa),
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 2.5vw;' }).text(data.nombre_sucursal),
                    ),
                ),
                $('<div>', { class: 'my-3 text-muted', style: 'font-size: 2.5vw;' }).append(
                    '<i class="fas fa-calendar me-2" style="font-size: 2.5vw;"></i>' + data.fecha_vis
                ),
                $('<div>', { class: 'my-2 text-muted', style: 'font-size: 2.5vw;' }).append(
                    $('<span>', { class: 'me-2' }).append(
                        '<i class="fas fa-clock me-2" style="font-size: 2.5vw;"></i>' + data.iniciado + ' - ' + data.finalizado
                    ),
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

    $('#listado_orden_visitas .botones-accion').append(accionesVisitasResueltas);
} else {
    contenedor_registros_visitas.append($('<div>', { class: 'card' })
        .append($('<div>', { class: 'card-body' })
            .append(
                $('<h6>', { class: 'card-title col-form-label-sm text-primary mb-3' }).append($('<strong>').text('Visitas Terminadas')),
                accionesVisitasResueltas,
                $('<div>', { class: 'row' })
                    .append($('<div>', { class: 'col-12' })
                        .append(
                            $('<table>', { class: 'table table-hover text-nowrap', id: 'listado_orden_visitas', style: 'width: 100%' })
                                .append($('<thead>', { class: 'text-center' })
                                    .append($('<tr>')
                                        .append($('<th>').text('N° Orden'))
                                        .append($('<th>').text('Fecha Visita'))
                                        .append($('<th>').text('Empresa'))
                                        .append($('<th>').text('Sucursal'))
                                        .append($('<th>').text('Iniciada'))
                                        .append($('<th>').text('Terminada'))
                                        .append($('<th>').text('Acciones'))
                                    )
                                )
                                .append($('<tbody>'))
                        )
                    )
            )
        )
    );

    listado_orden_visitas = new DataTable('#listado_orden_visitas', {
        autoWidth: true,
        scrollX: true,
        scrollY: 400,
        ajax: {
            url: getUrlListarVisitasResueltas(),
            dataSrc: dataSetVisitasResueltas,
            error: function (xhr, error, thrown) {
                boxAlert.table(updateTableVis);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'cod_orden' },
            { data: 'fecha_vis' },
            {
                data: 'id_sucursal', render: function (data, type, row) {
                    var ruc = sucursales[data].ruc;
                    return `${empresas[ruc].ruc} - ${empresas[ruc].razon_social}`;
                }
            },
            {
                data: 'id_sucursal', render: function (data, type, row) {
                    return sucursales[data].nombre;
                }
            },
            { data: 'iniciado' },
            { data: 'finalizado' },
            { data: 'acciones' }
        ],
        order: [[1, 'desc']],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(2), td:eq(3)').addClass('text-center');
            $(row).find('td:eq(6)').addClass(`td-acciones`);
        },
        processing: true
    });
    mostrar_acciones(listado_orden_visitas);
}

function updateTableVis() {
    if (esCelular()) {
        return listado_orden_visitas.reload();
    }
    listado_orden_visitas.ajax.reload();
}