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
        tb_orden.columns.adjust().draw();
    });
});

function updateTable() {
    tb_orden.ajax.reload();
}
mostrar_acciones('tb_orden');

function filtroBusqueda() {
    var sucursal = $('#sucursal').val();
    var fechas = $('#dateRango').val().split('  al  ');
    var tEstado = $('#tEstado').val();
    var tSop = $('#tSoporte').val();
    var nuevoUrl = `${__url}/empresa/incidencias/index?sucursal=${sucursal}&fechaIni=${fechas[0]}&fechaFin=${fechas[1]}&tSoporte=${tSop}&tEstado=${tEstado}`;

    tb_orden.ajax.url(nuevoUrl).load();
}

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
                estado: getBadgeIncidencia(inc.estado_informe),
                razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                direccion: empresa.direccion,
                sucursal: sucursal.nombre,
                dir_sucursal: sucursal.direccion,
                soporte: tipo_soporte[inc.id_tipo_soporte].descripcion,
                problema: `${obj_problem[inc.id_problema].codigo} - ${obj_problem[inc.id_problema].descripcion}`,
                subproblema: getBadgePrioridad(obj_subproblem[inc.id_subproblema].prioridad, .75) + obj_subproblem[inc.id_subproblema].descripcion,
                observacion: inc.observacion,
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
    window.open(`${__url}/soporte/orden/documentopdf/${cod}`, `Visualizar PDF ${cod}`, "width=900, height=800");
}

function OrdenTicket(cod) {
    window.open(`${__url}/soporte/orden/documentoticket/${cod}`, `Visualizar TICKET ${cod}`, "width=650, height=800");
}