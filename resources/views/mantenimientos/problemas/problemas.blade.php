@extends('layout.app')
@section('title', 'Problemas')

@section('cabecera')
<!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/registradas.css')}}"> -->
@endsection
@section('content')


<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title col-form-label-sm text-primary mb-3">
                <strong>Listado de Problemas</strong>
            </h6>
            <div>
                <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#modal_problemas">
                    <i class="fas fa-plus"></i>
                    Nuevo Problema
                </button>
                <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_problemas" class="table table-hover text-nowrap" style="width:100%">
                        <thead>
                            <tr class="text-bg-primary text-center">
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Tipo</th>
                                <th>Fecha Registro</th>
                                <th>Actualizado</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                                <!-- <th class="text-bg-primary px-2 th-acciones">Acciones</th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_problemas" tabindex="-1" aria-labelledby="modal_problemasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="form-problema">
            <div class="modal-header  bg-primary text-white">
                <h6 class="modal-title" id="modal_problemasLabel">REGISTRAR PROBLEMA</h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="id" id="id">
                    <div class="col-lg-4 mb-2">
                        <label class="form-label mb-0" for="codigo">Codigo</label>
                        <input class="form-control" id="codigo">
                    </div>
                    <div class="col-lg-8 mb-2">
                        <label class="form-label mb-0" for="descripcion">Descripcion</label>
                        <input class="form-control" id="descripcion">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label mb-0" for="tipo">Tipo</label>
                        <select class="select" id="tipo">
                            <option selected value="1">REMOTO</option>
                            <option value="2">PRESENCIAL</option>
                        </select>
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label mb-0" for="estado">Estado</label>
                        <select class="select" id="estado">
                            <option selected value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
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

@endsection

@section('scripts')
<script src="{{secure_asset('front/vendor/signature/signature_pad.js')}}"></script>
<script src="{{secure_asset('front/js/app/mantenimiento/problemas/problemas.js')}}"></script>
@endsection