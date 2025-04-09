$(document).ready(function () {
    formatSelect('modal_visitas');

    $('.modal').on('shown.bs.modal', function () {
        $('#fecha_visita').val(date('Y-m-d'));
        changeCodOrdenV();
    });

    $('.modal').on('hidden.bs.modal', function () {
        // $('#contenedor-personal').addClass('d-none');
        cPersonal.deleteTable();
        cPersonal1.deleteTable();
    });

    $('#fecha_visita, #fecha_visita_asign').daterangepicker({
        singleDatePicker: true,
        startDate: date('Y-m-d'),
        minDate: date('Y-m-d'),
        opens: "center",
        cancelClass: "btn-link",
        locale: {
            format: 'YYYY-MM-DD',
            separator: '  al  ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cerrar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Rango personalizado',
            daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            firstDay: 1 // Comienza la semana en lunes
        }
    });

    fObservador('.content-wrapper', () => {
        tb_visitas.columns.adjust().draw();
        tb_vprogramadas.columns.adjust().draw();
    });
});

const cPersonal = new CTable('#createPersonal', {
    thead: ['#', 'Nro. Documento', 'Nombres y Apellidos'],
    tbody: [
        { data: 'id' },
        { data: 'doc' },
        { data: 'nombre' }
    ],
    extract: ['id']
});

const cPersonal1 = new CTable('#createPersonal1', {
    thead: ['#', 'Nro. Documento', 'Nombres y Apellidos'],
    tbody: [
        { data: 'id' },
        { data: 'doc' },
        { data: 'nombre' }
    ],
    extract: ['id']
});

function updateTableVisitas() {
    tb_visitas.ajax.reload();
}

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
                direccion: empresa.direccion,
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
        $('#tb_visitas_asignadas').addClass('d-none').find('tbody').html('');
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
                    direccion: empresa.direccion,
                    sucursal: sucursal.nombre,
                    dir_sucursal: sucursal.direccion,
                    vTotal: dt.totalVisitas,
                    rDias: dt.diasVisitas,
                    mensaje: dt.message
                });

                if (dt.visitas.length) {
                    dt.visitas.forEach(e => {
                        $('#tb_visitas_asignadas').find('tbody').append(`<tr>
                            <td>${e.creador}</td>
                            <td class="text-center">${e.fecha}</td>
                            <td class="text-center">${e.created_at}</td>
                            <td class="text-center">${getBadgeVisita(e.estado)}</td>
                        </tr>`);
                    });
                    $('#tb_visitas_asignadas').removeClass('d-none');
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