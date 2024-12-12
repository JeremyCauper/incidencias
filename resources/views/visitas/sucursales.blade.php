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
            <h4 class="card-title">Visitas Registrados</h4>
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
<div class="modal fade" id="modal_visitas" tabindex="-1" aria-labelledby="modal_visitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="form-visita">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_visitasLabel">REGISTRAR VISITA</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="id" id="id">
                    <div class="col-lg-8 col-sm-12 mb-2">
                        <label class="form-label mb-0" for="visita">Visita *</label>
                        <input type="text" class="form-control" id="visita" name="visita" require="Visita">
                    </div>
                    <div class="col-lg-4 col-sm-12 mb-2">
                        <label class="form-label mb-0" for="estado">Estado *</label>
                        <select class="select" id="estado" name="estado" require="Estado">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init
                    data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
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
            { data: 'visita', render: function(data, type, row) {
                    let visita = data ? {'c': 'info', 't': data + ' Visita' + (data>1) ? 's' : ''} : {'c': 'warning', 't': 'Sin Visitas'};
                    return `<label class="badge badge-${visita.c}" style="font-size: .7rem;">${visita.t}</label>`;
                }
            },
            { data: 'id', render: function(data, type, row) {
                    return '';
                }
            }
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(2), td:eq(3)').addClass('text-center');
        },
        processing: true
    });

    function updateTable() {
        tb_vsucursales.ajax.reload();
    }
</script>
@endsection