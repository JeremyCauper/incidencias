function setInfoEmpresas(modal, ruc_empresa, id_sucursal, dataSet) {
    // 
}

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

function getBadgeIncidencia(estado) {
    estadoInforme = {
        "0": { 'color': 'warning', 'text': 'Sin Asignar' },
        "1": { 'color': 'info', 'text': 'Asignada' },
        "2": { 'color': 'primary', 'text': 'En Proceso' },
        "3": { 'color': 'success', 'text': 'Finalizado' },
        "4": { 'color': 'danger', 'text': 'Faltan Datos' },
        "5": { 'color': 'danger', 'text': 'Cierre Sistema' },
    };

    return `<label class="badge badge-${estadoInforme[estado]['color']}" style="font-size: .7rem;">${estadoInforme[estado]['text']}</label>`;
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

        if (rect.right <= $(window).width() - 43) {
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