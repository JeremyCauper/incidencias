class AlertMananger {
    constructor() {
        //this.Swal = new Swal();
    }

    loading() {
        Swal.fire({
            title: '<div class="my-5"><div class="gear"><div><label></label><span></span><span></span><span></span><span></span></div></div></div>',
            text: 'Realizando los cambios, por favor espere',
            allowOutsideClick: false,
            showConfirmButton: false
        });
    }

    async confirm(message) {
        if (!(await Swal.fire({
            title: `<h6 class="text-info"><b>${message}</b></h6>`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Sí!",
            cancelButtonText: "Cancelar"
        })).isConfirmed)
            return false;
        return true;
    }

    box(icon, title, text, op = {}) {
        Swal.fire({
            icon: icon,
            title: `<h5 class="card-title text-secondary"><b>${title}</b></h5>`,
            html: text,
            ...op,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar"
        });
    }

    minbox(icon, title, op={}, position) {
        const Toast = Swal.mixin({
            toast: true,
            position: position || "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: icon,
            html: title,
            ...op
        });
    }
}

var boxAlert = new AlertMananger();


// alertMananger.loading();
// alertMananger.setLoading('2018', 'Enero', '', '');