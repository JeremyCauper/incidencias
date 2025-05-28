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

    $('.check-trail').on('click', function () {
        setTimeout(() => {
            myChart_estado.updateOption();
            myChart_peronal.updateOption();
            myChart_problemas.updateOption();
            myChart_niveles.updateOption();
        }, 10);
    })

    fObservador('.content-wrapper', () => {
        myChart_estado.resize();
        myChart_peronal.resize();
        myChart_problemas.resize();
        myChart_niveles.resize();

        myChart_estado.resizeGraphic(700);
        myChart_peronal.resizeGraphic(700);
        myChart_niveles.resizeGraphic(700);
    });

    filtroBusqueda();
});

var myChart_estado = new ChartMananger({
    id: '#chart-estado',
    type: 'pie',
    name: 'ESTADO'
});

var myChart_peronal = new ChartMananger({
    id: '#chart-personal',
    type: 'bar',
    config: {
        xAxis: 'category',
        yAxis: 'value'
    }
});

var myChart_problemas = new ChartMananger({
    id: '#chart-problemas',
    type: 'bar',
    config: {
        xAxis:'value',
        yAxis: 'category'
    }
});

var myChart_niveles = new ChartMananger({
    id: '#chart-niveles',
    type: 'pie',
    name: 'NIVEL'
});


function capturar() {
    $('.chart-container-title').addClass('chart-title').find('.logo_rci').removeClass('d-none');
    setTimeout(() => {
        var nodo = document.getElementById('chart-container');
        domtoimage.toPng(nodo)
            .then(async function (dataUrl) {
                var enlace = document.createElement('a');
                enlace.download = `ANALISIS DE INCIDENCIAS ${$('#dateRango').val()} - ${date('H:i:s')}.png`;
                enlace.href = dataUrl;
                enlace.click();
                $('.chart-container-title').removeClass('chart-title').find('.logo_rci').addClass('d-none');
            })
            .catch(function (error) {
                console.error('Error al generar imagen:', error);
            });
    }, 100);
}

function filtroBusqueda() {
    var empresa = $('#empresa').val();
    var sucursal = $('#sucursal').val();
    var fechas = $('#dateRango').val().split('  al  ');

    $('.grid-estadisticas').each(function () {
        $(this).addClass('grid-loading');
    });

    $.ajax({
        method: "GET",
        url: `${__url}/soporte/reportes/reporte-incidencias/index`,
        data: {
            ruc: empresa,
            sucursal: sucursal,
            fechaIni: fechas[0],
            fechaFin: fechas[1],
        },
        timeout: 0,
    }).done(function (response) {
        if (response.success) {
            myChart_estado.updateOption(response.data.estados);
            myChart_peronal.updateOption(response.data.personal);
            myChart_problemas.updateOption(response.data.problemas);
            myChart_niveles.updateOption(response.data.niveles);

            setTimeout(() => {
                $('.grid-estadisticas').each(function () {
                    $(this).removeClass('grid-loading');
                });
            }, 100);
        };
    });
}