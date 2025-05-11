$(document).ready(function () {
    $('#empresa').on('change', function () {
        fillSelect(['#sucursal'], sucursales, 'ruc', $(this).val(), 'id', 'nombre', 'status');
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
            daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            firstDay: 1 // Comienza la semana en lunes
        }
    });

    // fObservador('.content-wrapper', () => {
    //     tb_orden.columns.adjust().draw();
    // });
});

// function updateTable() {
//     tb_orden.ajax.reload();
// }
// mostrar_acciones(tb_orden);

// function filtroBusqueda() {
//     var empresa = $('#empresa').val();
//     var sucursal = $('#sucursal').val();
//     var fechas = $('#dateRango').val().split('  al  ');
//     var tIncidencia = $('#tIncidencia').val();
//     var nuevoUrl = `${__url}/soporte/reportes/reporte-incidencias/index?ruc=${empresa}&sucursal=${sucursal}&fechaIni=${fechas[0]}&fechaFin=${fechas[1]}&tIncidencia=${tIncidencia}`;

//     tb_orden.ajax.url(nuevoUrl).load();
// }