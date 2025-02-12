const tb_vprogramadas = new DataTable('#tb_vprogramadas', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
    ajax: {
        url: `${__url}/visitas/programadas/index`,
        dataSrc: function (json) {
            // $.each(json.count, function (panel, count) {
            //     $(`b[data-panel="${panel}"]`).html(count);
            // });
            return json;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table(updateTableVProgramadas);
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'estado' },
        { data: 'sucursal' },
        { data: 'tecnicos' },
        { data: 'fecha' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(0), td:eq(2)').addClass('text-center');
    },
    processing: true
});

function updateTableVProgramadas() {
    tb_vprogramadas.ajax.reload();
}

function ShowDetail(e, id) {
    $('#modal_seguimiento_visitasp').find('.modal-body').addClass('d-none');
    $('#modal_seguimiento_visitasp').modal('show');
    fMananger.formModalLoding('modal_seguimiento_visitasp', 'show');
    $('#content-seguimiento').html('');
    $.ajax({
        type: 'GET',
        url: `${__url}/visitas/programadas/detail/${id}`,
        contentType: 'application/json',
        success: function (data) {
            
            if (data.success) {
                var seguimiento = data.data.seguimiento;
                var visita = data.data.visita;

                $(`#modal_seguimiento_visitasp [aria-item="empresa"]`).html(visita.empresa);
                $(`#modal_seguimiento_visitasp [aria-item="direccion"]`).html(visita.direccion);
                $(`#modal_seguimiento_visitasp [aria-item="sucursal"]`).html(visita.sucursal);

                fMananger.formModalLoding('modal_seguimiento_visitasp', 'hide');
                seguimiento.sort((a, b) => new Date(a.date) - new Date(b.date));
                seguimiento.forEach(function (element) {
                    $('#content-seguimiento').append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="${element.img}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                            <div class="ms-3">
                                <p class="fw-bold mb-1">${element.nombre}</p>
                                <p class="text-muted" style="font-size: .73rem;font-family: Roboto; margin-bottom: .2rem;">${element.text}</p>
                                <p class="text-muted mb-0" style="font-size: .73rem;font-family: Roboto;">${element.contacto}</p>
                            </div>
                        </div>
                        <span class="badge rounded-pill badge-primary">${element.date}</span>
                    </li>`);
                });
                $('#modal_seguimiento_visitasp').find('.modal-body').removeClass('d-none');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_seguimiento_visitasp', 'hide');
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'No se pudo extraer los datos con exito.' });
            console.log(jqXHR);
        }
    });
}

async function StartVisita(id, estado) {
    if (!await boxAlert.confirm(`¿Esta seguro de <b>${estado == 2 ? 're' : ''}iniciar</b> la visita?`)) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/visitas/programadas/startVisita`,
        data: JSON.stringify({
            'id': id,
            'estado': estado
        }),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        beforeSend: boxAlert.loading,
        success: function (data) {
            if (data.success) {
                boxAlert.minbox({ h: data.message });
                updateTableVProgramadas();
                return true;
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
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: obj_error.message });
            console.log(obj_error);
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

async function DeleteVisita(id) {
    if (!await boxAlert.confirm('¿Esta seguro de elimniar?, no se podrá revertir los cambios')) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/visitas/programadas/destroy`,
        data: JSON.stringify({ 'id': id }),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        beforeSend: boxAlert.loading,
        success: function (data) {
            if (data.success) {
                boxAlert.minbox({ h: data.message });
                updateTableVProgramadas();
                return true;
            }
            boxAlert.box({ i: 'error', t: '¡Ocurrio un inconveniente!', h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: obj_error.message });
            console.log(obj_error);
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}