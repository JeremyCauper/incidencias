function fillSelect(selector, data, filterField, filterValue, optionValue, optionText) {
    $(selector.join()).html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
    if (!filterValue) return false;

    if (Array.isArray(data)) {
        data.forEach(e => {
            if (e[filterField] == filterValue)
                $(selector[0]).append($('<option>').val(e[optionValue]).text(e[optionText]));
        });
    } else if (typeof data === 'object') {
        Object.entries(data).forEach(([key, e]) => {
            if (e[filterField] == filterValue)
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

        let controllerRegistry = {
            name: config.name || idString,
            type: config.type || false,
            "con-type": config.name || false,
            require: config.require || false,
            minLength: config.mnl || false,
            maxLength: config.mxl || false,
            placeholder: config.pholder || "",
            errorMessage: config.errorMessage || "Error en el controlador.",
            lengthMessage: config.lengthMessage || `El campo '${label.html()}' debe tener entre ${config.mnl || 0} y ${config.mxl || 0} dígitos.`,
        };

        for (let [key, value] of Object.entries(controllerRegistry)) {
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


/*if (!controllerRegistry[controllerId]) {
    // Crear un controlador vacío con los atributos definidos
    controllerRegistry[controllerId] = {
        name: config.name || null,
        required: config.required || false,
        minLength: config.minLength || 0,
        maxLength: config.maxLength || Infinity,
        errorMessage: config.errorMessage || "Error en el controlador.",
        lengthMessage:
            config.lengthMessage || "La longitud debe estar en el rango permitido.",
    };
    console.log(`Controlador '${controllerId}' configurado con éxito.`);
} else {
    console.warn(`El controlador '${controllerId}' ya está configurado.`);
}*/

function validFrom(dat) {
    var dataF = { success: true, data: { data: {}, require: "" } };

    dat.forEach(function (e) {
        if (e.getAttribute("require") && e.value == "") {
            dataF.data['require'] += `<li><b>${e.getAttribute("require")}</b></li>`;
        }
        dataF.data.data[e.name] = e.value;
    });
    if (dataF.data['require']) {
        dataF.success = false;
        boxAlert.box({
            i: 'info',
            t: 'Faltan datos',
            h: `<h6 class="text-secondary">Los siguientes campos son requeridos</h6>
                <ul style="font-size:.75rem;">${dataF.data['require']}</ul>`
        });
    }
    return dataF;
}

function extractDataRow($this) {
    const ths = document.querySelectorAll('.dataTables_scrollHeadInner table thead tr th');
    const tr = $this.parentNode.parentNode.parentNode.parentNode;
    const tds = tr.querySelectorAll('td');
    var obj_return = {};

    ths.forEach(function (e, i) {
        if (i != 9) obj_return[(e.innerHTML).toLowerCase()] = tds[i].innerHTML;
    });

    return obj_return;
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