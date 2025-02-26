@extends('layout.app')
@section('title', 'Visitas')

@section('style')
<script type="text/javascript" src="{{asset('front/vendor/daterangepicker/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/vendor/daterangepicker/daterangepicker.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('front/vendor/daterangepicker/daterangepicker.css')}}">
<!-- <link rel="stylesheet" href="{{asset('front/css/app/incidencias/registradas.css')}}"> -->
@endsection
@section('content')

<div class="col-12 mb-4">
    <div class="card">
        <div class="card-body form-container">
            <h6 class="text-primary"><i class="fas fa-filter"></i> Filtros de Busqueda</h6>
            <div class="row">
                <div class="col-xxl-5 my-1">
                    <label class="form-label mb-0" for="empresa">Empresa</label>
                    <select id="empresa" name="empresa" class="select-clear">
                        <option value=""></option>
                        @foreach ($data['empresas'] as $key => $val)
                            @if ($val->status)
                                <option value="{{$val->ruc}}" id-empresa="{{$val->id}}">{{$val->ruc . ' - ' . $val->razon_social}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-xxl-3 col-md-8 my-1">
                    <label class="form-label mb-0" for="idGrupo">Sucursal</label>
                    <select id="sucursal" name="sucursal" class="select" disabled="true">
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>
                <div class="col-xxl-2 col-md-4 my-1">
                    <label class="form-label mb-0" for="dateRango">Rango</label>
                    <input type="text" class="form-control" id="dateRango" name="dateRango" role="button" readonly>
                </div> 
                <div class="align-items-end col-xxl-2 d-flex my-1 justify-content-end">
                    <div>
                        <button type="button" class="btn btn-primary" data-mdb-ripple-init onclick="filtroBusqueda()">
                            <i class="fas fa-magnifying-glass"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title col-form-label-sm text-primary mb-3">
                <strong>Visitas Terminadas</strong>
            </h6>
            <div>
                <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_vterminadas" class="table table-hover text-nowrap" style="width:100%">
                        <thead>
                            <tr class="text-bg-primary">
                                <th>#</th>
                                <th>N° Orden</th>
                                <th>Fecha Servicio</th>
                                <th>Tecnico</th>
                                <th>Empresa</th>
                                <th>Sucursal</th>
                                <th>Iniciada</th>
                                <th>Terminada</th>
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
<button class="d-none" data-mdb-modal-init data-mdb-target="#modal_seguimiento_visitasp"></button>
<div class="modal fade" id="modal_seguimiento_visitasp" aria-labelledby="modal_detalle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title"><i class="fas fa-book-open"></i> Seguimiento de la visita</h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-end" aria-item="estado"></div>
                <div class="col-md-12 col-sm-12 col-xs-12 my-2">
                    <div class="list-group list-group-light">
                        <div class="list-group-item">
                            <span aria-item="empresa">20506467854 - CORPORACION JULCAN S.A.</span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Direccion:</label><span style="font-size: .75rem;"
                                aria-item="direccion">AV. GERARDO UNGER N° 3689 MZ D LT 26 INDEPENDENCIA</span>
                        </div>
                        <div class="list-group-item">
                            <label class="form-label me-2">Sucursal: </label><span style="font-size: .75rem;"
                                aria-item="sucursal">E/S INDEPENDENCIA</span>
                        </div>
                    </div>
                </div>
                <h6 class="font-weight-semibold col-form-label text-primary mt-2">Seguimiento Visita</h6>
                <div class="">
                    <ul class="list-group list-group-light" id="content-seguimiento">
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link " data-mdb-ripple-init
                    data-mdb-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const empresas = <?php echo json_encode($data['empresas']); ?>;
    const sucursales = <?php echo json_encode($data['sucursales']); ?>;
</script>
<script src="{{asset('front/js/app/visitas/terminadas.js')}}"></script>
@endsection