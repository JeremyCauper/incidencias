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