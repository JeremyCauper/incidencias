let url_base_login;
let form_require;
let btn_disable_modo = false;

const configuracion = {
    soporte: {
        svg: `<path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"></path><path d="M21 16v2a4 4 0 0 1-4 4h-5"></path>`,
        modo: {
            titulo: "Portal de Soporte Técnico",
            descripcion: "Acceso exclusivo para el equipo interno de soporte RC",
        },
        form: {
            label: "Usuario tecnico",
            placeholder_input: "Ingrese su usuario",
            require: {
                usuario: "Tiene que ingresar su usuario",
                password: "Tiene que ingresar su contraseña"
            }
        },
        acceso: "El acceso al portal de soporte está restringido al personal autorizado. Contacta al administrador del sistema para obtener credenciales.",
        url_login: "/soporte/iniciar"
    },
    cliente: {
        svg: `<path d="M10 12h4"></path><path d="M10 8h4"></path><path d="M14 21v-3a2 2 0 0 0-4 0v3"></path><path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"></path><path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"></path>`,
        modo: {
            titulo: "Portal de Cliente",
            descripcion: "Portal para empresas que reportan incidencias",
        },
        form: {
            label: "Usuario cliente",
            placeholder_input: "Ingrese su numero de ruc",
            require: {
                usuario: "Tiene que ingresar su numero de ruc",
                password: "Tiene que ingresar su contraseña"
            }
        },
        acceso: "Para obtener acceso al portal de cliente, contacta con el equipo de atención al cliente.",
        url_login: "/empresa/iniciar"
    }
}

setTimeout(() => {
    $('.contenedor-login').removeClass('overflow-hidden').addClass('overflow-auto');
}, 600);

const botones_modo = $('.botones-modo');
botones_modo.click(function () {
    if (btn_disable_modo) return false;
    const modo = $(this).data('tipo-modo');
    cambiarModo(modo);

    $('.informacion-modo').addClass('fade-in-up');
    setTimeout(() => {
        $('.informacion-modo').removeClass('fade-in-up');
    }, 751);
});

function cambiarModo(modo) {
    url_base_login = configuracion[modo].url_login;
    form_require = configuracion[modo].form.require;
    botones_modo.removeClass('active');
    $(`.botones-modo[data-tipo-modo="${modo}"]`).addClass('active');

    const config = configuracion[modo];
    $('[data-imagen').removeClass('active');
    $(`[data-imagen="${modo}"]`).addClass('active');

    $('.informacion-modo svg, .form-icon-usuario').html(config.svg);
    $('.form-label-usuario span[data-text]').text(config.form.label);
    $('#usuario').attr('placeholder', config.form.placeholder_input);

    $('[data-modo="titulo"]').text(config.modo.titulo);
    $('[data-modo="descripcion"]').text(config.modo.descripcion);
    $('[data-acceso]').text(config.acceso);
}

cambiarModo('soporte');

$('.button-password').click(function () {
    const password = $('#password');
    const svg = $(this).find('svg');
    if (password.attr('type') === 'password') {
        password.attr('type', 'text');
        svg.html('<path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"></path><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"></path><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"></path><path d="m2 2 20 20"></path>');
    } else {
        password.attr('type', 'password');
        svg.html('<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle>');
    }
});

$('#usuario, #password').on('focus', function () {
    $(`span[data-error-message="${this.getAttribute("name")}"]`).fadeOut(200);
});

function btnIngresar(accion = null) {
    let btn = $('#btn-ingresar');
    let icon_loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

    btn.attr('disabled', true);
    btn_disable_modo = true;
    switch (accion) {
        case 'iniciando':
            btn.html(icon_loading + ' Iniciando...');
            break;
        case 'ingresando':
            btn.html(icon_loading + ' Ingresando...');
            break;
        default:
            btn.html('Ingresar').attr('disabled', false);
            btn_disable_modo = false;
            break;
    }
}

function alertError(message) {
    let form_alerta = $('div[form-alerta]');

    // 1. Preparamos el contenido y limpiamos clases previas
    form_alerta.find('span').text(message);
    form_alerta.removeClass('hide-alert').show();

    // 2. Pequeño delay para que el navegador renderice el display:block antes de la animación
    setTimeout(() => {
        form_alerta.addClass('show-alert');
    }, 10);

    // 3. Timer para desaparecer
    setTimeout(() => {
        // Quitamos la clase de "aparición" y ponemos la de "desvanecer hacia abajo"
        form_alerta.removeClass('show-alert').addClass('hide-alert');

        // 4. Cuando termine la animación (500ms), ocultamos el elemento del DOM
        setTimeout(() => {
            form_alerta.hide();
        }, 500);

    }, 5000);
}

document.getElementById('form-login').addEventListener('submit', function (event) {
    event.preventDefault();
    let changue = false;
    let payload = {};

    Array.from(this.querySelectorAll('[name]')).some(function (elemento) {
        const require = form_require[elemento.name];
        if (require && elemento.value == "") {
            $(`span[data-error-message="${elemento.name}"]`).fadeIn(200).find('span').text(require);
            changue = true;
            return true;
        }
        payload[elemento.name] = elemento.value;
    });
    if (changue) return false;

    $.ajax({
        type: 'POST',
        url: __url + url_base_login,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(payload),
        beforeSend: btnIngresar('iniciando'),
        success: function (response) {
            if (response.success) {
                btnIngresar('ingresando');
                let ruta = response.data;
                window.location.href = __url + ruta;
            } else {
                alertError(response.message);
                console.log(response.message);
                btnIngresar();
            }
        },
        error: async function (xhr, status, error) {
            console.log(xhr);
            const statusCode = xhr.status;
            let errorMessage = 'Ha ocurrido un error al intentar iniciar sesión.';

            switch (statusCode) {
                case 400:
                    errorMessage = 'Solicitud incorrecta. Verifica los datos ingresados.';
                    break;
                case 401:
                    errorMessage = 'Credenciales inválidas. Verifica tu usuario y contraseña.';
                    break;
                case 404:
                    errorMessage = 'No se encontró el recurso solicitado.';
                    break;
                case 419:
                    Swal.fire({
                        title: "La pagina venció",
                        text: "Recargue nuevamente la pagina",
                        icon: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Recargar",
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) location.reload();;
                    });
                    return true;
                    break;
                case 500:
                    errorMessage = 'Error interno del servidor. Inténtalo más tarde.';
                    break;
                default:
                    errorMessage = 'Error desconocido. Por favor, intenta de nuevo.';
            }
            console.log('Código de estado:', statusCode, ' mensaje de error:', error);
            boxAlert.box({ i: 'error', t: 'Ocurrio un error al iniciar session', h: errorMessage });
            btnIngresar();
        }
    });
});