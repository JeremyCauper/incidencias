document.getElementById('form-login').addEventListener('submit', function (event) {
    event.preventDefault();
    let changue = false;
    let dataForm = {};

    Array.from(this.querySelectorAll('[name]')).some(function (elemento) {
        const require = elemento.getAttribute("require");
        if (elemento.getAttribute("require") && elemento.value == "") {
            $(`[aria-cinput="${require}"]`).prepend(`<span info-message="${require}" style="position: absolute; bottom: -20px; left: 3px; font-size: 0.7rem; color: #e4a11b;"><i class="fas fa-circle-info" style="font-size: 0.75rem"></i> El campo ${require} es requerido</span>`);
            changue = true;
            return true;
        }
        dataForm[elemento.name] = elemento.value;
    });
    if (changue) return false;
    $('#btn-ingresar').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Iniciando...').attr('disabled', true);
    $.ajax({
        type: 'POST',
        url: `${__url}/iniciar`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(dataForm),
        success: function (response) {
            if (response.success) {
                window.location.href = `${__url}/soporte`;
            } else {
                alertLogin(response.message);
                console.log(response.message);
                $('#btn-ingresar').html('Ingresar').attr('disabled', false);
            }
        },
        error: function (xhr, status, error) {
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
                    alert('La pagina venció, se actualizará');
                    window.location.href = `${__url}/inicio`;
                    return true;
                    break;
                case 500:
                    errorMessage = 'Error interno del servidor. Inténtalo más tarde.';
                    break;
                default:
                    errorMessage = 'Error desconocido. Por favor, intenta de nuevo.';
            }

            console.log('Código de estado:', statusCode);
            console.log('Mensaje de error:', error);
            alert(errorMessage);
            $('#btn-ingresar').html('Ingresar').attr('disabled', false);
        }
    });
});

function funKeyup() {
    $(`[info-message="${this.getAttribute("require")}"]`).remove();
}
document.getElementById('usuario').addEventListener('keyup', funKeyup);
document.getElementById('contrasena').addEventListener('keyup', funKeyup);


function alertLogin(message) {
    const alertElement = $('[role="alert"]');
    alertElement.html(`<i class="fas fa-triangle-exclamation"></i> ${message}`);

    alertElement.removeClass('hidden');
    requestAnimationFrame(() => {
        alertElement.addClass('show');
    });

    setTimeout(() => {
        alertElement.removeClass('show').addClass('hide');
        setTimeout(() => {
            alertElement.addClass('hidden').removeClass('hide');
        }, 500);
    }, 4000);
}