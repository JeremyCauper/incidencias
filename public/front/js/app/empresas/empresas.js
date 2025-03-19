$(document).ready(function () {
    const controles = [
        // Formulario empresas datos de la empresas
        {
            control: '#idGrupo',
            config: {
                require: true
            }
        },
        {
            control: '#ruc',
            config: {
                require: true,
                "control-type": "ruc",
                mnl: 11,
                mxl: 11,
                errorMessage: "El numero de ruc es invalido.",
                mask: { reg: "99999999999" }
            }
        },
        {
            control: '#razonSocial',
            config: {
                require: true,
                "control-type": "string",
                mxl: 400
            }
        },
        {
            control: '#direccion',
            config: {
                require: true,
                "control-type": "string",
                mxl: 500
            }
        },
        {
            control: ['#ubigeo', '#contrato', '#facturacion', '#prico', '#eds'],
            config: {
                require: true
            }
        },
        {
            control: ['#idNube', '#visitas', '#diasVisita', '#mantenimientos'],
            config: {
                mxl: 11,
                type: "number",
                val: "0",
                "control-type": "int",
                mask: { reg: "99999999999" }
            }
        },
        {
            control: ['#estado', '#codVisita'],
            config: {
                require: true
            }
        },
        // Formulario empresas datos del contacto de la empresa
        {
            control: '#cargo',
            config: {}
        },
        {
            control: '#encargado',
            config: {
                "control-type": "string",
                mxl: 100
            }
        },
        {
            control: '#telefono',
            config: {
                "control-type": "int",
                mxl: 9,
                mask: { reg: "999999999" }
            }
        },
        {
            control: '#correo',
            config: {
                "control-type": "email",
                mxl: 250
            }
        }
    ];

    controles.forEach(control => {
        defineControllerAttributes(control.control, control.config);
    });

    formatSelect('modal_empresas');

    $('.modal').on('hidden.bs.modal', function () {
        $('#modal_empresasLabel').html('REGISTRAR EMPRESA');
        $('#id').val('');
    });

    ubigeo.forEach(e => {
        $('#ubigeo').append($('<option>').val(e.codigo).text(e.nombre));
    });

    fObservador('.content-wrapper', () => {
        tb_empresas.columns.adjust().draw();
    });
});

const tb_empresas = new DataTable('#tb_empresas', {
    scrollX: true,
    scrollY: 300,
    ajax: {
        url: __url + '/empresas/empresas/index',
        dataSrc: "",
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
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
        $(row).find('td:eq(1), td:eq(3), td:eq(4), td:eq(5), td:eq(6), td:eq(7)').addClass('text-center');
        $(row).find('td:eq(7)').addClass(`td-acciones`);
    },
    processing: true
});

function updateTable() {
    tb_empresas.ajax.reload();
}
mostrar_acciones('tb_empresas');

document.getElementById('form-empresa').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_empresas', 'show');
    const accion = $('#id').val();
    const url = accion ? `actualizar` : `registrar`;

    var valid = validFrom(this);

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
        if (!await boxAlert.confirm({ h: `Esta apunto de ${estado ? 'des' : ''}activar la empresa.` })) return true;

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