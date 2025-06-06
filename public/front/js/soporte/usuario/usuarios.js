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
            config: {}
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
        setCheckedFromJson('eyI4IjpbIjExIiwiMTIiXX0=');
        $('#tipo_acceso').val(3).trigger('change.select2');
        // $('#content-permisos').attr({ 'style': 'opacity: .55; pointer-events: none;' });
        $('#id').val('');
    });

    $("#fechan_usu").flatpickr({
        maxDate: date('Y-m-d')
    });

    $('#n_doc').blur(async function () {
        let datos = await consultarDniInput($(this));
        if (datos.success) {
            $('#nom_usu').val(datos.data.Nombres);
            $('#ape_usu').val(`${datos.data.ApePaterno} ${datos.data.ApeMaterno}`);
        }
    });

    $('#tipo_acceso').on('change', function () {
        let permisos = 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiNCI6WyIzIiwiNCIsIjUiXSwiNSI6WyI2Il0sIjYiOlsiNyIsIjgiXX0=';
        // $('#content-permisos').attr({ 'style': 'opacity: .55; pointer-events: none;' });
        switch ($(this).val()) {
            case "3":
                permisos = 'eyI4IjpbIjExIiwiMTIiXX0=';
                break;

            case "4":
                permisos = null;
                break;

            // case "5":
            //     permisos = null;
            //     // $('#content-permisos').removeAttr('style');
            //     break;

            default:
                break;
        }
        setCheckedFromJson(permisos);
    });
    setCheckedFromJson('eyI4IjpbIjExIiwiMTIiXX0=');

    fObservador('.content-wrapper', () => {
        tb_usuario.columns.adjust().draw();
    });
});

function updateTable() {
    tb_usuario.ajax.reload();
}
mostrar_acciones(tb_usuario);

document.getElementById('form-usuario').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_usuarios', 'show');

    var valid = validFrom(this);
    let permisos = getCheckedValues();

    if (!valid.success || !permisos)
        return fMananger.formModalLoding('modal_usuarios', 'hide');
    valid.data.data.permisos = permisos;

    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/control-de-usuario/usuarios/${ $('#id').val() ? 'actualizar' : 'registrar' }`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            $('#modal_usuarios').modal('hide');
            boxAlert.box({ i: data.icon, t: data.title, h: data.message })
            updateTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let message = datae.message;
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    message = formatRequired(datae.required);
                }
                if (datae.hasOwnProperty('unique')) {
                    message = formatUnique(datae.unique);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_usuarios', 'hide');
        }
    });
});

function Editar(id) {
    try {
        $('#modal_usuariosLabel').html('EDITAR GRUPO');
        $('#modal_usuarios').modal('show');
        fMananger.formModalLoding('modal_usuarios', 'show');
        $.ajax({
            type: 'GET',
            url: `${__url}/soporte/control-de-usuario/usuarios/${id}`,
            contentType: 'application/json',
            success: function (data) {
                if (!data.success) {
                    return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
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
                $('#PreviFPerfil').attr('src', json.foto_perfil ? `${__asset}/images/auth/${json.foto_perfil}` : imgUserDefault);
                $('#PreviFirma').attr('src', json.firma_digital ? `${__asset}/images/firms/${json.firma_digital}` : imgFirmDefault);
                $('#tipo_acceso').val(json.tipo_acceso).trigger('change.select2');
                /*if (json.tipo_acceso == 4 || json.tipo_acceso == 5) {
                    $('#content-permisos').removeAttr('style');
                } else {
                    $('#content-permisos').attr({ 'style': 'opacity: .55; pointer-events: none;' });
                }*/
                setCheckedFromJson(json.menu_usuario);

                fMananger.formModalLoding('modal_usuarios', 'hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                const datae = jqXHR.responseJSON;
                boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
            }
        });
    } catch (error) {
        boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: 'Ocurrio un inconveniente en el proceso de busqueda, intentelo nuevamente.' });
        console.log('Error producido: ', error);
    }
}

async function CambiarEstado(id, estado) {
    try {
        if (!await boxAlert.confirm({ h: `Esta apunto de ${estado ? 'des' : ''}activar el usuario.` })) return true;

        $.ajax({
            type: 'POST',
            url: `${__url}/soporte/control-de-usuario/usuarios/cambiarEstado`,
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
                    return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
                }
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
                updateTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                datae = jqXHR.responseJSON;
                boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
            }
        });
    } catch (error) {
        boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: 'Hubo un problema en el servidor. Estamos trabajando para solucionarlo lo antes posible. Por favor, intenta de nuevo más tarde.' });
        console.log('Error producido: ', error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Función para actualizar la “línea” (cambiando la clase del li)
    function updateLine(li, isChecked) {
        if (isChecked) {
            li.classList.add('active');
        } else {
            li.classList.remove('active');
        }
    }

    /* 
      1. Si se marca un menú (checkbox con clase "parent"):
         - Si tiene hijos (una lista interna), se marcan o desmarcan todos los ítems.
         - Se actualiza la línea del li correspondiente.
    */
    document.querySelectorAll('.tree > .parent > input').forEach(function (parentCheckbox) {
        parentCheckbox.addEventListener('change', function () {
            const li = parentCheckbox.parentElement;
            // Si el menú tiene hijos:
            const children = li.querySelectorAll('ul li input[type="checkbox"]');
            if (children.length > 0) {
                children.forEach(function (childCheckbox) {
                    childCheckbox.checked = parentCheckbox.checked;
                    updateLine(childCheckbox.closest('li'), childCheckbox.checked);
                });
            }
            updateLine(li, parentCheckbox.checked);
        });
    });

    /* 
      2. Al marcar o desmarcar un ítem (checkbox con clase "child"):
         - Se actualiza la línea del li del ítem.
         - Si se marca el ítem, se marca automáticamente el menú padre.
         - Si se desmarca, se verifica si quedan otros ítems marcados para mantener o quitar el check del menú.
    */
    document.querySelectorAll('.tree li ul .child input').forEach(function (childCheckbox) {
        childCheckbox.addEventListener('change', function () {
            const li = childCheckbox.closest('li');

            updateLine(li, childCheckbox.checked);
            // Obtener el menú padre (checkbox del li que contiene la lista)
            const parentLi = childCheckbox.closest('ul').parentElement;
            const parentCheckbox = parentLi.querySelector('.parent input');

            if (childCheckbox.checked) {
                parentCheckbox.checked = true;
            } else {
                // Verificar si hay algún otro ítem marcado en el mismo grupo
                const siblings = childCheckbox.closest('ul').querySelectorAll('input');
                let anyChecked = false;
                siblings.forEach(function (sibling) {
                    if (sibling.checked) {
                        anyChecked = true;
                    }
                });
                if (!anyChecked) {
                    parentCheckbox.checked = false;
                }
            }
        });
    });
});

function getCheckedValues() {
    let result = {};

    let contenedor = document.getElementById('content-permisos');
    // Recorre cada menú padre
    contenedor.querySelectorAll('.tree .parent > input[type="checkbox"]').forEach(parentCheckbox => {
        let menuValue = parentCheckbox.value;
        let parentLi = parentCheckbox.closest('li');
        let children = parentLi.querySelectorAll('.child input[type="checkbox"]:checked');

        if (children.length > 0) {
            // Si hay hijos seleccionados, los agregamos al JSON
            result[menuValue] = Array.from(children).map(child => child.value);
        } else if (parentCheckbox.checked) {
            // Si no hay hijos pero el padre está marcado, se agrega solo
            result[menuValue] = [];
        }
    });
    if (!Object.keys(result).length) {
        boxAlert.box({ i: 'warning', t: 'Permiso Invalido', h: "Tiene que seleccionar aunque sea un modulo" });
        return false;
    }
    return window.btoa(JSON.stringify(result));
}

function setCheckedFromJson(jsonString = null) {
    if (!jsonString) {
        jsonString = 'e30=';
    }
    let jsonData = JSON.parse(window.atob(jsonString));
    let contenedor = document.getElementById('content-permisos');
    // Primero, desmarcar todo
    contenedor.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
        checkbox.parentNode.classList.remove('active');
    });
    // Recorrer cada clave del JSON
    for (let parentValue in jsonData) {
        let parentCheckbox = contenedor.querySelector(`input[id="menu${parentValue}"]`);

        if (parentCheckbox) {
            let childValues = jsonData[parentValue];

            if (childValues.length > 0) {
                let lichild = parentCheckbox.closest('li');

                // Marcar solo los hijos especificados
                childValues.forEach(childValue => {
                    let childCheckbox = lichild.querySelector(`input[id="menu${parentValue}-item${childValue}"]`);
                    if (childCheckbox) {
                        childCheckbox.checked = true;
                        childCheckbox.parentNode.classList.add('active');
                    }
                });
                // Si al menos un hijo está marcado, marcar el padre
                let hasCheckedChild = lichild.querySelector('.child input[type="checkbox"]:checked');
                if (hasCheckedChild) {
                    parentCheckbox.checked = true;
                }
            } else {
                // Si el array está vacío, marcar solo el padre
                parentCheckbox.checked = true;
            }
        }
    }
}

// Script Basico para modificar, guardar y eliminar Foto de perfil y Firma del usuario
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