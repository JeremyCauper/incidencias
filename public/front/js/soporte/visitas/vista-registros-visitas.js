let contenedor_registros = $('#contenedor_registros');
let listado_visitas;
let getUrlListar = () => generateUrl(`${__url}/soporte/visitas/sucursales/index`, {});
let dataSet = (json) => {
    $.each(json.conteo, function (panel, count) {
        $(`b[data-panel="${panel}"]`).html(count);
    });
    return json.data;
}


let acciones_visitas = $('<div>').append(
    $('<button>', {
        class: 'btn btn-primary',
        onclick: 'updateTableVisitas()',
        'data-mdb-ripple-init': '',
        role: 'button'
    }).append($('<i>', { class: 'fas fa-rotate-right' })),
);

let cabecera_visitas = $('<div>', { class: 'col-12 d-flex align-items-center justify-content-between my-3' })
    .append(
        $('<h6>', { class: 'card-title text-primary mb-0' }).append($('<strong>').text('Visitas Registradas')),
        acciones_visitas
    );

let selector = $('<select>', { 'id': 'filtroEstado', class: 'select-clear-simple' }).html($('<option>', { value: '', text: 'Seleccione...' }));
Object.entries({
    0: 'Sin Asignar',
    1: 'Asignada',
    2: 'Completada'
}).forEach(([clave, valor]) => {
    selector.append($('<option>').val(clave).text(valor));
});

if (esCelular()) {
    contenedor_registros.append(cabecera_visitas, $('<div>', { id: 'listado_visitas' }));

    listado_visitas = new CardTable('listado_visitas', {
        ajax: {
            url: getUrlListar(),
            dataSrc: dataSet,
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'ruc', title: 'Ruc - Sucursal' },
            { data: 'visita', title: 'Visitas Realizadas' }
        ],
        cardTemplate: (data, index) => {
            badgeOptions = data.visita == 'completado'
                ? { t: 'Completado', c: 'primary' }
                : (data.visita ? { 'c': 'info', 't': `${data.visita} Visita${(data.visita > 1) ? 's' : ''}` } : { 'c': 'warning', 't': 'Sin Visitas' });

            return $('<div>').append(
                $('<div>', { class: 'd-flex align-items-center justify-content-between pb-1' }).append(
                    $('<div>', { class: 'fw-medium mb-0', style: 'overflow: hidden;font-size: 3.2vw;' }).append(`<label class="badge badge-${badgeOptions.c}" style="font-size: .85rem;">${badgeOptions.t}</label>`),
                    $('<div>', { class: 'btn-acciones-movil' }).append(data.acciones)
                ),
                $('<div>', { class: 'mt-2 mb-3 d-flex align-items-center gap-2' }).append(
                    $('<div>', { class: 'p-3 rounded-7', style: 'background-color: rgb(33 81 159 / 15%)' }).append(
                        '<i class="fas fa-store text-primary"></i>'
                    ),
                    $('<div>').append(
                        $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 3.25vw;' }).html(data.ruc),
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 2.5vw;' }).text(data.sucursal),
                    ),
                ),
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

    $('#listado_visitas .botones-accion').append(selector);
    // Disparar filtro cuando cambia el select
    $('#filtroEstado').on('change', function () {
        listado_visitas.search('visita', $(this).val()).draw();
    });
} else {
    contenedor_registros.append($('<div>', { class: 'card' })
        .append($('<div>', { class: 'card-body' })
            .append(
                cabecera_visitas,
                $('<div>', { class: 'row' })
                    .append($('<div>', { class: 'col-12' })
                        .append(
                            $('<table>', { class: 'table table-hover text-nowrap', id: 'listado_visitas', style: 'width: 100%' })
                                .append($('<thead>', { class: 'text-center' })
                                    .append($('<tr>')
                                        .append($('<th>').text('Ruc - Sucursal'))
                                        .append($('<th>').text('Visitas Realizadas'))
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
        fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
        dom: `<"row"
                <"col-lg-12 mb-2"B>>
            <"row"
                <"col-sm-4 text-xsm-start text-center my-1"l>
                <"col-sm-3 col-xsm-4 text-xsm-end text-center my-1 selectFiltroEstado">
                <"col-sm-5 col-xsm-8 text-xsm-end text-center my-1"f>>
            <"contenedor_tabla my-2"tr>
            <"row"
                <"col-md-5 text-md-start text-center my-1"i>
                <"col-md-7 text-md-end text-center my-1"p>>`,
        ajax: {
            url: getUrlListar(),
            dataSrc: dataSet,
            error: function (xhr, error, thrown) {
                boxAlert.table(updateTableVisitas);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            {
                data: 'ruc', render: function (data, type, row) {
                    return `${data} - ${row.sucursal}`;
                }
            },
            {
                data: 'visita', render: function (data, type, row) {
                    badgeOptions = data == 'completado'
                        ? { t: 'Completado', c: 'primary' }
                        : (data ? { 'c': 'info', 't': `${data} Visita${(data > 1) ? 's' : ''}` } : { 'c': 'warning', 't': 'Sin Visitas' });

                    return `<label class="badge badge-${badgeOptions.c}" style="font-size: .7rem;">${badgeOptions.t}</label>`;
                }
            },
            { data: 'acciones' }
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(1), td:eq(2)').addClass('text-center');
        },
        ordering: false,
        processing: true
    });

    let cSelect_search = $('.selectFiltroEstado');
    if (cSelect_search.length) {
        cSelect_search.append(selector);
        // Disparar filtro cuando cambia el select
        $('#filtroEstado').on('change', function () {
            listado_visitas.column(2).search($(this).val()).draw();
        });
    }
}

function updateTableVisitas() {
    if (esCelular()) {
        return listado_visitas.reload();
    }
    listado_visitas.ajax.reload();
}

selector.customSelect2({
    placeholder: 'Seleccione...',
    allowClear: true,
});