@extends('layout.app')
@section('title', 'Sucursales')

@section('content')
    <script src="{{secure_asset('front/vendor/ubigeos-peru/ubigeo.js')}}"></script>
    <style>
    </style>

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-primary mb-3">
                    <strong>Listado de Sucursales</strong>
                </h6>
                <div class="mb-3">
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#modal_sucursales">
                        <i class="fas fa-plus me-1"></i>
                        Nueva Sucursal
                    </button>
                    <button class="btn btn-primary" onclick="updateTable()">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_sucursales" class="table text-nowrap" style="width: 100%;">
                            <thead>
                                <tr class="text-center">
                                    <th>Grupo</th>
                                    <th>Cofide</th>
                                    <th>Ruc</th>
                                    <th>Sucursal</th>
                                    <th>Direccion</th>
                                    <th>Ubigeo</th>
                                    <th>Registrado</th>
                                    <th>Actualizado</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                        <script>
                            const tb_sucursales = new DataTable('#tb_sucursales', {
                                scrollX: true,
                                scrollY: 300,
                                ajax: {
                                    url: `${__url}/soporte/empresas/sucursales/index`,
                                    dataSrc: "",
                                    error: function (xhr, error, thrown) {
                                        console.log('Error en la solicitud Ajax:', error);
                                        console.log('Respuesta del servidor:', xhr);
                                    }
                                },
                                columns: [
                                    { data: 'grupo' },
                                    { data: 'cofide' },
                                    { data: 'ruc' },
                                    { data: 'sucursal' },
                                    { data: 'direccion' },
                                    {
                                        data: 'ubigeo', render: function (data, type, row) {
                                            const ubg = ubigeo.find(u => u.codigo == data);
                                            return ubg.nombre;
                                        }
                                    },
                                    { data: 'created_at' },
                                    { data: 'updated_at' },
                                    { data: 'estado' },
                                    { data: 'acciones' }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    $(row).find('td:eq(2), td:eq(6), td:eq(7), td:eq(8), td:eq(9)').addClass('text-center');
                                    $(row).find('td:eq(9)').addClass(`td-acciones`);
                                },
                                processing: true
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_sucursales" class="modal fade" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-sucursal">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title" id="modal_sucursalesLabel">REGISTRAR SUCURSAL</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12 mb-2">
                        <span style="font-size: 12.5px; color:#9FA6B2;">Completar todos los campos obligatorios (*)</span>
                    </div>
                    <div class="row">
                        <h6 class="tittle text-primary my-3">Datos Sucursal</h6>
                        <input type="hidden" name="id" id="id">
                        <div class="col-lg-4 col-7 mb-2">
                            <select class="select-clear" id="empresa">
                                <option value=""></option>
                                @foreach ($data['empresas'] as $key => $val)
                                    @if ($val['status'])
                                        <option value="{{$val['ruc']}}">{{$val['ruc'] . ' - ' . $val['razonSocial']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5 col-5 mb-2">
                            <input class="form-control" id="sucursal">
                        </div>
                        <div class="col-lg-3 col-12 mb-2">
                            <input class="form-control" id="codCofide">
                        </div>

                        <div class="col-lg-7 mb-2">
                            <input class="form-control" id="direccion">
                        </div>
                        <div class="col-lg-5 mb-2">
                            <select class="select-clear" id="ubigeo">
                                <option value=""></option>
                            </select>
                        </div>

                        <div class="col-lg-4 col-4 mb-2">
                            <input class="form-control" id="telefonoS">
                        </div>
                        <div class="col-lg-8 col-8 mb-2">
                            <input class="form-control" id="correoS">
                        </div>

                        <div class="col-lg-4 col-4 mb-2">
                            <select class="select" id="vVisitas">
                                <option selected value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-4 mb-2">
                            <select class="select" id="vMantenimientos">
                                <option selected value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-4 mb-2">
                            <select class="select" id="estado">
                                <option selected value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <div class="col-12 mb-2">
                            <input class="form-control" id="urlMapa">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('scripts')
    <!-- jQuery Mask Plugin CDN -->
    <script src="{{secure_asset('front/js/soporte/empresas/sucursales.js')}}"></script>
@endsection