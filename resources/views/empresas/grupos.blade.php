@extends('layout.app')
@section('title', 'Grupos')

@section('style')
<link rel="stylesheet" href="{{asset('front/css/app/incidencias/registradas.css')}}">
@endsection
@section('content')


<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Grupos Registrados</h4>
            <div>
                <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#modal_grupos">
                    <i class="fas fa-book-medical"></i>
                    Nuevo Grupo
                </button>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_grupos" class="table table-hover text-nowrap" style="width:100%">
                        <thead>
                            <tr class="text-bg-primary">
                                <th>#</th>
                                <th>Grupo</th>
                                <th>Fecha Registro</th>
                                <th>Actualizado</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
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
<div class="modal fade" id="modal_grupos" tabindex="-1" aria-labelledby="modal_gruposLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="form-grupo">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_gruposLabel">REGISTRAR GRUPO</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="id" id="id">
                    <div class="col-lg-8 col-sm-12 mb-2 required">
                        <label class="form-label mb-0" for="grupo">Grupo</label>
                        <input type="text" class="form-control" id="grupo" name="grupo" require="Grupo">
                    </div>
                    <div class="col-lg-4 col-sm-12 mb-2 required">
                        <label class="form-label mb-0" for="estado">Estado</label>
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
<script src="{{asset('front/vendor/signature/signature_pad.js')}}"></script>
<script src="{{asset('front/js/app/empresas/grupos.js')}}"></script>
@endsection