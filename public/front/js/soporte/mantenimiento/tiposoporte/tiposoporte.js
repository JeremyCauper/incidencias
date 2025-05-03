$(document).ready(function () {
    const controles = [
        // Formulario problemas
        {
            control: '#descripcion',
            config: {
                mxl: 50,
                require: true
            }
        },
        {
            control: ['#estado'],
            config: {
                require: true
            }
        },
    ];

    controles.forEach(control => {
        defineControllerAttributes(control.control, control.config);
    });

    $('.modal').on('hidden.bs.modal', function () {
        $('#modal_tipo_soporteLabel').html('REGISTRAR TIPO SOPORTE');
        $('#id').val('');
    }); 

    fObservador('.content-wrapper', () => {
        tb_tipo_soporte.columns.adjust().draw();
    });
});

function updateTable() {
    tb_tipo_soporte.ajax.reload();
}
mostrar_acciones('tb_tipo_soporte');

document.getElementById('form-tipo-soporte').addEventListener('submit', function (event) {
    event.preventDefault();

    fMananger.formModalLoding('modal_tipo_soporte', 'show');

    var valid = validFrom(this);

    if (!valid.success) {
        return fMananger.formModalLoding('modal_tipo_soporte', 'hide');
    }

    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/mantenimiento/tiposoporte/tiposoporte/${ $('#id').val() ? 'actualizar' : 'registrar' }`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_tipo_soporte', 'hide');

            if (!data.success) {
                return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message || 'No se pudo completar la operación.' });
            }

            $('#modal_tipo_soporte').modal('hide');
            boxAlert.minbox({ h: data.message });
            updateTable();
        },
        error: function (jqXHR) {
            console.log(jqXHR.responseJSON);

            fMananger.formModalLoding('modal_tipo_soporte', 'hide');
            let mensaje = 'Hubo un problema al procesar la solicitud. Intenta nuevamente.';

            if (jqXHR.status === 400) {
                mensaje = 'Datos inválidos. Por favor, revisa los campos e intenta nuevamente.';
            } else if (jqXHR.status === 409) {
                mensaje = jqXHR.responseJSON?.message || 'El código o la descripción ya existen en la base de datos.';
            } else if (jqXHR.status === 500) {
                mensaje = 'Ocurrió un error interno en el servidor. Intenta más tarde.';
            }

            boxAlert.box({ i: 'error', t: 'Error en la solicitud', h: mensaje });
            console.log("Error en AJAX:", jqXHR);
        }
    });
});

function Editar(id) {
    try {
        $('#modal_tipo_soporteLabel').html('EDITAR TIPO SOPORTE');
        $('#modal_tipo_soporte').modal('show');
        fMananger.formModalLoding('modal_tipo_soporte', 'show');

        $.ajax({
            type: 'GET',
            url: `${__url}/soporte/mantenimiento/tiposoporte/tiposoporte/${id}`,
            contentType: 'application/json',
            success: function (data) {

                // Verificar si la respuesta indica un error
                if (!data.success) {
                    console.log(data.message || "Error desconocido.");
                    return boxAlert.box({
                        i: 'error',
                        t: 'No pudimos obtener el problema',
                        h: data.message || 'Hubo un problema al recuperar la información. Intenta nuevamente.'
                    });
                }

                // Verificar si los datos existen
                if (!data.data) {
                    return boxAlert.box({
                        i: 'error',
                        t: 'Problema no encontrado',
                        h: 'No se encontró información sobre el problema seleccionado. Es posible que haya sido eliminado.'
                    });
                }

                var json = data.data;
                $('#id').val(json.id);
                $('#descripcion').val(json.descripcion);
                $('#estado').val(json.estatus).trigger('change');

                fMananger.formModalLoding('modal_tipo_soporte', 'hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                let mensaje = 'No pudimos procesar tu solicitud en este momento. Por favor, intenta más tarde.';

                if (jqXHR.status === 404) {
                    mensaje = 'El problema que intentas editar no existe. Verifica el código y vuelve a intentarlo.';
                } else if (jqXHR.status === 500) {
                    mensaje = 'Ocurrió un error interno en el servidor. Nuestro equipo está trabajando en ello.';
                }

                boxAlert.box({
                    i: 'error',
                    t: 'Error al obtener los datos',
                    h: mensaje
                });

                console.log("Error en AJAX:", jqXHR);
            }
        });
    } catch (error) {
        boxAlert.box({
            i: 'error',
            t: 'Error inesperado',
            h: 'Ocurrió un problema inesperado. Por favor, intenta nuevamente.'
        });
        console.log('Error producido: ', error);
    }
}

async function CambiarEstado(id, estado) {
    try {
        if (!await boxAlert.confirm({ h: `Esta apunto de ${estado ? 'des' : ''}activar el tipo soporte.` })) return true;

        $.ajax({
            type: 'POST',
            url: `${__url}/soporte/mantenimiento/tiposoporte/tiposoporte/cambiarEstado`,
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': __token,
            },
            data: JSON.stringify({
                "id": id,
                "estado": estado ? 0 : 1
            }),
            beforeSend: boxAlert.loading,
            success: function (data) {
                if (!data.success) {
                    return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
                }
                boxAlert.minbox({ h: data.message });
                updateTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                let mensaje = 'No pudimos procesar tu solicitud en este momento. Por favor, intenta más tarde.';

                if (jqXHR.status === 404) {
                    mensaje = 'El problema que intentas editar no existe. Verifica el código y vuelve a intentarlo.';
                } else if (jqXHR.status === 500) {
                    mensaje = 'Ocurrió un error interno en el servidor. Nuestro equipo está trabajando en ello.';
                }

                boxAlert.box({
                    i: 'error',
                    t: 'Error al obtener los datos',
                    h: mensaje
                });

                console.log("Error en AJAX:", jqXHR);
            }
        });
    } catch (error) {
        boxAlert.box({
            i: 'error',
            t: 'Error inesperado',
            h: 'Ocurrió un problema inesperado. Por favor, intenta nuevamente.'
        });
        console.log('Error producido: ', error);
    }
}

async function Eliminar(id) {
    try {
        if (!await boxAlert.confirm({ h: `Esta apunto de eliminar el tipo soporte.` })) return true;

        $.ajax({
            type: 'POST',
            url: `${__url}/soporte/mantenimiento/tiposoporte/tiposoporte/eliminar`,
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': __token,
            },
            data: JSON.stringify({
                "id": id
            }),
            beforeSend: boxAlert.loading,
            success: function (data) {
                if (!data.success) {
                    return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
                }
                boxAlert.minbox({ h: data.message });
                updateTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                let mensaje = 'No pudimos procesar tu solicitud en este momento. Por favor, intenta más tarde.';

                if (jqXHR.status === 404) {
                    mensaje = 'El problema que intentas editar no existe. Verifica el código y vuelve a intentarlo.';
                } else if (jqXHR.status === 500) {
                    mensaje = 'Ocurrió un error interno en el servidor. Nuestro equipo está trabajando en ello.';
                }

                boxAlert.box({ 
                    i: 'error', 
                    t: 'Error al obtener los datos', 
                    h: mensaje 
                });

                console.log("Error en AJAX:", jqXHR);
            }
        });
    } catch (error) {
        boxAlert.box({ 
            i: 'error', 
            t: 'Error inesperado', 
            h: 'Ocurrió un problema inesperado. Por favor, intenta nuevamente.' 
        });
        console.log('Error producido: ', error);
    }
}