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
            cargarCharts();
        }, 10);
    })

    fObservador('.content-wrapper', () => {
        myChart_estado.resize();
        myChart_peronal.resize();
        myChart_problemas.resize();
        myChart_niveles.resize();

        manejarResizePie(myChart_estado, option_estado());
        manejarResizePie(myChart_peronal, option_peronal());
        manejarResizePie(myChart_niveles, option_nivel());
    });

    filtroBusqueda();
});

const estadoAnteriorMap = new WeakMap();
let manejarResizePie = (myChart, option) => {
    const ancho = myChart.getWidth();
    const estadoAnterior = estadoAnteriorMap.get(myChart);

    if (estadoAnterior === undefined) {
        estadoAnteriorMap.set(myChart, null);
        return;
    }

    if (ancho < 700 && estadoAnterior !== true) {
        myChart.setOption(option, true);
        estadoAnteriorMap.set(myChart, true);
    } else if (ancho >= 700 && estadoAnterior !== false) {
        myChart.setOption(option, true);
        estadoAnteriorMap.set(myChart, false);
    }
};

const getConfigSeriePie = (gConfig = {}) => {
    const title = gConfig.title ?? null;
    const name = gConfig.name ?? null;
    const data = gConfig.data ?? null;
    const chart = gConfig.chart ?? null;

    const varColor = getComputedStyle(document.documentElement).getPropertyValue('--mdb-surface-color').trim();

    let legendSelected = {};
    data.forEach(item => {
        legendSelected[item.name] = item.value !== 0; // true si no es cero
    });
    return {
        ...(title ? {
            title: {
                text: title,
                left: 'center',
                textStyle: {
                    color: '#999',
                    fontWeight: 'normal',
                    fontSize: 15,
                    fontWeight: 'bold'
                }
            }
        } : {}),
        tooltip: {
            trigger: 'item'
        },
        legend: {
            top: '6%',
            textStyle: {
                color: varColor,
                fontSize: 11,
                fontFamily: 'Arial'
            },
            itemWidth: 14,
            itemHeight: 14,
            selected: legendSelected
        },
        series: [
            {
                name: name,
                type: 'pie',
                top: '15%',
                left: 'center',
                width: chart.getWidth() < 700 ? '100%' : '60%',
                radius: chart.getWidth() < 700 ? ['25%', '45%'] : ['35%', '60%'],
                avoidLabelOverlap: false,
                itemStyle: {
                    borderRadius: 2,
                },
                label: {
                    alignTo: 'edge',
                    formatter: '{name|{b}}\n{time|{c} ({d}%)}',
                    minMargin: 5,
                    edgeDistance: 10,
                    lineHeight: 15,
                    color: varColor,
                    rich: {
                        time: {
                            fontSize: 10,
                            color: '#999'
                        }
                    }
                },
                labelLine: {
                    length: 40,
                    length2: 15,
                    smooth: true, // Curva las líneas, mejora la visibilidad
                    maxSurfaceAngle: 80
                },
                labelLayout: function (params) {
                    const isLeft = params.labelRect.x < chart.getWidth() / 2;
                    const points = params.labelLinePoints;
                    // Update the end point.
                    points[2][0] = isLeft
                        ? params.labelRect.x
                        : params.labelRect.x + params.labelRect.width;
                    return {
                        labelLinePoints: points
                    };
                },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                },
                data: data
            }
        ]
    }
}

const getConfigSerieBar = (gConfig = {}) => {
    const title = gConfig.title ?? null;
    let data = gConfig.data ?? null;
    const config = gConfig.config ?? null;
    const chart = gConfig.chart ?? null;

    const varColor = getComputedStyle(document.documentElement).getPropertyValue('--mdb-surface-color').trim();
    const theme = $('html').attr('data-mdb-theme') == 'dark' ? false : true;

    if (!data.length) {
        data = [{ name: 'Sin Datos', series: { sin_datos: 0 } }]
    }
    const keys = Object.keys(data[0].series);

    const getSeries = () => {
        const totals = {};
        keys.forEach(key => {
            totals[key] = data.reduce((sum, item) => sum + item.series[key], 0);
        });

        const series = keys.map((key) => {
            return {
                name: key.toUpperCase(), // Nombre en mayúsculas
                type: 'bar',
                barGap: 0,
                label: {
                    show: true,
                    position: config.xAxis == 'value' ? 'right' : 'top',
                    distance: 2,
                    ...(config.xAxis == 'value' ? {} : {
                        align: 'left',
                        verticalAlign: 'middle',
                        rotate: 90,
                    }),
                    formatter: function (params) {
                        const total = totals[key]; // Total de la serie actual
                        const percent = ((params.value / total) * 100).toFixed(1);
                        return `${params.value} (${percent}%)`;
                    },
                    fontSize: chart ? (chart.getWidth() < 700 ? 8.5 : 10) : 10,
                    color: varColor,
                },
                emphasis: {
                    focus: 'series'
                },
                data: data.map(item => item.series[key]) // Valores para la serie actual
            };
        });

        return series;
    };

    const config_axis = (axis) => {
        return axis == 'value' ? {
            axisLine: {
                lineStyle: {
                    color: theme ? '#ccc' : '#757575' // Color de la línea del eje Y
                }
            },
            splitLine: {
                lineStyle: {
                    color: theme ? '#ccc' : '#757575', // Cambia al color que prefieras
                    width: 1,
                    type: 'dotted' // Opcional: puede ser 'solid', 'dashed', o 'dotted'
                }
            }
        } : {
            axisTick: { show: false },
            data: data.map(item => item.name),
            axisLabel: {
                interval: 0,
                rotate: 30,
                textStyle: {
                    color: varColor,
                    fontSize: 10.5,
                    fontWeight: 'bold'
                },
            },
        }
    }
    return {
        ...(title ? {
            title: {
                text: title,
                left: 'center',
                textStyle: {
                    color: '#999',
                    fontWeight: 'normal',
                    fontSize: 14,
                    fontWeight: 'bold'
                }
            }
        } : {}),
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {
            top: '6%',
            data: keys.map(k => { return k.toUpperCase() }),
            textStyle: {
                color: varColor,
                fontSize: 11,
                fontFamily: 'Arial'
            },
            itemWidth: 14,
            itemHeight: 14
        },
        // toolbox: {
        //     show: true,
        //     orient: 'vertical',
        //     left: 'right',
        //     top: 'center',
        //     feature: {
        //         mark: { show: true },
        //         dataView: { show: true, readOnly: false },
        //         magicType: { show: true, type: ['line', 'bar', 'stack'] },
        //         restore: { show: true },
        //         saveAsImage: { show: true }
        //     }
        // },
        grid: [
            {
                top: config.xAxis == 'value' ? '20%' : '35%',
                bottom: '10%',
                left: '5%',
                right: config.xAxis == 'value' ? '18%' : '5%',
                with: '100%',
                containLabel: true
            },
        ],
        xAxis: [
            {
                type: config.xAxis,
                ...config_axis(config.xAxis)
            }
        ],
        yAxis: [
            {
                type: config.yAxis,
                ...config_axis(config.yAxis)
            }
        ],
        series: getSeries()
    }
}

// DATOS DE SERIES
let data_estado = [];
let data_personal = [];
let data_problema = [];
let data_nivel = [];

// CONFIGURACION CONTEINER SERIES
let config_init = {
    renderer: 'canvas',
    useDirtyRect: false
}
var myChart_estado = echarts.init($('#chart-estado').get(0), null, config_init);
var myChart_peronal = echarts.init($('#chart-personal').get(0), null, config_init);
var myChart_problemas = echarts.init($('#chart-problemas').get(0), null, config_init);
var myChart_niveles = echarts.init($('#chart-niveles').get(0), null, config_init);

// CONFIGURACION OPTIONS SERIES
var option_estado = () => {
    return getConfigSeriePie({ name: 'ESTADO', data: data_estado, chart: myChart_estado });
}
var option_peronal = () => {
    return getConfigSerieBar({ data: data_personal, config: { xAxis: 'category', yAxis: 'value' }, chart: myChart_peronal });
}
var option_problema = () => {
    return getConfigSerieBar({ data: data_problema, config: { xAxis: 'value', yAxis: 'category' } });
}
var option_nivel = () => {
    return getConfigSeriePie({ name: 'NIVEL', data: data_nivel, chart: myChart_niveles });
}

// VERIFICAR SI OPTIONS ES OBJETO
if (option_estado() && typeof option_estado() === 'object') {
    myChart_estado.setOption(option_estado());
}
if (option_peronal() && typeof option_peronal() === 'object') {
    myChart_peronal.setOption(option_peronal());
}
if (option_problema() && typeof option_problema() === 'object') {
    myChart_problemas.setOption(option_problema());
}
if (option_nivel() && typeof option_nivel() === 'object') {
    myChart_niveles.setOption(option_nivel());
}

function cargarCharts() {
    myChart_estado.setOption(option_estado(), true);
    myChart_peronal.setOption(option_peronal(), true);
    myChart_problemas.setOption(option_problema(), true);
    myChart_niveles.setOption(option_nivel(), true);
}


function capturar() {
    var nodo = document.getElementById('chart-container');
    domtoimage.toPng(nodo)
        .then(function (dataUrl) {
            var enlace = document.createElement('a');
            enlace.download = `ANALISIS DE INCIDENCIAS ${$('#dateRango').val()} - ${date('H:i:s')}.png`;
            enlace.href = dataUrl;
            enlace.click();
        })
        .catch(function (error) {
            console.error('Error al generar imagen:', error);
        });
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
            data_estado = response.data.estados;
            data_personal = response.data.personal;
            data_problema = response.data.problemas;
            data_nivel = response.data.niveles;

            setTimeout(() => {
                cargarCharts();
                $('.grid-estadisticas').each(function () {
                    $(this).removeClass('grid-loading');
                });
            }, 100);
        };
    });
}