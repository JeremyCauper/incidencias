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
            control: '#icono',
            config: {
                mxl: 255,
                require: true
            }
        },
        {
            control: '#ruta',
            config: {
                mxl: 255,
                require: true
            }
        },
        {
            control: ['#submenu', '#desarrollo', '#estado'],
            config: {
                require: true
            }
        },
    ];

    controles.forEach(control => {
        defineControllerAttributes(control.control, control.config);
    });

    $('.modal').on('hidden.bs.modal', function () {
        $('#modal_menuLabel').html('REGISTRAR MENU');
        $('#id').val('');
    });

    $('#tb_menu').off("draw.dt").on('draw.dt', function () {
        iniciarTbOrden();
    });
});

const tb_menu = new DataTable('#tb_menu', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
    ajax: {
        url: __url + '/mantenimiento/menu/menu/index',
        dataSrc: function (json) {
            $("#tb_orden_menu tbody").html('');
            $.each(json, function (i, e) {
                $("#tb_orden_menu tbody").append($('<tr>', {
                    "tr-id": e.id_menu,
                    "tr-orden": e.orden,
                    "draggable": "true"
                }).html(`<td>${e.orden}</td><td><i class="${e.icon}"></i> ${e.descripcion}</td>`));
            });
            return json;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'orden' },
        { data: 'descripcion' },
        { data: 'iconText' },
        { data: 'ruta' },
        { data: 'submenu' },
        { data: 'created_at' },
        { data: 'updated_at' },
        { data: 'estado' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).addClass('text-center');
        $(row).find('td:eq(0), td:eq(1), td:eq(2)').addClass('text-start');
    },
    processing: true
});

function updateTable() {
    tb_menu.ajax.reload();
}

document.getElementById('form-menu').addEventListener('submit', function (event) {
    event.preventDefault();

    fMananger.formModalLoding('modal_menu', 'show');

    const accion = $('#id').val();
    const url = accion ? `actualizar` : `registrar`;

    var elementos = this.querySelectorAll('[name]');
    var valid = validFrom(elementos);

    if (!valid.success) {
        return fMananger.formModalLoding('modal_menu', 'hide');
    }

    $.ajax({
        type: 'POST',
        url: `${__url}/mantenimiento/menu/menu/${url}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_menu', 'hide');

            if (!data.success) {
                return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message || 'No se pudo completar la operación.' });
            }

            $('#modal_menu').modal('hide');
            boxAlert.minbox({ h: data.message });
            updateTable();
        },
        error: function (jqXHR) {
            console.log(jqXHR.responseJSON);

            fMananger.formModalLoding('modal_menu', 'hide');
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
        $('#modal_menuLabel').html('Editar Menu');
        $('#modal_menu').modal('show');
        fMananger.formModalLoding('modal_menu', 'show');

        $.ajax({
            type: 'GET',
            url: `${__url}/mantenimiento/menu/menu/${id}`,
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
                $('#id').val(json.id_menu);
                $('#descripcion').val(json.descripcion);
                $('#icono').val(json.icon);
                $('#ruta').val(json.ruta);
                $('#submenu').val(json.submenu).trigger('change');
                $('#estado').val(json.estatus).trigger('change');

                fMananger.formModalLoding('modal_menu', 'hide');
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
        if (!await boxAlert.confirm('¿Esta seguro de esta accion?')) return true;

        $.ajax({
            type: 'POST',
            url: `${__url}/mantenimiento/menu/menu/cambiarEstado`,
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

async function cambiarOrden() {
    try {
        if (!await boxAlert.confirm('¿Esta seguro de esta accion?')) return true;
        fMananger.formModalLoding('modal_ordenm', 'show');

        $.ajax({
            type: 'POST',
            url: `${__url}/mantenimiento/menu/menu/cambiarOrdenMenu`,
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': __token,
            },
            data: JSON.stringify({
                "data": extraerIdsYOrdenes()
            }),
            beforeSend: boxAlert.loading,
            success: function (data) {
                if (!data.success) {
                    return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
                }
                boxAlert.minbox({ h: data.message });
                updateTable();
                fMananger.formModalLoding('modal_ordenm', 'hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                fMananger.formModalLoding('modal_ordenm', 'hide');
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