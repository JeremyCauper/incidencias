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
    var tInc = $('#tIncidencia').val();
    var nuevoUrl = `${__url}/empresa/incidencias/index?sucursal=${sucursal}&fechaIni=${fechas[0]}&fechaFin=${fechas[1]}&tIncidencia=${tInc}&tEstado=${tEstado}`;
    
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
        url: `${__url}/soporte/incidencias/registradas/detail/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                var seguimiento = data.data.seguimiento;
                var incidencia = data.data.incidencia;

                $(`#modal_detalle [aria-item="observacion"]`).html(incidencia.observacion);

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

function OrdenPdf(cod) {
    window.open(`${__url}/soporte/orden/documentopdf/${cod}`, `Visualizar PDF ${cod}`, "width=900, height=800");
}

function OrdenTicket(cod) {
    window.open(`${__url}/soporte/orden/documentoticket/${cod}`, `Visualizar TICKET ${cod}`, "width=650, height=800");
}