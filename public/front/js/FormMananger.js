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
}

var fMananger = new FormMananger();