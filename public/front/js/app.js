function fillSelect(selector, data, filterField, filterValue, optionValue, optionText, optionCondition) {
    $(selector.join()).html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
    if (!filterValue) return false;

    if (Array.isArray(data)) {
        data.forEach(e => {
            if (e[filterField] == filterValue && e[optionCondition])
                $(selector[0]).append($('<option>').val(e[optionValue]).text(e[optionText]));
        });
    } else if (typeof data === 'object') {
        Object.entries(data).forEach(([key, e]) => {
            if (e[filterField] == filterValue && e[optionCondition])
                $(selector[0]).append($('<option>').val(e[optionValue]).text(e[optionText]));
        });
    }
    $(selector[0]).attr('disabled', false);
}

function configControls(selector, config) {
    const configuracion = (control, config) => {
        const input = $(control);
        if (!input) return console.log(`El control "${control}" no existe`);
        const { mxl: maxlength, mnl: minlength, mask } = config;
        if (maxlength) input.attr('maxlength', maxlength);
        if (minlength) input.attr('minlength', minlength);

        if (mask) {
            const { reg = "999999999", conf = {
                placeholder: "0",
                greedy: true,
                casing: "upper",
                jitMasking: true
            }
            } = mask;
            Inputmask(reg, conf).mask(control);
        }
    }

    if (typeof selector === "string") {
        return configuracion(selector, config);
    }
    selector.forEach(e => {
        configuracion(e, config);
    });
}

// Función para manejar controladores y configurar atributos
function defineControllerAttributes(selector, config) {
    const configuracion = (control, config) => {
        const idString = control.replaceAll('#', '');
        const input = $(control);
        const label = $(`[for="${idString}"]`);

        if (input.length === 0) {
            console.error(`El control "${idString}" no existe`);
            return;
        }

        let settings = {
            name: config.name || idString,
            type: config.type || "text",
            value: config.val || false,
            "control-type": config["control-type"] || "string",
            require: config.require || false,
            minLength: config.mnl || false,
            maxLength: config.mxl || false,
            disabled: config.disabled || false,
            placeholder: config.pholder || "",
            lengthMessage: config.lengthMessage || `El campo '${label.html()}' debe tener entre ${config.mnl || 0} y ${config.mxl || 0} dígitos.`,
            errorMessage: config.errorMessage || false,
        };

        for (let [key, value] of Object.entries(settings)) {
            if (key == 'errorMessage' && !value) {
                switch (settings['control-type']) {
                    case 'ruc':
                        value = "El numero de RUC es invalido.";
                        break;

                    case 'int':
                        value = "El dato ingresado debe ser un numero.";
                        break;

                    case 'float':
                        value = "El dato ingresado debe ser un numero decimal.";
                        break;

                    case 'date':
                        value = "El dato ingresado debe ser un numero.";
                        break;

                    case 'email':
                        value = "El correo electrónico ingresado no es válido.";
                        break;

                    default:
                        value = "";
                        break;
                }
            }
            if (value) {
                if (key == 'require') {
                    value = label.html();
                    label.addClass('required');
                }
                input.attr(key, value);
            }
        }

        const { mask } = config;
        if (mask) {
            const { reg = "999999999", conf = { placeholder: "0", greedy: true, casing: "upper", jitMasking: true } } = mask;
            Inputmask(reg, conf).mask(control);
        }
    };

    if (typeof selector === "string") {
        return configuracion(selector, config);
    }
    selector.forEach(e => {
        configuracion(e, config);
    });
}

function validFrom(_this) {
    var dat = _this.querySelectorAll('[name]');
    var dataF = { success: true, data: { data: {}, require: [] } };

    for (let i = 0; i < dat.length; i++) {
        const e = dat[i];
        if (e.value != "" && e.getAttribute("control-type")) {

        }
        switch (e.getAttribute("control-type")) {
            case 'ruc':
                var validRuc = /^(10|20)/.test(e.value);
                if (!validRuc) {
                    dataF.success = false;
                    boxAlert.box({ i: 'warning', t: 'Datos invalidos', h: e.getAttribute("errorMessage") });
                    return dataF;
                }
                break;

            case 'email':
                const emailValue = e.value;
                const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (emailValue && !emailRegex.test(emailValue)) {
                    dataF.success = false;
                    boxAlert.box({ i: 'warning', t: 'Datos invalidos', h: e.getAttribute("errorMessage") });
                    return dataF;
                }
                break;

            default:
                break;
        }

        if (e.getAttribute("require") && e.value === "") {
            dataF.data.require.push(`<li><b>${e.getAttribute("require")}</b></li>`);
        }
        dataF.data.data[e.name] = e.value;
    }

    if (dataF.data.require.length > 0) {
        dataF.success = false;
        boxAlert.box({
            i: 'info',
            t: 'Faltan datos',
            h: `<h6 class="text-secondary">Los siguientes campos son requeridos</h6>
                <ul style="font-size:.75rem;">${dataF.data.require.join('')}</ul>`
        });
    }

    return dataF;
}

function extractDataRow($this, $table = null) {
    const ths = document.querySelectorAll(`${$table ? `#${$table}_wrapper` : ""} .dataTables_scrollHeadInner table thead tr th`);
    const tr = $this.parentNode.parentNode.parentNode.parentNode;
    const tds = tr.querySelectorAll('td');
    var obj_return = {};

    ths.forEach(function (e, i) {
        if (i != 9) obj_return[(e.innerHTML).toLowerCase()] = tds[i].innerHTML;
    });

    return obj_return;
}

function formatRequired(data) {
    let $mensaje = "EL campo :atributo es requerido.";
    var result = "";
    for (const key in data) {
        const text = $(`[for="${key}"]`).html() ?? key.toUpperCase();
        result = `<li><b>${$mensaje.replace(':atributo', text)}</b></li>`;
    }
    return `<ul style="font-size:.75rem;">${result}</ul>`;
}

function formatUnique(data) {
    let $mensaje = "El dato ingresado en el campo :atributo, ya está en uso.";
    var result = "";
    data.forEach(function (e) {
        if (e == "cod_inc") {
            $mensaje = "El codigo de incidencia ya está en uso.";
        }
        const text = $(`[for="${e}"]`).html() ?? e.toUpperCase();
        result = `<li><b>${$mensaje.replace(':atributo', text)}</b></li>`;
    });
    return `<ul style="font-size:.75rem;">${result}</ul>`;
}

function date(format) {
    const now = new Date();

    const map = {
        'Y': now.getFullYear(),                // Año completo (2024)
        'm': String(now.getMonth() + 1).padStart(2, '0'),  // Mes (01-12)
        'd': String(now.getDate()).padStart(2, '0'),       // Día del mes (01-31)
        'H': String(now.getHours()).padStart(2, '0'),      // Horas (00-23)
        'i': String(now.getMinutes()).padStart(2, '0'),    // Minutos (00-59)
        's': String(now.getSeconds()).padStart(2, '0'),    // Segundos (00-59)
        'j': now.getDate(),                                // Día del mes sin ceros iniciales (1-31)
        'n': now.getMonth() + 1,                           // Mes sin ceros iniciales (1-12)
        'w': now.getDay(),                                 // Día de la semana (0 = domingo, 6 = sábado)
        'G': now.getHours(),                               // Horas sin ceros iniciales (0-23)
        'a': now.getHours() >= 12 ? 'pm' : 'am',           // am o pm
        'A': now.getHours() >= 12 ? 'PM' : 'AM'            // AM o PM en mayúsculas
    };

    return format.replace(/[YmdHisjwnGaA]/g, (match) => map[match]);
}

async function consultarDni(dni) {
    const url = `${__url}/soporte/ConsultaDni/${dni}`;

    try {
        const response = await $.ajax({
            url: url,
            method: "GET",
            dataType: "json"
        });
        return response; // Retorna la respuesta obtenida
    } catch (error) {
        console.error("Error al consultar el DNI:", error);
        throw error; // Lanza el error para que el manejador lo procese
    }
}

async function consultarDniInput($this) {
    if (!$this.val()) return false;

    const label = $(`[for="${$this.attr('id')}"`);
    const labelHtml = label.html();
    if (label.find('span.text-info').length) {
        return true;
    }
    try {
        label.addClass('d-flex justify-content-between').html(`${labelHtml} <span class="text-info"><span class="spinner-border" role="status" style="width: 1rem; height: 1rem;"></span> Consultando</span>`);
        const datos = await consultarDni($this.val());
        return datos;
    } catch (error) {
        console.error("No se pudo consultar el DNI.");
    } finally {
        label.removeClass('d-flex justify-content-between').html(labelHtml);
    }
}

function llenarInfoModal(id_modal, data) {
    Object.entries(data).forEach(([key, e]) => {
        $(`#${id_modal} [aria-item="${key}"]`).html(e);
    });
}

function llenarInfoTipoInc(id_modal, data) {
    let tipoi = data.incidencia.tipo_incidencia;
    let estado = data.incidencia.estado_informe;
    let seguimiento = data.seguimiento?.final?.date ?? "En Proceso ...";

    let resultado = Object.entries(tipoi).map(([key, e]) => {
        let tipoInc = tipo_incidencia[e.id_tipo_inc];
        let dnone = "", fecha_ini = "", fecha_fin = "";

        if (estado == 0 || estado == 1) {
            fecha_ini = "Sin Iniciar";
            dnone = "d-none";
        } else {
            fecha_ini = `${e.fecha} ${e.hora}`;
            if (key == (tipoi.length - 1)) {
                fecha_fin = seguimiento;
            } else {
                let ffin = tipoi[eval(key) + 1];
                fecha_fin = `${ffin.fecha} ${ffin.hora}`;
            }
        }
        return `
            <div class="col-lg-4 col-md-6 mt-2">
                <div class="d-flex align-items-center">
                    <label class="badge badge-${tipoInc.color}">${tipoInc.tipo}</label>
                    <div class="ms-2 w-100">
                        <p class="d-flex justify-content-between mb-0 pe-lg-5 col-5 col-md-12" style="font-weight: 500;font-size: small;">${tipoInc.descripcion}<span>${calcularDuracion(fecha_ini, fecha_fin)}</span></p>
                        <p class="text-muted mb-0" style="font-size: .675rem;"><b style="letter-spacing: 0.1em;">I: </b>${fecha_ini}</p>
                        <p class="text-muted mb-0 ${dnone}" style="font-size: .675rem;"><b>F: </b>${fecha_fin}</p>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    $(`#${id_modal} [aria-item="incidencia"]`).html(`
        <div class="row">
            ${resultado}
        </div>
    `);
}

function llenarInfoSeguimientoInc(id_modal, data) {
    const acciones = {
        'registro': '<i class="fas fa-folder-open text-warning"></i> Registro de Incidencia',
        'asignado': '<i class="fas fa-user-clock text-info"></i> Asignaciones',
        'inicio': '<i class="fas fa-hourglass-start text-primary"></i> Inició la Incidencia',
        'final': '<i class="fas fa-check-double text-success"></i> Finalizó la Incidencia',
    };

    let seguimiento = Object.entries(data).map(([key, e]) => {
        let bodySeguimiento = "";
        if (key === "asignado") {
            if (!data["asignado"].length) return;
        }

        const contactoTemplate = (persona) => `
            <div class="d-flex align-items-center mt-2">
                <img src="${persona.img}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                <div class="ms-3">
                    <p class="fw-bold mb-1">${persona.nombre}</p>
                    <p class="text-muted mb-0 mt-2" style="font-size: .73rem;">
                        <i class="fab fa-whatsapp text-success"></i> ${persona.telefono} / <i class="far fa-envelope text-danger"></i> ${persona.email}
                    </p>
                </div>
            </div>
        `;

        if (key === "asignado") {
            bodySeguimiento = e.map(asignacion => {
                const tecnicos = asignacion.tecnicos.map(tecnico => `
                    <div class="col my-1 mx-2">
                        <label class="d-flex align-items-center text-nowrap">
                            <img src="${tecnico.img}" alt="" style="width: 24px; height: 24px" class="rounded-circle" />
                            <div class="ms-2">
                                <p class="mb-0" style="font-weight: 500;font-size: .75rem;">${tecnico.nombre}</p>
                                <p class="text-muted mb-0" style="font-size: .675rem;">${tecnico.date}</p>
                            </div>
                        </label>
                    </div>
                `).join('');

                return `
                    <div class="d-flex align-items-center mt-2">
                        <img src="${asignacion.img}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                        <div class="ms-3 w-100">
                            <p class="fw-bold mb-1">${asignacion.nombre}</p>
                            <p class="text-muted mb-1" style="font-size: .73rem;">Asignó la incidencia a:</p>
                            <div class="row row-cols-1 row-cols-lg-5">${tecnicos}</div>
                            <p class="text-muted mb-0 mt-2" style="font-size: .73rem;">
                                <i class="fab fa-whatsapp text-success"></i> ${asignacion.telefono} / <i class="far fa-envelope text-danger"></i> ${asignacion.email}
                            </p>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            bodySeguimiento = contactoTemplate(e);
        }

        return `
            <li class="list-group-item border-0">
                <div class="p-3 rounded-5 shadow-4-strong">
                    <div class="d-flex justify-content-between align-items-center title-seguimiento">
                        <span class="tt-upper font-weight-semibold">${acciones[key]}</span>
                        <span class="font-weight-semibold">${e.date ?? ""}</span>
                    </div>
                    <div>${bodySeguimiento}</div>
                </div>
            </li>
        `;
    }).join('');

    $(`#${id_modal} [aria-item="contenedor-seguimiento"]`).html(`
        <ul class="list-group list-group-light">
            ${seguimiento}
        </ul>
    `);
}

function llenarInfoSeguimientoVis(id_modal, data) {
    const acciones = {
        'registro': '<i class="fas fa-folder-open text-warning"></i> Registro de Visita',
        'asignado': '<i class="fas fa-user-clock text-info"></i> Asignaciones',
        'inicio': '<i class="fas fa-hourglass-start text-primary"></i> Inició la Visita',
        'final': '<i class="fas fa-check-double text-success"></i> Finalizó la Visita',
    };

    let seguimiento = Object.entries(data).map(([key, e]) => {
        let bodySeguimiento = "";
        if (key === "asignado") {
            if (!data["asignado"].length) return;
        }

        const contactoTemplate = (persona) => `
            <div class="d-flex align-items-center mt-2">
                <img src="${persona.img}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                <div class="ms-3">
                    <p class="fw-bold mb-1">${persona.nombre}</p>
                    <p class="text-muted mb-0 mt-2" style="font-size: .73rem;">
                        <i class="fab fa-whatsapp text-success"></i> ${persona.telefono} / <i class="far fa-envelope text-danger"></i> ${persona.email}
                    </p>
                </div>
            </div>
        `;

        if (key === "asignado") {
            bodySeguimiento = e.map(asignacion => {
                const tecnicos = asignacion.tecnicos.map(tecnico => `
                    <label class="p-1 rounded text-nowrap" role="button" data-mdb-ripple-init title="${tecnico.date}">
                        <img src="${tecnico.img}" alt="" style="width: 24px; height: 24px" class="rounded-circle" />
                        <span>${tecnico.nombre}</span>
                    </label>
                `).join('');

                return `
                    <div class="d-flex align-items-center mt-2">
                        <img src="${asignacion.img}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                        <div class="ms-3">
                            <p class="fw-bold mb-1">${asignacion.nombre}</p>
                            <p class="text-muted mb-1" style="font-size: .73rem;">Asignó la visita a:</p>
                            <div class="mb-0 ms-2" style="font-size: .73rem;">${tecnicos}</div>
                            <p class="text-muted mb-0 mt-2" style="font-size: .73rem;">
                                <i class="fab fa-whatsapp text-success"></i> ${asignacion.telefono} / <i class="far fa-envelope text-danger"></i> ${asignacion.email}
                            </p>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            bodySeguimiento = contactoTemplate(e);
        }

        return `
            <li class="list-group-item border-0">
                <div class="p-3 rounded-5 shadow-4-strong">
                    <div class="d-flex justify-content-between align-items-center title-seguimiento">
                        <span class="tt-upper font-weight-semibold">${acciones[key]}</span>
                        <span class="font-weight-semibold">${e.date ?? ""}</span>
                    </div>
                    <div>${bodySeguimiento}</div>
                </div>
            </li>
        `;
    }).join('');

    $(`#${id_modal} [aria-item="contenedor-seguimiento"]`).html(`
        <ul class="list-group list-group-light">
            ${seguimiento}
        </ul>
    `);
}

/**
 * Calcula la diferencia entre dos fechas (format "YYYY-MM-DD HH:mm:ss")
 * y devuelve un string "Xh Ym Zs".
 *
 * @param {string} fechaIni - Fecha de inicio, p. ej. "2025-04-21 10:49:44"
 * @param {string} fechaFin - Fecha de fin,    p. ej. "2025-04-21 13:55:44"
 * @returns {string} Diferencia en "##h ##m ##s"
 */
function calcularDuracion(fechaIni, fechaFin) {
    // Convertir " " en "T" para que Date lo interprete como ISO
    const inicio = new Date(fechaIni.replace(' ', 'T'));
    const fin = new Date(fechaFin.replace(' ', 'T'));

    // Diferencia en milisegundos
    let diffMs = fin - inicio;
    if (isNaN(diffMs)) {
        return '';
    }
    if (diffMs < 0) {
        // Si la fecha fin es anterior, invertimos o lanzamos error
        diffMs = Math.abs(diffMs);
    }

    const totalSegundos = Math.floor(diffMs / 1000);
    const segundos = totalSegundos % 60;
    const totalMinutos = Math.floor(totalSegundos / 60);
    const minutos = totalMinutos % 60;
    const horas = Math.floor(totalMinutos / 60);

    return `${horas}h ${minutos}m ${segundos}s`;
}

//   // Ejemplo de uso:
//   const inicio = "2025-04-21 10:49:44";
//   const fin    = "2025-04-21 13:55:44";
//   console.log(calcularDuracion(inicio, fin)); // "3h 6m 0s"


function getBadgeIncidencia(estado, size = '.7') {
    estadoInforme = {
        "0": { 'color': 'warning', 'text': 'Sin Asignar' },
        "1": { 'color': 'info', 'text': 'Asignada' },
        "2": { 'color': 'primary', 'text': 'En Proceso' },
        "3": { 'color': 'success', 'text': 'Finalizado' },
        "4": { 'color': 'danger', 'text': 'Faltan Datos' },
        "5": { 'color': 'danger', 'text': 'Cierre Sistema' },
    };
    let tsize = `style="font-size: ${size}rem;"` ?? null;

    return `<label class="badge badge-${estadoInforme[estado]['color']}" ${tsize}>${estadoInforme[estado]['text']}</label>`;
}

function getBadgeTIncidencia(estado, size = '.7') {
    estadoInforme = {
        "1": { 'color': 'success', 'text': 'N1' },
        "2": { 'color': 'warning', 'text': 'N2' },
        "3": { 'color': 'danger', 'text': 'N3' },
    };
    let tsize = `style="font-size: ${size}rem;"` ?? null;

    return `<label class="badge badge-${estadoInforme[estado]['color']}" ${tsize}>${estadoInforme[estado]['text']}</label>`;
}

function getBadgeVisita(estado, size = null) {
    estadoInforme = {
        "0": { 'color': 'warning', 'text': 'Sin Iniciar' },
        "1": { 'color': 'primary', 'text': 'En Proceso' },
        "2": { 'color': 'success', 'text': 'Finalizado' },
        "4": { 'color': 'danger', 'text': 'Faltan Datos' },
    };
    let tsize = `style="font-size: ${size}rem;"` ?? null;

    return `<label class="badge badge-${estadoInforme[estado]['color']}" ${tsize}>${estadoInforme[estado]['text']}</label>`;
}

function getBadgeContrato(estado, size = null) {
    estadoInforme = [
        { 'color': 'danger', 'text': 'Sin Contrato' },
        { 'color': 'success', 'text': 'En Contrato' },
    ];
    let tsize = `style="font-size: ${size}rem;"` ?? null;

    return `<label class="badge badge-${estadoInforme[estado]['color']}" ${tsize}>${estadoInforme[estado]['text']}</label>`;
}

function getBadgePrioridad(estado, size = null) {
    estadoInforme = {
        "P1": { 'color': 'dark', 'text': 'P1' },
        "P2": { 'color': 'danger', 'text': 'P2' },
        "P3": { 'color': 'warning', 'text': 'P3' },
        "P4": { 'color': 'success', 'text': 'P4' },
    };
    let tsize = `style="font-size: ${size}rem;"` ?? null;

    return `<label class="badge badge-${estadoInforme[estado]['color']} me-2" ${tsize}>${estadoInforme[estado]['text']}</label>`;
}

function mostrar_acciones(table = null) {
    if (!table) return;
    const idTabla = table.table().node().id;

    const tableSelector = idTabla ? `#${idTabla}` : '';
    const dataTables_scrollBody = $(`${idTabla ? `#${idTabla}_wrapper` : '.dataTables_wrapper'} .dataTables_scrollBody`);
    let filaAccionActivo = null;
    let filaAccionOld = null;
    let openOnCkick = false;

    const animateProperty = (element, property, start, end, duration, fps, callback = null) => {
        let current = start;
        element.css((property == 'left' ? 'right' : 'left'), '');
        const totalFrames = duration / (1000 / fps);
        const delta = (end - start) / totalFrames;
        const interval = setInterval(() => {
            current += delta;
            element.css(property, `${current}px`);
            // Condición de finalización según dirección de la animación
            if ((delta > 0 && current >= end) || (delta < 0 && current <= end)) {
                clearInterval(interval);
                if (typeof callback === "function") callback();
            }
        }, 1000 / fps);
    }

    const getWidthTdAccion = (td_accion) => {
        const width_tabla = $(tableSelector)[0].clientWidth + 11;
        const width_scroll = dataTables_scrollBody[0].clientWidth;
        const width_tdAccion = td_accion[0].clientWidth;
        const width_btnGroup = td_accion.find('.dropdown i')[0].clientWidth;

        return (width_tabla - ((width_tdAccion / 2) + (width_btnGroup / 2))) < width_scroll;
    }

    const getScrollTdAccion = (td_accion) => {
        const distanciaAlFinal = dataTables_scrollBody.get(0).scrollWidth - dataTables_scrollBody.get(0).scrollLeft - dataTables_scrollBody.get(0).clientWidth;
        const anchoField = ((td_accion[0].clientWidth / 2) + (td_accion.find('.dropdown i')[0].clientWidth / 2));
        return distanciaAlFinal < anchoField;
    }

    const getBgColorRow = fila_activa => { // Extraemos los valores RGB de la fila, para eliminar cualquier opacidad
        const [r, g, b] = fila_activa.css('background-color').match(/\d+/g) || [255, 255, 255];
        return `rgb(${r}, ${g}, ${b})`;
    };

    let paginaActual = null;
    // Cuando se dibuja la tabla (draw.dt) se asocian los eventos a cada fila
    table.off("draw.dt").on('draw.dt', function () {
        let nuevaPagina = table.page();

        $("tr:has(.td-acciones)").off();
        $("tr:has(.td-acciones)").each(function () {
            const tdAcciones = $(this).find(".td-acciones");
            if (!tdAcciones.length) return;

            if (paginaActual != nuevaPagina) {
                tdAcciones.removeClass('active-acciones sticky-activo').removeAttr('style');
                filaAccionActivo = null;
                filaAccionOld = null;
            }

            let evento = esCelular() ? 'click' : 'mouseenter';
            $(this).off(evento).on(evento, function () { // Fila a la que se le dió click
                if (openOnCkick) return;

                filaAccionActivo = $(this);
                if (!esCelular()) {
                    filaAccionOld = ($("tr:has(.active-acciones)")?.length) ? $("tr:has(.active-acciones)") : $("tr:has(.dropdown-menu.show)");
                } else {
                    filaAccionOld = $("tr:has(.active-acciones)");
                }
                const newTdAccion = filaAccionActivo.find(".td-acciones");

                if (filaAccionOld?.length) {
                    const oldTdAccion = filaAccionOld.find(".td-acciones");
                    if (!newTdAccion.hasClass('active-acciones') && oldTdAccion.hasClass('active-acciones')) {
                        if (!esCelular() && oldTdAccion.find('.dropdown-menu').hasClass('show')) return;
                        animateProperty(oldTdAccion, 'right', -43, -75, 150, 60, () => {
                            oldTdAccion.removeClass('active-acciones sticky-activo').removeAttr('style');
                        });
                        filaAccionOld = null;
                    }
                }
                if (getScrollTdAccion(newTdAccion)) return;

                if (getWidthTdAccion(newTdAccion)) {
                    if (!newTdAccion.find('.dropdown-menu').hasClass('show')) {
                        newTdAccion.removeClass('active-acciones').removeAttr('style');
                    }
                    return newTdAccion.removeClass('sticky-activo');
                }

                if (newTdAccion.hasClass('active-acciones')) {
                    if (newTdAccion.find('.dropdown-menu').hasClass('show')) return false;
                    return animateProperty(newTdAccion, 'right', -43, -75, 150, 60, () => {
                        newTdAccion.removeClass('active-acciones sticky-activo').removeAttr('style');
                    });
                }
                // Se añaden cuando el scroll esta a unos pixeles menos del final 
                if (getScrollTdAccion(newTdAccion)) return;
                newTdAccion.addClass('active-acciones sticky-activo').css('background-color', getBgColorRow(filaAccionActivo));
                animateProperty(newTdAccion, 'right', -75, -43, 150, 60);
            });

            if (!esCelular()) {
                $(this).off('mouseleave').on('mouseleave', function () {
                    const newTdAccion = filaAccionActivo.find(".td-acciones");
                    if (!newTdAccion.find('.dropdown-menu').hasClass('show') && !openOnCkick) {
                        animateProperty(newTdAccion, 'right', -43, -75, 150, 60, () => {
                            newTdAccion.removeClass('active-acciones sticky-activo').removeAttr('style');
                        });
                    }
                });

                $(this).off('click').on('click', function () {
                    filaAccionActivo = $(this);
                    const newTdAccion = filaAccionActivo.find(".td-acciones");

                    if (!getScrollTdAccion(newTdAccion) && !newTdAccion.hasClass('active-acciones')) {
                        newTdAccion.addClass('active-acciones sticky-activo').css('background-color', getBgColorRow(filaAccionActivo));
                        animateProperty(newTdAccion, 'right', -75, -43, 150, 60);
                        openOnCkick = true;
                    }

                    if (filaAccionOld?.length) {
                        const oldTdAccion = filaAccionOld.find(".td-acciones");
                        animateProperty(oldTdAccion, 'right', -43, -75, 150, 60, () => {
                            oldTdAccion.removeClass('active-acciones sticky-activo').removeAttr('style');
                        });
                        filaAccionOld = null;
                    }
                    setTimeout(() => openOnCkick = false, 165);
                });
            }
        });
        paginaActual = nuevaPagina; // Actualizar la página actual
    });

    // $('.dropdown-menu').on('show.mdb.dropdown', function () {
    //     console.log($(this));
    // });

    // Evento de scroll para actualizar la clase sticky-activo
    dataTables_scrollBody.on('scroll', function () {
        try {
            if (!filaAccionActivo?.length) return;

            let filaActiva = filaAccionOld?.length ? filaAccionOld : filaAccionActivo;
            const accionTd = filaActiva.find('.td-acciones');
            if (getScrollTdAccion(accionTd)) {
                return accionTd.removeClass('active-acciones sticky-activo').removeAttr('style');
            }
            accionTd.addClass("active-acciones sticky-activo").css({ 'right': '-43px', 'background-color': getBgColorRow(filaActiva) });
        } catch (error) {
            console.log(error);
        }
    });
}

function iniciarGrafico(selector, data, type = 'doughnut') {
    const transformarData = (oldData) => {
        return {
            labels: oldData.dbody.map(item => item.titulo),
            datasets: [{
                label: "Total",
                data: oldData.dbody.map(item => item.total),
                backgroundColor: oldData.dbody.map(item => parseColor(item.color)),
                hoverOffset: 20
            }]
        };
    }

    let config = null;
    switch (type) {
        case 'doughnut':
            config = {
                type: 'doughnut',
                data: transformarData(data),
                options: {
                    responsive: true,
                    layout: {
                        padding: 20
                    },
                    plugins: {
                        legend: {
                            position: 'left',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 10,
                                padding: 10,
                                color: 'rgb(112, 112, 112)',
                                font: {
                                    weight: 'bold',
                                    size: 12
                                },
                            },
                            onHover: (event, legendItem, legend) => {
                                const chart = legend.chart;
                                const index = legendItem.index;
                                chart.setActiveElements([{ datasetIndex: 0, index: index }]);
                                chart.update();
                            },
                            onLeave: (event, legendItem, legend) => {
                                const chart = legend.chart;
                                chart.setActiveElements([]);
                                chart.update();
                            }
                        },
                        title: {
                            display: true,
                            text: data.dhead,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.chart._metasets[context.datasetIndex].total;
                                    const percentage = ((value / total) * 100).toFixed(1) + '%';
                                    return `Total: ${value} (${percentage})`; // `${label}: ${value} (${percentage})`
                                }
                            }
                        },
                        datalabels: {
                            color: 'rgba(255, 255, 255, 0)',
                        }
                    }
                },
                plugins: [ChartDataLabels]
            };            
            break;

        case 'bar':
            config = {
                type: 'bar',
                data: transformarData(data),
                options: {
                    responsive: true,
                    layout: {
                        padding: 20
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: data.dhead,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.chart._metasets[context.datasetIndex].total;
                                    const percentage = ((value / total) * 100).toFixed(1) + '%';
                                    return `Total: ${value} (${percentage})`; // `${label}: ${value} (${percentage})`
                                }
                            }
                        },
                        datalabels: {
                            color: 'rgba(255, 255, 255, 0)',
                        }
                    }
                },
                plugins: [ChartDataLabels]
            };
            break;
    }

    return new Chart($(selector), config);
}

function parseColor(color) {
    if (!color) return;
    let colores = {
        primary: '#3b71ca',
        secondary: '#9fa6b2',
        success: '#14a44d',
        danger: '#dc4c64',
        warning: '#e4a11b',
        info: '#54b4d3',
        light: '#fbfbfb',
        dark: '#332d2d',
    }

    if (colores.hasOwnProperty(color)) {
        color = colores[color];
    }
    const rgbRegex = /^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/i;
    const hexRegex = /^#([a-f\d]{3}|[a-f\d]{6})$/i;

    // Si es RGB
    const rgbMatch = color.match(rgbRegex);
    if (rgbMatch) {
        return color;
    }

    // Si es HEX
    const hexMatch = color.match(hexRegex);
    if (hexMatch) {
        let hex = hexMatch[1];
        if (hex.length === 3) { hex = hex.split('').map(c => c + c).join(''); }
        const r = parseInt(hex.slice(0, 2), 16);
        const g = parseInt(hex.slice(2, 4), 16);
        const b = parseInt(hex.slice(4, 6), 16);
        return `rgb(${r}, ${g}, ${b})`;
    }

    throw new Error('Formato de color no reconocido');
}


function fObservador(selector, callback) {
    if (typeof selector !== 'string') return null;

    let contenedor = null;
    if (selector.startsWith('.')) {
        contenedor = document.querySelector(selector);
    } else if (selector.startsWith('#')) {
        contenedor = document.getElementById(selector);
    } else {
        return null;
    }

    const observer = new ResizeObserver(entries => {
        if (typeof callback === "function") callback();
    });
    observer.observe(contenedor);
}

function esCelular() {
    return /Android|iPhone|iPad|iPod|Windows Phone/i.test(navigator.userAgent);
}