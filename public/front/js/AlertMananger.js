class AlertMananger {
    constructor() {
        //this.Swal = new Swal();
    }

    loading() {
        Swal.fire({
            title: '<div class="my-5"><div class="gear"><div><label></label><span></span><span></span><span></span><span></span></div></div></div>',
            text: 'Realizando los cambios, por favor espere',
            allowOutsideClick: false,
            showConfirmButton: false,
            showClass: {
                popup: `
                  animate__animated
                  animate__fadeIn
                  animate__faster
                `
            },
            hideClass: {
                popup: `
                  animate__animated
                  animate__fadeOut
                  animate__faster
                `
            }
        });
    }

    async confirm(message) {
        if (!(await Swal.fire({
            title: `<h6 style="color: #a4bcc5;">${message}</h6>`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Sí!",
            cancelButtonText: "Cancelar",
            showClass: {
                popup: `
                  animate__animated
                  animate__fadeInDown
                  animate__faster
                `
            },
            hideClass: {
                popup: `
                  animate__animated
                  animate__fadeOutDown
                  animate__faster
                `
            }
        })).isConfirmed)
            return false;
        return true;
    }

    box(op = {}) {
        const icono = op.i || 'success';
        const titulo = op.t || 'Titulo de prueba';
        const thtml = op.h || false;
        delete op.i;
        delete op.t;
        delete op.h;
        Swal.fire({
            icon: icono,
            title: `<h5 class="card-title text-secondary"><b>${titulo}</b></h5>`,
            html: thtml,
            ...op,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar",
            showClass: {
                popup: `
                  animate__animated
                  animate__fadeIn
                  animate__faster
                `
            },
            hideClass: {
                popup: `
                  animate__animated
                  animate__fadeOut
                  animate__faster
                `
            }
        });
    }

    minbox(op={}) {
        const icono = op.i || 'success';
        const thtml = op.h || false;
        const bground = op.b || "#5e87ca";
        const color = op.c || "#ffffff";
        const position = op.p || "top";
        delete op.i;
        delete op.h;
        delete op.b;
        delete op.c;
        delete op.p;

        const Toast = Swal.mixin({
            toast: true,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
            showClass: {
                popup: `
                  animate__animated
                  animate__fadeInDownBig
                  animate__faster
                `
            },
            hideClass: {
                popup: `
                  animate__animated
                  animate__fadeOutUp
                  animate__faster
                `
            }
        });
        Toast.fire({
            icon: icono,
            html: thtml,
            position: position || "top-end",
            background: bground,
            color: color,
            ...op
        });
    }
}

var boxAlert = new AlertMananger();


// alertMananger.loading();
// alertMananger.setLoading('2018', 'Enero', '', '');