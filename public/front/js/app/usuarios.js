$(document).ready(function () {
    $("#fechan_usu").flatpickr({
        maxDate: __fecha
    });
});

const tb_usuario = new DataTable('#tb_usuario', {
    scrollX: true,
    scrollY: 300,
    ajax: {
        url: `${__url}/usuarios/datatable`,
        dataSrc: "",
        error: function (xhr, error, thrown) {
            console.log('Error en la solicitud Ajax:', error);
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'ndoc_usuario' },
        {
            data: 'nombres', render: function (data, type, row) {
                return `${row.nombres} ${row.apellidos}`;
            }
        },
        { data: 'descripcion' },
        { data: 'usuario' },
        { data: 'pass_view' },
        { data: 'estatus' },
        { data: 'id_usuario' }
    ],
    processing: true
});

function updateTable() {
    tb_usuario.ajax.reload();
}

document.getElementById('form-usuario').addEventListener('submit', function (event) {
    event.preventDefault();
    $('#form-usuario .modal-dialog .modal-content').append(`<div class="loader-of-modal" style="position: absolute;height: 100%;width: 100%;z-index: 999;background: #dadada60;border-radius: inherit;align-content: center;"><div class="loader"></div></div>`);

    var elementos = this.querySelectorAll('[name]');
    var datosFormulario = {};

    let cad_require = "";
    elementos.forEach(function (elemento) {
        if (elemento.getAttribute("require") && elemento.value == "") {
            cad_require += `<b>${elemento.getAttribute("require")}</b>, `;
        }
        datosFormulario[elemento.name] = elemento.value;
    });
    if (cad_require) {
        $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
        return boxAlert.box('info', 'Faltan datos', `<h6 class="text-secondary">El campo ${cad_require} es requerido.</h6>`);
    }

    url = [
        `/usuarios/create`, `/usuarios/edit/${$('#form-usuario').attr('idu')}`
    ];
    $.ajax({
        type: 'POST',
        url: __url + url[$('#form-usuario').attr('frm-accion')],
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(datosFormulario),
        success: function (response) {
            boxAlert.minbox('success', response.message, { background: "#3b71ca", color: "#ffffff" }, "top");
            updateTable();
            $('[data-mdb-dismiss="modal"]').click();
            console.log(response);
            $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            boxAlert.box('error', '¡Ocurrio un error!', 'Error al registrar el usuario');
            console.log(jqXHR.responseJSON);
            $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
});

$('.modal').on('hidden.bs.modal', function () {
    $('#form-usuario').attr('idu', '').attr('frm-accion', '0');
});

document.querySelectorAll('.inputMenu').forEach(toggle => {
    toggle.addEventListener('change', function () {
        const checkboxes = this.closest('.treeview').querySelectorAll('.inputSubMenu');
        checkboxes.forEach(checkbox => {
            checkbox.disabled = !this.checked;
            checkbox.checked = false;
        });
    });
});

function showUsuario(id) {
    $('#modal_frm_usuarios').modal('show');
    $('#form-usuario .modal-dialog .modal-content').append(`<div class="loader-of-modal" style="position: absolute;height: 100%;width: 100%;z-index: 999;background: #dadada60;border-radius: inherit;align-content: center;"><div class="loader"></div></div>`);
    const urlImg = {
        'perfil': `${__asset}/images/auth`,
        'firma': `${__asset}/images/firms`
    };
    $.ajax({
        type: 'GET',
        url: `${__url}/usuarios/show/${id}`,
        contentType: 'application/json',
        success: function (response) {
            console.log(response);
            const data = response[0];
            $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
            $('#form-usuario').attr('idu', id).attr('frm-accion', '1');
            $('#id_area').val(data.id_area).trigger('change.select2');
            $('#n_doc').val(data.ndoc_usuario);
            $('#nom_usu').val(data.nombres);
            $('#ape_usu').val(data.apellidos);
            $('#emailp_usu').val(data.email_personal);
            $('#emailc_usu').val(data.email_corporativo);
            $('#fechan_usu').val(data.fecha_nacimiento);
            $('#telp_usu').val(data.tel_personal);
            $('#telc_usu').val(data.tel_corporativo);
            $('#usuario').val(data.usuario);
            $('#contrasena').val(data.pass_view);
            if (data.foto_perfil) $('#PreviFPerfil').attr('src', `${urlImg['perfil']}/${data.foto_perfil}`);
            if (data.firma_digital) $('#PreviFirma').attr('src', `${urlImg['firma']}/${data.firma_digital}`);
            $('#tipo_acceso').val(data.tipo_acceso).trigger('change.select2');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error al registrar el usuario');
            console.log(jqXHR.responseJSON);
        }
    });
}


document.getElementById('conDoc').addEventListener('click', function () {
    const nDoc = document.getElementById('n_doc').value;
    $.ajax({
        type: 'GET',
        url: `${__url}/consultaDni/${nDoc}`,
        contentType: 'application/json',
        success: function (response) {
            if (!response.success) {
                return Swal.fire({
                    'title': 'Ocurrio un error',
                    'icon': 'info',
                    'text': response.message
                });
            }
            $('#nom_usu').val(response.data.nombres);
            $('#ape_usu').val(`${response.data.apellidop} ${response.data.apellidom}`);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error al registrar el usuario');
            console.log(jqXHR.responseJSON);
        }
    });
});


const fileInput = document.getElementById('foto_perfil');
const removeButton = document.getElementById('removeButton');
const PreviFPerfil = document.getElementById('PreviFPerfil');
const txtFotoPerfil = document.getElementById('txtFotoPerfil');

document.getElementById('uploadButton').addEventListener('click', () => {
    fileInput.click();
});

fileInput.addEventListener('change', function (event) {
    const file = event.target.files[0];
    const maxFileSize = 20 * 1024 * 1024; // 20MB
    if (file) {
        if (file.size > maxFileSize) {
            alert('El archivo debe ser menor a 20MB');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            PreviFPerfil.src = e.target.result;
            PreviFPerfil.alt = file.name;
            removeButton.style.display = 'block';
            document.getElementById('txtFotoPerfil').value = btoa(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

removeButton.addEventListener('click', () => {
    PreviFPerfil.src = PreviFPerfil.getAttribute("imagedefault");
    txtFotoPerfil.value = '';
    removeButton.style.display = 'none';
    fileInput.value = '';
});


const fileInputFirma = document.getElementById('firma_digital');
const PreviFirma = document.getElementById('PreviFirma');
const removeImgFirma = document.getElementById('removeImgFirma');
const textFirmaDigital = document.getElementById('textFirmaDigital');

document.getElementById('uploadImgFirma').addEventListener('click', () => {
    fileInputFirma.click();
});

fileInputFirma.addEventListener('change', function (event) {
    const file = event.target.files[0];
    const maxFileSize = 10 * 1024 * 1024; // 10MB
    if (file) {
        if (file.size > maxFileSize) {
            alert('El archivo debe ser menor a 10MB');
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            PreviFirma.src = e.target.result;
            PreviFirma.alt = file.name;
            removeImgFirma.style.display = 'block';
            textFirmaDigital.value = btoa(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('createFirma').addEventListener('click', async () => {
    Swal.fire({
        title: '<h6 class="text-primary">CREAR FIRMA DIGITAL</h6>',
        html: `
            <div>
                <div class="content-signature-pad">
                    <canvas id="signature-pad" width="400" height="250" style="border: 2px dashed #ccc;"></canvas>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary btn-sm" id="save">Guardar</button>
                    <button class="btn btn-danger btn-sm" id="clear">Limpiar</button>
                    <button class="btn btn-info btn-sm" onclick="Swal.close()">Cerrar</button>
                </div>
            </div>`,
        showConfirmButton: false
    });
    resizeWindow();

    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    document.getElementById('clear').addEventListener('click', function () {
        signaturePad.clear();
    });

    document.getElementById('save').addEventListener('click', function () {
        if (signaturePad.isEmpty()) {
            alert("Por favor, dibuja una firma primero.");
        } else {
            var dataURL = signaturePad.toDataURL();
            document.getElementById('textFirmaDigital').value = btoa(dataURL.toString());
            document.getElementById('PreviFirma').src = dataURL.toString();
            removeImgFirma.style.display = 'block';
            Swal.close();
        }
    });
});

window.addEventListener('resize', resizeWindow);
function resizeWindow() {
    var canvas = document.getElementById('signature-pad');
    if (window.matchMedia('(max-width: 545px)').matches) {
        canvas.width = 300;
        canvas.height = 175;
    }
    else {
        canvas.width = 400;
        canvas.height = 250;
    }
    if (window.matchMedia('(max-width: 382px)').matches) {
        canvas.width = 200;
        canvas.height = 140;
    }
}


removeImgFirma.addEventListener('click', () => {
    PreviFirma.src = PreviFirma.getAttribute("imagedefault");
    textFirmaDigital.value = '';
    removeImgFirma.style.display = 'none';
    fileInputFirma.value = '';
});

function PreviImagenes(data) {
    Swal.fire({
        title: '<h5 class="card-title text-linkedin">PREVISUALIZACIÓN DE LA IMAGEN CARGADA</h5>',
        html: `<div>
                <img src="${data}" />
            </div>`
    });
}