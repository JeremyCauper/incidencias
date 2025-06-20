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
        yAxis: 'value',
        toolTip: {
            formatter: (params) => {
                let result = `<strong style="font-size:.725rem;">${params[0].data.text}</strong><br>`;

                params.forEach(item => {
                    const value = item.data.value;
                    const data = item.data.data;
                    result += `${item.marker} <span style="font-size:.7rem;">${item.seriesName}</span>: <b>${value}</b><br/>`;

                    if (item.seriesName == "INCIDENCIAS") {
                        result += `<ul style="font-size:.7rem;">
                            <li>N1 - REMOTO: ${data.niveles.n1}</li>
                            <li>N2 - PRESENCIAL: ${data.niveles.n2}</li>
                        </ul>`;
                    }
                });
                return result;
            }
        }
    }
});

var myChart_problemas = new ChartMananger({
    id: '#chart-problemas',
    type: 'bar',
    config: {
        xAxis: 'value',
        yAxis: 'category',
        order: 'asc'
    }
});

let subproblemas = {};
myChart_problemas.chart.on('click', function (params) {
    let codigo = params.name;
    $('#modal_subproblema').modal('show').find('.chart-title').html(params.data.text);
    setTimeout(() => {
        var myChart_subproblemas = new ChartMananger({
            id: '#chart-subproblemas',
            type: 'bar',
            config: {
                xAxis: 'value',
                yAxis: 'category',
                order: 'asc'
            }
        });
        myChart_subproblemas.updateOption(subproblemas[codigo]);
    }, 200);
});

var myChart_niveles = new ChartMananger({
    id: '#chart-niveles',
    type: 'pie',
    name: 'NIVEL'
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
    container.find('[aria-item="empresa"]').html(empresa ? `${empresas[empresa].ruc} - ${empresas[empresa].razon_social}` : 'Todas las empresas');
    container.find('[aria-item="sucursal"]').html(sucursal ? sucursales[sucursal].nombre : 'Todas las sucursales');
    container.find('[aria-item="fechas"]').html($('#dateRango').val());

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
            subproblemas = response.data.subproblemas;

            setTimeout(() => {
                $('.chart-contenedor').each(function () {
                    $(this).removeClass('chart-loading');
                });
            }, 100);
        };
    });
}