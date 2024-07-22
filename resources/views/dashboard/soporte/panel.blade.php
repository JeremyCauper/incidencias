@extends('layout.app')
@section('title', 'Panel de Control')

@section('style')
    <style>
        .title-count {
            font-weight: bold;
            font-size: .8rem;
        }

        .subtitle-count {
            margin-bottom: 0px;
        }

        .grid-margin .card .card-body {
            cursor: pointer !important;
        }
    </style>
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body text-primary" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="far fa-clock"></i> Incidencias Registradas</h6>
                        <h4 class="subtitle-count"><b>17944</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin">
                <div class="card">
                    <div class="card-body text-info" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-user-check"></i> Incidencias Asignadas</h6>
                        <h4 class="subtitle-count"><b>6</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin">
                <div class="card">
                    <div class="card-body text-warning" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-business-time"></i> Incidencias En Proceso</h6>
                        <h4 class="subtitle-count"><b>5</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin">
                <div class="card">
                    <div class="card-body text-success" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-clipboard-check"></i> Incidencias Resueltas</h6>
                        <h4 class="subtitle-count"><b>16708</b></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xxl-3 grid-margin">
                <div class="card">
                    <a class="card-body text-secondary" href="{{url('/soport-empresa/empresas')}}" data-mdb-ripple-init>
                        <h6 class="card-title title-count mb-2"><i class="fas fa-database"></i> Clientes Registrados</h6>
                        <h4 class="subtitle-count"><b>{{$countEmpresas}}</b></h4>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Incidencias Registradas</h4>
            <div>
                <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modal_frm_incidencias">
                    <i class="fas fa-book-medical"></i>
                    Nueva Incidencia
                </button>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_incidencia" class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Empresa</th>
                                <th>Sucursal</th>
                                <th>Contacto</th>
                                <th>Registrada</th>
                                <th>Tecnico</th>
                                <th>Estacion</th>
                                <th>Atencion</th>
                                <th>Informe</th>
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

<div id="modal_frm_incidencias" class="modal fade" aria-modal="true" role="dialog">
    <form id="form-incidencias">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content" style="position: relative;">
                <div class="modal-body">
                    <h6 class="card-title mb-4 text-primary"><b>CREAR NUEVA INCIDENCIA</b></h6>
                    <div class="col-12 mb-2">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-6 mb-3">
                            <label class="form-label mb-0" for="id_area"><b>Area <span class="text-danger">*</span></b></label>
                            <select id="id_area" class="select" name="id_area" require="Area">
                                <option value="">-- Seleccione --</option>
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
                    <div class="mb-3">
                        <label class="form-label mb-0" for="tipo_acceso"><b>Tipo Personal <span class="text-danger">*</span></b></label>
                        <select id="tipo_acceso" name="tipo_acceso" class="select" require="Tipo Personal">
                            <option value="">-- Seleccione --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        Registrar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        const tb_incidencia = new DataTable('#tb_incidencia', {
        scrollX: true,
        scrollY: 300,
        ajax: {
            url: "{{ url('/DataTableUser') }}",
            dataSrc: "",
            error: function(xhr, error, thrown) {
                boxAlert.box('error', 'Ocurrio un error', 'Error en la solicitud Ajax: ' + error);
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

    function updateTable() {
        tb_usuario.ajax.reload();
    }
    </script>
@endsection