class FormMananger {
    constructor(parameters) {

    }

    formModalLoding(id, accion) {
        const modalf = $(`#${id} .modal-dialog .modal-content`);
        switch (accion) {
            case 'show':
                modalf.append(`<div class="loader-of-modal"><div style="display:flex; justify-content:center;"><div class="loader"></div></div></div>`);
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

    extractDataRow($this) {
        const ths = document.querySelectorAll('.dataTables_scrollHeadInner table thead tr th');
        const tr = $this.parentNode.parentNode.parentNode.parentNode;
        const tds = tr.querySelectorAll('td');
        var obj_return = {};
    
        ths.forEach(function (e, i) {
            if (i != 9) obj_return[(e.innerHTML).toLowerCase()] = tds[i].innerHTML;
        });
    
        return obj_return;
    }
}

var fMananger = new FormMananger();