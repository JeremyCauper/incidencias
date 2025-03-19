$(document).ready(function () {
    const controles = [
        // Formulario grupos
        {
            control: '#grupo',
            config: {
                mxl: 100,
                require: true
            }
        },
        {
            control: '#estado',
            config: {
                require: true
            }
        },
    ];

    controles.forEach(control => {
        defineControllerAttributes(control.control, control.config);
    });

    $('.modal').on('hidden.bs.modal', function () {
        $('#modal_gruposLabel').html('REGISTRAR GRUPO');
        $('#id').val('');
    });

    fObservador('.content-wrapper', () => {
        tb_grupos.columns.adjust().draw();
    });
});

const tb_grupos = new DataTable('#tb_grupos', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
    ajax: {
        url: __url + '/empresas/grupos/index',
        dataSrc: "",
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'nombre' },
        { data: 'created_at' },
        { data: 'updated_at' },
        { data: 'estado' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(1), td:eq(2), td:eq(3), td:eq(4)').addClass('text-center');
        $(row).find('td:eq(4)').addClass(`td-acciones`);
    },
    processing: true
});

function updateTable() {
    tb_grupos.ajax.reload();
}
mostrar_acciones('tb_grupos');

document.getElementById('form-grupo').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_grupos', 'show');
    const accion = $('#id').val();
    const url = accion ? `actualizar` : `registrar`;

    var valid = validFrom(this);

    if (!valid.success)
        return fMananger.formModalLoding('modal_grupos', 'hide');

    $.ajax({
        type: 'POST',
        url: `${__url}/empresas/grupos/${url}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_grupos', 'hide');
            if (!data.success) {
                return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
            }
            $('#modal_grupos').modal('hide');
            boxAlert.minbox({ h: data.message });
            updateTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: 'Parece que hubo un problema en el servidor. Estamos trabajando para solucionarlo lo antes posible. Por favor, intenta de nuevo más tarde. Si el problema persiste, contacta con el soporte.' });
            console.log(jqXHR);
            fMananger.formModalLoding('modal_grupos', 'hide');
        }
    });
});

function Editar(id) {
    try {
        $('#modal_gruposLabel').html('EDITAR GRUPO');
        $('#modal_grupos').modal('show');
        fMananger.formModalLoding('modal_grupos', 'show');
        $.ajax({
            type: 'GET',
            url: `${__url}/empresas/grupos/${id}`,
            contentType: 'application/json',
            success: function (data) {
                if (!data.success) {
                    console.log(data.error);
                    return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
                }
                var json = data.data;
                $('#id').val(json.id);
                $('#grupo').val(json.nombre);
                $('#estado').val(json.status).trigger('change');
                fMananger.formModalLoding('modal_grupos', 'hide');
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
        if (!await boxAlert.confirm({ h: `Esta apunto de ${estado ? 'des' : ''}activar el grupo.` })) return true;

        $.ajax({
            type: 'POST',
            url: `${__url}/empresas/grupos/cambiarEstado`,
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