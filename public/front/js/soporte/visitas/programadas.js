function updateTableVProgramadas() {
    tb_vprogramadas.ajax.reload();
}
mostrar_acciones(tb_vprogramadas);
let visita_tmp = null;

function ShowDetail(e, id) {
    $('#modal_seguimiento_visitasp').modal('show');
    fMananger.formModalLoding('modal_seguimiento_visitasp', 'show', true);
    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/visitas/programadas/detail/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                var seguimiento = data.data.seguimiento;
                var visita = data.data.visita;
                sucursal = sucursales[visita.id_sucursal];
                empresa = empresas[sucursal.ruc];

                llenarInfoModal('modal_seguimiento_visitasp', {
                    estado: getBadgeVisita(visita.estado),
                    razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                    direccion: empresa.direccion,
                    sucursal: sucursal.nombre,
                    dir_sucursal: sucursal.direccion,
                });

                fMananger.formModalLoding('modal_seguimiento_visitasp', 'hide');
                llenarInfoSeguimientoVis('modal_seguimiento_visitasp', seguimiento);
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
    if (!await boxAlert.confirm({ h: `Esta apunto de <b class="text-warning"><i class="fas fa-${estado == 2 ? 'clock-rotate-left"></i> re' : 'stopwatch"></i> '}iniciar</b> la visita` })) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/visitas/programadas/startVisita`,
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
            if (data.success || data.status == 202) {
                updateTableVProgramadas()
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
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
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

function ShowAssign(e, id) {
    $('#modal_assign').modal('show');
    fMananger.formModalLoding('modal_assign', 'show', true);
    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/visitas/programadas/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                var visita = data.data;
                let sucursal = sucursales[visita.id_sucursal];
                let empresa = empresas[sucursal.ruc];
                visita_tmp = visita;

                llenarInfoModal('modal_assign', {
                    estado: getBadgeVisita(visita.estado),
                    razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                    direccion: empresa.direccion,
                    sucursal: sucursal.nombre,
                    dir_sucursal: sucursal.direccion,
                });

                $('#id_visitas_asign').val(visita.id);
                $('#fecha_visita_asign').val(visita.fecha);
                fMananger.formModalLoding('modal_assign', 'hide');
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
    if (!await boxAlert.confirm({ h: `Está apunto de asignar personal a la visita` })) return true;
    fMananger.formModalLoding('modal_assign', 'show');

    const personal = cPersonal1.extract();
    if (!personal) {
        return fMananger.formModalLoding('modal_assign', 'hide');
    }

    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/visitas/programadas/assignPer`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify({
            id: $('#id_visitas_asign').val(),
            estado: visita_tmp.estado == 1 ? false : true,
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
            let message = datae.message;
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    message = formatRequired(datae.required);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_assign', 'hide');
        }
    });
}

async function DeleteVisita(id) {
    if (!await boxAlert.confirm({ t: '¿Estas de suguro de eliminar la visita?', h: 'no se podrá no se podrá revertir está operación.' })) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/visitas/programadas/destroy`,
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
    fMananger.formModalLoding('modal_orden', 'show', true);
    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/visitas/programadas/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                let dt = data.data;
                if (dt.cod_ordenv) {
                    $('#modal_orden').modal('hide');
                    cod_ordenv = dt.new_cod_ordenv;
                    if (dt.id_tipo_incidencia == 2) {
                        const url = `${__url}/soporte/orden/exportar-documento?documento=pdf&codigo=${dt.cod_ordenv}`;
                        if (esCelular()) {
                            cargarIframeDocumento(url + '&tipo=movil');
                        } else {
                            window.open(url, `Visualizar PDF ${dt.cod_ordenv}`, "width=900, height=800");
                        }
                    }
                    updateTableVProgramadas();
                    boxAlert.box({ i: 'info', t: 'Atencion', h: `Ya se emitió un orden de visita con el siguiente codigo <b>${dt.cod_ordenv}</b>.` });
                    return true;
                }
                var visita = data.data;
                sucursal = sucursales[visita.id_sucursal];
                empresa = empresas[sucursal.ruc];
                var tecnicos = visita.personal_asig.map(persona => persona.tecnicos);

                llenarInfoModal('modal_orden', {
                    registrado: visita.seguimiento[0].created_at,
                    razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                    direccion: empresa.direccion,
                    sucursal: sucursal.nombre,
                    dir_sucursal: sucursal.direccion,
                    tecnicos: '<i class="fas fa-user-gear"></i>' + tecnicos.join(', <i class="fas fa-user-gear ms-1"></i>')
                });

                $('[name="id_visita_orden"]').val(id);
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

    if (!valid.success)
        return fMananger.formModalLoding('modal_orden', 'hide');
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/orden-visita/create`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            let dt = data.data;
            console.log(data);
            if (data.success || data.status == 202) {
                $('#modal_orden').modal('hide');
                changeCodOrdenV(dt.new_cod_ordenv);

                const url = `${__url}/soporte/orden-visita/exportar-documento?documento=pdf&codigo=${dt.old_cod_ordenv}`;
                if (esCelular()) {
                    cargarIframeDocumento(url + '&tipo=movil');
                } else {
                    window.open(url, `Visualizar PDF ${dt.old_cod_ordenv}`, "width=900, height=800");
                }
                updateTableVProgramadas()
                updateTableVisitas()
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
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
            fMananger.formModalLoding('modal_orden', 'hide');
        }
    });
});

function changeCheck($this) {
    let contentInput = $($this).parent('.input-group');
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