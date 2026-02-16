let contenedor_registros = $('#contenedor_registros');
let listado_vterminadas;
let getUrlListar = () => generateUrl(`${__url}/soporte/visitas/terminadas/index`, {
    sucursal: $('#sucursal').val(),
    fechaIni: $('#dateRango').val().split('  al  ')[0],
    fechaFin: $('#dateRango').val().split('  al  ')[1]
});
let dataSet = (json) => {
    return json;
}

let acciones = $('<div>').append(
    $('<button>', {
        class: 'btn btn-primary',
        onclick: 'updateTable()',
        'data-mdb-ripple-init': '',
        role: 'button'
    }).append($('<i>', { class: 'fas fa-rotate-right' })),
);

if (esCelular()) {
    contenedor_registros.append($('<div>', { id: 'listado_vterminadas' }));

    listado_vterminadas = new CardTable('listado_vterminadas', {
        ajax: {
            url: getUrlListar(),
            dataSrc: dataSet,
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'cod_ordenv', title: 'Código' },
            { data: 'fecha', title: 'Fecha' },
            { data: 'tecnicos', title: 'Técnicos' },
            { data: 'id_sucursal', title: 'Sucursal' },
            { data: 'horaIni', title: 'Hora Inicio' },
            { data: 'horaFin', title: 'Visitas Realizadas' }
        ],
        cardTemplate: (data, index) => {
            let sucursal = sucursales[data.id_sucursal];
            return $('<div>').append(
                $('<div>', { class: 'd-flex align-items-center justify-content-between pb-1' }).append(
                    $('<div>', { class: 'fw-medium mb-0', style: 'overflow: hidden;font-size: 3.2vw;' }).append(data.cod_ordenv),
                    $('<div>', { class: 'btn-acciones-movil' }).append(data.acciones)
                ),
                $('<div>', { class: 'mt-2 mb-3 d-flex align-items-center gap-2' }).append(
                    $('<div>', { class: 'p-3 rounded-7', style: 'background-color: rgb(33 81 159 / 15%)' }).append(
                        '<i class="fas fa-store text-primary"></i>'
                    ),
                    $('<div>').append(
                        $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 3.25vw;' }).html(`${empresas[sucursal.ruc].ruc} - ${empresas[sucursal.ruc].razon_social}`),
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 2.5vw;' }).text(sucursal.nombre),
                    ),
                ),
                $('<div>', { class: 'my-3 d-flex align-items-center justify-content-between text-muted', style: 'font-size: 2.5vw;' }).append(
                    $('<div>').append(
                        '<i class="fas fa-user me-2" style="font-size: 2.5vw;"></i>' + data.tecnicos
                    ),
                    $('<div>').append(
                        '<i class="fas fa-calendar me-2" style="font-size: 2.5vw;"></i>' + data.fecha
                    ),
                ),
                $('<div>', { class: 'my-2 text-muted', style: 'font-size: 2.5vw;' }).append(
                    $('<span>', { class: 'me-2' }).append(
                        '<i class="fas fa-clock me-2" style="font-size: 2.5vw;"></i>' + data.horaIni + ' - ' + data.horaFin
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

    $('.botones-accion').append(acciones);
} else {
    contenedor_registros.append($('<div>', { class: 'card' })
        .append($('<div>', { class: 'card-body' })
            .append(
                $('<h6>', { class: 'card-title col-form-label-sm text-primary mb-3' }).append($('<strong>').text('Visitas Terminadas')),
                acciones,
                $('<div>', { class: 'row' })
                    .append($('<div>', { class: 'col-12' })
                        .append(
                            $('<table>', { class: 'table table-hover text-nowrap', id: 'listado_vterminadas', style: 'width: 100%' })
                                .append($('<thead>', { class: 'text-center' })
                                    .append($('<tr>')
                                        .append($('<th>').text('#'))
                                        .append($('<th>').text('N° Orden'))
                                        .append($('<th>').text('Fecha Servicio'))
                                        .append($('<th>').text('Tecnico'))
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

    listado_vterminadas = new DataTable('#listado_vterminadas', {
        autoWidth: true,
        scrollX: true,
        scrollY: 400,
        fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
        ajax: {
            url: getUrlListar(),
            dataSrc: dataSet,
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'id' },
            { data: 'cod_ordenv' },
            { data: 'fecha' },
            { data: 'tecnicos' },
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
            { data: 'horaIni' },
            { data: 'horaFin' },
            { data: 'acciones' }
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(0), td:eq(2), td:eq(8)').addClass('text-center');
            $(row).find('td:eq(8)').addClass(`td-acciones`);
        },
        processing: true
    });
    mostrar_acciones(listado_vterminadas);
}

function updateTable() {
    if (esCelular()) {
        return listado_vterminadas.reload();
    }
    listado_vterminadas.ajax.reload();
}

function filtroBusqueda() {
    const nuevoUrl = getUrlListar();
    listado_vterminadas.ajax.url(nuevoUrl).load();
}