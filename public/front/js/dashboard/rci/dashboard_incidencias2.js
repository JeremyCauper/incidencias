$(document).ready(function () {
    $('#empresa').on('change', function () {
        fillSelect(['#sucursal'], sucursales, 'ruc', $(this).val(), 'id', 'nombre', 'status');
    });

    $('#dateRango').daterangepicker({
        showDropdowns: true,
        startDate: date('2020-m-01'),
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

    fObservador('.content-wrapper', () => {
        chartEstadot.resize();
        chartEstado0.resize();
        chartEstado1.resize();
        chartEstado2.resize();
        chartEstado3.resize();
        chartEstado4.resize();
    });
});

let chartEstadot = new ChartMananger({ id: 'chart-estadot', config: { tipo: 'estado', bg: '#9fa6b2' }, data: { total: 150, value: 56 } });
let chartEstado0 = new ChartMananger({ id: 'chart-estado0', config: { tipo: 'estado', bg: '#e4a11b' }, data: { total: 150, value: 28 } });
let chartEstado1 = new ChartMananger({ id: 'chart-estado1', config: { tipo: 'estado', bg: '#54b4d3' }, data: { total: 150, value: 35 } });
let chartEstado2 = new ChartMananger({ id: 'chart-estado2', config: { tipo: 'estado', bg: '#3b71ca' }, data: { total: 150, value: 24 } });
let chartEstado3 = new ChartMananger({ id: 'chart-estado3', config: { tipo: 'estado', bg: '#14a44d' }, data: { total: 150, value: 36 } });
let chartEstado4 = new ChartMananger({ id: 'chart-estado4', config: { tipo: 'estado', bg: '#dc4c64' }, data: { total: 150, value: 75 } });

var dom = document.getElementById('chart-incidencias');
var myChart = echarts.init(dom, null, {
    renderer: 'canvas',
    useDirtyRect: false
});

var DATA_ZOOM_MIN_VALUE_SPAN = 3600 * 1000;

var option = {
    useUTC: true,
    title: {
        text: 'Estadística por fecha',
        left: 'center'
    },
    tooltip: {
        trigger: 'axis'
    },
    grid: {
        left: '5%',
        right: '2%',
        with: '100%',
        outerBounds: {
            top: '15%',
            bottom: '40%'
        }
    },
    xAxis: {
        type: 'time',
        axisLabel: {
            formatter: function (value) {
                return echarts.time.format(value, '{yyyy}-{MM}-{dd}', true);
            }
        }
    },
    yAxis: {
        type: 'value',
        min: 'dataMin'
    },
    dataZoom: [
        {
            type: 'inside',
            minValueSpan: DATA_ZOOM_MIN_VALUE_SPAN
        },
        {
            type: 'slider',
            top: '88%',
            minValueSpan: DATA_ZOOM_MIN_VALUE_SPAN
        }
    ],
    series: [
        {
            type: 'line',
            symbolSize: 0,
            areaStyle: {},
            data: generarFechasConMontosIncrementales('2024-01-01', 1000)
        }
    ]
};

function generarFechasConMontosIncrementales(inicio, totalDias) {
  const resultado = [];
  const fechaInicio = new Date(inicio);
  let monto = 1; // Monto inicial

  for (let i = 0; i < totalDias; i++) {
    const fechaActual = new Date(fechaInicio);
    fechaActual.setDate(fechaInicio.getDate() + i);

    const fechaFormateada = fechaActual.toISOString().split('T')[0];

    // Incremento aleatorio entre 1 y 10
    console.log(Math.floor(Math.random() * 50));
    
    const incremento = Math.floor(Math.random() * Math.random() + Math.random() / Math.random() + Math.random() * Math.random() / Math.random()) + 1;
    monto += incremento;

    resultado.push([fechaFormateada, monto]);
  }

  return resultado;
}


myChart.setOption(option);
window.addEventListener('resize', myChart.resize);
