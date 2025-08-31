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
        chartActividades.resize();

        chartEstado0.resize();
        chartEstado1.resize();
        chartEstado2.resize();
        chartEstado3.resize();
        chartEstado4.resize();

        chartIncidencia.resize();
        chartTipoIncidencia.resize();
    });
});

let chartActividades = new ChartMananger({
    id: 'chart-actividades', config: { tipo: 'actividades' }, data: [
        {
            "name": "Vigo M.",
            "text": "72878242 Renzo Vigo M.",
            "series": {
                "incidencias": 3,
                "visitas": 1,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 6,
            "niveles": {
                "n1": 3,
                "n2": 0
            }
        },
        {
            "name": "Tecnico",
            "text": "00000001 Soporte01 Tecnico",
            "series": {
                "incidencias": 6,
                "visitas": 3,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 7,
            "niveles": {
                "n1": 5,
                "n2": 0
            }
        },
        {
            "name": "Tecnico",
            "text": "00000002 Soporte02 Tecnico",
            "series": {
                "incidencias": 4,
                "visitas": 0,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 9,
            "niveles": {
                "n1": 3,
                "n2": 1
            }
        },
        {
            "name": "Saenz Q.",
            "text": "40778797 Omar Saenz Q.",
            "series": {
                "incidencias": 16,
                "visitas": 5,
                "mantenimientos": 2
            },
            "transporte": "fas fa-motorcycle text-danger",
            "idTecnico": 14,
            "niveles": {
                "n1": 15,
                "n2": 1
            }
        },
        {
            "name": "Huerta",
            "text": "72159292 Alvaro Huerta",
            "series": {
                "incidencias": 3,
                "visitas": 2,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 15,
            "niveles": {
                "n1": 3,
                "n2": 0
            }
        },
        {
            "name": "Vilcapoma",
            "text": "77043291 Jherson Vilcapoma",
            "series": {
                "incidencias": 3,
                "visitas": 2,
                "mantenimientos": 1
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 16,
            "niveles": {
                "n1": 3,
                "n2": 0
            }
        },
        {
            "name": "Esteban",
            "text": "47833900 Gianfranco Esteban",
            "series": {
                "incidencias": 1,
                "visitas": 1,
                "mantenimientos": 1
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 17,
            "niveles": {
                "n1": 1,
                "n2": 0
            }
        },
        {
            "name": "Canchari",
            "text": "75530490 Khesnil Canchari",
            "series": {
                "incidencias": 0,
                "visitas": 0,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 18,
            "niveles": {
                "n1": 0,
                "n2": 0
            }
        },
        {
            "name": "Mendoza",
            "text": "75005472 Daysi Mendoza",
            "series": {
                "incidencias": 0,
                "visitas": 0,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 19,
            "niveles": {
                "n1": 0,
                "n2": 0
            }
        },
        {
            "name": "Velarde F.",
            "text": "45458303 Josue Velarde F.",
            "series": {
                "incidencias": 0,
                "visitas": 1,
                "mantenimientos": 0
            },
            "transporte": "fas fa-person-hiking text-success",
            "idTecnico": 20,
            "niveles": {
                "n1": 0,
                "n2": 0
            }
        },
        {
            "name": "Alvarez",
            "text": "73042819 Rodrigo Alvarez",
            "series": {
                "incidencias": 0,
                "visitas": 0,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 23,
            "niveles": {
                "n1": 0,
                "n2": 0
            }
        },
        {
            "name": "Trujillo",
            "text": "71545548 Owen Trujillo",
            "series": {
                "incidencias": 0,
                "visitas": 0,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 24,
            "niveles": {
                "n1": 0,
                "n2": 0
            }
        },
        {
            "name": "Incio",
            "text": "73206022 Sebastian Incio",
            "series": {
                "incidencias": 0,
                "visitas": 0,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 25,
            "niveles": {
                "n1": 0,
                "n2": 0
            }
        },
        {
            "name": "Escobar",
            "text": "45716026 Eduardo Escobar",
            "series": {
                "incidencias": 0,
                "visitas": 0,
                "mantenimientos": 0
            },
            "transporte": "fas fa-laptop",
            "idTecnico": 28,
            "niveles": {
                "n1": 0,
                "n2": 0
            }
        }
    ]
});

function toggleSeries(name, event) {
    let color = {
        INCIDENCIAS: 'primary',
        VISITAS: 'success',
        MANTENIMIENTOS: 'warning',
    };
    // simula el click sobre la leyenda
    chartActividades.chart.dispatchAction({
        type: 'legendToggleSelect',
        name: name
    });

    let selected = chartActividades.chart.getOption().legend[0].selected;
    if(selected[name]) {
        event.classList.add('text-bg-' + color[name]);
    } else {
        event.classList.remove('text-bg-' + color[name]);
    }

}


let chartEstado0 = new ChartMananger({ id: 'chart-estado0', config: { tipo: 'estado', altura: 5, bg: '#e4a11b' }, data: { total: 150, value: 28 } });
let chartEstado1 = new ChartMananger({ id: 'chart-estado1', config: { tipo: 'estado', altura: 5, bg: '#54b4d3' }, data: { total: 150, value: 35 } });
let chartEstado2 = new ChartMananger({ id: 'chart-estado2', config: { tipo: 'estado', altura: 5, bg: '#3b71ca' }, data: { total: 150, value: 24 } });
let chartEstado3 = new ChartMananger({ id: 'chart-estado3', config: { tipo: 'estado', altura: 5, bg: '#14a44d' }, data: { total: 150, value: 36 } });
let chartEstado4 = new ChartMananger({ id: 'chart-estado4', config: { tipo: 'estado', altura: 5, bg: '#dc4c64' }, data: { total: 150, value: 75 } });

const generarFechasConMontosIncrementales = (inicio, totalDias) => {
    const fechas = [];
    const valores = [];
    const fechaInicio = new Date(inicio);
    let monto = 1;
    for (let i = 0; i < totalDias; i++) {
        const fechaActual = new Date(fechaInicio);
        fechaActual.setDate(fechaInicio.getDate() + i);

        const fechaFormateada = fechaActual.toISOString().split('T')[0]; // yyyy-mm-dd
        fechas.push(fechaFormateada);

        const incremento = Math.floor(Math.random() * 10 + Math.random() * 10 / Math.random() * 10 + Math.random() * 10) + 1;
        monto += incremento;
        valores.push(monto);
    }
    return { fechas, valores };
}
const { fechas, valores } = generarFechasConMontosIncrementales('2024-01-01', 1000);
let chartIncidencia = new ChartMananger({ id: 'chart-incidencias', config: { tipo: 'incidencia', altura: 35 }, data: { fechas: fechas, valores: valores } });

let chartTipoIncidencia = new ChartMananger({
    id: 'chart-tipo-incidencias', config: { tipo: 'tipo_incidencia', altura: 35 }, data: [
        { value: 274, name: 'Union Ads' },
        { value: 255, name: 'Video Ads' },
        { value: 400, name: 'Search Engine' }
    ]
});
