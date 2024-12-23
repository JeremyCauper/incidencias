
const tb_orden = new DataTable('#tb_orden', {
    scrollX: true,
    scrollY: 400,
    ajax: {
        url: `${__url}/incidencias/resueltas/index`,
        dataSrc: function (json) {
            // $('b[data-panel="_count"]').html(json.count.count);
            // $('b[data-panel="_inc_a"]').html(json.count.inc_a);
            // $('b[data-panel="_inc_s"]').html(json.count.inc_s);
            // $('b[data-panel="_inc_p"]').html(json.count.inc_p);
            return json.data;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_ordens' },
        { data: 'tipo_orden' },
        { data: 'asignados' },
        {
            data: 'fecha_f', render: function (data, type, row) {
                return `${data} ${row.hora_f}`;
            }
        },
        { data: 'empresa' },
        { data: 'sucursal' },
        { data: 'problema' },
        { data: 'f_inicio' },
        { data: 'f_final' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(9)').addClass('td-acciones');
    },
    processing: true
});

function updateTable() {
    tb_orden.ajax.reload();
}


function ShowDetail(e, id) {
    let obj = extractDataRow(e);
    $.each(obj, function (panel, count) {
        $(`#modal_detalle [aria-item="${panel}"]`).html(count);
    });

    $('#modal_detalle').modal('show');
    fMananger.formModalLoding('modal_detalle', 'show');
    $('#content-seguimiento').html('');
    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/detail/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                var seguimiento = data.data.seguimiento;
                var incidencia = data.data.incidencia;

                $(`#modal_detalle [aria-item="observasion"]`).html(incidencia.observasion);

                fMananger.formModalLoding('modal_detalle', 'hide');
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
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_detalle', 'hide');
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'No se pudo extraer los datos con exito.' });
            console.log(jqXHR);
        }
    });
}

function OrdenDisplay(e, cod) {
    let obj = extractDataRow(e);
    $.each(obj, function (panel, count) {
        $(`#modal_orden [aria-item="${panel}"]`).html(count);
    });
    $(`#modal_orden [aria-item="empresaFooter"]`).html(obj.empresa);

    $('#PreviFirma, #firmaCreador').addClass('visually-hidden');
    $('#doc_clienteFirma, #nomCreador').html('');
    $('#modal_orden').modal('show');
    fMananger.formModalLoding('modal_orden', 'show');
    $('#content-seguimiento').html('');
    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/resueltas/detail/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                var datos = data.data;
                
                let personal = datos.personal;
                $('#modal_orden [aria-item="observacion"]').html(datos.observasion);
                var tecnicos = personal.map(persona => persona.tecnicos);
                $('#modal_orden [aria-item="tecnicos"]').html('<i class="fas fa-user-gear"></i>' + tecnicos.join(', <i class="fas fa-user-gear ms-1"></i>'));

                $('#observaciones').val(datos.observaciones);
                $('#recomendaciones').val(datos.recomendaciones);
                $('#fecha_f').val(datos.fecha_f);
                $('#hora_f').val(datos.hora_f);
                var contacto = datos.contacto;
                var creador = datos.creador;
                if (contacto) {
                    if (contacto.firma_digital)
                        $('#PreviFirma').attr('src', `${__asset}/images/client/${contacto.firma_digital}`).removeClass('visually-hidden');
                    $('#doc_clienteFirma').html(`${contacto.nro_doc} - ${contacto.nombre_cliente}`);
                }

                if (creador) {
                    if (creador.firma_digital)
                        $('#firmaCreador').attr('src', `${__asset}/images/firms/${creador.firma_digital}`).removeClass('visually-hidden');
                    $('#nomCreador').html(`${creador.ndoc_usuario} - ${creador.nombres} ${creador.apellidos}`);
                }
                fMananger.formModalLoding('modal_orden', 'hide');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_orden', 'hide');
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'No se pudo extraer los datos con exito.' });
            console.log(jqXHR);
        }
    });
}

function OrdenPdf(cod) {
    window.open(`${__url}/orden/documentopdf/${cod}`, `Visualizar PDF ${cod}`, "width=900, height=800");
}

function OrdenTicket(cod) {
    window.open(`${__url}/orden/documentoticket/${cod}`, `Visualizar TICKET ${cod}`, "width=650, height=800");
}

function AddSignature(cod) {
    $('#modal_firmas').modal('show');
    fMananger.formModalLoding('modal_firmas', 'show');
    console.log(cod);
}