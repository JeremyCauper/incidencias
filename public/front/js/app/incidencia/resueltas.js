$(document).ready(function () {
    $('#empresa').on('change', function () {
        fillSelect(['#sucursal'], sucursales, 'ruc', $(this).val(), 'id', 'nombre');
    });

    $('#dateRango').daterangepicker({
        showDropdowns: true,
        startDate: date('Y-m-01'),
        endDate: date('Y-m-d'),
        maxDate: date('Y-m-d'),
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

const tb_orden = new DataTable('#tb_orden', {
    scrollX: true,
    scrollY: 400,
    ajax: {
        url: `${__url}/incidencias/resueltas/index?ruc=&sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}`,
        dataSrc: function (json) {
            return json.data;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_orden' },
        { data: 'tipo_incidencia' },
        { data: 'asignados' },
        { data: 'fecha_servicio' },
        { data: 'empresa' },
        { data: 'nombre_sucursal' },
        { data: 'problema' },
        { data: 'iniciado' },
        { data: 'finalizado' },
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

function filtroBusqueda() {
    var empresa = $(`#empresa option[value="${$('#empresa').val()}"]`).attr('id-empresa');
    var sucursal = $('#sucursal').val();
    var fechas = $('#dateRango').val().split('  al  ');
    var nuevoUrl = `${__url}/incidencias/resueltas/index?ruc=${empresa}&sucursal=${sucursal}&fechaIni=${fechas[0]}&fechaFin=${fechas[1]}`;
    
    tb_orden.ajax.url(nuevoUrl).load();
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