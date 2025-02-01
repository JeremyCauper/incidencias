$(document).ready(function () {
    const controles = [
        // Formulario problemas
        {
            control: ['#problema'],
            config: {
                require: true
            }
        },
        {
            control: '#codigo_sub',
            config: {
                mxl: 50,
                require: true
            }
        },
        {
            control: '#descripcion',
            config: {
                mxl: 255,
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
        $('#modal_subproblemasLabel').html('REGISTRAR SUB PROBLEMA');
        $('#id').val('');
    });
});

const tb_subproblemas = new DataTable('#tb_subproblemas', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
    ajax: {
        url: __url + '/mantenimiento/problemas/subproblemas/index',
        dataSrc: "",
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_problema' },
        { data: 'codigo_sub' },
        { data: 'descripcion' },
        { data: 'created_at' },
        { data: 'updated_at' },
        { data: 'estado' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).addClass('text-center');
    },
    processing: true
});

function updateTable() {
    tb_subproblemas.ajax.reload();
}

document.getElementById('form-subproblema').addEventListener('submit', function (event) {
    event.preventDefault();

    fMananger.formModalLoding('modal_subproblemas', 'show');

    const accion = $('#id').val();
    const url = accion ? `actualizar` : `registrar`;

    var elementos = this.querySelectorAll('[name]');
    var valid = validFrom(elementos);

    if (!valid.success) {
        return fMananger.formModalLoding('modal_subproblemas', 'hide');
    }

    $.ajax({
        type: 'POST',
        url: `${__url}/mantenimiento/problemas/subproblemas/${url}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_subproblemas', 'hide');

            if (!data.success) {
                return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message || 'No se pudo completar la operación.' });
            }

            $('#modal_subproblemas').modal('hide');
            boxAlert.minbox({ h: data.message });
            updateTable();
        },
        error: function (jqXHR) {
            console.log(jqXHR.responseJSON);
            
            fMananger.formModalLoding('modal_subproblemas', 'hide');
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
        $('#modal_subproblemasLabel').html('Editar Problema');
        $('#modal_subproblemas').modal('show');
        fMananger.formModalLoding('modal_subproblemas', 'show');

        $.ajax({
            type: 'GET',
            url: `${__url}/mantenimiento/problemas/subproblemas/${id}`,
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
                $('#id').val(json.id_subproblema);
                $('#problema').val(json.id_problema).trigger('change');
                $('#codigo_sub').val(json.codigo_sub);
                $('#descripcion').val(json.descripcion);
                $('#estado').val(json.estatus).trigger('change');

                fMananger.formModalLoding('modal_subproblemas', 'hide');
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
            url: `${__url}/mantenimiento/problemas/subproblemas/cambiarEstado`,
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