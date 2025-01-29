$(document).ready(function () {
    const controles = [
        // Formulario grupos
        {
            control: '#id_area',
            config: {
                require: true
            }
        },
        {
            control: '#n_doc',
            config: {
                "control-type": "int",
                mnl: 8,
                mxl: 20,
                require: true,
                errorMessage: "El numero de DNI es invalido.",
                mask: { reg: "99999999999" }
            }
        },
        {
            control: ['#nom_usu', '#ape_usu'],
            config: {
                mxl: 100,
                require: true
            }
        },
        {
            control: ['#emailp_usu', '#emailc_usu'],
            config: {
                "control-type": "email",
                mxl: 250
            }
        },
        {
            control: '#fechan_usu',
            config: {
                require: true
            }
        },
        {
            control: ['#telp_usu', '#telc_usu'],
            config: {
                "control-type": "int",
                mxl: 9,
                mask: { reg: "999999999" }
            }
        },
        {
            control: ['#usuario', '#contrasena'],
            config: {
                mxl: 50,
                require: true
            }
        },
        {
            control: '#tipo_acceso',
            config: {
                require: true
            }
        }
    ];

    controles.forEach(control => {
        defineControllerAttributes(control.control, control.config);
    });

    $('.modal').on('hidden.bs.modal', function () {
        $('#modal_usuariosLabel').html('REGISTRAR USUARIO');
        $('#id').val('');
    });

    $("#fechan_usu").flatpickr({
        maxDate: date('Y-m-d')
    });

    $('#n_doc').blur(async function () {
        let datos = await consultarDniInput($(this));
        if (datos.success) {
            $('#nom_usu').val(datos.data.nombres);
            $('#ape_usu').val(`${datos.data.apellidop} ${datos.data.apellidom}`);
        }
    });
});

const tb_usuario = new DataTable('#tb_usuario', {
    scrollX: true,
    scrollY: 300,
    ajax: {
        url: `${__url}/control-de-usuario/usuarios/index`,
        dataSrc: "",
        error: function (xhr, error, thrown) {
            boxAlert.table();
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
        { data: 'estado' },
        { data: 'acciones' }
    ],
    processing: true
});

function updateTable() {
    tb_usuario.ajax.reload();
}

document.getElementById('form-usuario').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_usuarios', 'show');
    const accion = $('#id').val();
    const url = accion ? `actualizar` : `registrar`;

    var elementos = this.querySelectorAll('[name]');
    var valid = validFrom(elementos);

    if (!valid.success)
        return fMananger.formModalLoding('modal_usuarios', 'hide');

    $.ajax({
        type: 'POST',
        url: `${__url}/control-de-usuario/usuarios/${url}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_usuarios', 'hide');
            if (!data.success) {
                return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
            }
            $('#modal_usuarios').modal('hide');
            boxAlert.minbox({ h: data.message });
            updateTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: 'Parece que hubo un problema en el servidor. Estamos trabajando para solucionarlo lo antes posible. Por favor, intenta de nuevo más tarde. Si el problema persiste, contacta con el soporte.' });
            console.log(jqXHR);
            fMananger.formModalLoding('modal_usuarios', 'hide');
        }
    });
});

function Editar(id) {
    try {
        $('#modal_usuariosLabel').html('EDITAR GRUPO');
        $('#modal_usuarios').modal('show');
        fMananger.formModalLoding('modal_usuarios', 'show');
        const urlImg = {
            'perfil': `${__asset}/images/auth`,
            'firma': `${__asset}/images/firms`
        };
        $.ajax({
            type: 'GET',
            url: `${__url}/control-de-usuario/usuarios/${id}`,
            contentType: 'application/json',
            success: function (data) {
                if (!data.success) {
                    console.log(data.error);
                    return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
                }
                var json = data.data;
                $('#id').val(id);
                $('#id_area').val(json.id_area).trigger('change.select2');
                $('#n_doc').val(json.ndoc_usuario);
                $('#nom_usu').val(json.nombres);
                $('#ape_usu').val(json.apellidos);
                $('#emailp_usu').val(json.email_personal);
                $('#emailc_usu').val(json.email_corporativo);
                $('#fechan_usu').val(json.fecha_nacimiento);
                $('#telp_usu').val(json.tel_personal);
                $('#telc_usu').val(json.tel_corporativo);
                $('#usuario').val(json.usuario);
                $('#contrasena').val(json.pass_view);
                $('#PreviFPerfil').attr('src', json.foto_perfil ? `${urlImg['perfil']}/${json.foto_perfil}`: imgUserDefault);
                $('#PreviFirma').attr('src', json.firma_digital ? `${urlImg['firma']}/${json.firma_digital}`: imgFirmDefault);
                $('#tipo_acceso').val(json.tipo_acceso).trigger('change.select2');

                fMananger.formModalLoding('modal_usuarios', 'hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: 'Parece que hubo un problema en el servidor. Estamos trabajando para solucionarlo lo antes posible. Por favor, intenta de nuevo más tarde. Si el problema persiste, contacta con el soporte' });
                console.log(jqXHR);
            }
        });
    } catch (error) {
        boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: 'Parece que hubo un problema en el servidor. Estamos trabajando para solucionarlo lo antes posible. Por favor, intenta de nuevo más tarde. Si el problema persiste, contacta con el soporte' });
        console.log('Error producido: ', error);
    }
}

async function CambiarEstado(id, estado) {
    try {
        if (!await boxAlert.confirm('¿Esta seguro de esta accion?')) return true;

        $.ajax({
            type: 'POST',
            url: `${__url}/control-de-usuario/usuarios/cambiarEstado`,
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': __token,
            },
            data: JSON.stringify({
                "id": id,
                "estatus": estado ? 0 : 1
            }),
            beforeSend: boxAlert.loading,
            success: function (data) {
                if (!data.success) {
                    console.log(data.error);
                    return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
                }
                boxAlert.minbox({ h: data.message });
                updateTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                const obj_error = jqXHR.responseJSON;
                boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: obj_error.message });
                console.log(jqXHR);
            }
        });
    } catch (error) {
        boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: 'Parece que hubo un problema en el servidor. Estamos trabajando para solucionarlo lo antes posible. Por favor, intenta de nuevo más tarde. Si el problema persiste, contacta con el soporte' });
        console.log('Error producido: ', error);
    }
}

document.querySelectorAll('.inputMenu').forEach(toggle => {
    toggle.addEventListener('change', function () {
        const checkboxes = this.closest('.treeview').querySelectorAll('.inputSubMenu');
        checkboxes.forEach(checkbox => {
            checkbox.disabled = !this.checked;
            checkbox.checked = false;
        });
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