let contenedor_registros = $('#contenedor_registros');
let listado_incidencia;
let base_url = `${__url}/soporte/incidencias/registradas/index`;
let dataSet = (json) => {
    $.each(json.conteo_data, function (panel, count) {
        $(`p[data-panel="${panel}"]`).html(count);
    });
    fillSelectContac(json.contact);
    return json.data;
}

let acciones = $('<div>').append(
    $('<button>', {
        class: 'btn btn-primary me-2',
        'data-mdb-ripple-init': '',
        'data-mdb-modal-init': '',
        'data-mdb-target': '#modal_incidencias'
    }).append($('<i>', { class: 'fas fa-book-medical' }), 'Nueva Incidencia'),
    $('<button>', {
        class: 'btn btn-primary',
        onclick: 'updateTable()',
        'data-mdb-ripple-init': '',
        role: 'button'
    }).append($('<i>', { class: 'fas fa-rotate-right' })),
);

let cabecera = $('<div>', { class: 'col-12 d-flex align-items-center justify-content-between my-3' })
    .append(
        $('<h6>', { class: 'card-title text-primary mb-0' }).append($('<strong>').text('Incidencias Registradas')),
        acciones
    );

if (esCelular()) {
    contenedor_registros.append(cabecera, $('<div>', { id: 'listado_incidencia' }));

    listado_incidencia = new CardTable('listado_incidencia', {
        ajax: {
            url: base_url,
            dataSrc: dataSet,
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'incidencia', title: 'Codigo' },
            { data: 'estado', title: 'Estado' },
            { data: 'tecnicos', title: 'Tecnicos' },
            { data: 'empresa', title: 'Empresa' },
            { data: 'sucursal', title: 'Sucursal' },
            { data: 'registrado', title: 'Registrado' },
            { data: 'tipo_estacion', title: 'Estacion' },
            { data: 'tipo_incidencia', title: 'Nivel Incidencia' },
            { data: 'tipo_soporte', title: 'Soporte' },
            { data: 'problema', title: 'Problema' },
            { data: 'subproblema', title: 'Sub Problema' }
        ],
        cardTemplate: (data, index) => {
            let empresa = empresas[data.empresa];
            let empresa_nombre = `${empresa.ruc} - ${empresa.razon_social}`;
            let tipoIncidencia = tipo_incidencia[data.tipo_incidencia[data.tipo_incidencia.length - 1]];

            return $('<div>').append(
                $('<div>', { class: 'd-flex align-items-center justify-content-between pb-1' }).append(
                    $('<div>', { class: 'fw-medium mb-0', style: 'overflow: hidden;font-size: 3.25vw;' }).append(
                        $('<span>').html(`<span class="badge rounded-pill me-1" style="background-color: rgb(90 139 219 / 10%);color: rgb(90 139 219);font-size: 0.7rem;" aria-item="codigo">${data.incidencia}</span>`)
                    ),
                    $('<div>', { class: 'btn-acciones-movil' }).append(data.acciones)
                ),
                $('<div>', { class: 'mb-3' }).append(
                    $('<div>', { style: 'font-size: 3.25vw;' }).html('<i class="fas fa-building me-1"></i>' + empresa_nombre),
                    $('<div>', { class: 'text-muted mt-2', style: 'font-size: 2.5vw;' }).html(sucursales[data.sucursal].nombre),
                ),
                $('<div>', { class: 'mb-2' + (data.tecnicos.length ? '' : ' text-muted'), style: 'font-size: 2.75vw;' }).html(
                    data.tecnicos.length ?
                        '<i class="fas fa-user me-1"></i>' + (data.tecnicos.map(usu => usuarios[usu].nombre)).join(", ") :
                        '<i class="fas fa-user-large-slash me-1"></i>Sin asignar'
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

} else {
    contenedor_registros.append($('<div>', { class: 'card' })
        .append($('<div>', { class: 'card-body' })
            .append(
                cabecera,
                $('<div>', { class: 'row' })
                    .append($('<div>', { class: 'col-12' })
                        .append(
                            $('<table>', { class: 'table table-hover text-nowrap', id: 'listado_incidencia', style: 'width: 100%' })
                                .append($('<thead>', { class: 'text-center' })
                                    .append($('<tr>')
                                        .append($('<th>').text('Codigo'))
                                        .append($('<th>').text('Estado'))
                                        .append($('<th>').text('Tecnicos'))
                                        .append($('<th>').text('Empresa'))
                                        .append($('<th>').text('Sucursal'))
                                        .append($('<th>').text('Registrado'))
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
        scrollX: true,
        scrollY: 400,
        ajax: {
            url: base_url,
            dataSrc: dataSet,
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [{
            data: 'incidencia'
        },
        {
            data: 'estado'
        },
        {
            data: 'tecnicos',
            render: function (data, type, row) {
                return (data.map(usu => usuarios[usu].nombre)).join(", ");
            }
        },
        {
            data: 'empresa',
            render: function (data, type, row) {
                let empresa = empresas[data];
                return `${empresa.ruc} - ${empresa.razon_social}`;
            }
        },
        {
            data: 'sucursal',
            render: function (data, type, row) {
                return sucursales[data].nombre;
            }
        },
        {
            data: 'registrado'
        },
        {
            data: 'tipo_estacion',
            render: function (data, type, row) {
                return tipo_estacion[data].descripcion;
            }
        },
        {
            data: 'tipo_incidencia',
            render: function (data, type, row) {
                let tipo = tipo_incidencia[data[data.length - 1]];
                return `<label class="badge badge-${tipo.color} me-2" style="font-size: 0.75rem;">${tipo.tipo}</label>${tipo.descripcion}`;
            }
        },
        {
            data: 'tipo_soporte',
            render: function (data, type, row) {
                return tipo_soporte[data].descripcion;
            }
        },
        {
            data: 'problema',
            render: function (data, type, row) {
                return `${getBadgePrioridad(obj_subproblem[row.subproblema].prioridad, .75)} ${obj_problem[data].descripcion} / ${obj_subproblem[row.subproblema].descripcion}`;
            }
        },
        {
            data: 'acciones'
        }
        ],
        order: [
            [5, 'desc']
        ],
        createdRow: function (row, data, dataIndex) {
            const row_bg = ['row-warning', 'row-info', 'row-primary', '', 'row-danger'];
            $(row).find('td:eq(0), td:eq(1), td:eq(4), td:eq(5), td:eq(6), td:eq(10)').addClass(
                'text-center');
            $(row).find('td:eq(10)').addClass(`td-acciones`);
            $(row).addClass('row-bg ' + row_bg[data.estado_informe]);
        },
        processing: true
    });
    mostrar_acciones(listado_incidencia);
}

function updateTable() {
    if (esCelular()) {
        return listado_incidencia.reload();
    }
    listado_incidencia.ajax.reload();
}

function searchTable(search) {
    const biblio = ['', 'asignada', 'sin asignar', 'en proceso'];

    if (esCelular()) {
        listado_incidencia.search('estado', search == 0 ? '' : biblio[search]).draw();
    } else {
        listado_incidencia.column([1]).search(biblio[search]).draw();
    }
}