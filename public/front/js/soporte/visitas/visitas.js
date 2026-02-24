$(document).ready(function () {
    $('.modal').on('shown.bs.modal', function () {
        changeCodOrdenV();
    });

    $('.modal').on('hidden.bs.modal', function () {
        fecha_visita.val(date('Y-m-d'));
        cPersonal.deleteTable();
        cPersonal1.deleteTable();
        MRevision.deleteAll();

        $('#contendor-filas').find('.input-group .input-group-text i').each(function () {
            $(this).attr({ 'class': 'fas fa-circle-check' });
        });
    });

    fObservador('.content-wrapper', () => {
        if (!esCelular()) {
            listado_visitas.columns.adjust().draw();
            listado_vprogramadas.columns.adjust().draw();
        }
    });
});

const config_personal = {
    dataSet: usuarios,
    table: {
        thead: ['Tecnicos'],
        tbody: [
            {
                data: 'doc', render: function (data, type, row) {
                    return `${data} - ${row.nombre}`;
                }
            }
        ]
    },
    extract: ['id'],
    select: {
        value: 'id',
        text: function (data) {
            return `${data.doc} - ${data.nombre}`;
        },
        validation: [
            { clave: 'estatus', operation: '===', value: 0, badge: 'Inac.' },
            { clave: 'eliminado', operation: '===', value: 1, badge: 'Elim.' },
        ]
    }
}

const cPersonal = new CTable('createPersonal', config_personal);
const cPersonal1 = new CTable('createPersonal1', config_personal);

function AsignarVisita(id) {
    $('#modal_visitas').modal('show');
    fMananger.formModalLoding('modal_visitas', 'show', true);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/visitas/sucursales/${id}`,
        contentType: 'application/json',
        success: function (data) {
            console.log(data);

            if (data.status == 204)
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });

            const dt = data.data;
            sucursal = sucursales[id];
            empresa = empresas[sucursal.ruc];

            llenarInfoModal('modal_visitas', {
                contrato: getBadgeContrato(dt.contrato),
                razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                direccion: '<i class="fas fa-location-dot me-2"></i>' + empresa.direccion,
                sucursal: sucursal.nombre,
                dir_sucursal: sucursal.direccion,
            });

            $('#idSucursal').val(dt.id);
            fMananger.formModalLoding('modal_visitas', 'hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

function DetalleVisita(id) {
    try {
        $('#modal_detalle_visitas').modal('show');
        fMananger.formModalLoding('modal_detalle_visitas', 'show', true);
        $('#tb_visitas_asignadas').parent().addClass('d-none');
        $('#tb_visitas_asignadas').find('tbody').html('');
        $('#modal_detalle_visitas [aria-item="mensaje"]').html('');

        $.ajax({
            type: 'GET',
            url: `${__url}/soporte/visitas/sucursales/${id}`,
            contentType: 'application/json',
            success: function (data) {
                if (data.status == 204)
                    return boxAlert.box({ i: data.icon, t: data.title, h: data.message });

                const dt = data.data;
                $('#idSucursal').val(dt.id);
                sucursal = sucursales[id];
                empresa = empresas[sucursal.ruc];

                llenarInfoModal('modal_detalle_visitas', {
                    contrato: getBadgeContrato(dt.contrato),
                    razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                    direccion: '<i class="fas fa-location-dot me-2"></i>' + empresa.direccion,
                    sucursal: sucursal.nombre,
                    dir_sucursal: sucursal.direccion,
                    rDias: dt.diasVisitas + ' día' + ((dt.diasVisitas > 0) ? 's' : ''),
                    vTotal: dt.totalVisitas,
                    vRealizada: dt.vRealizadas,
                    vPendiente: dt.vPendientes,
                    mensaje: dt.message
                });

                if (dt.visitas.length) {
                    dt.visitas.forEach(e => {
                        $('#tb_visitas_asignadas').find('tbody').append(`<tr>
                            <td>${e.creador}</td>
                            <td class="text-center">${e.fecha}</td>
                            <td class="text-center">${e.created_at}</td>
                            <td class="text-center">${getBadgeVisita(e.estado, .7)}</td>
                        </tr>`);
                    });
                    $('#tb_visitas_asignadas').parent().removeClass('d-none');
                }
                fMananger.formModalLoding('modal_detalle_visitas', 'hide');
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

document.getElementById('form-visita').addEventListener('submit', function (event) {
    event.preventDefault();
    if (!Object.keys(cPersonal.extract()).length)
        return boxAlert.box({
            i: 'warning',
            t: 'Personal',
            h: 'Primero debe asignar un personal'
        });
    fMananger.formModalLoding('modal_visitas', 'show');
    var valid = validFrom(this);
    if (!valid.success)
        return fMananger.formModalLoding('modal_visitas', 'hide');
    valid.data.data['personal'] = cPersonal.extract();
    valid.data.data.fecha_visita = fecha_visita.val();
    

    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/visitas/sucursales/create`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            $('#modal_visitas').modal('hide');
            boxAlert.box({ i: data.icon, t: data.title, h: data.message })
            updateTableVProgramadas();
            return updateTableVisitas();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let message = datae.message;
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    message = formatRequired(datae.required);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_visitas', 'hide');
        }
    });
});