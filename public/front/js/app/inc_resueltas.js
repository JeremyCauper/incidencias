

const tb_orden = new DataTable('#tb_orden', {
    scrollX: true,
    scrollY: 300,
    ajax: {
        url: `${__url}/soporte/incidencias-resueltas/datatable`,
        dataSrc: function (json) {
            // $('b[data-panel="_count"]').html(json.count.count);
            // $('b[data-panel="_inc_a"]').html(json.count.inc_a);
            // $('b[data-panel="_inc_s"]').html(json.count.inc_s);
            // $('b[data-panel="_inc_p"]').html(json.count.inc_p);
            return json.data;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_ordens' },
        { data: 'tipo_orden' },
        { data: 'asignados' },
        { data: 'fecha_f', render: function (data, type, row) {
                return `${data} ${row.hora_f}`;
            }
        },
        { data: 'empresa' },
        { data: 'sucursal' },
        { data: 'problema' },
        { data: 'f_incio' },
        { data: 'f_final' },
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


function orderDetail(cod) {
    console.log(cod);
}

function displayOrder(cod) {
    console.log(cod);
}

function pdfOrder(cod) {
    window.open(`${__url}/documentoPdf/${cod}`, `Visualizar PDF ${cod}`, "width=900, height=800");
}

function orderTicket(cod) {
    console.log(cod);
}

function addSignature(cod) {
    console.log(cod);
}