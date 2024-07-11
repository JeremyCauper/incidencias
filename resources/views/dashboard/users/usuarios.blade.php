@extends('layout.app')
@section('title', 'Panel de Control')

@section('content')

<style>
    .modal-dialog .form-label {
        color: #9FA6B2;
    }
</style>

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Listado de usuarios</h4>
            <div>
                <button class="btn btn-primary btn-sm" onclick="$('#modal_frm_usuarios').modal('show')">
                    <i class="mdi mdi-account-plus me-2"></i>
                    Nuevo Usuario
                </button>
                <button class="btn btn-primary btn-sm">
                    <i class="mdi mdi-autorenew"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre Personal</th>
                                <th>Tipo Usuario</th>
                                <th>Usuario</th>
                                <th>Contraseña</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_frm_usuarios" class="modal fade" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="card-title mb-4 text-primary"><b>REGISTRAR NUEVO USUARIO</b></h4>
                <form id="form-clientes">
                    <div class="row">
                        <div class="col-xl-3 col-sm-7 mb-3">
                            <label class="form-label mb-0" for="id_area"><b>Area *</b></label>
                            <select id="id_area" class="form-control form-control-sm">
                                <option value="">-- Seleccione --</option>
                                <option value="1">Soportes</option>
                                <option value="2">Facturacion</option>
                                <option value="3">Supervisor</option>
                                <option value="4">Reportes</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-sm-5 mb-3">
                            <label class="form-label mb-0" for="n_dni"><b>Dni *</b></label>
                            <input type="text" class="form-control form-control-sm" placeholder="Número de Dni"
                                id="n_dni" maxlength="8">
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="nom_usu"><b>Nombres *</b></label>
                            <input type="text" class="form-control form-control-sm" id="nom_usu">
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="ape_usu"><b>Apellidos *</b></label>
                            <input type="text" class="form-control form-control-sm" id="ape_usu">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xxl-4 col-lg-7 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="email_usu"><b>Email</b></label>
                            <input type="text" class="form-control form-control-sm" id="email_usu">
                        </div>
                        <div class="col-xxl-2 col-lg-5 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="fechan_usu"><b>Fecha de Nacimiento *</b></label>
                            <input type="date" class="form-control form-control-sm" id="fechan_usu">
                        </div>
                        <div class="col-xxl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="usuario"><b>Usuario *</b></label>
                            <input type="text" class="form-control form-control-sm" id="usuario">
                        </div>
                        <div class="col-xxl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="contrasena"><b>Contraseña *</b></label>
                            <input type="text" class="form-control form-control-sm" id="contrasena">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <label class="form-label mb-0" for="foto_perfil"><b>Foto de Perfil</b></label>
                            <input type="file" class="form-control form-control-sm" id="foto_perfil">
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label class="form-label mb-0" for="firma_digital"><b>Firma Digital</b></label>
                            <input type="file" class="form-control form-control-sm" id="firma_digital">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-0" for="tipo_acceso"><b>TIPO PERSONAL *</b></label>
                        <select id="tipo_acceso" class="form-control form-control-sm">
                            <option></option>
                            <option value="1">Gerencial</option>
                            <option value="2">Administrativo</option>
                            <option value="3">Tecnico</option>
                            <option value="4">Personalizado</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal"
                    onclick="$('#modal_frm_usuarios').modal('hide')">Cerrar</button>
                <button type="button" class="btn btn-indigo btn-sm col-form-label-sm"
                    onclick="clientes.saveChangeCliente()">
                    <i class="icon-floppy-disk"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection