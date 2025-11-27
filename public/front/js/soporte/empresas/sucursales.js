$(document).ready(function () {
    configControls([
        // Formulario empresas datos de la empresas
        {
            control: '#empresa',
            requested: true
        },
        {
            control: '#sucursal',
            requested: true,
            mxl: 250
        },
        {
            control: '#codCofide',
            addLabel: 'Cod. Cofide',
            mxl: 100
        },
        {
            control: '#direccion',
            requested: true,
            mxl: 250
        },
        {
            control: '#ubigeo',
            requested: true
        },
        {
            control: '#telefonoS',
            addLabel: 'Telefono Sucursal',
            controlType: "int",
            mxl: 9,
            mask: { reg: "999999999" }
        },
        {
            control: '#correoS',
            addLabel: 'Correo Sucursal',
            controlType: "email",
            mxl: 250
        },
        {
            control: '#vVisitas',
            addLabel: 'Tienes Visitas?',
        },
        {
            control: '#vMantenimientos',
            addLabel: 'Tiene Mantenimientos?',
        },
        {
            control: '#estado',
            requested: true
        },
        {
            control: '#urlMapa',
        },
    ]);

    formatSelect('modal_sucursales');

    $('.modal').on('hidden.bs.modal', function () {
        $('#modal_sucursalesLabel').html('REGISTRAR EMPRESA');
        $('#id').val('');
    });

    ubigeo.forEach(e => {
        $('#ubigeo').append($('<option>').val(e.codigo).text(e.nombre));
    });

    fObservador('.content-wrapper', () => {
        tb_sucursales.columns.adjust().draw();
    });
});

function updateTable() {
    tb_sucursales.ajax.reload();
}
mostrar_acciones(tb_sucursales);

document.getElementById('form-sucursal').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_sucursales', 'show');
    const accion = $('#id').val();
    const url = accion ? `actualizar` : `registrar`;

    var valid = validFrom(this);

    if (!valid.success)
        return fMananger.formModalLoding('modal_sucursales', 'hide');

    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/empresas/sucursales/${url}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_sucursales', 'hide');
            if (!data.success) {
                return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
            }
            $('#modal_sucursales').modal('hide');
            boxAlert.minbox({ h: data.message });
            updateTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: 'Parece que hubo un problema en el servidor. Estamos trabajando para solucionarlo lo antes posible. Por favor, intenta de nuevo más tarde. Si el problema persiste, contacta con el soporte.' });
            console.log(jqXHR);
            fMananger.formModalLoding('modal_sucursales', 'hide');
        }
    });
});

function Editar(id) {
    try {
        $('#modal_sucursalesLabel').html('EDITAR SUCURSAL');
        $('#modal_sucursales').modal('show');
        fMananger.formModalLoding('modal_sucursales', 'show');
        $.ajax({
            type: 'GET',
            url: `${__url}/soporte/empresas/sucursales/${id}`,
            contentType: 'application/json',
            success: function (data) {
                if (!data.success) {
                    console.log(data.error);
                    return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
                }
                var json = data.data;
                $('#id').val(json.id);
                $('#empresa').val(json.ruc).trigger('change');
                $('#sucursal').val(json.nombre);
                $('#codCofide').val(json.cofide);
                $('#direccion').val(json.direccion);
                $('#ubigeo').val(json.ubigeo).trigger('change');
                $('#telefonoS').val(json.telefono);
                $('#correoS').val(json.correo);
                $('#vVisitas').val(json.v_visitas).trigger('change');
                $('#vMantenimientos').val(json.v_mantenimientos).trigger('change');
                $('#urlMapa').val(json.url_mapa);
                $('#estado').val(json.status).trigger('change');
                fMananger.formModalLoding('modal_sucursales', 'hide');
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
        if (!await boxAlert.confirm({ h: `Esta apunto de ${estado ? 'des' : ''}activar la sucursal.` })) return true;

        $.ajax({
            type: 'POST',
            url: `${__url}/soporte/empresas/sucursales/cambiarEstado`,
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