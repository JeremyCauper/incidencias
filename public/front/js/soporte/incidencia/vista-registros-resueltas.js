let contenedor_registros = $('#contenedor_registros');
let listado_orden;
let getUrlListar = () => generateUrl(`${__url}/soporte/incidencias/resueltas/index`, {
    ruc: $('#empresa').val(),
    sucursal: $('#sucursal').val(),
    fProblema: $('#fProblema').val(),
    fSubProblema: $('#fSubProblema').val(),
    fechaIni: $('#dateRango').val().split('  al  ')[0],
    fechaFin: $('#dateRango').val().split('  al  ')[1]
});
let dataSet = (json) => {
    return json.data;
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
    contenedor_registros.append($('<div>', { id: 'listado_orden' }));

    listado_orden = new CardTable('listado_orden', {
        ajax: {
            url: getUrlListar(),
            dataSrc: dataSet,
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'cod_incidencia', title: 'Codigo' },
            { data: 'fecha_inc', title: 'Fecha Incidencia' },
            { data: 'cod_orden', title: 'Codigo Orden' },
            { data: 'asignados', title: 'Asignados' },
            { data: 'empresa', title: 'Empresa' },
            { data: 'sucursal', title: 'Sucursal' },
            { data: 'tipo_incidencia', title: 'Estacion' },
            { data: 'tipo_soporte', title: 'Nivel Incidencia' },
            { data: 'problema', title: 'Problema' },
            { data: 'subproblema', title: 'Sub Problema' },
            { data: 'iniciado', title: 'Iniciado' },
            { data: 'finalizado', title: 'Finalizado' }
        ],
        cardTemplate: (data, index) => {
            let empresa = empresas[data.empresa];
            let empresa_nombre = `${empresa.ruc} - ${empresa.razon_social}`;
            let tipoIncidencia = tipo_incidencia[data.tipo_incidencia[data.tipo_incidencia.length - 1]];

            return $('<div>').append(
                $('<div>', { class: 'd-flex align-items-center justify-content-between pb-1' }).append(
                    $('<div>', { class: 'fw-medium mb-0', style: 'overflow: hidden;font-size: 3.25vw;' }).append(
                        $('<span>').html(
                            `<span class="badge rounded-pill me-1" style="background-color: rgb(90 139 219 / 10%);color: rgb(90 139 219);font-size: 0.7rem;" aria-item="codigo">${data.cod_incidencia}</span>`),
                        $('<span>').html(data.cod_orden)
                    ),
                    $('<div>', { class: 'btn-acciones-movil' }).append(data.acciones)
                ),
                $('<div>', { class: 'mb-3' }).append(
                    $('<div>', { style: 'font-size: 3.25vw;' }).html('<i class="fas fa-building me-1"></i>' + empresa_nombre),
                    $('<div>', { class: 'text-muted mt-2', style: 'font-size: 2.5vw;' }).html(sucursales[data.sucursal].nombre),
                ),
                $('<div>', { class: 'mb-2' + (data.asignados.length ? '' : ' text-muted'), style: 'font-size: 2.75vw;' }).html(
                    data.asignados.length ?
                        '<i class="fas fa-user me-1"></i>' + (data.asignados.map(usu => usuarios[usu].nombre)).join(", ") :
                        '<i class="fas fa-user-large-slash me-1"></i>Sin asignar'
                ),
                $('<div>', { class: 'mb-2 rounded-7 p-2', style: 'background-color: rgb(101 138 177 / 8%);' }).append(
                    $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 1.7vw;' }).text('Problema Reportado'),
                    $('<p>', { class: 'fw-bold mb-1', style: 'font-size: 2.2vw;' }).text(obj_problem[data.problema].descripcion),
                    $('<p>', { class: 'text-muted fst-italic mb-0', style: 'font-size: 2.1vw;' }).html(getBadgePrioridad(obj_subproblem[data.subproblema].prioridad, .65) + obj_subproblem[data.subproblema].descripcion),
                ),
                $('<div>', { class: 'mb-2 d-flex align-items-center justify-content-between' }).append(
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
                $('<div>', { class: 'd-flex align-items-center justify-content-between pt-1' }).append(
                    `<span style="font-size: 2.85vw;color: #909090;"><i class="fas fa-clock me-2"></i>${data.fecha_inc}</span>`,
                    $('<div>').append(
                        $('<p>', { class: 'mb-1 text-uppercase fw-bolder text-muted', style: 'font-size: 1.7vw;' }).text('Iniciado - Finalizado'),
                        $('<p>', { class: 'fw-bold mb-0', style: 'font-size: 2.2vw;' }).html(data.iniciado),
                        $('<p>', { class: 'fw-bold mb-0', style: 'font-size: 2.2vw;' }).html(data.finalizado),
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
                $('<h6>', { class: 'card-title col-form-label-sm text-primary mb-3' }).append($('<strong>').text('Incidencias Registradas')),
                acciones,
                $('<div>', { class: 'row' })
                    .append($('<div>', { class: 'col-12' })
                        .append(
                            $('<table>', { class: 'table table-hover text-nowrap', id: 'listado_orden', style: 'width: 100%' })
                                .append($('<thead>', { class: 'text-center' })
                                    .append($('<tr>')
                                        .append($('<th>').text('Incidencia'))
                                        .append($('<th>').text('Fecha Incidencia'))
                                        .append($('<th>').text('N° Orden'))
                                        .append($('<th>').text('Tecnico'))
                                        .append($('<th>').text('Empresa'))
                                        .append($('<th>').text('Sucursal'))
                                        .append($('<th>').text('Nivel Incidencia'))
                                        .append($('<th>').text('Soporte'))
                                        .append($('<th>').text('Prioridad'))
                                        .append($('<th>').text('Problema'))
                                        .append($('<th>').text('Sub Problema'))
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

    listado_orden = new DataTable('#listado_orden', {
        scrollX: true,
        scrollY: 400,
        ajax: {
            url: getUrlListar(),
            dataSrc: dataSet,
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [{
            data: 'cod_incidencia'
        },
        {
            data: 'fecha_inc'
        },
        {
            data: 'cod_orden'
        },
        {
            data: 'asignados',
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
            data: 'tipo_incidencia',
            render: function (data, type, row) {
                let tipo = tipo_incidencia[data[data.length - 1]];
                return `<label class="badge badge-${tipo.color} me-2" style="font-size: 0.75rem;">${tipo.tipo}</label> ${tipo.descripcion}`;
            }
        },
        {
            data: 'tipo_soporte',
            render: function (data, type, row) {
                return tipo_soporte[data].descripcion;
            }
        },
        {
            data: 'subproblema',
            render: function (data, type, row) {
                return getBadgePrioridad(obj_subproblem[row.subproblema].prioridad, .75);
            }
        },
        {
            data: 'problema',
            render: function (data, type, row) {
                return obj_problem[data].descripcion;
            }
        },
        {
            data: 'subproblema',
            render: function (data, type, row) {
                return obj_subproblem[data].descripcion;
            }
        },
        {
            data: 'iniciado'
        },
        {
            data: 'finalizado'
        },
        {
            data: 'acciones'
        }
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(3), td:eq(4), td:eq(5), td:eq(6)').addClass('text-left');
            $(row).addClass('text-center');
            $(row).find('td:eq(13)').addClass(`td-acciones`);
        },
        order: [
            [1, 'desc']
        ],
        processing: true
    });
    mostrar_acciones(listado_orden);
}

function updateTable() {
    if (esCelular()) {
        return listado_orden.reload();
    }
    listado_orden.ajax.reload();
}

function filtroBusqueda() {
    const nuevoUrl = getUrlListar();
    listado_orden.ajax.url(nuevoUrl).load();
    if (!esCelular()) {
        listado_orden.column([4]).search('').draw();
    }
}