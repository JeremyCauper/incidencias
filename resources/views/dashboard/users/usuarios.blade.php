@extends('layout.app')
@section('title', 'Panel de Control')

@section('content')

<style>
    .modal-dialog .form-label {
        font-size: .8rem;
        color: #9FA6B2;
    }

    .content-image {
        position: relative;
        border: 2px dashed #dee2e6;
        border-radius: 7px;
    }

    .content-image img {
        max-width: 100%;
        min-height: 168px;
        max-height: 300px;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        transition: .5s;
        opacity: 0;
        border: 2px dashed #dee2e6;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .removeImgButton {
        position: absolute;
        top: 4px;
        right: 4px;
    }

    .expandImgButton {
        position: absolute;
        bottom: 4px;
        right: 4px;
        display: none;
    }

    .content-image:hover .overlay,
    .content-image:hover .uploadImgButton {
        opacity: 1;
        transition: .5s;
    }

    .btn-img {
        border: none;
        font-size: .9rem;
        border-radius: 50px;
        width: 35px;
        height: 35px;
        padding: 0;
        background: #ffffff;
        color: #1F3BB3;
    }

    @media (max-width: 576px) {

        .content-image .overlay,
        .content-image .uploadImgButton {
            opacity: 1;
            transition: .5s;
        }

        .content-image img {
            min-height: 140px !important;
            height: 140px;
        }

        .btn-img {
            font-size: .7rem;
            width: 30px;
            height: 30px;
        }

        .expandImgButton {
            display: block;
        }
    }

    #n_doc {
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
    }

    #conDoc {
        height: 44px;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
    }


    .treeview {
        list-style-type: none;
        padding: 0;
    }

    .treeview, .treeview ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .treeview ul {
        position: relative;
    }

    .treeview ul::before {
        content: "";
        display: block;
        width: 2px;
        background: #ccc;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 5px;
        transition: all .2s ease-in;
    }

    .treeview li {
        margin: 0;
        padding: 0 0 0 20px;
        line-height: 25px;
        color: #369;
        font-weight: 700;
        position: relative;
    }

    .treeview .menu .submenu li input {
        margin-left: 5px;
    }

    .treeview .submenu li label::before {
        content: "";
        display: block;
        width: 14.5px;
        height: 2px;
        background: #D3D3D3;
        position: absolute;
        top: 10px;
        left: 5px;
        transition: all .2s ease-in;
    }

    .treeview .menu input[type="checkbox"]:checked ~ .submenu::before,
    .treeview .menu .submenu li input[type="checkbox"]:checked ~ label::before {
        transition: all .2s ease-in;
        background: #007bff;
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
                    <table id="tb_usuario" class="table">
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
    <form id="form-usuario">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 class="card-title mb-4 text-primary"><b>CREAR NUEVO USUARIO</b></h4>
                    <div class="col-12">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-sm-7 mb-3">
                            <label class="form-label mb-0" for="id_area"><b>Area <span class="text-danger">*</span></b></label>
                            <select id="id_area" name="id_area" class="select">
                                <option value="">-- Seleccione --</option>
                                @foreach ($areas as $r)
                                    <option value="{{$r->id_area}}">{{$r->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-sm-5 mb-3">
                            <label class="form-label mb-0" for="n_doc"><b>Dni/Carnet E.<span class="text-danger">*</span></b></label>
                            <div class="input-group">
                                <input type="search" class="form-control form-control-sm" placeholder="Número de Dni" id="n_doc" name="n_doc" maxlength="20">
                                <span class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="conDoc">
                                        <i class="ti-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="nom_usu"><b>Nombres <span class="text-danger">*</span></b></label>
                            <input type="text" class="form-control form-control-sm" id="nom_usu" name="nom_usu">
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="ape_usu"><b>Apellidos <span class="text-danger">*</span></b></label>
                            <input type="text" class="form-control form-control-sm" id="ape_usu" name="ape_usu">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-5 col-lg-4 mb-3">
                            <label class="form-label mb-0" for="emailp_usu"><b>Correo Personal</b></label>
                            <input type="text" class="form-control form-control-sm" id="emailp_usu" name="emailp_usu">
                        </div>
                        <div class="col-xxl-5 col-lg-4 mb-3">
                            <label class="form-label mb-0" for="emailc_usu"><b>Correo Corporativo</b></label>
                            <input type="text" class="form-control form-control-sm" id="emailc_usu" name="emailc_usu">
                        </div>
                        <div class="col-xxl-2 col-lg-4 mb-3">
                            <label class="form-label mb-0" for="fechan_usu"><b>Fecha de Nacimiento <span class="text-danger">*</span></b></label>
                            <input type="date" class="form-control form-control-sm" id="fechan_usu" name="fechan_usu">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="telp_usu"><b>Tel. Personal</b></label>
                            <input type="text" class="form-control form-control-sm" id="telp_usu" name="telp_usu">
                        </div>
                        <div class="col-xxl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="telc_usu"><b>Tel. Corporativo</b></label>
                            <input type="text" class="form-control form-control-sm" id="telc_usu" name="telc_usu">
                        </div>
                        <div class="col-xxl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="usuario"><b>Usuario <span class="text-danger">*</span></b></label>
                            <input type="text" class="form-control form-control-sm" id="usuario" name="usuario">
                        </div>
                        <div class="col-xxl-3 col-sm-6 mb-3">
                            <label class="form-label mb-0" for="contrasena"><b>Contraseña <span class="text-danger">*</span></b></label>
                            <input type="text" class="form-control form-control-sm" id="contrasena" name="contrasena">
                        </div>
                    </div>
                    <div class="col-12 text-center d-flex justify-content-center">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label mb-0" for="foto_perfil"><b>Foto de Perfil</b></label>
                                <div class="col-12 p-1 text-center content-image">
                                    <div class="overlay">
                                        <button class="btn-img removeImgButton" style="display: none;" id="removeButton" type="button"><i class="ti-close"></i></button>
                                        <button class="btn-img uploadImgButton" id="uploadButton" type="button"><i class="mdi mdi-cloud-upload"></i></button>
                                        <button class="btn-img expandImgButton" type="button" onclick="PreviImagenes(PreviFPerfil.src);"><i class="mdi mdi-arrow-expand-all"></i></button>
                                    </div>
                                    <input type="file" class="d-none" id="foto_perfil">
                                    <input type="text" class="d-none" name="foto_perfil" id="txtFotoPerfil">
                                    <img id="PreviFPerfil" src="{{asset('assets/images/auth/user_auth.jpg')}}" imageDefault="{{asset('assets/images/auth/user_auth.jpg')}}">
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label mb-0" for="firma_digital"><b>Firma Digital</b></label>
                                <div class="col-12 p-1 text-center content-image">
                                    <div class="overlay">
                                        <button class="btn-img removeImgButton" style="display: none;" id="removeImgFirma" type="button"><i class="ti-close"></i></button>
                                        <button class="btn-img mx-1 uploadImgButton" id="uploadImgFirma" type="button"><i class="mdi mdi-cloud-upload"></i></button>
                                        <button class="btn-img mx-1 uploadImgButton" id="createFirma" type="button"><i class="mdi mdi-pencil"></i></button>
                                        <button class="btn-img expandImgButton" type="button" onclick="PreviImagenes(PreviFirma.src);"><i class="mdi mdi-arrow-expand-all"></i></button>
                                    </div>
                                    <input type="file" class="d-none" id="firma_digital">
                                    <input type="text" class="d-none" name="firma_digital" id="textFirmaDigital">
                                    <img id="PreviFirma" src="{{asset('assets/images/firms/firm.png')}}" imageDefault="{{asset('assets/images/firms/firm.png')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-0" for="tipo_acceso"><b>TIPO PERSONAL <span class="text-danger">*</span></b></label>
                        <select id="tipo_acceso" name="tipo_acceso" class="select">
                            <option value="">-- Seleccione --</option>
                                @foreach ($tipoAcceso as $r)
                                    <option value="{{$r->id_tipo_acceso}}">{{$r->descripcion}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <h2 class="card-tittle form-label"><b>Administrar Permisos del Sistema</b></h2>
                            <div class="card-body border rounded">
                                <div class="row">
                                    div
                                    <ul class="treeview">
                                        <li class="menu-only">
                                            <input type="checkbox" id="title1" />
                                            <label for="title1">Titulo</label>
                                        </li>
                                    </ul>
                                    <ul class="treeview">
                                        <li class="menu">
                                            <input type="checkbox" id="title2" />
                                            <label for="title2">Titulo</label>
                                            <ul class="submenu">
                                                <li>
                                                    <input type="checkbox" id="subtitle1" />
                                                    <label for="subtitle1">Subtitle1</label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="subtitle2" />
                                                    <label for="subtitle2">Subtitle2</label>
                                                </li>
                                                <li>
                                                    <input type="checkbox" id="subtitle3" />
                                                    <label for="subtitle3">Subtitle3</label>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" onclick="$('#modal_frm_usuarios').modal('hide')">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@latest/dist/signature_pad.umd.min.js"></script>
<script src="{{asset('assets/js/app/usuarios.js')}}"></script>
<script>
    document.getElementById('form-usuario').addEventListener('submit', function(event) {
        event.preventDefault();

        var elementos = this.querySelectorAll('[name]');
        var datosFormulario = {};

        elementos.forEach(function(elemento) {
            datosFormulario[elemento.name] = elemento.value;
        });

        console.log(datosFormulario);

        $.ajax({
            type: 'POST',
            url: "{{url('/register')}}",
            contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            data: JSON.stringify(datosFormulario),
            success: function(response) {
                alert('Usuario registrado con éxito');
                console.log(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al registrar el usuario');
                console.log(jqXHR.responseJSON);
            }
        });
    });

    document.getElementById('conDoc').addEventListener('click', function() {
        const nDoc = document.getElementById('n_doc').value;
        $.ajax({
            type: 'GET',
            url: `{{url('/consultaDni')}}/${nDoc}`,
            contentType: 'application/json',
            success: function(response) {
                if (!response.success) {
                    return Swal.fire({
                        'title' : 'Ocurrio un error',
                        'icon' : 'info',
                        'text' : response.message
                    });
                }
                $('#nom_usu').val(response.data.nombres);
                $('#ape_usu').val(`${response.data.apellidop} ${response.data.apellidom}`);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al registrar el usuario');
                console.log(jqXHR.responseJSON);
            }
        });
    });
</script>
@endsection