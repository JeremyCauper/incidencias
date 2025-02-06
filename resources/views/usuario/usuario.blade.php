@extends('layout.app')
@section('title', 'Panel de Control')

@section('style')
<link rel="stylesheet" href="{{asset('front/css/app/usuario/usuarios.css')}}">
@endsection
@section('content')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title col-form-label-sm text-primary mb-3">
                <strong>Listado de Usuarios</strong>
            </h6>
            <div class="mb-3">
                <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#modal_usuarios">
                    <i class="fas fa-user-plus me-2"></i>
                    Nuevo Usuario
                </button>
                <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_usuario" class="table text-nowrap" style="width: 100%;">
                        <thead>
                            <tr class="text-bg-primary">
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

<div id="modal_usuarios" class="modal fade" tabindex="-1" aria-labelledby="modal_usuarios" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" id="form-usuario">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="modal_usuariosLabel">REGISTRAR USUARIO</h6>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12 mb-2">
                    <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios
                        (*)</span>
                </div>
                <div class="row">

                    <div class="col-xl-3 col-6 mb-3">
                        <input type="hidden" name="id" id="id">
                        <label class="form-label mb-0" for="id_area">Area</label>
                        <select id="id_area" class="select">
                            <option value="">-- Seleccione --</option>
                            @foreach ($areas as $r)
                                <option value="{{$r->id_area}}">{{$r->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-3 col-6 mb-3">
                        <label class="form-label mb-0" for="n_doc">Dni/Carnet E.</label>
                        <input class="form-control" id="n_doc">
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <label class="form-label mb-0" for="nom_usu">Nombres</label>
                        <input class="form-control" id="nom_usu">
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <label class="form-label mb-0" for="ape_usu">Apellidos</label>
                        <input class="form-control" id="ape_usu">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 col-sm-4 mb-3">
                        <label class="form-label mb-0" for="emailp_usu">Correo Personal</label>
                        <input class="form-control" id="emailp_usu">
                    </div>
                    <div class="col-lg-5 col-sm-4 mb-3">
                        <label class="form-label mb-0" for="emailc_usu">Correo Corporativo</label>
                        <input class="form-control" id="emailc_usu">
                    </div>
                    <div class="col-lg-2 col-sm-4 mb-3 form-date">
                        <label class="form-label mb-0" for="fechan_usu">Fecha de Nacimiento</label>
                        <input class="form-control" id="fechan_usu">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-6 mb-3">
                        <label class="form-label mb-0" for="telp_usu">Tel. Personal</label>
                        <input class="form-control" id="telp_usu">
                    </div>
                    <div class="col-lg-3 col-6 mb-3">
                        <label class="form-label mb-0" for="telc_usu">Tel. Corporativo</label>
                        <input class="form-control" id="telc_usu">
                    </div>
                    <div class="col-lg-3 col-6 mb-3">
                        <label class="form-label mb-0" for="usuario">Usuario</label>
                        <input class="form-control" id="usuario">
                    </div>
                    <div class="col-lg-3 col-6 mb-3">
                        <label class="form-label mb-0" for="contrasena">Contraseña</label>
                        <input class="form-control" id="contrasena">
                    </div>
                </div>
                <div class="col-12 text-center d-flex justify-content-center">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0" for="foto_perfil"><b>Foto de Perfil</b></label>
                            <div class="col-12 p-1 text-center content-image">
                                <div class="overlay">
                                    <button class="btn-img removeImgButton" style="display: none;" id="removeButton"
                                        type="button" button-reset><i class="fas fa-xmark"></i></button>
                                    <button class="btn-img uploadImgButton" id="uploadButton" type="button"><i
                                            class="fas fa-arrow-up-from-bracket"></i></button>
                                    <button class="btn-img expandImgButton" type="button"
                                        onclick="PreviImagenes(PreviFPerfil.src);"><i
                                            class="fas fa-expand"></i></button>
                                </div>
                                <input type="file" class="d-none" id="foto_perfil">
                                <input type="text" class="d-none" name="foto_perfil" id="txtFotoPerfil">
                                <img id="PreviFPerfil" src="{{asset('front/images/auth/user_auth.jpg')}}"
                                    imageDefault="{{asset('front/images/auth/user_auth.jpg')}}">
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0" for="firma_digital"><b>Firma Digital</b></label>
                            <div class="col-12 p-1 text-center content-image">
                                <div class="overlay">
                                    <button class="btn-img removeImgButton" style="display: none;" id="removeImgFirma"
                                        type="button" button-reset><i class="fas fa-xmark"></i></button>
                                    <button class="btn-img mx-1 uploadImgButton" id="uploadImgFirma" type="button"><i
                                            class="fas fa-arrow-up-from-bracket"></i></button>
                                    <button class="btn-img mx-1 uploadImgButton" id="createFirma" type="button"><i
                                            class="fas fa-pencil"></i></button>
                                    <button class="btn-img expandImgButton" type="button"
                                        onclick="PreviImagenes(PreviFirma.src);"><i class="fas fa-expand"></i></button>
                                </div>
                                <input type="file" class="d-none" id="firma_digital">
                                <input type="text" class="d-none" name="firma_digital" id="textFirmaDigital">
                                <img id="PreviFirma" src="{{asset('front/images/firms/firm.png')}}"
                                    imageDefault="{{asset('front/images/firms/firm.png')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label mb-0" for="tipo_acceso">Tipo Personal</label>
                    <select class="select" id="tipo_acceso">
                        <option value="">-- Seleccione --</option>
                        @foreach ($tipoAcceso as $r)
                            <option value="{{$r->id_tipo_acceso}}">{{$r->descripcion}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label mb-0">Administrar Modulos del Sistema</label>
                    <div class="border rounded p-2" id="content-permisos">
                        <div class="row">
                            @foreach ($menus as $menu)
                                <div class="col-xl-3 col-md-6 mb-2">
                                    <ul class="tree">
                                        <li class="parent">
                                            <input type="checkbox" id="menu{{ $menu->id_menu }}"
                                                value="{{ $menu->id_menu }}">
                                            <label class="parent-label" for="menu{{ $menu->id_menu }}">
                                                <i class="{{ $menu->icon }}"></i> {{ $menu->descripcion }}
                                            </label>

                                            @if (!empty($menu->submenu))
                                                <ul>
                                                    @foreach ($menu->submenu as $categoria => $submenus)
                                                        @if ($categoria !== 'sin_categoria' || count($menu->submenu) > 1)
                                                            <li class="child-categoria">
                                                                {{ $categoria === 'sin_categoria' ? 'Otros' : $categoria }}
                                                            </li>
                                                        @endif
                                                        @foreach ($submenus as $submenu)
                                                            <li class="child">
                                                                <input type="checkbox"
                                                                    id="menu{{ $submenu->id_menu }}-item{{ $submenu->id_submenu }}"
                                                                    value="{{ $submenu->id_submenu }}">
                                                                <label class="child-label"
                                                                    for="menu{{ $submenu->id_menu }}-item{{ $submenu->id_submenu }}">
                                                                    {{ $submenu->descripcion }}
                                                                </label>
                                                            </li>
                                                        @endforeach
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
                <button type="button" class="btn btn-link btn-sm" data-mdb-ripple-init
                    data-mdb-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init>
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const imgFirmDefault = "{{asset('front/images/firms/firm.png')}}";
    const imgUserDefault = "{{asset('front/images/auth/user_auth.jpg')}}";
</script>
<script src="{{asset('front/vendor/signature/signature_pad.js')}}"></script>
<script src="{{asset('front/js/app/usuario/usuarios.js')}}"></script>
@endsection