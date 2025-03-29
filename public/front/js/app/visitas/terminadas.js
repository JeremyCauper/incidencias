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
            daysOfWeek: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            firstDay: 1 // Comienza la semana en lunes
        }
    });

    fObservador('.content-wrapper', () => {
        tb_vterminadas.columns.adjust().draw();
    });
});

const tb_vterminadas = new DataTable('#tb_vterminadas', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
    ajax: {
        url: `${__url}/visitas/terminadas/index?sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}`,
        dataSrc: function (json) {
            return json;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'id' },
        { data: 'cod_ordenv' },
        { data: 'fecha' },
        { data: 'tecnicos' },
        {
            data: 'id_sucursal', render: function (data, type, row) {
                var ruc = sucursales[data].ruc;
                return `${empresas[ruc].ruc} - ${empresas[ruc].razon_social}`;
            }
        },
        {
            data: 'id_sucursal', render: function (data, type, row) {
                return sucursales[data].nombre;
            }
        },
        { data: 'horaIni' },
        { data: 'horaFin' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(0), td:eq(2)').addClass('text-center');
        $(row).find('td:eq(8)').addClass(`td-acciones`);
    },
    processing: true
});

function updateTable() {
    tb_vterminadas.ajax.reload();
}
mostrar_acciones('tb_vterminadas');

function filtroBusqueda() {
    var sucursal = $('#sucursal').val();
    var fechas = $('#dateRango').val().split('  al  ');
    var nuevoUrl = `${__url}/visitas/terminadas/index?sucursal=${sucursal}&fechaIni=${fechas[0]}&fechaFin=${fechas[1]}`;
    
    tb_vterminadas.ajax.url(nuevoUrl).load();
}


function OrdenPdf(cod) {
    window.open(`${__url}/orden-visita/documentopdf/${cod}`, `Visualizar PDF ${cod}`, "width=900, height=800");
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
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}