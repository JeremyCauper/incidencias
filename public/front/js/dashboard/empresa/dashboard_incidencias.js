$(document).ready(function () {
    $('#empresa').on('change', function () {
        fillSelect(['#sucursal'], sucursales, 'ruc', $(this).val(), 'id', 'nombre', 'status');
    });

    fObservador('.content-wrapper', () => {
        incidencia_estados.forEach((e, i) => {
            if (e.chart) e.chart.resize();
        });

        chartIncidenciaPorFechas.resize();

        chartProblemas.resize();
        chartNiveles.resize();
    });


    function setEstados(obj_estado) {
        let total = obj_estado.reduce((acc, item) => acc + item.value, 0);
        $('#count-estado-total').text(total);

        obj_estado.forEach((e, i) => {
            $('#count-' + e.name).text(e.value);
            let estado = incidencia_estados.find(ie => ie.name == e.name);
            if (estado.chart)
                estado.chart.updateOption({ data: { total: total, value: e.value } });
        });
    }

    function setNiveles(obj_niveles) {
        let list_niveles = $('#list-niveles');
        let total = obj_niveles.reduce((acc, item) => acc + item.value, 0);
        const ul = $('<ul>', { class: 'p-0 m-0' });

        list_niveles.html('').append(
            $('<div>', { class: 'mt-3 mb-5 mb-2' }).append(
                $('<h2>', { class: 'mb-0 text-body-secondary' }).text(total),
                $('<p>', { class: 'mb-0 text-body-secondary' }).text('Total de Niveles'),
            ),
            ul
        );

        obj_niveles.forEach((n, i) => {
            let li = $('<li>', { class: 'd-flex align-items-start py-1 no-sombrear btn-niveles', type: 'button' })
                .append(
                    $('<div>', { class: 'flex-shrink-0' }).append(
                        $('<div>', { class: 'p-2 text-white rounded-4 icono-niveles', style: 'background-color:' + bliColor[n.color] }).text(n.name.toUpperCase())
                    ),
                    $('<div>', { class: 'flex-grow-1 ms-3' }).append(
                        $('<h6>', { class: 'mb-0 text-body-secondary text-nowrap' }).text(n.text.toUpperCase()),
                        $('<small>', { class: 'text-body-secondary' }).text(n.value))
                );

            const handlerEnter = () => {
                chartNiveles.updateOption({ config: { color: bliColor[n.color] }, data: { total: total, value: n.value } });
            };
            const handlerLeave = () => {
                chartNiveles.updateOption({ config: { color: bliColor[obj_niveles[0].color] }, data: { total: total, value: obj_niveles[0].value } });
            };
            li.get(0).removeEventListener('pointerenter', handlerEnter);
            li.get(0).removeEventListener('pointerleave', handlerLeave);

            li.get(0).addEventListener('pointerenter', handlerEnter);
            li.get(0).addEventListener('pointerleave', handlerLeave);
            ul.append(li);
        });
        chartNiveles.updateOption({ config: { color: bliColor[obj_niveles[0].color] }, data: { total: total, value: obj_niveles[0].value } });
    }

    function setContable(obj_contable) {
        let empresa = $('#empresa').val();
        let list_contable = $('#list-contable');

        $('#title-contable').text(`Ranking de ${empresa ? 'sucursales' : 'empresas'} según total de incidencias`);
        if (obj_contable.length) {
            list_contable.removeClass('text-center py-4').addClass('overflow-auto')
        } else {
            list_contable.removeClass('overflow-auto').addClass('text-center py-4').html('<span>No hay datos disponibles</span>');
            return false;
        }
        const ul = $('<ul>', { class: 'list-group list-group-light me-2' });

        obj_contable.forEach(c => {
            let li = $('<li>', { class: 'list-group-item d-flex justify-content-between align-items-center py-2' })
                .append(
                    $('<div>', { class: 'd-flex align-items-center' }).append(
                        $('<div>', { class: 'd-grid align-content-center' })
                            .append($('<i>', { class: (empresa ? 'fas fa-city' : 'far fa-building') })),
                        $('<div>', { class: 'ms-3' }).append(
                            empresa ? null : $('<p>', { class: 'fw-bold mb-0', style: 'font-size: small;' }).text(c.name),
                            $('<p>', { class: 'text-muted mb-0', style: 'font-size: smaller;' }).text(c.text))
                    ),
                    $('<span>', { class: 'badge badge-warning rounded' }).text(c.total)
                );
            ul.append(li);
        });
        list_contable.html(ul);
    }

    $('#filterContable').on('input', function () {
        if (!$(this).val()) return setContable(contable); // si está vacío, devuelve todo
        const q = $(this).val().toString().toLowerCase();
        setContable(contable.filter(item =>
            item.name.toString().toLowerCase().includes(q) ||
            item.text.toLowerCase().includes(q)
        ));
    });

    let promise = null;
    let controller = null;
    let filterAvanzado = async () => {
        const empresa = $('#empresa').val();
        const sucursal = $('#sucursal').val();
        const [fechaIni, fechaFin] = $('#dateRango').val().split(' a ');

        // Cancelar petición anterior si sigue activa
        if (controller) controller.abort();

        ({ promise, controller } = fetchDashboardIncidencias({
            ruc: empresa,
            sucursal: sucursal,
            fechaIni: fechaIni,
            fechaFin: fechaFin
        }));

        try {
            const { data } = await promise;
            // console.log("✅ Datos recibidos:", data);

            // Mapear datos en un solo lugar
            const acciones = {
                estados: setEstados,
                fechas: (d) => chartIncidenciaPorFechas.updateOption({ data: d }),
                niveles: setNiveles,
                contable: (d) => { setContable(d); contable = d; },
                problemas: (d) => chartProblemas.updateOption({ data: d }),
                subproblemas: (d) => { subproblemas = d; }
            };

            Object.entries(acciones).forEach(([key, fn]) => {
                if (data[key] !== undefined) fn(data[key]);
            });

        } catch (err) {
            boxAlert.box({ i: 'error', t: 'Petición rechazada', h: 'Hubo un inconveniente al procesar los datos.' });
            console.warn("⚠️ Promesa rechazada:", err);
        }
    }
    // Eventos
    $('#btnFiltroAvanzado').on('click', filterAvanzado);
    filterAvanzado();

    let intervalID = setInterval(filterAvanzado, 5 * 60 * 1000); // Ejecuta cada 5 min

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            document.title = 'PROCESO EN PAUSA!!';
            // Detener el intervalo
            clearInterval(intervalID);
        } else if (document.visibilityState === 'visible') {
            document.title = 'ANALISIS DE INCIDENCIAS';
            // Reanudar el intervalo
            intervalID = setInterval(filterAvanzado, 5 * 60 * 1000);
            // Si quieres que se ejecute inmediatamente al volver:
            filterAvanzado();
        }
    });
});

// --- Función para consultar el dashboard de incidencias ---
function fetchDashboardIncidencias({ ruc = "", sucursal = "", fechaIni, fechaFin }) {
    const controller = new AbortController();
    const { signal } = controller;

    const baseUrl = `${__url}/empresa/dashboard/dashboard-incidencias/index`;
    const params = new URLSearchParams({ ruc, sucursal, fechaIni, fechaFin });
    const url = `${baseUrl}?${params}`;

    const promise = (async () => {
        try {
            const response = await fetch(url, { signal });
            if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
            return await response.json();
        } catch (error) {
            if (error.name === "AbortError") {
                console.info("⚠️ Petición cancelada por el usuario");
            } else {
                console.error("🚨 Error en fetchDashboardIncidencias:", error);
            }
            throw error;
        }
    })();

    return { promise, controller };
}

let bliColor = {
    info: '#54b4d3',
    warning: '#e4a11b',
    purple: '#7367f0',
    primary: '#3b71ca',
    success: '#14a44d',
    danger: '#dc4c64',
    light: '#fbfbfb',
    secondary: '#9fa6b2',
    dark: '#332d2d',
};
let subproblemas = {};
let contable = [];

let incidencia_estados = [
    {
        name: "estado-total",
        text: "TOTAL INCIDENCIAS",
        color: "purple",
        chart: false,
    },
    {
        name: "estado-sinasignar",
        text: "SIN ASIGNAR",
        color: "warning",
        chart: true,
    },
    {
        name: "estado-asignados",
        text: "ASIGNADOS",
        color: "info",
        chart: true,
    },
    {
        name: "estado-enproceso",
        text: "EN PROCESO",
        color: "primary",
        chart: true,
    },
    {
        name: "estado-finalizados",
        text: "FINALIZADOS",
        color: "success",
        chart: true,
    },
    {
        name: "estado-faltandatos",
        text: "FALTAN DATOS",
        color: "danger",
        chart: true,
    },
];

let list_estado = $('#list-estado');
incidencia_estados.forEach((e, i) => {
    list_estado.append(
        $('<div>', { class: 'col-xxl-2 col-md-4 col-6 mb-2' }).append(
            $('<div>', { class: 'card', style: 'height: 100%;' }).append(
                $('<div>', { class: 'card-body row', style: 'color: ' + bliColor[e.color] }).append(
                    $('<div>', { class: e.chart ? 'col-7' : '' }).append(
                        $('<h6>', { class: 'card-title chart-estado-title mb-1' }).text(e.text),
                        $('<h4>', { class: 'subtitle-count', id: 'count-' + e.name }).text(0)
                    ),
                    e.chart ? $('<div>', { class: 'col-5' }).append($('<div>', { id: 'chart-' + e.name })) : null
                )
            )
        )
    );
    if (e.chart) {
        e.chart = new ChartMananger({ id: 'chart-' + e.name, config: { tipo: 'estado', altura: 5, bg: bliColor[e.color] }, data: { total: 100, value: 0 } });
    }
});

let chartIncidenciaPorFechas = new ChartMananger({ id: 'chart-incidencias-fechas', config: { tipo: 'incidencia_fechas', altura: 35 } });
let chartNiveles = new ChartMananger({ id: 'chart-niveles', config: { tipo: 'niveles', altura: 32.5 } });
let chartProblemas = new ChartMananger({ id: 'chart-problemas', config: { tipo: 'problemas', altura: 40 } });
chartProblemas.chart.on('click', function (params) {
    let codigo = params.name;
    var dom = document.getElementById('chart-subproblemas');
    if (echarts.getInstanceByDom(dom)) {
        echarts.dispose(dom);
    }
    $('#modal_subproblema').modal('show').find('.modal-title').html(params.data.text);
    setTimeout(() => {
        var chartSubProblemas = new ChartMananger({ id: 'chart-subproblemas', config: { tipo: 'subproblemas', altura: 70 } });
        chartSubProblemas.updateOption({ data: subproblemas[codigo] ?? [] });
    }, 200);
});