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

const tb_incidencias = new DataTable('#tb_incidencias', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    ajax: {
        url: `${__url}/buzon-personal/incidencias/resueltas/index?ruc=&sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}`,
        dataSrc: function (json) {
            return json;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table(updateTableInc);
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_inc' },
        { data: 'fecha_inc' },
        { data: 'cod_orden' },
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
        { data: 'iniciado' },
        { data: 'finalizado' },
        { data: 'acciones' }
    ],
    order: [[2, 'desc']],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(7)').addClass(`td-acciones`);
    },
    processing: true
});

function updateTableInc() {
    tb_incidencias.ajax.reload();
}
mostrar_acciones('tb_incidencias');

function OrdenPdfInc(cod) {
    window.open(`${__url}/orden/documentopdf/${cod}`, `Visualizar PDF ${cod}`, "width=900, height=800");
}

function OrdenTicketInc(cod) {
    window.open(`${__url}/orden/documentoticket/${cod}`, `Visualizar TICKET ${cod}`, "width=650, height=800");
}

function ShowDetailInc(e, id) {
    // let obj = extractDataRow(e);
    // $.each(obj, function (panel, count) {
    //     $(`#modal_detalle [aria-item="${panel}"]`).html(count);
    // });

    $('#modal_detalle').modal('show');
    fMananger.formModalLoding('modal_detalle', 'show');
    $('#content-seguimiento-inc').html('');
    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/detail/${id}`,
        contentType: 'application/json',
        success: function (data) {
            console.log(data);
            
            if (data.success) {
                var seguimiento = data.data.seguimiento;
                var incidencia = data.data.incidencia;

                var sucursal = sucursales[incidencia.id_sucursal];
                var empresa = empresas[sucursal.ruc];

                $(`#modal_detalle [aria-item="codigo"]`).html(incidencia.cod_orden);
                $(`#modal_detalle [aria-item="empresa"]`).html(`${empresa.ruc} - ${empresa.razon_social}`);
                $(`#modal_detalle [aria-item="direccion"]`).html(sucursal.direccion);
                $(`#modal_detalle [aria-item="sucursal"]`).html(sucursal.nombre);
                $(`#modal_detalle [aria-item="atencion"]`).html(tipos_incidencia[incidencia.id_tipo_incidencia].descripcion);
                $(`#modal_detalle [aria-item="problema_sub_problema"]`).html(`${problemas[incidencia.id_problema].descripcion} / ${subproblemas[incidencia.id_subproblema].descripcion}`);
                $(`#modal_detalle [aria-item="observasion"]`).html(incidencia.observasion);

                fMananger.formModalLoding('modal_detalle', 'hide');
                seguimiento.sort((a, b) => new Date(a.date) - new Date(b.date));
                seguimiento.forEach(function (element) {
                    $('#content-seguimiento-inc').append(`
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


const tb_visitas = new DataTable('#tb_visitas', {
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    ajax: {
        url: `${__url}/buzon-personal/visitas/resueltas/index?ruc=&sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}`,
        dataSrc: function (json) {
            return json;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table(updateTableVis);
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_orden' },
        { data: 'fecha_vis' },
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
        { data: 'iniciado' },
        { data: 'finalizado' },
        { data: 'acciones' }
    ],
    order: [[1, 'desc']],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(6)').addClass(`td-acciones`);
    },
    processing: true
});

function updateTableVis() {
    tb_visitas.ajax.reload();
}
mostrar_acciones('tb_visitas');

function OrdenPdfVis(cod) {
    window.open(`${__url}/orden-visita/documentopdf/${cod}`, `Visualizar PDF ${cod}`, "width=900, height=800");
}

function ShowDetailVis(e, id) {
    $('#modal_seguimiento_visitasp').find('.modal-body').addClass('d-none');
    $('#modal_seguimiento_visitasp').modal('show');
    fMananger.formModalLoding('modal_seguimiento_visitasp', 'show');
    $('#content-seguimiento-vis').html('');
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
                    $('#content-seguimiento-vis').append(`
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


let valorChange = false;
async function resetTable(val) {
    $('#empresa').val('').trigger('change');
    $('#sucursal').val('').trigger('change');
    $('#dateRango').data('daterangepicker').setStartDate(date('Y-m-01'));
    $('#dateRango').data('daterangepicker').setEndDate(date('Y-m-d'));

    var nuevoUrl = `${__url}/buzon-personal/${val ? 'visitas' : 'incidencias'}/resueltas/index?sucursal=&fechaIni=${date('Y-m-01')}&fechaFin=${date('Y-m-d')}`;
    valorChange = val;
    $(`#tb_${val ? 'visitas' : 'incidencias'}_wrapper .dataTables_scrollHeadInner`).css('width', '100%')
        .find('table').css('width', '100%');
    if (val) {
        tb_visitas.ajax.url(nuevoUrl).load();
    } else {
        tb_incidencias.ajax.url(nuevoUrl).load();
    }
}

async function filtroBusqueda() {
    var empresa = $('#empresa').val();
    var sucursal = $('#sucursal').val();
    var fechas = $('#dateRango').val().split('  al  ');
    var nuevoUrl = `${__url}/buzon-personal/${valorChange ? 'visitas' : 'incidencias'}/resueltas/index?ruc=${empresa}&sucursal=${sucursal}&fechaIni=${fechas[0]}&fechaFin=${fechas[1]}`;

    if (valorChange) {
        tb_visitas.ajax.url(nuevoUrl).load();
    } else {
        tb_incidencias.ajax.url(nuevoUrl).load();
    }
}