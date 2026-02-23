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
            daysOfWeek: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            firstDay: 1 // Comienza la semana en lunes
        }
    });

    fObservador('.content-wrapper', () => {
        if (!esCelular()) {
            listado_vterminadas.columns.adjust().draw();
        }
    });
});

function OrdenPdf(cod) {
    const url = `${__url}/soporte/orden-visita/exportar-documento?documento=pdf&codigo=${cod}`;
    if (esCelular()) {
        cargarIframeDocumento(url + '&tipo=movil');
    } else {
        window.open(url, `Visualizar PDF ${cod}`, "width=900, height=800");
    }
}

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