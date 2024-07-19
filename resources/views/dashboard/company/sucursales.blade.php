@extends('layout.app')
@section('title', 'Sucursales')

@section('content')

<style>
</style>

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Listado de Sucursales</h4>
            <div class="mb-3">
                <button class="btn btn-primary btn-sm" onclick="$('#modal_frm_sucursales').modal('show')">
                    <i class="fas fa-plus me-1"></i>
                    Nueva Sucursal
                </button>
                <button class="btn btn-primary btn-sm px-1" onclick="updateTable()" data-mdb-ripple-init role="button">
                    <i class="fas fa-rotate-right"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="tb_sucursales" class="table text-nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                            <th>Ruc</th>
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>Departamento</th>
                            <th>Provincia</th>
                            <th>Distrito</th>
                            <th>Ubigeo</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Monitoreo</th>
                            <th>Registrado</th>
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

<div id="modal_frm_sucursales" class="modal fade" aria-modal="true" role="dialog">
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
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" onclick="$('#modal_frm_sucursales').modal('hide')">Cerrar</button>
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
    const tb_sucursales = new DataTable('#tb_sucursales', {
        scrollX: true,
        scrollY: 300,
        ajax: {
            url: "https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=sucursales",
            dataSrc: "",
            error: function(xhr, error, thrown) {
                console.log('Error en la solicitud Ajax:', error);
                console.log('Respuesta del servidor:', xhr);
            }
        },
        columns: [
            { data: 'ruc' },
            { data: 'Nombre' },
            { data: 'Direccion' },
            { data: 'Departamento' },
            { data: 'Provincia' },
            { data: 'Distrito' },
            { data: 'Ubigeo' },
            { data: 'Telefono' },
            { data: 'Correo' },
            { data: 'Monitoreo' },
            { data: 'Registrado' },
            { data: 'Estado' },
            { data: 'id' },
        ],
        processing: true
    });

    function updateTable() {
        tb_sucursales.ajax.reload();
    }
</script>
@endsection