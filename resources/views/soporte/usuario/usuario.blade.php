@extends('layout.app')
@section('title', 'Panel de Control')

@section('cabecera')
    <link rel="stylesheet" href="{{secure_asset('front/css/app/usuario/usuarios.css')}}?v={{ config('app.version') }}">
@endsection
@section('content')

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-primary mb-3">
                    <strong>Listado de Usuarios</strong>
                </h6>
                <div class="mb-3">
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#modal_usuarios">
                        <i class="fas fa-user-plus me-2"></i>
                        Nuevo Usuario
                    </button>
                    <button class="btn btn-primary" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_usuario" class="table text-nowrap" style="width: 100%;">
                            <thead>
                                <tr class="text-center">
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
                        <script>
                            const tb_usuario = new DataTable('#tb_usuario', {
                                scrollX: true,
                                scrollY: 300,
                                ajax: {
                                    url: `${__url}/soporte/control-de-usuario/personal-rci/index`,
                                    dataSrc: "",
                                    error: function (xhr, error, thrown) {
                                        boxAlert.table();
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'ndoc_usuario' },
                                    { data: 'personal' },
                                    {
                                        data: 'tipo_acceso', render: function (data, dataSet, row) {
                                            return `<label class="badge badge-${tipoAcceso[data].color}" style="font-size: .7rem;">${tipoAcceso[data].descripcion}</label>`;
                                        }
                                    },
                                    { data: 'usuario' },
                                    { data: 'pass_view' },
                                    { data: 'estado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).find('td:eq(0), td:eq(2), td:eq(5), td:eq(6)').addClass('text-center');
                                    $(row).find('td:eq(6)').addClass(`td-acciones`);
                                },
                                processing: true
                            });
                        </script>
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
                        <input type="hidden" name="id" id="id">
                        <div class="col-xl-3 col-6 mb-3">
                            <select id="id_area" class="select">
                                <option value="">Seleccione...</option>
                                @foreach ($areas as $r)
                                    <option value="{{$r['id']}}">{{$r['descripcion']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-6 mb-3">
                            <input class="form-control" id="n_doc">
                        </div>
                        <div class="col-xl-3 col-6 mb-3">
                            <input class="form-control" id="nom_usu">
                        </div>
                        <div class="col-xl-3 col-6 mb-3">
                            <input class="form-control" id="ape_usu">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-6 mb-3">
                            <input class="form-control" id="emailp_usu">
                        </div>
                        <div class="col-lg-5 col-6 mb-3">
                            <input class="form-control" id="emailc_usu">
                        </div>
                        <div class="col-lg-2 mb-3 form-date">
                            <input class="form-control" id="fechan_usu">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-6 mb-3">
                            <input class="form-control" id="telp_usu">
                        </div>
                        <div class="col-lg-3 col-6 mb-3">
                            <input class="form-control" id="telc_usu">
                        </div>
                        <div class="col-lg-3 col-6 mb-3">
                            <input class="form-control" id="usuario">
                        </div>
                        <div class="col-lg-3 col-6 mb-3">
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
                                    <img id="PreviFPerfil" src="{{secure_asset('front/images/auth/user_auth.jpg')}}"
                                        imageDefault="{{secure_asset('front/images/auth/user_auth.jpg')}}">
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
                                    <img id="PreviFirma" src="{{secure_asset('front/images/firms/firm.png')}}"
                                        imageDefault="{{secure_asset('front/images/firms/firm.png')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <select class="select-clear-simple" id="modo_transporte">
                                <option value="">Seleccione...</option>
                                <option value="1">Sin vehículo</option>
                                <option value="2">Motorizado</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <select class="select" id="tipo_acceso">
                                <option value="">Seleccione...</option>
                                @foreach ($tipoAcceso as $r)
                                    <option {{$r['id'] == 3 ? 'selected' : ''}} value="{{$r['id']}}">{{$r['descripcion']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label mb-0">Administrar Modulos del Sistema</label>
                        <div class="border rounded p-2" id="content-permisos">
                            <!-- style="opacity: .4; pointer-events: none;" -->
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
        let tipoAcceso = <?=$tipoAcceso?>;
        const imgFirmDefault = "{{secure_asset('front/images/firms/firm.png')}}";
        const imgUserDefault = "{{secure_asset('front/images/auth/user_auth.jpg')}}";
    </script>
    <script src="{{secure_asset('front/vendor/signature/signature_pad.js')}}?v={{ config('app.version') }}"></script>
    <script src="{{secure_asset('front/js/soporte/usuario/usuarios.js')}}?v={{ config('app.version') }}"></script>
@endsection