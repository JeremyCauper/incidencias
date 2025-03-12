const tb_vprogramadas = new DataTable('#tb_vprogramadas', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
    ajax: {
        url: `${__url}/visitas/programadas/index`,
        dataSrc: function (json) {            
            $.each(json.conteo, function (panel, count) {
                $(`b[data-panel="${panel}"]`).html(count);
            });
            return json.data;
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
        $(row).find('td:eq(0), td:eq(3), td:eq(4)').addClass('text-center');
        $(row).find('td:eq(4)').addClass(`td-acciones`);
    },
    processing: true
});

function updateTableVProgramadas() {
    tb_vprogramadas.ajax.reload();
}
mostrar_acciones('tb_vprogramadas');

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
                let sucursal = sucursales[visita.id_sucursal];
                let empresa = empresas[sucursal.ruc];

                $(`#modal_seguimiento_visitasp [aria-item="empresa"]`).html(`${empresa.ruc} - ${empresa.razon_social}`);
                $(`#modal_seguimiento_visitasp [aria-item="direccion"]`).html(sucursal.direccion);
                $(`#modal_seguimiento_visitasp [aria-item="sucursal"]`).html(sucursal.nombre);

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
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
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
            // 'estado': estado
        }),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        beforeSend: boxAlert.loading,
        success: function (data) {
            if (data.success) {
                updateTableVProgramadas();
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let mensaje = datae.message;
            if (jqXHR.status == 422) {
                console.log("jqXHR");
                if (datae.hasOwnProperty('required')) {
                    mensaje = formatRequired(datae.required);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: mensaje });
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

function ShowAssign(e, id) {
    const obj = extractDataRow(e, "tb_vprogramadas");
    obj.estado = (obj.estado).replaceAll('.7rem;', '.8rem;');

    $('#modal_assign').find('.modal-body').addClass('d-none');
    $('#modal_assign').modal('show');
    fMananger.formModalLoding('modal_assign', 'show');
    $.ajax({
        type: 'GET',
        url: `${__url}/visitas/programadas/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                var visita = data.data;
                let sucursal = sucursales[visita.id_sucursal];
                let empresa = empresas[sucursal.ruc];
                
                $('#id_visitas_asign').val(visita.id);
                $('#fecha_visita_asign').val(visita.fecha);
                $(`#modal_assign [aria-item="estado"]`).html(obj.estado);
                $(`#modal_assign [aria-item="empresa"]`).html(`${empresa.ruc} - ${empresa.razon_social}`);
                $(`#modal_assign [aria-item="direccion"]`).html(sucursal.direccion);
                $(`#modal_assign [aria-item="sucursal"]`).html(sucursal.nombre);

                fMananger.formModalLoding('modal_assign', 'hide');
                $('#modal_assign').find('.modal-body').removeClass('d-none');

                const dt = data.data;
                (dt.personal_asig).forEach(element => {
                    const accion = dt.estado == 1 ? false : true;
                    cPersonal1.fillTable(element.id, accion);
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_assign', 'hide');
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

async function AssignPer() {
    if (!await boxAlert.confirm('¿Esta seguro de realizar esta accion?')) return true;
    fMananger.formModalLoding('modal_assign', 'show');

    const id = $('#id_visitas_asign').val();
    const estado = $(`#modal_assign [aria-item="estado"]`).text().replaceAll(' ', '').toLowerCase();

    const personal = cPersonal1.extract();
    if (!personal) {
        return fMananger.formModalLoding('modal_assign', 'hide');
    }

    $.ajax({
        type: 'POST',
        url: `${__url}/visitas/programadas/assignPer`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify({
            id: id,
            estado: estado == 'enproceso' ? false : true,
            personal_asig: personal,
            fecha: $('#fecha_visita_asign').val()
        }),
        success: function (data) {
            if (data.success) {
                cPersonal1.data = data.data.personal;
                updateTableVProgramadas();
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let mensaje = "";
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    mensaje = formatRequired(datae.required);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_assign', 'hide');
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
                updateTableVProgramadas()
                updateTableVisitas()
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

function OrdenVisita(e, id) {
    $('#modal_orden').modal('show');
    fMananger.formModalLoding('modal_orden', 'show');
    $.ajax({
        type: 'GET',
        url: `${__url}/visitas/programadas/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            console.log(data);
            
            if (data.success) {
                var visita = data.data;
                let sucursal = sucursales[visita.id_sucursal];
                let empresa = empresas[sucursal.ruc];

                $('[name="id_visita_orden"]').val(id);
                $(`#modal_orden [aria-item="registrado"]`).html(visita.seguimiento[0].created_at);
                $(`#modal_orden [aria-item="empresa"]`).html(`${empresa.ruc} - ${empresa.razon_social}`);
                $(`#modal_orden [aria-item="direccion"]`).html(sucursal.direccion);
                $(`#modal_orden [aria-item="sucursal"]`).html(sucursal.nombre);
                var tecnicos = visita.personal_asig.map(persona => persona.tecnicos);
                $('#modal_orden [aria-item="tecnicos"]').html('<i class="fas fa-user-gear"></i>' + tecnicos.join(', <i class="fas fa-user-gear ms-1"></i>'));
                fMananger.formModalLoding('modal_orden', 'hide');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
            if (jqXHR.status == 404) {
                setTimeout(() => { $('#modal_orden').modal('hide'); }, 500);
                return false;
            }
            fMananger.formModalLoding('modal_orden', 'hide');
        }
    });
}

document.getElementById('form-orden').addEventListener('submit', async function (event) {
    event.preventDefault();

    fMananger.formModalLoding('modal_orden', 'show');

    var valid = validFrom(this);
    valid.data.data.islas = MRevision.extract();
    const old_cod_ordenv = valid.data.data.cod_ordenv;

    if (!valid.success)
        return fMananger.formModalLoding('modal_orden', 'hide');
    $.ajax({
        type: 'POST',
        url: __url + '/orden-visita/create',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            if (data.success) {
                $('#modal_orden').modal('hide');
                changeCodOrdenV(data.data.cod_ordenv);
                window.open(`${__url}/orden-visita/documentopdf/${old_cod_ordenv}`, `Visualizar PDF ${old_cod_ordenv}`, "width=900, height=800");
                updateTableVProgramadas()
                updateTableVisitas()
                return true;
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let mensaje = "";
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    mensaje = formatRequired(datae.required);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_orden', 'hide');
        }
    });
});

function changeCheck($this) {
    let contentInput = $($this).parent();
    let icon = contentInput.find('span').find('i');
    if ($($this).val()) {
        icon.addClass('text-success');
    } else {
        icon.removeClass('text-success');
    }
}

function changeCodOrdenV(val = cod_ordenv) {
    $('[name="cod_ordenv"]').val(val);
    $('#modal_orden [aria-item="codigo"]').text(val);
    cod_ordenv = val;
}