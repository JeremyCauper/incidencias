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

    // fObservador('.content-wrapper', () => {
    // });
});

const option_estado = (bg, max, value) => {
    var option = {
        series: [
            {
                type: 'gauge',
                center: ['50%', '85%'],
                startAngle: 180,
                endAngle: 0,
                min: 0,
                max: max,
                splitNumber: 1,
                itemStyle: {
                    color: bg
                },
                pointer: {
                    icon: 'path://M2090.36389,615.30999 L2090.36389,615.30999 C2091.48372,615.30999 2092.40383,616.194028 2092.44859,617.312956 L2096.90698,728.755929 C2097.05155,732.369577 2094.2393,735.416212 2090.62566,735.56078 C2090.53845,735.564269 2090.45117,735.566014 2090.36389,735.566014 L2090.36389,735.566014 C2086.74736,735.566014 2083.81557,732.63423 2083.81557,729.017692 C2083.81557,728.930412 2083.81732,728.84314 2083.82081,728.755929 L2088.2792,617.312956 C2088.32396,616.194028 2089.24407,615.30999 2090.36389,615.30999 Z',
                    length: '60%',
                    width: 3,
                    offsetCenter: [0, '5%']
                },
                progress: {
                    show: true,
                    roundCap: true,
                    width: 5
                },
                axisLine: {
                    roundCap: true,
                    lineStyle: {
                        width: 5
                    }
                },
                axisTick: {
                    show: false
                },
                splitLine: {
                    show: false
                },
                axisLabel: {
                    show: false
                },
                anchor: {
                    show: false
                },
                title: {
                    show: false
                },
                detail: {
                    valueAnimation: true,
                    width: '50%',
                    lineHeight: 30,
                    borderRadius: 8,
                    offsetCenter: [2, '-150%'],
                    fontSize: 13,
                    fontWeight: 'bolder',
                    formatter: '{value} %',
                    color: 'inherit'
                },
                data: [
                    {
                        value: value
                    }
                ]
            }
        ]
    };
    return option;
}

let create_chartEstado = (id, option) => {
    var chart = echarts.init(document.getElementById(id), null, {
        renderer: 'canvas',
        useDirtyRect: false
    });

    if (option && typeof option === 'object') {
        chart.setOption(option);
    }
    return chart;
}

let chartEstadot = create_chartEstado('chart-estadot', option_estado('#9fa6b2', 150, 56));
let chartEstado0 = create_chartEstado('chart-estado0', option_estado('#e4a11b', 150, 28));
let chartEstado1 = create_chartEstado('chart-estado1', option_estado('#54b4d3', 150, 35));
let chartEstado2 = create_chartEstado('chart-estado2', option_estado('#3b71ca', 150, 24));
let chartEstado3 = create_chartEstado('chart-estado3', option_estado('#14a44d', 150, 36));
let chartEstado4 = create_chartEstado('chart-estado4', option_estado('#dc4c64', 150, 75));

window.addEventListener('resize', () => {
    chartEstadot.resize();
    chartEstado0.resize();
    chartEstado1.resize();
    chartEstado2.resize();
    chartEstado3.resize();
    chartEstado4.resize();
});