

const tb_orden = new DataTable('#tb_orden', {
    scrollX: true,
    scrollY: 300,
    ajax: {
        url: `${__url}/soporte/datatable`,
        dataSrc: function (json) {
            $('b[data-panel="_count"]').html(json.count.count);
            $('b[data-panel="_inc_a"]').html(json.count.inc_a);
            $('b[data-panel="_inc_s"]').html(json.count.inc_s);
            $('b[data-panel="_inc_p"]').html(json.count.inc_p);
            return json.data;
        },
        error: function (xhr, error, thrown) {
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: xhr.responseJSON });
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_incidencia' },
        { data: 'id_empresa' },
        { data: 'id_sucursal' },
        { data: 'direccion' },
        { data: 'created_at' },
        { data: 'id_tipo_estacion' },
        { data: 'id_tipo_incidencia' },
        {
            data: 'id_problema', render: function (data, type, row) {
                return `${data} / ${row.id_subproblema}`;
            }
        },
        { data: 'estado_informe' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(9)').addClass('td-acciones');
    },
    processing: true
});

function updateTable() {
    tb_orden.ajax.reload();
}