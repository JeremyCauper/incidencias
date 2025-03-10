@extends('layout.app')
@section('title', 'Menu')

@section('cabecera')
    <!-- <link rel="stylesheet" href="{{asset('front/css/app/incidencias/registradas.css')}}"> -->

    <style>
        tbody tr {
            cursor: move;
        }

        .drag-over {
            border: 2px dashed #000;
        }
    </style>
@endsection
@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Listado de Menu</strong>
                </h6>
                <div>
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modal_menu">
                        <i class="fas fa-plus"></i>
                        Nuevo Menu
                    </button>
                    <button class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#modal_ordenm">
                        <i class="fas fa-arrow-right-arrow-left" style="transform: rotate(90deg);"></i>
                        Ordenar Menu
                    </button>
                    <button class="btn btn-primary px-2" onclick="updateTable()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="tb_menu" class="table table-hover text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-bg-primary text-center">
                                    <th>#</th>
                                    <th class="text-start">Descripcion</th>
                                    <th class="text-start">Icono</th>
                                    <th class="text-start">Ruta</th>
                                    <th>Sub Menu</th>
                                    <th>Fecha Registro</th>
                                    <th>Actualizado</th>
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

    <!-- Modal -->
    <div class="modal fade" id="modal_menu" tabindex="-1" aria-labelledby="modal_menuLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-menu">
                <div class="modal-header  bg-primary text-white">
                    <h6 class="modal-title" id="modal_menuLabel">REGISTRAR MENU</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <div class="col-lg-6 mb-2">
                            <label class="form-label mb-0" for="descripcion">Descripcion</label>
                            <input class="form-control" id="descripcion">
                        </div>
                        <div class="col-lg-6 mb-2">
                            <label class="form-label mb-0" for="icono">Icono</label>
                            <input class="form-control" id="icono">
                        </div>
                        <div class="col-lg-12 col-8 mb-2">
                            <label class="form-label mb-0" for="ruta">Ruta</label>
                            <input class="form-control" id="ruta">
                        </div>
                        <div class="col-lg-4 col-4 mb-2">
                            <label class="form-label mb-0" for="submenu">Sub Menu</label>
                            <select class="select" id="submenu">
                                <option value="1">Sí</option>
                                <option selected value="0">No</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 mb-2">
                            <label class="form-label mb-0" for="desarrollo">En Desarrollo</label>
                            <select class="select" id="desarrollo">
                                <option value="1">Sí</option>
                                <option selected value="0">No</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-6 mb-2">
                            <label class="form-label mb-0" for="estado">Estado</label>
                            <select class="select" id="estado">
                                <option selected value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
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

    <div class="modal fade" id="modal_ordenm" tabindex="-1" aria-labelledby="modal_ordenmLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-menu">
                <div class="modal-header  bg-primary text-white">
                    <h6 class="modal-title" id="modal_ordenmLabel">ORDENAR MENU</h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="tb_orden_menu" class="table">
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Menu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr tr-id="1" tr-orden="1" draggable="true">
                                        <td>1</td>
                                        <td>Elemento 1</td>
                                    </tr>
                                    <tr tr-id="2" tr-orden="2" draggable="true">
                                        <td>2</td>
                                        <td>Elemento 2</td>
                                    </tr>
                                    <tr tr-id="3" tr-orden="3" draggable="true">
                                        <td>3</td>
                                        <td>Elemento 3</td>
                                    </tr>
                                </tbody>
                            </table>
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
    <script>
        function iniciarTbOrden() {
            var draggedRow = null;

            // Inicia el arrastre de la fila
            $('#tb_orden_menu tbody tr').on('dragstart', function (e) {
                draggedRow = this;
                // Configura el efecto de mover
                e.originalEvent.dataTransfer.effectAllowed = 'move';
            });

            // Permite que la fila destino reciba el drop
            $('#tb_orden_menu tbody tr').on('dragover', function (e) {
                e.preventDefault(); // Necesario para permitir el drop
                e.originalEvent.dataTransfer.dropEffect = 'move';
                $(this).addClass('drag-over'); // Resalta la fila sobre la que se pasa el cursor
            });

            // Quita el estilo al salir de la fila
            $('#tb_orden_menu tbody tr').on('dragleave', function (e) {
                $(this).removeClass('drag-over');
            });

            // Al soltar la fila, se reordena la tb_orden_menu
            $('#tb_orden_menu tbody tr').on('drop', function (e) {
                e.preventDefault();
                $(this).removeClass('drag-over');
                if (draggedRow !== this) {
                    var $thisRow = $(this);
                    var rowHeight = $thisRow.outerHeight();
                    var offsetY = e.originalEvent.offsetY; // posición vertical del drop relativo a la fila
                    if (offsetY > rowHeight / 2) {
                        // Si se soltó en la mitad inferior, insertar después
                        $(draggedRow).insertAfter(this);
                    } else {
                        // Si se soltó en la mitad superior, insertar antes
                        $(draggedRow).insertBefore(this);
                    }
                    actualizarOrden();
                }
            });

            // Limpia la variable global al finalizar el arrastre
            $('#tb_orden_menu tbody tr').on('dragend', function (e) {
                draggedRow = null;
                $('#tb_orden_menu tbody tr').removeClass('drag-over');
            });

            // Actualiza el atributo tr-orden y la celda de orden
            function actualizarOrden() {
                $('#tb_orden_menu tbody tr').each(function (index) {
                    var nuevoOrden = index + 1; // comienza en 1
                    $(this).attr('tr-orden', nuevoOrden);
                    $(this).find('td:eq(0)').text(nuevoOrden);
                });
            }
        };

        function extraerIdsYOrdenes() {
            var datos = [];
            $("#tb_orden_menu tbody tr").each(function () {
                var id = $(this).attr("tr-id");
                var orden = $(this).attr("tr-orden");
                datos.push({ id: id, orden: orden });
            });
            return datos;
        }
    </script>
    <script src="{{asset('front/js/app/mantenimiento/menu/menu.js')}}"></script>
@endsection