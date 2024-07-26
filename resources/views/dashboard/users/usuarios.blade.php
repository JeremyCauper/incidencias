@extends('layout.app')
@section('title', 'Panel de Control')
@section('style')
    <link rel="stylesheet" href="{{asset('front/css/app/usuarios.css')}}">
@endsection
@section('content')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Listado de usuarios</h4>
            <div class="mb-3">
                <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modal_frm_usuarios">
                    <i class="fas fa-user-plus me-2"></i>
                    Nuevo Usuario
                </button>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_usuario" class="table text-nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nro. Documento</th>
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

<div id="modal_frm_usuarios" class="modal fade" tabindex="-1" aria-labelledby="modal_frm_usuarios" aria-hidden="true">
    <form id="form-usuario" frm-accion="0" idu="">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content" style="position: relative;">
                <div class="modal-body">
                    <h5 class="card-title mb-4 text-primary"><b>CREAR NUEVO USUARIO</b></h5>
                    <div class="col-12 mb-2">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-6 mb-3">
                            <label class="form-label mb-0" for="id_area"><b>Area <span class="text-danger">*</span></b></label>
                            <select id="id_area" class="select" name="id_area" require="Area">
                                <option value="">-- Seleccione --</option>
                                @foreach ($areas as $r)
                                    <option value="{{$r->id_area}}">{{$r->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-6 mb-3">
                            <label class="form-label mb-0" for="n_doc"><b>Dni/Carnet E.<span class="text-danger">*</span></b></label>
                            <div class="input-group">
                                <input type="search" class="form-control" id="n_doc" name="n_doc" maxlength="20" require="Dni/Carnet E.">
                                <span class="input-group-append">
                                    <button class="btn btn-primary px-2" type="button" id="conDoc"  data-mdb-ripple-init style="border-radius: 0 .25rem .25rem 0;">
                                        <i class="fas fa-magnifying-glass"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="nom_usu"><b>Nombres <span class="text-danger">*</span></b></label>
                            <input type="text" class="form-control" id="nom_usu" name="nom_usu" require="Nombres">
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="ape_usu"><b>Apellidos <span class="text-danger">*</span></b></label>
                            <input type="text" class="form-control" id="ape_usu" name="ape_usu" require="Apellidos">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-sm-4 mb-3">
                            <label class="form-label mb-0" for="emailp_usu"><b>Correo Personal</b></label>
                            <input type="text" class="form-control" id="emailp_usu" name="emailp_usu">
                        </div>
                        <div class="col-lg-5 col-sm-4 mb-3">
                            <label class="form-label mb-0" for="emailc_usu"><b>Correo Corporativo</b></label>
                            <input type="text" class="form-control" id="emailc_usu" name="emailc_usu">
                        </div>
                        <div class="col-lg-2 col-sm-4 mb-3 form-date">
                            <label class="form-label mb-0" for="fechan_usu"><b>Fecha de Nacimiento <span class="text-danger">*</span></b></label>
                            <input type="text" class="form-control" id="fechan_usu" name="fechan_usu" require="Fecha de Nacimiento">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-6 mb-3">
                            <label class="form-label mb-0" for="telp_usu"><b>Tel. Personal</b></label>
                            <input type="text" class="form-control" id="telp_usu" name="telp_usu">
                        </div>
                        <div class="col-lg-3 col-6 mb-3">
                            <label class="form-label mb-0" for="telc_usu"><b>Tel. Corporativo</b></label>
                            <input type="text" class="form-control" id="telc_usu" name="telc_usu">
                        </div>
                        <div class="col-lg-3 col-6 mb-3">
                            <label class="form-label mb-0" for="usuario"><b>Usuario <span class="text-danger">*</span></b></label>
                            <input type="text" class="form-control" id="usuario" name="usuario" require="Usuario">
                        </div>
                        <div class="col-lg-3 col-6 mb-3">
                            <label class="form-label mb-0" for="contrasena"><b>Contraseña <span class="text-danger">*</span></b></label>
                            <input type="text" class="form-control" id="contrasena" name="contrasena" require="Contraseña">
                        </div>
                    </div>
                    <div class="col-12 text-center d-flex justify-content-center">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label mb-0" for="foto_perfil"><b>Foto de Perfil</b></label>
                                <div class="col-12 p-1 text-center content-image">
                                    <div class="overlay">
                                        <button class="btn-img removeImgButton" style="display: none;" id="removeButton" type="button" button-reset><i class="fas fa-xmark"></i></button>
                                        <button class="btn-img uploadImgButton" id="uploadButton" type="button"><i class="fas fa-arrow-up-from-bracket"></i></button>
                                        <button class="btn-img expandImgButton" type="button" onclick="PreviImagenes(PreviFPerfil.src);"><i class="fas fa-expand"></i></button>
                                    </div>
                                    <input type="file" class="d-none" id="foto_perfil">
                                    <input type="text" class="d-none" name="foto_perfil" id="txtFotoPerfil">
                                    <img id="PreviFPerfil" src="{{asset('front/images/auth/user_auth.jpg')}}" imageDefault="{{asset('front/images/auth/user_auth.jpg')}}">
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label mb-0" for="firma_digital"><b>Firma Digital</b></label>
                                <div class="col-12 p-1 text-center content-image">
                                    <div class="overlay">
                                        <button class="btn-img removeImgButton" style="display: none;" id="removeImgFirma" type="button" button-reset><i class="fas fa-xmark"></i></button>
                                        <button class="btn-img mx-1 uploadImgButton" id="uploadImgFirma" type="button"><i class="fas fa-arrow-up-from-bracket"></i></button>
                                        <button class="btn-img mx-1 uploadImgButton" id="createFirma" type="button"><i class="fas fa-pencil"></i></button>
                                        <button class="btn-img expandImgButton" type="button" onclick="PreviImagenes(PreviFirma.src);"><i class="fas fa-expand"></i></button>
                                    </div>
                                    <input type="file" class="d-none" id="firma_digital">
                                    <input type="text" class="d-none" name="firma_digital" id="textFirmaDigital">
                                    <img id="PreviFirma" src="{{asset('front/images/firms/firm.png')}}" imageDefault="{{asset('front/images/firms/firm.png')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-0" for="tipo_acceso"><b>Tipo Personal <span class="text-danger">*</span></b></label>
                        <select id="tipo_acceso" name="tipo_acceso" class="select" require="Tipo Personal">
                            <option value="">-- Seleccione --</option>
                                @foreach ($tipoAcceso as $r)
                                    <option value="{{$r->id_tipo_acceso}}">{{$r->descripcion}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label mb-0" for="ape_usu"><b>Administrar Permisos del Sistema <span class="text-danger">*</span></b></label>
                        <div class="border rounded p-2">
                            <div class="row">
                                @foreach ($menu as $m)
                                <div class="col-xl-3 col-md-6 mb-2">
                                    <ul class="treeview">
                                        <li class="{{'menu' . (count($m['submenu']) ? '' : '-only')}}">
                                            <input type="checkbox" class="{{count($m['submenu']) ? 'inputMenu' : ''}}" id="menu{{$m['id_m']}}" value="{{$m['id_m']}}"/>
                                            <label for="menu{{$m['id_m']}}"><i class="{{$m['icon']}}"></i> {{$m['text']}}</label>
                                            @if (count($m['submenu']))
                                            <ul class="submenu">
                                                @foreach ($m['submenu'] as $sm)
                                                <li>
                                                    <input type="checkbox" class="inputSubMenu" id="submenu{{$sm['id_sm']}}" value="{{$sm['id_sm']}}" disabled="true"/>
                                                    <label for="submenu{{$sm['id_sm']}}">{{$sm['text']}}</label>
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<!-- jQuery Mask Plugin CDN -->
<script>
    const __fecha = "{{ (date('Y') - 18) . '-' . date('m-d')}}";
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="{{asset('front/vendor/signature/signature_pad.min.js')}}"></script>
<script src="{{asset('front/js/app/usuarios.js')}}"></script>
@endsection