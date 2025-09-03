$(document).ready(function () {
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

    fObservador('.content-wrapper', () => {
        chartActividades.resize();

        incidencia_estados.forEach((e, i) => {
            if (e.chart) e.chart.resize();
        });

        chartIncidenciaPorFechas.resize();

        chartProblemas.resize();
        chartNiveles.resize();
    });

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

    let incidencia_fechas = [
        {
            name: "incidencias",
            icon: "fas fa-file-invoice",
            color: "primary",
        },
        {
            name: "visitas",
            icon: "fas fa-van-shuttle",
            color: "success",
        },
        {
            name: "mantenimientos",
            icon: "fas fa-screwdriver-wrench",
            color: "warning",
        },
    ];
    let list_fechas = $('#list-fechas');
    incidencia_fechas.forEach((e, i) => {
        let div_btn = $('<div>', {
            class: 'btn border text-lg-start d-lg-block my-2 py-lg-4 text-nowrap', type: 'button', 'data-mdb-ripple-init': '', 'data-mdb-ripple-color': 'dark', 'data-name': e.name, 'data-color': e.color
        }).append(
            $('<i>', { class: e.icon, style: 'min-width: 1.5em;' }),
            e.name.toUpperCase()
        )
        div_btn.get(0).addEventListener('click', function () {
            let dname = (this.getAttribute('data-name')).toUpperCase();
            let dcolor = this.getAttribute('data-color');

            chartActividades.chart.dispatchAction({
                type: 'legendToggleSelect',
                name: dname
            });

            let selected = chartActividades.chart.getOption().legend[0].selected;
            if (selected[dname]) {
                this.classList.add('text-bg-' + dcolor);
            } else {
                this.classList.remove('text-bg-' + dcolor);
            }
        });
        list_fechas.append(div_btn);
    });

    let chartActividades = new ChartMananger({ id: 'chart-actividades', config: { tipo: 'actividades' } });
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
            var chartSubProblemas = new ChartMananger({ id: 'chart-subproblemas', config: { tipo: 'subproblemas', altura: 60 } });
            chartSubProblemas.updateOption({ data: subproblemas[codigo] ?? [] });
        }, 200);
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

    function setActividades(obj_actividades) {
        chartActividades.updateOption({ data: obj_actividades });
        incidencia_fechas.forEach((e, i) => {
            let div_btn = document.querySelector(`[data-name="${e.name}"]`);

            let selected = chartActividades.chart.getOption().legend[0].selected;
            if (selected[e.name.toUpperCase()]) {
                div_btn.classList.add('text-bg-' + e.color);
            } else {
                div_btn.classList.remove('text-bg-' + e.color);
            }
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
                            .append($('<i>', { class: (empresa ? 'fas fa-city' : 'far fa-building') + ' text-white' })),
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
        console.log(contable);
        if (!$(this).val()) return setContable(contable); // si está vacío, devuelve todo
        const q = $(this).val().toString().toLowerCase();
        setContable(contable.filter(item =>
            item.name.toString().toLowerCase().includes(q) ||
            item.text.toLowerCase().includes(q)
        ));
    });

    // --- Ejemplo de uso ---
    let promise = null;
    let controller = null;

    const filtroBusqueda = async () => {
        var empresa = $('#empresa').val();
        var sucursal = $('#sucursal').val();
        var fechas = $('#dateRango').val().split('  al  ');

        // Cancelar la solicitud anterior si sigue en curso
        if (controller) controller.abort();

        ({ promise, controller } = fetchDashboardIncidencias({
            ruc: empresa,
            sucursal: sucursal,
            fechaIni: fechas[0],
            fechaFin: fechas[1]
        }));

        try {
            const data = await promise; // o promise.then(...)
            console.log("Datos recibidos:", data);
            setEstados(data.data.estados);
            setActividades(data.data.personal);
            chartIncidenciaPorFechas.updateOption({ data: data.data.fechas });
            setNiveles(data.data.niveles);
            setContable(data.data.contable);
            contable = data.data.contable;
            chartProblemas.updateOption({ data: data.data.problemas });
            subproblemas = data.data.subproblemas;
            
        } catch (err) {
            console.warn("Promesa rechazada:", err);
        }
    }
    filtroBusqueda();

    $('#btnFiltroBusqueda').on('click', filtroBusqueda);
});

// --- Función para consultar el dashboard de incidencias ---
function fetchDashboardIncidencias({ ruc = "", sucursal = "", fechaIni, fechaFin }) {
    // Crear un AbortController para poder cancelar la petición
    const controller = new AbortController();
    const { signal } = controller;

    // Construcción de la URL dinámicamente con parámetros
    const baseUrl = "http://localhost/incidencias/public/soporte/dashboard/dashboard-incidencias2/index";
    const params = new URLSearchParams({ ruc, sucursal, fechaIni, fechaFin });
    const url = `${baseUrl}?${params.toString()}`;

    // Devolver promesa y también el controlador para cancelar
    const promise = new Promise(async (resolve, reject) => {
        try {
            const response = await fetch(url, { signal });

            if (!response.ok) {
                alert(`❌ Error en la petición: ${response.status} ${response.statusText}`);
                return reject(new Error(`HTTP error: ${response.status}`));
            }

            const data = await response.json();
            // alert("✅ Petición realizada con éxito");
            resolve(data);

        } catch (error) {
            if (error.name === "AbortError") {
                alert("⚠️ Petición cancelada por el usuario");
            } else {
                alert("🚨 Ocurrió un error en la petición");
                console.error("Error en fetchDashboardIncidencias:", error);
            }
            reject(error);
        }
    });

    return { promise, controller };
}
