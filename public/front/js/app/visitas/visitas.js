$(document).ready(function () {
    formatSelect('modal_visitas');

    $('.modal').on('shown.bs.modal', function () {
        $('#fecha_visita').val(date('Y-m-d'));
    });

    $('.modal').on('hidden.bs.modal', function () {
        // $('#contenedor-personal').addClass('d-none');
        cPersonal.deleteTable();
    });

    $('#fecha_visita').daterangepicker({
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

const tb_visitas = new DataTable('#tb_visitas', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
    ajax: {
        url: `${__url}/visitas/sucursales/index`,
        dataSrc: function (json) {
            // $.each(json.count, function (panel, count) {
            //     $(`b[data-panel="${panel}"]`).html(count);
            // });
            return json;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table(updateTableVisitas);
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'ruc' },
        { data: 'sucursal' },
        {
            data: 'visita', render: function (data, type, row) {
                badgeOptions = data == 'completado'
                    ? { t: 'Completado', c: 'primary' }
                    : (data ? { 'c': 'info', 't': `${data} Visita${(data > 1) ? 's' : ''}` } : { 'c': 'warning', 't': 'Sin Visitas' });

                return `<label class="badge badge-${badgeOptions.c}" style="font-size: .7rem;">${badgeOptions.t}</label>`;
            }
        },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(2), td:eq(3)').addClass('text-center');
    },
    processing: true
});

function updateTableVisitas() {
    tb_visitas.ajax.reload();
}

function DetalleVisita(id) {
    $('#modal_detalle_visitas').modal('show');
    fMananger.formModalLoding('modal_detalle_visitas', 'show');
    $('#tb_visitas_asignadas').addClass('d-none').find('tbody').html('');
    $('#modal_detalle_visitas [aria-item="mensaje"]').html('');

    $.ajax({
        type: 'GET',
        url: `${__url}/visitas/sucursales/${id}`,
        contentType: 'application/json',
        success: function (data) {
            console.log(data);

            if (!data.success)
                boxAlert.box({ i: 'error', t: 'Algo salió mal', h: data.message });

            const dt = data.data;
            $('#idSucursal').val(dt.id);
            $('#modal_detalle_visitas [aria-item="contrato"]').html(dt.contrato ? 'En Contrato' : 'Sin Contrato');
            $('#modal_detalle_visitas [aria-item="empresa"]').html(`${dt.ruc} - ${dt.razonSocial}`);
            $('#modal_detalle_visitas [aria-item="direccion"]').html(dt.direccion);
            $('#modal_detalle_visitas [aria-item="sucursal"]').html(dt.sucursal);
            $('#modal_detalle_visitas [aria-item="vTotal"]').html(dt.totalVisitas);
            $('#modal_detalle_visitas [aria-item="rDias"]').html(dt.diasVisitas);
            if (dt.visitas.length) {
                dt.visitas.forEach(e => {
                    const estado = {
                        "0": ['warning', 'Sin Iniciar'],
                        "1": ['info', 'Asignada'],
                        "2": ['primary', 'En Proceso'],
                        "4": ['danger', 'Faltan Datos']
                    };
                    $('#tb_visitas_asignadas').find('tbody').append(`<tr>
                        <td>${e.creador}</td>
                        <td class="text-center">${e.fecha}</td>
                        <td class="text-center">${e.created_at}</td>
                        <td class="text-center"><label class="badge badge-${estado[e.estado][0]}" style="font-size: .8rem;">${estado[e.estado][1]}</label></td>
                    </tr>`);
                });
                $('#tb_visitas_asignadas').removeClass('d-none');
            }
            $('#modal_detalle_visitas [aria-item="mensaje"]').html(dt.message);
            fMananger.formModalLoding('modal_detalle_visitas', 'hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Error al extraer datos de la incidencia', h: obj_error.message });
            console.log(jqXHR.responseJSON);
        }
    });
}

function AsignarVisita(id) {
    $('#modal_visitas').modal('show');
    fMananger.formModalLoding('modal_visitas', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/visitas/sucursales/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success)
                boxAlert.box({ i: 'error', t: 'Algo salió mal', h: data.message });

            const dt = data.data;
            $('#idSucursal').val(dt.id);
            $('#modal_visitas [aria-item="contrato"]').html(dt.contrato ? 'En Contrato' : 'Sin Contrato');
            $('#modal_visitas [aria-item="empresa"]').html(`${dt.ruc} - ${dt.razonSocial}`);
            $('#modal_visitas [aria-item="direccion"]').html(dt.direccion);
            $('#modal_visitas [aria-item="sucursal"]').html(dt.sucursal);

            fMananger.formModalLoding('modal_visitas', 'hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Error al extraer datos de la incidencia', h: obj_error.message });
            console.log(jqXHR.responseJSON);
        }
    });
}

document.getElementById('form-visita').addEventListener('submit', function (event) {
    event.preventDefault();
    if (!cPersonal.extract().length)
        return boxAlert.box({
            i: 'warning',
            t: 'Personal',
            h: 'Primero debe asignar un personal'
        });
    fMananger.formModalLoding('modal_visitas', 'show');
    var elementos = this.querySelectorAll('[name]');
    var valid = validFrom(elementos);
    if (!valid.success)
        return fMananger.formModalLoding('modal_visitas', 'hide');
    valid.data.data['personal'] = cPersonal.extract();

    $.ajax({
        type: 'POST',
        url: __url + `/visitas/sucursales/create`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_visitas', 'hide');
            if (data.success) {
                $('#modal_visitas').modal('hide');
                boxAlert.minbox({ h: data.message });
                return updateTableVisitas();
            }
            var message = "";
            if (data.hasOwnProperty('validacion')) {
                for (const key in data.validacion) {
                    message += `<li>${data.validacion[key][0]}</li>`;
                }
                message = `<ul>${message}</ul>`;
            }
            boxAlert.box({ i: 'error', t: 'Algo salió mal', h: message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({
                i: 'error',
                t: 'Ocurrio un error en el processo',
                h: obj_error.message
            });
            console.log(obj_error);
            fMananger.formModalLoding('modal_visitas', 'hide');
        }
    });
});