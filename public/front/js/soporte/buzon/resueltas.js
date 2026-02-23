$(document).ready(function () {
    $('#empresa').on('change', function () {
        fillSelect(['#sucursal'], sucursales, 'ruc', $(this).val(), 'id', 'nombre', 'status');
    });

    $('#dateRango').daterangepicker({
        showDropdowns: true,
        startDate: date('Y-m-01'),
        endDate: date('Y-m-d'),
        maxDate: date('Y-m-d'),
        opens: "center",
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
        if (!esCelular()) {
            listado_orden_incidencias.columns.adjust().draw();
            listado_orden_visitas.columns.adjust().draw();
        }
    });
});

function OrdenPdfInc(cod) {
    const url = `${__url}/soporte/orden/exportar-documento?documento=pdf&codigo=${cod}`;
    if (esCelular()) {
        cargarIframeDocumento(url + '&tipo=movil');
    } else {
        window.open(url, `Visualizar PDF ${cod}`, "width=900, height=800");
    }
}

function OrdenTicketInc(cod) {
    const url = `${__url}/soporte/orden/exportar-documento?documento=ticket&codigo=${cod}`;
    if (esCelular()) {
        cargarIframeDocumento(url + '&tipo=movil');
    } else {
        window.open(url, `Visualizar TICKET ${cod}`, "width=650, height=800");
    }
}

function CompartirWhatsApp(tecnico, cod) {
    const linkPdf = `${__url}/soporte/orden/exportar-documento?documento=pdf&codigo=${cod}`;
    const saludo = saludoPorHora(); // o sin timeZone para hora local
    let mensaje = `${saludo} 👋, le saluda *${tecnico}* de *RC Ingenieros SAC* 🛠️\nLe comparto el enlace para la descarga de su *Orden de Servicio* 📄:\n\n👉 ${linkPdf}\n\nQuedamos atentos a cualquier consulta 📞`;

    const url = "https://api.whatsapp.com/send?text=" + encodeURIComponent(mensaje);
    window.open(url, "_blank");
}

function ShowDetailInc(e, id) {

    $('#modal_detalle').modal('show');
    fMananger.formModalLoding('modal_detalle', 'show');
    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias/registradas/detail/${id}`,
        contentType: 'application/json',
        success: function (data) {

            if (data.success) {
                var seguimiento = data.data.seguimiento;
                var inc = data.data.incidencia;

                sucursal = sucursales[inc.id_sucursal];
                empresa = empresas[sucursal.ruc];

                llenarInfoModal('modal_detalle', {
                    codigo: inc.cod_incidencia,
                    codigo_orden: inc.cod_orden,
                    estado: getBadgeIncidencia(inc.estado_informe, '.75', true, true),
                    razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                    direccion: '<i class="fas fa-location-dot me-2"></i>' + empresa.direccion,
                    sucursal: sucursal.nombre,
                    dir_sucursal: sucursal.direccion,
                    soporte: tipo_soporte[inc.id_tipo_soporte].descripcion,
                    problema: obj_problem[inc.id_problema].descripcion,
                    subproblema: getBadgePrioridad(obj_subproblem[inc.id_subproblema].prioridad, .75) + obj_subproblem[inc.id_subproblema].descripcion,
                    observacion: inc.observacion || '<span class="fst-italic">No hay observaciones adicionales registradas para este incidente.</span>',
                });

                fMananger.formModalLoding('modal_detalle', 'hide');
                llenarInfoTipoInc('modal_detalle', data.data);
                llenarInfoSeguimientoInc('modal_detalle', seguimiento);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_detalle', 'hide');
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'No se pudo extraer los datos con exito.' });
            console.log(jqXHR);
        }
    });
}

function OrdenPdfVis(cod) {
    const url = `${__url}/soporte/orden-visita/exportar-documento?documento=pdf&codigo=${cod}`;
    if (esCelular()) {
        cargarIframeDocumento(url + '&tipo=movil');
    } else {
        window.open(url, `Visualizar PDF ${cod}`, "width=900, height=800");
    }
}

function ShowDetailVis(e, id) {
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
                    estado: getBadgeVisita(visita.estado, .75, true, true),
                    razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                    direccion: '<i class="fas fa-location-dot me-2"></i>' + empresa.direccion,
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

let valorChange = false;
async function resetTable(chang) {
    $('#empresa').val('').trigger('change');
    $('#sucursal').val('').trigger('change');
    $('#dateRango').data('daterangepicker').setStartDate(date('Y-m-01'));
    $('#dateRango').data('daterangepicker').setEndDate(date('Y-m-d'));

    if (!esCelular()) {
        listado_orden_incidencias.columns.adjust().draw();
        listado_orden_visitas.columns.adjust().draw();
    }
    valorChange = chang;
}

async function filtroBusqueda() {
    var empresa = $('#empresa').val();
    var sucursal = $('#sucursal').val();
    var fechas = $('#dateRango').val().split('  al  ');
    var nuevoUrl = generateUrl(`${__url}/soporte/buzon-personal/${valorChange ? 'visitas' : 'incidencias'}/resueltas/index`, {
        ruc: empresa,
        sucursal: sucursal,
        fechaIni: fechas[0],
        fechaFin: fechas[1]
    });

    if (valorChange) {
        listado_orden_visitas.ajax.url(nuevoUrl).load();
    } else {
        listado_orden_incidencias.ajax.url(nuevoUrl).load();
    }
}