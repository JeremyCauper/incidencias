@extends('layout.app')
@section('title', 'Sub Menu')

@section('cabecera')
<!-- <link rel="stylesheet" href="{{secure_asset('front/css/app/incidencias/registradas.css')}}"> -->
@endsection
@section('content')


<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title col-form-label-sm text-primary mb-3">
                <strong>Listado de Sub Menu</strong>
            </h6>
            <div>
                <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#modal_submenu">
                    <i class="fas fa-plus"></i>
                    Nuevo Sub Menu
                </button>
                <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_submenu" class="table table-hover text-nowrap" style="width:100%">
                        <thead>
                            <tr class="text-bg-primary text-center">
                                <th class="text-start">Menu</th>
                                <th class="text-start">Categoria</th>
                                <th class="text-start">Descripcion</th>
                                <th class="text-start">Ruta</th>
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
<div class="modal fade" id="modal_submenu" tabindex="-1" aria-labelledby="modal_submenuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="form-submenu">
            <div class="modal-header  bg-primary text-white">
                <h6 class="modal-title" id="modal_submenuLabel">REGISTRAR MENU</h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="id" id="id">
                    <div class="col-lg-7 col-8 mb-2">
                        <label class="form-label mb-0" for="menu">Menu</label>
                        <select class="select-icons" id="menu">
                            <option value=""></option>
                            @foreach ($data['menus'] as $key => $val)
                                @if (!$val['eliminado'])
                                    <option value="{{$val['id']}}">{{$val['icon']}}::{{$val['descripcion']}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5 col-4 mb-2">
                        <label class="form-label mb-0" for="categoria">Categoria</label>
                        <input class="form-control" id="categoria">
                    </div>
                    <div class="col-lg-4 col-5 mb-2">
                        <label class="form-label mb-0" for="descripcion">Descripcion</label>
                        <input class="form-control" id="descripcion">
                    </div>
                    <div class="col-lg-6 col-7 mb-2">
                        <label class="form-label mb-0" for="ruta">Ruta</label>
                        <input class="form-control" id="ruta">
                    </div>
                    <div class="col-lg-2 mb-2">
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
<script src="{{secure_asset('front/js/app/mantenimiento/menu/submenu.js')}}"></script>
@endsection