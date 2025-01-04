@extends('layout.app')
@section('title', 'Empresas')

@section('content')

<style>
</style>

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Listado de Empresas</h4>
            <div class="mb-3">
                <!-- <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modal_frm_empresas"> -->
                <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#modal_empresas">
                    <i class="fas fa-plus me-1"></i>
                    Nueva Empresa
                </button>
                <button class="btn btn-primary btn-sm px-2" onclick="updateTable()">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_empresas" class="table text-nowrap" style="width: 100%;">
                        <thead>
                            <tr class="text-bg-primary">
                                <th>#</th>
                                <th>Grupo</th>
                                <th>Ruc</th>
                                <th>Empresa</th>
                                <th>Contrato</th>
                                <th>Fecha Registro</th>
                                <th>Actualizado</th>
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

<div id="modal_empresas" class="modal fade" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" id="form-empresa">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="modal_empresasLabel">REGISTRAR EMPRESA</h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12 mb-2">
                    <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
                </div>
                <div class="row">
                    <h6 class="tittle text-primary my-3">Datos Empresa</h6>
                    <input type="hidden" name="id" id="id">
                    <div class="col-lg-4 col-7 mb-2">
                        <label class="form-label mb-0 required" for="idGrupo">Grupo</label>
                        <select id="idGrupo" name="idGrupo" require="Grupo" class="select-clear">
                            <option value=""></option>
                            @foreach ($data['grupos'] as $key => $val)
                                @if ($val->status)
                                    <option value="{{$val->id}}">{{$val->nombre}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-5 mb-2">
                        <label class="form-label mb-0" for="ruc">Ruc</label>
                        <input class="form-control" id="ruc">
                    </div>
                    <div class="col-lg-5 col-12 mb-2">
                        <label class="form-label mb-0 required" for="razonSocial">Razon Social</label>
                        <input type="text" class="form-control" id="razonSocial" name="razonSocial"
                            require="Razon Social">
                    </div>

                    <div class="col-lg-7 mb-2">
                        <label class="form-label mb-0 required" for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" require="Dirección">
                    </div>
                    <div class="col-lg-5 mb-2">
                        <label class="form-label mb-0 required" for="ubigeo">Ubigeo</label>
                        <select id="ubigeo" name="ubigeo" require="Ubigeo" class="select-clear">
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-6 mb-2">
                        <label class="form-label mb-0 required" for="contrato">Contrato</label>
                        <select id="contrato" name="contrato" require="Contrato" class="select">
                            <option value=""></option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-6 mb-2">
                        <label class="form-label mb-0 required" for="facturacion">Facturacion</label>
                        <select id="facturacion" name="facturacion" require="Facturacion" class="select">
                            <option value=""></option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-6 mb-2">
                        <label class="form-label mb-0 required" for="prico">Prico</label>
                        <select id="prico" name="prico" require="Prico" class="select">
                            <option value=""></option>
                            <option value="1">Ose</option>
                            <option value="0">Sunat</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-6 mb-2">
                        <label class="form-label mb-0 required" for="eds">Eds</label>
                        <select id="eds" name="eds" require="Eds" class="select">
                            <option value=""></option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-4 mb-2">
                        <label class="form-label mb-0" for="idNube">Id Nube</label>
                        <input type="text" class="form-control" id="idNube" name="idNube">
                    </div>

                    <div class="col-lg-2 col-4 mb-2">
                        <label class="form-label mb-0" for="visitas">Visitas</label>
                        <input type="text" class="form-control" id="visitas" name="visitas">
                    </div>
                    <div class="col-lg-2 col-4 mb-2">
                        <label class="form-label mb-0" for="diasVisita">Dias Visita</label>
                        <input type="text" class="form-control" id="diasVisita" name="diasVisita">
                    </div>
                    <div class="col-lg-2 col-4 mb-2">
                        <label class="form-label mb-0" for="mantenimientos">Mantenimientos</label>
                        <input type="text" class="form-control" id="mantenimientos" name="mantenimientos">
                    </div>
                    <div class="col-lg-2 col-4 mb-2">
                        <label class="form-label mb-0 required" for="estado">Estado</label>
                        <select class="select" id="estado" name="estado" require="Estado">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-4 mb-2">
                        <label class="form-label mb-0 required" for="codVisita">Cod. Visita</label>
                        <select class="select" id="codVisita" name="codVisita" require="Estado">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </div>

                    <h6 class="tittle text-primary my-3">Datos Contacto</h6>

                    <div class="col-lg-3 mb-2">
                        <label class="form-label mb-0" for="cargo">Cargo</label>
                        <select class="select-clear" id="cargo" name="cargo">
                            <option value=""></option>
                            @foreach ($data['cargos'] as $key => $val)
                                @if ($val->estatus)
                                    <option value="{{$val->id_cargo}}">{{$val->descripcion}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <label class="form-label mb-0" for="encargado">Encargado</label>
                        <input type="text" class="form-control" id="encargado" name="encargado" maxlength="100">
                    </div>
                    <div class="col-lg-2 col-4 mb-2">
                        <label class="form-label mb-0" for="telefono">Telefono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" maxlength="9">
                    </div>
                    <div class="col-lg-4 col-8">
                        <label class="form-label mb-0" for="correo">Correo</label>
                        <input type="text" class="form-control" id="correo" name="correo" maxlength="250">
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
<!-- jQuery Mask Plugin CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script src="{{asset('front/vendor/ubigeos-peru/ubigeo.js')}}"></script>
<script src="{{asset('front/js/app/empresas/empresas.js')}}"></script>
@endsection