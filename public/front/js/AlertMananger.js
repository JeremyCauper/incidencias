class AlertMananger {
    constructor() {
        //this.Swal = new Swal();
    }

    loading() {
        Swal.fire({
            title: '<div class="cargando"></div><br><h5 style="color: #039BE5"><b>PROCESANDO LOS BACKUPS</b></h5>',
            text: 'Procesando el Backup, espere por favor!',
            html: `
            <div style="max-height: 300px; overflow-y: auto; text-align: left;" id="contetLoading">
            </div>
            `,
            confirmButtonColor: "#DC4C64",
            confirmButtonText: "Cerrar",
            allowOutsideClick: false,
            showCloseButton: true
        });
    }

    setLoading(anio, mes, op = {}) {
        const iD = `loading${anio}${mes}`;
        if ($(`#${iD}`).length) {
            const text = `<b>Backup ${op.tipo}:</b> Generado en ${op.tiempo}`;
            $(`#${iD} ul .${op.tipo}`)[0].innerHTML = text;
        } else {
            $('#contetLoading').prepend(`
                <span class="mb-3" id="${iD}">
                    <h6 class="text-info m-0"><b>${anio}-${mes}</b></h6>
                    <ul>
                        <li style="font-size: 1rem;" class="recibos"><b>Backup recibos:</b> <span class="dots"></span></li>
                        <li style="font-size: 1rem;" class="items"><b>Backup items  :</b> <span class="dots"></span></li>
                    </ul>
                </span>`);
        }
    }


    async confirm(message) {
        if (!(await Swal.fire({
            title: message,
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
            title: `<h4 class="card-title text-secondary"><b>${title}</b></h4>`,
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
            title: title,
            ...op
        });
    }
}

var boxAlert = new AlertMananger();


// alertMananger.loading();
// alertMananger.setLoading('2018', 'Enero', '', '');