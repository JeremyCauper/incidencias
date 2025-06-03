$(document).ready(function () {
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
            myChart_niveles.updateOption();
            myChart_problemas.updateOption();
        }, 10);
    })

    fObservador('.content-wrapper', () => {
        myChart_estado.resize();
        myChart_niveles.resize();
        myChart_problemas.resize();

        myChart_estado.resizeGraphic(700);
        myChart_niveles.resizeGraphic(700);
    });

    filtroBusqueda();
});

var myChart_estado = new ChartMananger({
    id: '#chart-estado',
    type: 'pie',
    name: 'ESTADO'
});

var myChart_niveles = new ChartMananger({
    id: '#chart-niveles',
    type: 'pie',
    name: 'NIVEL'
});

var myChart_problemas = new ChartMananger({
    id: '#chart-problemas',
    type: 'bar',
    config: {
        xAxis: 'value',
        yAxis: 'category'
    }
});

function capturar() {
    $('.chart-container-header').addClass('chart-header').find('.logo_rci_white').removeClass('d-none');
    $('.chart-container-body').find('.chart-info').removeClass('d-none');
    
    var nodo = document.getElementById('chart-container');
    domtoimage.toPng(nodo)
        .then(async function (dataUrl) {
            var enlace = document.createElement('a');

            const fecha = $('#dateRango').val();
            const hora = new Date().toLocaleTimeString().replaceAll(':', '-');
            const nombreArchivo = `ANALISIS DE INCIDENCIAS ${fecha} - ${hora}.png`;

            enlace.download = nombreArchivo;
            enlace.href = dataUrl;
            enlace.click();
            $('.chart-container-header').removeClass('chart-header').find('.logo_rci_white').addClass('d-none');
            $('.chart-container-body').find('.chart-info').addClass('d-none');
        })
        .catch(function (error) {
            console.error('Error al generar imagen:', error);
        });
}

function generarPDF() {
    var nodo = document.getElementById('chart-container');
    $('.chart-container-header').addClass('chart-header').find('.logo_rci_white').removeClass('d-none');
    $('.chart-container-body').find('.chart-info').removeClass('d-none');

    domtoimage.toPng(nodo)
        .then(function (dataUrl) {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'landscape', // puedes cambiar a 'portrait'
                unit: 'px',
                format: [nodo.offsetWidth, nodo.offsetHeight]
            });

            // Agregamos la imagen generada del gráfico
            pdf.addImage(dataUrl, 'PNG', 0, 0, nodo.offsetWidth, nodo.offsetHeight);

            const fecha = $('#dateRango').val();
            const hora = new Date().toLocaleTimeString().replaceAll(':', '-');
            const nombreArchivo = `ANALISIS DE INCIDENCIAS ${fecha} - ${hora}.pdf`;
            $('.chart-container-header').addClass('chart-header').find('.logo_rci_white').removeClass('d-none');
            $('.chart-container-body').find('.chart-info').removeClass('d-none');

            // Guardamos el PDF
            pdf.save(nombreArchivo);
        })
        .catch(function (error) {
            console.error('Error al generar PDF:', error);
        });
}


function filtroBusqueda() {
    var empresa = $('#empresa').val();
    var sucursal = $('#sucursal').val();
    var fechas = $('#dateRango').val().split('  al  ');

    $('.chart-contenedor').each(function () {
        $(this).addClass('chart-loading');
    });

    var container = $('.chart-container-body');
    container.find('[aria-item="empresa"]').html(empresa);
    container.find('[aria-item="sucursal"]').html(sucursal ? sucursales[sucursal].nombre : 'Todas las sucursales');
    container.find('[aria-item="fechas"]').html($('#dateRango').val());

    $.ajax({
        method: "GET",
        url: `${__url}/empresa/reportes/reporte-incidencias/index`,
        data: {
            sucursal: sucursal,
            fechaIni: fechas[0],
            fechaFin: fechas[1],
        },
        timeout: 0,
    }).done(function (response) {
        if (response.success) {
            myChart_estado.updateOption(response.data.estados);
            myChart_niveles.updateOption(response.data.niveles);
            myChart_problemas.updateOption(response.data.problemas);

            setTimeout(() => {
                $('.chart-contenedor').each(function () {
                    $(this).removeClass('chart-loading');
                });
            }, 100);
        };
    });
}