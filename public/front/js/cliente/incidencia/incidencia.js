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
            listado_orden.columns.adjust().draw();
        }
    });
});

function ShowDetail(e, id) {
    $('#modal_detalle').modal('show');
    fMananger.formModalLoding('modal_detalle', 'show', true);
    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias/registradas/detail/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            var seguimiento = data.data.seguimiento;
            var inc = data.data.incidencia;

            sucursal = sucursales[inc.id_sucursal];

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
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_detalle', 'hide');
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'No se pudo extraer los datos con exito.' });
            console.log(jqXHR);
        }
    });
}

function OrdenPdf(cod) {
    const url = `${__url}/soporte/orden/exportar-documento?documento=pdf&codigo=${cod}`;
    if (esCelular()) {
        cargarIframeDocumento(url + '&tipo=movil');
    } else {
        window.open(url, `Visualizar PDF ${cod}`, "width=900, height=800");
    }
}

function OrdenTicket(cod) {
    const url = `${__url}/soporte/orden/exportar-documento?documento=ticket&codigo=${cod}`;
    if (esCelular()) {
        cargarIframeDocumento(url + '&tipo=movil');
    } else {
        window.open(url, `Visualizar TICKET ${cod}`, "width=650, height=800");
    }
}