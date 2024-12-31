$(document).ready(function () {
    formatSelect('modal_empresas');

    $('.modal').on('hidden.bs.modal', function () {
        $('#modal_empresasLabel').html('REGISTRAR EMPRESA');
        $('#id').val('');
    });

    ubigeo.forEach(e => {
        $('#ubigeo').append($('<option>').val(e.codigo).text(e.nombre));
    });

    configControls(['#ruc', '#idNube', '#visitas', '#diasVisita', '#mantenimientos'], { mxl: 11, mask: { reg: "99999999999" } });
    configControls('#razonSocial', { mxl: 400 });
    configControls('#direccion', { mxl: 500 });
    configControls('#telefono', { mxl: 11, mask: { reg: "999999999" } });
});

const tb_empresas = new DataTable('#tb_empresas', {
    scrollX: true,
    scrollY: 300,
    ajax: {
        url: __url + '/empresas/empresas/index',
        dataSrc: "",
        error: function (xhr, error, thrown) {
            console.log('Error en la solicitud Ajax:', error);
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'id' },
        { data: 'grupo' },
        { data: 'ruc' },
        { data: 'razonSocial' },
        { data: 'contrato' },
        { data: 'created_at' },
        { data: 'updated_at' },
        { data: 'estado' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(5), td:eq(6)').addClass('text-center');
    },
    processing: true
});

function updateTable() {
    tb_empresas.ajax.reload();
}

document.getElementById('form-empresa').addEventListener('submit', function (event) {
    event.preventDefault();
    if ($('#ruc').val().length < 11) {
        return boxAlert.box({ i: 'warning', t: 'Datos invalidos', h: 'El ruc ingresado no es válido, deben ser 11 digitos.' });
    }
    const emailValue = $('#correo').val();
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailRegex.test(emailValue) && emailValue) {
        return boxAlert.box({ i: 'warning', t: 'Datos invalidos', h: 'El correo electrónico ingresado no es válido.' });
    }
    fMananger.formModalLoding('modal_empresas', 'show');
    const accion = $('#id').val();
    const url = accion ? `actualizar` : `registrar`;

    var elementos = this.querySelectorAll('[name]');
    var valid = validFrom(elementos);

    if (!valid.success)
        return fMananger.formModalLoding('modal_empresas', 'hide');

    $.ajax({
        type: 'POST',
        url: `${__url}/empresas/empresas/${url}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_empresas', 'hide');
            if (!data.success) {
                return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
            }
            $('#modal_empresas').modal('hide');
            boxAlert.minbox({ h: data.message });
            updateTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: 'Parece que hubo un problema en el servidor. Estamos trabajando para solucionarlo lo antes posible. Por favor, intenta de nuevo más tarde. Si el problema persiste, contacta con el soporte.' });
            console.log(jqXHR);
            fMananger.formModalLoding('modal_empresas', 'hide');
        }
    });
});

function Editar(id) {
    try {
        $('#modal_empresasLabel').html('EDITAR EMPRESA');
        $('#modal_empresas').modal('show');
        fMananger.formModalLoding('modal_empresas', 'show');
        $.ajax({
            type: 'GET',
            url: `${__url}/empresas/empresas/${id}`,
            contentType: 'application/json',
            success: function (data) {
                console.log(data);
                
                if (!data.success) {
                    console.log(data.error);
                    return boxAlert.box({ i: 'error', t: 'Algo salió mal...', h: data.message });
                }
                var json = data.data;
                $('#id').val(json.id);
                $('#ruc').val(json.ruc);
                $('#razonSocial').val(json.razon_social);
                $('#contrato').val(json.contrato).trigger('change');
                $('#idNube').val(json.id_nube);
                $('#idGrupo').val(json.id_grupo).trigger('change');
                $('#direccion').val(json.direccion);
                $('#ubigeo').val(json.ubigeo).trigger('change');
                $('#facturacion').val(json.facturacion).trigger('change');
                $('#prico').val(json.prico).trigger('change');
                $('#encargado').val(json.encargado);
                $('#cargo').val(json.cargo).trigger('change');
                $('#telefono').val(json.telefono);
                $('#correo').val(json.correo);
                $('#eds').val(json.eds).trigger('change');
                $('#visitas').val(json.visitas);
                $('#mantenimientos').val(json.mantenimientos);
                $('#diasVisita').val(json.dias_visita);
                $('#estado').val(json.status).trigger('change');
                $('#codVisita').val(json.codigo_aviso).trigger('change');
                fMananger.formModalLoding('modal_empresas', 'hide');
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
            url: `${__url}/empresas/empresas/cambiarEstado`,
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