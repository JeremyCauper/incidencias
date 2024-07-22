@extends('layout.app')
@section('title', 'Panel de Control')
@section('style')
    <link rel="stylesheet" href="{{asset('front/css/app/style-usuarios.css')}}">
    <!-- <link rel="stylesheet" href="{{asset('front/vendor/select/select2.min.css')}}"> -->
     <style>
        .content-signature-pad {
            position: relative;
        }

        .content-signature-pad::before {
            content: "";
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            height: 2px;
            background: #000000;
        }
     </style>
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
                                    <img id="PreviFPerfil" src="{{asset('assets/images/auth/user_auth.jpg')}}" imageDefault="{{asset('assets/images/auth/user_auth.jpg')}}">
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
                                    <img id="PreviFirma" src="{{asset('assets/images/firms/firm.png')}}" imageDefault="{{asset('assets/images/firms/firm.png')}}">
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
                    <button type="submit" class="btn btn-primary btn-sm">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.4/signature_pad.min.js"></script>
<script src="{{asset('front/js/app/script-usuarios.js')}}"></script>
<script>
    let tb_usuario = null;
    $(document).ready(function() {
        tb_usuario = new DataTable('#tb_usuario', {
            scrollX: true,
            scrollY: 300,
            ajax: {
                url: "{{ url('/DataTableUser') }}",
                dataSrc: "",
                error: function(xhr, error, thrown) {
                    console.log('Error en la solicitud Ajax:', error);
                    console.log('Respuesta del servidor:', xhr);
                }
            },
            columns: [
                { data: 'ndoc_usuario' },
                { data: 'nombres', render: function (data, type, row) {
                        return `${row.nombres} ${row.apellidos}`;
                    }
                },
                { data: 'descripcion' },
                { data: 'usuario' },
                { data: 'pass_view' },
                { data: 'estatus' },
                { data: 'id_usuario' }
            ],
            processing: true
        });

        $("#fechan_usu").flatpickr({
            maxDate: "{{ (date('Y') - 18) . '-' . date('m-d')}}"
        });
    });

    function updateTable() {
        tb_usuario.ajax.reload();
    }

    document.getElementById('form-usuario').addEventListener('submit', function(event) {
        event.preventDefault();
        $('#form-usuario .modal-dialog .modal-content').append(`<div class="loader-of-modal" style="position: absolute;height: 100%;width: 100%;z-index: 999;background: #dadada60;border-radius: inherit;align-content: center;"><div class="loader"></div></div>`);

        var elementos = this.querySelectorAll('[name]');
        var datosFormulario = {};

        let cad_require = "";
        elementos.forEach(function(elemento) {
            if (elemento.getAttribute("require") && elemento.value == "") {
                cad_require += `<b>${elemento.getAttribute("require")}</b>, `;
            }
            datosFormulario[elemento.name] = elemento.value;
        });
        if (cad_require) {
            $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
            return boxAlert.box('info', 'Faltan datos', `<h6 class="text-secondary">El campo ${cad_require} es requerido.</h6>`);
        }
        
        url = [
            "{{url('/register')}}", `{{url('/editusu')}}/${$('#form-usuario').attr('idu')}`
        ];
        $.ajax({
            type: 'POST',
            url: url[$('#form-usuario').attr('frm-accion')],
            contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            data: JSON.stringify(datosFormulario),
            success: function(response) {
                boxAlert.minbox('success', response.message, {background:"#3b71ca", color:"#ffffff"}, "top");
                updateTable();
                $('[data-mdb-dismiss="modal"]').click();
                console.log(response);
                $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                boxAlert.box('error', '¡Ocurrio un error!', 'Error al registrar el usuario');
                console.log(jqXHR.responseJSON);
                $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
            }
        });
    });

    $('.modal').on('hidden.bs.modal', function () {
        $('#form-usuario').attr('idu', '').attr('frm-accion', '0');
    });

    document.querySelectorAll('.inputMenu').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const checkboxes = this.closest('.treeview').querySelectorAll('.inputSubMenu');
            checkboxes.forEach(checkbox => {
                checkbox.disabled = !this.checked;
                checkbox.checked = false;
            });
        });
    });

    function showUsuario(id) {
        $('#modal_frm_usuarios').modal('show');
        $('#form-usuario .modal-dialog .modal-content').append(`<div class="loader-of-modal" style="position: absolute;height: 100%;width: 100%;z-index: 999;background: #dadada60;border-radius: inherit;align-content: center;"><div class="loader"></div></div>`);
        const urlImg = {
            'perfil':"{{asset('assets/images/auth')}}",
            'firma':"{{asset('assets/images/firms')}}"
        };
        $.ajax({
            type: 'GET',
            url: `{{url('/showusu')}}/${id}`,
            contentType: 'application/json',
            success: function(response) {
                console.log(response);
                const data = response[0];
                $('#form-usuario .modal-dialog .modal-content .loader-of-modal').remove();
                $('#form-usuario').attr('idu', id).attr('frm-accion', '1');
                $('#id_area').val(data.id_area).trigger('change.select2');
                $('#n_doc').val(data.ndoc_usuario);
                $('#nom_usu').val(data.nombres);
                $('#ape_usu').val(data.apellidos);
                $('#emailp_usu').val(data.email_personal);
                $('#emailc_usu').val(data.email_corporativo);
                $('#fechan_usu').val(data.fecha_nacimiento);
                $('#telp_usu').val(data.tel_personal);
                $('#telc_usu').val(data.tel_corporativo);
                $('#usuario').val(data.usuario);
                $('#contrasena').val(data.pass_view);
                if (data.foto_perfil) $('#PreviFPerfil').attr('src', `${urlImg['perfil']}/${data.foto_perfil}`);
                if (data.firma_digital) $('#PreviFirma').attr('src', `${urlImg['firma']}/${data.firma_digital}`);
                $('#tipo_acceso').val(data.tipo_acceso).trigger('change.select2');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al registrar el usuario');
                console.log(jqXHR.responseJSON);
            }
        });
    }


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