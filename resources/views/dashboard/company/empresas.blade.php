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
                <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init>
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
                            <tr>
                                <th>Ruc</th>
                                <th>Empresa</th>
                                <th>N. Comercial</th>
                                <th>Direccion</th>
                                <th>Telefono</th>
                                <th>Fecha Registro</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_frm_empresas" class="modal fade" aria-modal="true" role="dialog">
    <form id="form-usuario">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="position: relative;">
                <div class="modal-body">
                    <h4 class="card-title mb-4 text-primary"><b>CREAR NUEVO USUARIO</b></h4>
                    <div class="col-12">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
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
<script>
    const tb_empresas = new DataTable('#tb_empresas', {
        scrollX: true,
        scrollY: 300,
        ajax: {
            url: "https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=empresas",
            dataSrc: "",
            error: function(xhr, error, thrown) {
                console.log('Error en la solicitud Ajax:', error);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'Ruc' },
            { data: 'RazonSocial' },
            { data: 'NombreComercial' },
            { data: 'DomicilioFiscal' },
            { data: 'Telefono' },
            { data: 'FechaRegistro' },
            { data: 'Estado', render: function (data, type, row) {
                    return `<span class="badge badge-${data ? 'success' : 'danger'}">${data ? 'Activo' : 'Inactivo'}</span>`;
                } 
            }
        ],
        processing: true
    });

    function updateTable() {
        tb_empresas.ajax.reload();
    }
</script>
@endsection