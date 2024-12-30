@extends('layout.app')
@section('title', 'Visitas')

@section('style')
<!-- <link rel="stylesheet" href="{{asset('front/css/app/incidencias/registradas.css')}}"> -->
@endsection
@section('content')

<div class="col-12 mb-4">
    <div class="card">
        <div class="card-body">
            <h6 class="text-primary"><i class="fas fa-filter"></i> Filtro Avanzado</h6>
            <div class="row">
                <div class="col-xl-5 col-md-8 my-1">
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
                <div class="col-xl-3 col-md-4 my-1">
                    <label class="form-label mb-0" for="idGrupo">Sucursal</label>
                    <select id="sucursal" name="sucursal" class="select" disabled="true">
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>
                <div class="col-xl-2 col-6 my-1">
                    <label class="form-label mb-0" for="razonSocial1">Fecha Inicio</label>
                    <input type="date" class="form-control" id="razonSocial1" name="razonSocial1">
                </div>
                <div class="col-xl-2 col-6 my-1">
                    <label class="form-label mb-0" for="razonSocial">Fecha Final</label>
                    <input type="date" class="form-control" id="razonSocial" name="razonSocial">
                </div>
                <div class="col-12 my-1 text-end">
                    <button type="button" class="btn btn-primary" data-mdb-ripple-init>
                        <i class="fas fa-magnifying-glass"></i> Buscar
                    </button>
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
                <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#modal_visitas">
                    <i class="fas fa-book-medical"></i>
                    Nuevo Visita
                </button>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_visitas" class="table table-hover text-nowrap" style="width:100%">
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
    const sucursales = <?php echo json_encode($data['sucursales']); ?>;
</script>
<script src="{{asset('front/vendor/signature/signature_pad.js')}}"></script>
<script src="{{asset('front/js/app/visitas/terminadas.js')}}"></script>
@endsection