let contenedor_registros_visitas = $('#contenedor_registros_visitas');
let listado_visitas;
let getUrlListarVisitasAsignadas = () => generateUrl(`${__url}/soporte/buzon-personal/visitas/asignadas/index`, {});
let dataSetVisitasAsignadas = (json) => {
    $('#count_vis').html(json.count_vis ? json.count_vis : "");
    return json.data.map(item => {
        let sucursal = sucursales[item.id_sucursal];
        let nombre_empresa = `${empresas[sucursal.ruc].ruc} - ${empresas[sucursal.ruc].razon_social}`;
        let nombre_sucursal = sucursal.nombre;
        item.empresa = nombre_empresa;
        item.sucursal = nombre_sucursal;
        return item;
    });
}


let acciones_visitas_asignadas = $('<div>').append(
    $('<button>', {
        class: 'btn btn-primary',
        onclick: 'updateTableVis()',
        'data-mdb-ripple-init': '',
        role: 'button'
    }).append($('<i>', { class: 'fas fa-rotate-right' })),
);

let cabecera_visitas_asignadas = $('<div>', { class: 'col-12 d-flex align-items-center justify-content-between my-3' })
    .append(
        $('<h6>', { class: 'card-title text-primary mb-0' }).append($('<strong>').text('Visitas Asignadas')),
        acciones_visitas_asignadas
    );

if (esCelular()) {
    contenedor_registros_visitas.append(cabecera_visitas_asignadas, $('<div>', { id: 'listado_visitas' }));

    listado_visitas = new CardTable('listado_visitas', {
        ajax: {
            url: getUrlListarVisitasAsignadas(),
            dataSrc: dataSetVisitasAsignadas,
            error: function (xhr, error, thrown) {
                boxAlert.table(updateTableVis);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'estado', title: 'Estado' },
            { data: 'registrado', title: 'Registrado' },
            { data: 'empresa', title: 'Empresa' },
            { data: 'sucursal', title: 'Sucursal' },
            { data: 'asignado', title: 'Asignado' },
            { data: 'programado', title: 'Programado' }
        ],
        cardTemplate: (data, index) => {
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
                        $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 3.25vw;' }).html(data.empresa),
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 2.5vw;' }).text(data.sucursal),
                    ),
                ),
                $('<div>', { class: 'my-2 d-flex align-items-center justify-content-between text-muted', style: 'font-size: 2.5vw;' }).append(
                    $('<div>').append(
                        '<i class="fas fa-user-clock me-2"></i>' + data.asignado
                    ),
                    $('<div>').append(
                        '<i class="fas fa-calendar me-2"></i>' + data.programado
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
} else {
    contenedor_registros_visitas.append($('<div>', { class: 'card' })
        .append($('<div>', { class: 'card-body' })
            .append(
                cabecera_visitas_asignadas,
                $('<div>', { class: 'row' })
                    .append($('<div>', { class: 'col-12' })
                        .append(
                            $('<table>', { class: 'table table-hover text-nowrap', id: 'listado_visitas', style: 'width: 100%' })
                                .append($('<thead>', { class: 'text-center' })
                                    .append($('<tr>')
                                        .append($('<th>').text('Estado'))
                                        .append($('<th>').text('Registrado'))
                                        .append($('<th>').text('Empresa'))
                                        .append($('<th>').text('Sucursal'))
                                        .append($('<th>').text('Asignado'))
                                        .append($('<th>').text('Programado'))
                                        .append($('<th>').text('Acciones'))
                                    )
                                )
                                .append($('<tbody>'))
                        )
                    )
            )
        )
    );

    listado_visitas = new DataTable('#listado_visitas', {
        autoWidth: true,
        scrollX: true,
        scrollY: 400,
        ajax: {
            url: getUrlListarVisitasAsignadas(),
            dataSrc: dataSetVisitasAsignadas,
            error: function (xhr, error, thrown) {
                boxAlert.table(updateTableVis);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'estado' },
            { data: 'registrado' },
            { data: 'empresa' },
            { data: 'sucursal' },
            { data: 'asignado' },
            { data: 'programado' },
            { data: 'acciones' }
        ],
        order: [[1, 'desc']],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(2), td:eq(3)').addClass('text-start');
            $(row).find('td:eq(6)').addClass(`td-acciones`);
        },
        processing: true
    });
    mostrar_acciones(listado_visitas);
}

function updateTableVis() {
    if (esCelular()) {
        return listado_visitas.reload();
    }
    listado_visitas.ajax.reload();
}