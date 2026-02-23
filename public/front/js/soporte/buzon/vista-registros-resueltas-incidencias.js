let contenedor_registros_incidencias = $('#contenedor_registros_incidencias');
let listado_orden_incidencias;
let getUrlListarIncidenciasResueltas = () => generateUrl(`${__url}/soporte/buzon-personal/incidencias/resueltas/index`, {
    ruc: $('#empresa').val(),
    sucursal: $('#sucursal').val(),
    fechaIni: $('#dateRango').val().split('  al  ')[0],
    fechaFin: $('#dateRango').val().split('  al  ')[1]
});
let dataSetIncidenciasResueltas = (json) => {
    return json;
}


let acciones_incidencias = $('<div>').append(
    $('<button>', {
        class: 'btn btn-primary',
        onclick: 'updateTableInc()',
        'data-mdb-ripple-init': '',
        role: 'button'
    }).append($('<i>', { class: 'fas fa-rotate-right' })),
);

let cabecera_incidencias = $('<div>', { class: 'col-12 d-flex align-items-center justify-content-between my-3' })
    .append(
        $('<h6>', { class: 'card-title text-primary mb-0' }).append($('<strong>').text('Incidencias Resueltas')),
        acciones_incidencias
    );

if (esCelular()) {
    contenedor_registros_incidencias.append(cabecera_incidencias, $('<div>', { id: 'listado_orden_incidencias' }));

    listado_orden_incidencias = new CardTable('listado_orden_incidencias', {
        ajax: {
            url: getUrlListarIncidenciasResueltas(),
            dataSrc: dataSetIncidenciasResueltas,
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

} else {
    contenedor_registros_incidencias.append($('<div>', { class: 'card' })
        .append($('<div>', { class: 'card-body' })
            .append(
                cabecera_incidencias,
                $('<div>', { class: 'row' })
                    .append($('<div>', { class: 'col-12' })
                        .append(
                            $('<table>', { class: 'table table-hover text-nowrap', id: 'listado_orden_incidencias', style: 'width: 100%' })
                                .append($('<thead>', { class: 'text-center' })
                                    .append($('<tr>')
                                        .append($('<th>').text('Incidencia'))
                                        .append($('<th>').text('Fecha Incidencia'))
                                        .append($('<th>').text('N° Orden'))
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

    listado_orden_incidencias = new DataTable('#listado_orden_incidencias', {
        autoWidth: true,
        scrollX: true,
        scrollY: 400,
        ajax: {
            url: getUrlListarIncidenciasResueltas(),
            dataSrc: dataSetIncidenciasResueltas,
            error: function (xhr, error, thrown) {
                boxAlert.table(updateTableInc);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'cod_inc' },
            { data: 'fecha_inc' },
            { data: 'cod_orden' },
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
            $(row).find('td:eq(3), td:eq(4)').addClass('text-left');
            $(row).find('td:eq(7)').addClass(`td-acciones`);
        },
        processing: true
    });
    mostrar_acciones(listado_orden_incidencias);
}

function updateTableInc() {
    if (esCelular()) {
        return listado_orden_incidencias.reload();
    }
    listado_orden_incidencias.ajax.reload();
}

function filtroBusqueda() {
    const nuevoUrl = getUrlListarIncidenciasResueltas();
    listado_orden_incidencias.ajax.url(nuevoUrl).load();
    if (!esCelular()) {
        listado_orden_incidencias.column([4]).search('').draw();
    }
}