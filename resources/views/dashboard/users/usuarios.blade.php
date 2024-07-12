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

    .content-image:hover .overlay,
    .content-image:hover .uploadImgButton {
        opacity: 1;
        transition: .5s;
    }

    @media (max-width: 576px) {
        .content-image img {
            min-height: 80px !important;
        }
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
                    <div class="col-12">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
                    </div>
                    <div class="row">
                        <div class="col-xxl-10 col-lg-9">
                            <div class="row">
                                <div class="col-xl-3 col-sm-7 mb-3">
                                    <label class="form-label mb-0" for="id_area"><b>Area <span class="text-danger">*</span></b></label>
                                    <select id="id_area" class="form-control form-control-sm">
                                        <option value="">-- Seleccione --</option>
                                        <option value="1">Soporte</option>
                                        <option value="2">Facturacion</option>
                                        <option value="3">Supervisor</option>
                                        <option value="4">Reportes</option>
                                    </select>
                                </div>
                                <div class="col-xl-3 col-sm-5 mb-3">
                                    <label class="form-label mb-0" for="n_dni"><b>Dni <span class="text-danger">*</span></b></label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Número de Dni" id="n_dni" maxlength="8">
                                </div>
                                <div class="col-xl-3 col-sm-6 mb-3">
                                    <label class="form-label mb-0" for="nom_usu"><b>Nombres <span class="text-danger">*</span></b></label>
                                    <input type="text" class="form-control form-control-sm" id="nom_usu">
                                </div>
                                <div class="col-xl-3 col-sm-6 mb-3">
                                    <label class="form-label mb-0" for="ape_usu"><b>Apellidos <span class="text-danger">*</span></b></label>
                                    <input type="text" class="form-control form-control-sm" id="ape_usu">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-4 col-lg-7 col-sm-6 mb-3">
                                    <label class="form-label mb-0" for="email_usu"><b>Email</b></label>
                                    <input type="text" class="form-control form-control-sm" id="email_usu">
                                </div>
                                <div class="col-xxl-2 col-lg-5 col-sm-6 mb-3">
                                    <label class="form-label mb-0" for="fechan_usu"><b>Fecha de Nacimiento <span class="text-danger">*</span></b></label>
                                    <input type="date" class="form-control form-control-sm" id="fechan_usu">
                                </div>
                                <div class="col-xxl-3 col-sm-6 mb-3">
                                    <label class="form-label mb-0" for="usuario"><b>Usuario <span class="text-danger">*</span></b></label>
                                    <input type="text" class="form-control form-control-sm" id="usuario">
                                </div>
                                <div class="col-xxl-3 col-sm-6 mb-3">
                                    <label class="form-label mb-0" for="contrasena"><b>Contraseña <span class="text-danger">*</span></b></label>
                                    <input type="text" class="form-control form-control-sm" id="contrasena">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-lg-3">
                            <div class="row">
                                <div class="col-md-12 col-6 mb-3">
                                    <label class="form-label mb-0" for="foto_perfil"><b>Foto de Perfil</b></label>
                                    <div class="col-12 p-1 text-center content-image">
                                        <div class="overlay">
                                            <button class="btn btn-primary btn-sm removeImgButton" style="display: none;" id="removeButton" type="button">x</button>
                                            <button class="btn btn-primary btn-sm uploadImgButton" id="uploadButton" type="button"><i class="mdi mdi-cloud-upload"></i></button>
                                        </div>
                                        <input type="file" class="d-none" id="foto_perfil">
                                        <img id="PreviFPerfil" src="{{asset('assets/images/auth/user_auth.jpg')}}" imageDefault="{{asset('assets/images/auth/user_auth.jpg')}}">
                                    </div>
                                </div>
                                <div class="col-md-12 col-6 mb-3">
                                    <label class="form-label mb-0" for="firma_digital"><b>Firma Digital</b></label>
                                    <div class="col-12 p-1 text-center content-image">
                                        <div class="overlay">
                                            <button class="btn btn-primary btn-sm removeImgButton" style="display: none;" id="removeImgFirma" type="button">Remover</button>
                                            <button class="btn btn-primary btn-sm mx-1 uploadImgButton" id="uploadImgFirma" type="button"><i class="mdi mdi-cloud-upload"></i></button>
                                            <button class="btn btn-primary btn-sm mx-1 uploadImgButton" id="createFirma" type="button"><i class="mdi mdi-pencil"></i></button>
                                        </div>
                                        <input type="file" class="d-none" id="firma_digital">
                                        <input type="text" class="d-none" id="textFirmaDigital">
                                        <img id="PreviFirma" src="{{asset('assets/images/firms/firm.png')}}" imageDefault="{{asset('assets/images/firms/firm.png')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-0" for="tipo_acceso"><b>TIPO PERSONAL <span class="text-danger">*</span></b></label>
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
                <button type="button" class="btn btn-link" data-dismiss="modal" onclick="$('#modal_frm_usuarios').modal('hide')">Cerrar</button>
                <button type="button" class="btn btn-indigo btn-sm col-form-label-sm" onclick="clientes.saveChangeCliente()">
                    <i class="icon-floppy-disk"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@latest/dist/signature_pad.umd.min.js"></script>
<script>
    const fileInput = document.getElementById('foto_perfil');
    const removeButton = document.getElementById('removeButton');
    const PreviFPerfil = document.getElementById('PreviFPerfil');

    document.getElementById('uploadButton').addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        const maxFileSize = 20 * 1024 * 1024; // 20MB
        if (file) {
            if (file.size > maxFileSize) {
                alert('El archivo debe ser menor a 20MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                PreviFPerfil.src = e.target.result;
                PreviFPerfil.alt = file.name;
                console.log(`Nombre: ${file.name}`);
                console.log(`Peso: ${(file.size / 1024 / 1024).toFixed(2)} MB`);
                console.log(`Base64: ${e.target.result}`);
                removeButton.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    removeButton.addEventListener('click', () => {
        PreviFPerfil.src = PreviFPerfil.getAttribute("imagedefault");
        removeButton.style.display = 'none';
        fileInput.value = '';
    });



    const fileInputFirma = document.getElementById('firma_digital');
    const PreviFirma = document.getElementById('PreviFirma');
    const removeImgFirma = document.getElementById('removeImgFirma');
    const textFirmaDigital = document.getElementById('textFirmaDigital');

    document.getElementById('uploadImgFirma').addEventListener('click', () => {
        fileInputFirma.click();
    });

    fileInputFirma.addEventListener('change', function(event) {
        const file = event.target.files[0];
        const maxFileSize = 10 * 1024 * 1024; // 10MB
        if (file) {
            if (file.size > maxFileSize) {
                alert('El archivo debe ser menor a 10MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                PreviFirma.src = e.target.result;
                PreviFirma.alt = file.name;
                console.log(`Nombre: ${file.name}`);
                console.log(`Peso: ${(file.size / 1024 / 1024).toFixed(2)} MB`);
                console.log(`Base64: ${e.target.result}`);
                removeImgFirma.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    textFirmaDigital.addEventListener('change', function(event) {
        console.log(event);
    });

    document.getElementById('createFirma').addEventListener('click', async () => {
        Swal.fire({
            title: "CREAR FIRMA DIGITAL",
            html: `<canvas id="signature-pad" width="400" height="200" style="border: 2px dashed #dee2e6; border-radius: 7px; padding:5px; min-width: 160px"></canvas>
                    <button class="btn btn-primary btn-sm" id="save">Guardar</button>
                    <button class="btn btn-danger btn-sm" id="clear">Limpiar</button>
                    <button class="btn btn-info btn-sm" onclick="Swal.close()">Cerrar</button>`,
            showConfirmButton: false
        });

        var canvas = document.getElementById('signature-pad');
        var signaturePad = new SignaturePad(canvas);

        document.getElementById('clear').addEventListener('click', function() {
            signaturePad.clear();
        });

        document.getElementById('save').addEventListener('click', function() {
            if (signaturePad.isEmpty()) {
                alert("Por favor, dibuja una firma primero.");
            } else {
                var dataURL = signaturePad.toDataURL();
                console.log(dataURL.toString());
                document.getElementById('textFirmaDigital').value = dataURL.toString();
                document.getElementById('PreviFirma').src = dataURL.toString();
                removeImgFirma.style.display = 'block';
                Swal.close();
            }
        });
    });

    removeImgFirma.addEventListener('click', () => {
        PreviFirma.src = PreviFirma.getAttribute("imagedefault");
        removeImgFirma.style.display = 'none';
        fileInputFirma.value = '';
    });
</script>
@endsection