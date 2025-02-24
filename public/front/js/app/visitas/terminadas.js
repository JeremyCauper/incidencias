$(document).ready(function () {
    $('#empresa').on('change', function () {
        fillSelect(['#sucursal'], sucursales, 'ruc', $(this).val(), 'id', 'nombre');
    });

    $('#dateRango').daterangepicker({
        showDropdowns: true,
        startDate: date('Y-m-01'),
        endDate: date('Y-m-d'),
        maxDate: date('Y-m-d'),
        opens: "center",
        cancelClass: "btn-link",
        locale: {
            format: 'YYYY-MM-DD',
            separator: '  al  ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cerrar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Rango personalizado',
            daysOfWeek: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            firstDay: 1 // Comienza la semana en lunes
        }
    });
});

const tb_vterminadas = new DataTable('#tb_vterminadas', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
    ajax: {
        url: `${__url}/visitas/terminadas/index`,
        dataSrc: function (json) {
            return json;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'id' },
        { data: 'estado' },
        { data: 'sucursal' },
        { data: 'tecnicos' },
        { data: 'fecha' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(0), td:eq(2)').addClass('text-center');
    },
    processing: true
});

function updateTable() {
    tb_vterminadas.ajax.reload();
}