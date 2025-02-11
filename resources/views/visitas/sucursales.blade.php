@extends('layout.app')
@section('title', 'Visitas')

@section('style')
<script type="text/javascript" src="{{asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('front/vendor/daterangepicker/daterangepicker.css')}}">
<!-- <link rel="stylesheet" href="{{asset('front/css/app/incidencias/registradas.css')}}"> -->
<style>
    #tb_visitas thead tr * {
        font-size: 12px;
        /* padding-top: ; */
    }
</style>
@endsection
@section('content')

<div class="row panel-view">
    <div class="col-12">
        <div class="row">
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-danger" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-xmark"></i></i> Visitas Sin Programar</h6>
                        <h4 class="subtitle-count"><b data-panel="tAsignadas">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-warning" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clock"></i> Visitas Programadas</h6>
                        <h4 class="subtitle-count"><b data-panel="tSinAsignar">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-info" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-business-time"></i> Visitas En Proceso</h6>
                        <h4 class="subtitle-count"><b data-panel="tEnProceso">0</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-6 grid-margin">
                <div class="card">
                    <div class="card-body text-success" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clipboard-check"></i> Visitas Realizadas</h6>
                        <h4 class="subtitle-count"><b data-panel="tEnProceso">0</b></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Visitas a Programar</strong>
                </h6>
                <div>
                    <button class="btn btn-primary btn-sm px-1" onclick="updateTableVisitas()" data-mdb-ripple-init
                        role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_visitas" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-bg-primary">
                                    <th>Ruc</th>
                                    <th>Sucursal</th>
                                    <th class="text-center">Visitas Realizadas</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Visitas Programadas</strong>
                </h6>
                <div>
                    <button class="btn btn-primary btn-sm px-1" onclick="updateTableVProgramadas()" data-mdb-ripple-init
                        role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_vprogramadas" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-bg-primary">
                                    <th>Sucursal</th>
                                    <th>Técnico</th>
                                    <th class="text-center">Fecha Visita</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<button class="d-none" data-mdb-modal-init data-mdb-target="#modal_visitas"></button>
<div class="modal fade" id="modal_visitas" tabindex="-1" aria-labelledby="modal_visitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="form-visita">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="modal_visitasLabel">Asignar Personal Visita <span
                        class="badge badge-success" aria-item="contrato">En Contrato</span></h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                    <div class="list-group list-group-light">
                        <div class="list-group-item">
                            <span aria-item="empresa"></span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                aria-item="direccion"></span>
                        </div>
                        <div class="list-group-item">
                            <div class="row col-12">
                                <div class="col-sm-6">
                                    <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                        aria-item="sucursal"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-3 pb-0 fieldset mb-3">
                    <input type="hidden" id="idSucursal" name="idSucursal">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label mb-0" for="createPersonal">Asignar Personal</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text border-0 ps-0"><i
                                        class="fas fa-chalkboard-user"></i></span>
                                <select class="select-clear" id="createPersonal">
                                    <option value=""></option>
                                    @foreach ($data['usuarios'] as $u)
                                        <option value="{{$u['value']}}" data-value="{{$u['dValue']}}">{{$u['text']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-0" for="fecha_visita">Fecha Visita</label>
                            <div class="input-group" role="button">
                                <label class="input-group-text ps-0 pe-1 border-0"><i
                                        class="far fa-calendar"></i></label>
                                <input type="text" class="form-control rounded" id="fecha_visita" name="fecha_visita"
                                    role="button" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_detalle_visitas" tabindex="-1" aria-labelledby="modal_detalle_visitasLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="modal_detalle_visitasLabel">Asignar Personal Visita <span
                        class="badge badge-success" aria-item="contrato">En Contrato</span></h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                    <div class="list-group list-group-light">
                        <div class="list-group-item">
                            <span aria-item="empresa"></span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                aria-item="direccion"></span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                aria-item="sucursal"></span>
                        </div>
                        <div class="list-group-item">
                            <div class="row col-12">
                                <div class="col-md-3 col-6">
                                    <label class="form-label me-2">Limitacion: </label><span style="font-size: .75rem;"
                                        aria-item="rDias">0</span>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label me-2">Visitas Totales: </label><span
                                        style="font-size: .75rem;" aria-item="vTotal">0</span>
                                </div>
                                <div class="col-md-3 col-6 text-center">
                                    <label class="form-label me-2">Visitas Realizadas: </label><span
                                        style="font-size: .75rem;" aria-item="vRealizada">0</span>
                                </div>
                                <div class="col-md-3 col-6 text-end">
                                    <label class="form-label me-2">Visitas Pendientes: </label><span
                                        style="font-size: .75rem;" aria-item="vPendiente">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <table class="table mb-0 d-none" id="tb_visitas">
                                <thead>
                                    <tr class="text-bg-primary">
                                        <th>Asignado Por</th>
                                        <th class="text-center">Fecha Visita</th>
                                        <th class="text-center">Registrado</th>
                                        <th class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Nota: </label><span style="font-size: .75rem;"
                                aria-item="mensaje"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        formatSelect('modal_visitas');

        $('.modal').on('shown.bs.modal', function () {
            $('#fecha_visita').val(date('Y-m-d'));
        });

        $('.modal').on('hidden.bs.modal', function () {
            // $('#contenedor-personal').addClass('d-none');
            cPersonal.deleteTable();
        });

        $('#fecha_visita').daterangepicker({
            singleDatePicker: true,
            startDate: date('Y-m-d'),
            minDate: date('Y-m-d'),
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

    const cPersonal = new CTable('#createPersonal', {
        thead: ['#', 'Nro. Documento', 'Nombres y Apellidos'],
        tbody: [
            { data: 'id' },
            { data: 'doc' },
            { data: 'nombre' }
        ],
        extract: ['id']
    });

    const tb_visitas = new DataTable('#tb_visitas', {
        autoWidth: true,
        scrollX: true,
        scrollY: 400,
        fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
        ajax: {
            url: `${__url}/visitas/sucursales/index`,
            dataSrc: function (json) {
                // $.each(json.count, function (panel, count) {
                //     $(`b[data-panel="${panel}"]`).html(count);
                // });
                return json;
            },
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'ruc' },
            { data: 'sucursal' },
            {
                data: 'visita', render: function (data, type, row) {
                    badgeOptions = data == 'completado'
                        ? { t: 'Completado', c: 'primary' }
                        : (data ? { 'c': 'info', 't': `${data} Visita${(data > 1) ? 's' : ''}` } : { 'c': 'warning', 't': 'Sin Visitas' });

                    return `<label class="badge badge-${badgeOptions.c}" style="font-size: .7rem;">${badgeOptions.t}</label>`;
                }
            },
            { data: 'acciones' }
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(2), td:eq(3)').addClass('text-center');
        },
        processing: true
    });

    function updateTableVisitas() {
        tb_visitas.ajax.reload();
    }

    function DetalleVisita(id) {
        $('#modal_detalle_visitas').modal('show');
        fMananger.formModalLoding('modal_detalle_visitas', 'show');
        $('#tb_visitas').addClass('d-none');
        $('#tb_visitas').find('tbody').html('');
        $('#modal_detalle_visitas [aria-item="mensaje"]').html('');

        $.ajax({
            type: 'GET',
            url: `${__url}/visitas/sucursales/${id}`,
            contentType: 'application/json',
            success: function (data) {
                if (!data.success)
                    boxAlert.box({ i: 'error', t: 'Algo salió mal', h: data.message });

                const dt = data.data;
                $('#idSucursal').val(dt.id);
                $('#modal_detalle_visitas [aria-item="contrato"]').html(dt.contrato ? 'En Contrato' : 'Sin Contrato');
                $('#modal_detalle_visitas [aria-item="empresa"]').html(`${dt.ruc} - ${dt.razonSocial}`);
                $('#modal_detalle_visitas [aria-item="direccion"]').html(dt.direccion);
                $('#modal_detalle_visitas [aria-item="sucursal"]').html(dt.sucursal);
                $('#modal_detalle_visitas [aria-item="vTotal"]').html(dt.totalVisitas);
                $('#modal_detalle_visitas [aria-item="rDias"]').html(dt.diasVisitas);
                if (dt.visitas.length) {
                    dt.visitas.forEach(e => {
                        const estado = {
                            "0": ['warning', 'Pendiente'],
                            "1": ['primary', 'En Proceso'],
                            "2": ['success', 'Culminado'],
                        };
                        $('#tb_visitas').find('tbody').append(`<tr>
                            <td>${e.creador}</td>
                            <td class="text-center">${e.fecha}</td>
                            <td class="text-center">${e.created_at}</td>
                            <td class="text-center"><label class="badge badge-${estado[e.estado][0]}" style="font-size: .8rem;">${estado[e.estado][1]}</label></td>
                        </tr>`);
                    });
                    $('#tb_visitas').removeClass('d-none');
                }
                $('#modal_detalle_visitas [aria-item="mensaje"]').html(dt.message);
                fMananger.formModalLoding('modal_detalle_visitas', 'hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                const obj_error = jqXHR.responseJSON;
                boxAlert.box({ i: 'error', t: 'Error al extraer datos de la incidencia', h: obj_error.message });
                console.log(jqXHR.responseJSON);
            }
        });
    }

    function AsignarVisita(id) {
        $('#modal_visitas').modal('show');
        fMananger.formModalLoding('modal_visitas', 'show');

        $.ajax({
            type: 'GET',
            url: `${__url}/visitas/sucursales/${id}`,
            contentType: 'application/json',
            success: function (data) {
                if (!data.success)
                    boxAlert.box({ i: 'error', t: 'Algo salió mal', h: data.message });

                const dt = data.data;
                $('#idSucursal').val(dt.id);
                $('#modal_visitas [aria-item="contrato"]').html(dt.contrato ? 'En Contrato' : 'Sin Contrato');
                $('#modal_visitas [aria-item="empresa"]').html(`${dt.ruc} - ${dt.razonSocial}`);
                $('#modal_visitas [aria-item="direccion"]').html(dt.direccion);
                $('#modal_visitas [aria-item="sucursal"]').html(dt.sucursal);

                fMananger.formModalLoding('modal_visitas', 'hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                const obj_error = jqXHR.responseJSON;
                boxAlert.box({ i: 'error', t: 'Error al extraer datos de la incidencia', h: obj_error.message });
                console.log(jqXHR.responseJSON);
            }
        });
    }

    document.getElementById('form-visita').addEventListener('submit', function (event) {
        event.preventDefault();
        if (!cPersonal.extract().length)
            return boxAlert.box({
                i: 'warning',
                t: 'Personal',
                h: 'Primero debe asignar un personal'
            });
        fMananger.formModalLoding('modal_visitas', 'show');
        var elementos = this.querySelectorAll('[name]');
        var valid = validFrom(elementos);
        if (!valid.success)
            return fMananger.formModalLoding('modal_visitas', 'hide');
        valid.data.data['personal'] = cPersonal.extract();

        $.ajax({
            type: 'POST',
            url: __url + `/visitas/sucursales/create`,
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': __token,
            },
            data: JSON.stringify(valid.data.data),
            success: function (data) {
                fMananger.formModalLoding('modal_visitas', 'hide');
                if (data.success) {
                    $('#modal_visitas').modal('hide');
                    boxAlert.minbox({ h: data.message });
                    return updateTableVisitas();
                }
                var message = "";
                if (data.hasOwnProperty('validacion')) {
                    for (const key in data.validacion) {
                        message += `<li>${data.validacion[key][0]}</li>`;
                    }
                    message = `<ul>${message}</ul>`;
                }
                boxAlert.box({ i: 'error', t: 'Algo salió mal', h: message });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                const obj_error = jqXHR.responseJSON;
                boxAlert.box({
                    i: 'error',
                    t: 'Ocurrio un error en el processo',
                    h: obj_error.message
                });
                console.log(obj_error);
                fMananger.formModalLoding('modal_visitas', 'hide');
            }
        });
    });


    const tb_vprogramadas = new DataTable('#tb_vprogramadas', {
        autoWidth: true,
        scrollX: true,
        scrollY: 400,
        fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
        ajax: {
            url: `${__url}/visitas/programadas/index`,
            dataSrc: function (json) {
                // $.each(json.count, function (panel, count) {
                //     $(`b[data-panel="${panel}"]`).html(count);
                // });
                return json;
            },
            error: function (xhr, error, thrown) {
                boxAlert.table();
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'sucursal' },
            { data: 'tecnicos' },
            { data: 'fecha' },
            { data: 'estado' },
            { data: 'acciones' }
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(2), td:eq(3)').addClass('text-center');
        },
        processing: true
    });

    function updateTableVProgramadas() {
        tb_vprogramadas.ajax.reload();
    }
</script>
@endsection