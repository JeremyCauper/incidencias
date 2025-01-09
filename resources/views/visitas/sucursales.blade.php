@extends('layout.app')
@section('title', 'Visitas')

@section('style')
<!-- <link rel="stylesheet" href="{{asset('front/css/app/incidencias/registradas.css')}}"> -->
@endsection
@section('content')

<div class="col-12 mb-4">
    <div class="accordion" id="accordionExampleY">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOneY">
                <button data-mdb-collapse-init class="accordion-button" type="button" data-mdb-target="#collapseOneY"
                    aria-expanded="true" aria-controls="collapseOneY" style="font-weight: bold; font-size: small;">
                    <i class="fas fa-filter"></i> Filtro
                </button>
            </h2>
            <div id="collapseOneY" class="accordion-collapse collapse show" aria-labelledby="headingOneY"
                data-mdb-parent="#accordionExampleY">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-8 my-1">
                            <label class="form-label mb-0" for="empresa">Empresa</label>
                            <select id="empresa" name="empresa" class="select-clear">
                                <option value=""></option>
                                @foreach ($data['empresas'] as $key => $val)
                                    @if ($val['status'])
                                        <option value="{{$val['ruc']}}">{{$val['ruc'] . ' - ' . $val['razonSocial']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-4 col-md-4 my-1">
                            <label class="form-label mb-0" for="idGrupo">Estado Visita</label>
                            <select id="sucursal" name="sucursal" class="select">
                                <option value="10">TODAS</option>
                                <option value="0">SIN INICIAR</option>
                                <option value="1">PARCIAL</option>
                                <option value="2">COMPLETADAS</option>
                            </select>
                        </div>
                        <div class="col-xl-2 my-1 mt-xl-3 text-end" style="padding-top: .3rem;">
                            <button type="button" class="btn btn-primary" data-mdb-ripple-init>
                                <i class="fas fa-magnifying-glass"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Visitas a Programar</h4>
            <div>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_vsucursales" class="table table-hover text-nowrap" style="width:100%">
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
                                <span class="input-group-text border-0 ps-0"><i class="fas fa-chalkboard-user"></i></span>
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
                            <input type="date" class="form-control rounded" id="fecha_visita" name="fecha_visita">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init
                    data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_detalle_visitas" tabindex="-1" aria-labelledby="modal_detalle_visitasLabel" aria-hidden="true">
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
                                    <label class="form-label me-2">Limitacion: </label><span
                                        style="font-size: .75rem;" aria-item="rDias">0</span>
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
                            <table class="table">
                                <thead></thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-mdb-ripple-init
                    data-mdb-dismiss="modal">Cerrar</button>
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

    const tb_vsucursales = new DataTable('#tb_vsucursales', {
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

    function updateTable() {
        tb_vsucursales.ajax.reload();
    }

    function DetalleVisita(id) {
        $('#modal_detalle_visitas').modal('show');
        fMananger.formModalLoding('modal_detalle_visitas', 'show');

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
                    return updateTable();
                }
                var message = "";
                if (data.hasOwnProperty('validacion')) {
                    for (const key in data.validacion) {
                        message +=  `<li>${data.validacion[key][0]}</li>`;
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
</script>
@endsection