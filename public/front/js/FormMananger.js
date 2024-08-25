class FormMananger {
    constructor(parameters) {

    }

    formModalLoding(id, accion) {
        const modalf = $(`#${id} .modal-dialog .modal-content`);
        switch (accion) {
            case 'show':
                modalf.append(`<div class="loader-of-modal"><div class="gear"><div><label></label><span></span><span></span><span></span><span></span></div></div></div>`);
                break;

            case 'hide':
                modalf.children('.loader-of-modal').remove();
                break;

            default:
                break;
        }
    }

    validFrom(dat) {
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
}

var fMananger = new FormMananger();