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

function animateProperty(element, property, start, end, duration, fps, callback) {
    let current = start;
    const totalFrames = duration / (1000 / fps);
    const delta = (end - start) / totalFrames;
    const interval = setInterval(() => {
        current += delta;
        element.style[property] = `${current}px`;
        // Condición de finalización según dirección de la animación
        if ((delta > 0 && current >= end) || (delta < 0 && current <= end)) {
            clearInterval(interval);
            if (typeof callback === "function") callback();
        }
    }, 1000 / fps);
}

function mostrar_acciones(table = null) {
    const tableSelector = table ? `#${table}` : '';
    // Determina el contenedor en base a si se pasa o no un id de tabla
    const wrapperSelector = table ? `#${table}_wrapper` : '.dataTables_wrapper';

    // Cuando se dibuja la tabla (draw.dt) se asocian los eventos a cada fila
    $(tableSelector).off("draw.dt").on('draw.dt', function () {
        $("tr:has(.td-acciones)").each(function () {
            const $fila = $(this);
            $fila.find(".td-acciones").removeAttr("style").removeClass('active-acciones').removeClass('sticky-activo');
            const accionesTd = this.querySelector(".td-acciones");

            if (!accionesTd) return;

            $fila.off("click").on("click", function () {
                $activoTd = $(accionesTd);
                if($(tableSelector).width() - ($activoTd.width() / 2) < $(`${wrapperSelector} .dataTables_scroll`).width()) return;
                
                $td_activo = $("tr .td-acciones.active-acciones");

                if (!$activoTd.hasClass('active-acciones') && $td_activo.hasClass('active-acciones')) {
                    animateProperty($td_activo[0], "right", -43, -75, 150, 60, () => {
                        $td_activo[0].classList.remove("active-acciones");
                        $td_activo[0].classList.remove("sticky-activo");
                        $td_activo[0].removeAttribute("style");
                    });
                }

                if ($activoTd.hasClass('active-acciones')) {
                    $button = $activoTd.find('.btn-group button[type="button"]');
                    if (!$button.hasClass('show')) {
                        return animateProperty(accionesTd, "right", -43, -75, 150, 60, () => {
                            accionesTd.classList.remove("active-acciones");
                            accionesTd.classList.remove("sticky-activo");
                            accionesTd.removeAttribute("style");
                        });
                    } else {
                        return false;
                    }
                }

                const bgColor = $fila.css('background-color');
                // Extraemos los valores RGB para eliminar cualquier opacidad
                const valores = bgColor.match(/\d+/g);
                const nuevoColor = `rgb(${valores[0]}, ${valores[1]}, ${valores[2]})`;

                accionesTd.classList.add("active-acciones");
                let rect = $activoTd[0].getBoundingClientRect();
                if (rect.right >= $(window).width() - 43) {
                    $activoTd.addClass("sticky-activo");
                }
                accionesTd.setAttribute("style", `background-color: ${nuevoColor};`);

                // Animación: de -75 a -40 en 200ms a 60 fps
                animateProperty(accionesTd, "right", -75, -43, 150, 60);
            });
        });
    });

    // Evento de scroll para actualizar la clase sticky-activo
    $(`${wrapperSelector} .dataTables_scrollBody`).on("scroll", function () {
        let $accionTd = $(this).find('tr .td-acciones.active-acciones');
        if (!$accionTd.length) return;
        let rect = $accionTd[0].getBoundingClientRect();

        if (rect.right <= $(window).width() - ($accionTd.width() / 2 )) {
            $accionTd.removeClass("sticky-activo");
        } else if (!$accionTd.hasClass('sticky-activo')) {
            $accionTd.addClass("sticky-activo");
        }
    });
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