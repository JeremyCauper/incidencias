@extends('layout.app')
@section('title', 'Menu')

@section('cabecera')
    <!-- <link rel="stylesheet" href="{{asset('front/css/app/incidencias/registradas.css')}}"> -->

    <style>
        #tb_orden_menu tbody tr {
            cursor: move;
        }

        /* Estilo para el placeholder */

        /* Opcional: estilo para la fila que se está arrastrando */
        .dragging {
            opacity: 0.5;
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
                    <button type="submit" class="btn btn-primary" onclick="cambiarOrden()" data-mdb-ripple-init>Guardar</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function iniciarTbOrden() {
            var draggedRow = null;
            // Creamos el placeholder
            var $placeholder = $("<tr style='border: 2px dashed #ccc;'><td colspan='2'>&nbsp;</td></tr>");

            // Al iniciar el arrastre: ocultamos la fila y colocamos el placeholder en su posición original
            $('#tb_orden_menu tbody tr').on('dragstart', async function (e) {
                draggedRow = $(this);
                e.originalEvent.dataTransfer.effectAllowed = 'move';
                setTimeout(() => {
                    draggedRow.addClass('dragging').hide();
                }, 50);
                // Insertamos el placeholder donde estaba la fila
            });

            $('#tb_orden_menu tbody').on('dragover', function (e) {
                e.preventDefault();
                if (!draggedRow) return;

                var posY = e.originalEvent.pageY;
                var $target = $(e.target).closest('tr'); // Encuentra la fila sobre la que estamos pasando

                if ($($target[0]).attr('tr-id') == $(draggedRow).attr('tr-id')) return;

                if ($target.length && !$target.hasClass('placeholder')) {
                    var targetOffset = $target.offset().top;
                    var targetHeight = $target.outerHeight();

                    // Si el cursor está en la mitad inferior de la fila, colocamos el placeholder después
                    if (posY - targetOffset > targetHeight / 2) {
                        $target.after($placeholder);
                    } else {
                        $target.before($placeholder);
                    }
                }
            });

            // Al soltar, reemplazamos el placeholder por la fila oculta y actualizamos el orden
            $('#tb_orden_menu tbody').on('drop', function (e) {
                e.preventDefault();
                if (draggedRow) {
                    $placeholder.replaceWith(draggedRow);
                    draggedRow.show().removeClass('dragging');
                    actualizarOrden();
                    draggedRow = null;
                }
            });

            // En caso de cancelar el arrastre, mostramos la fila y removemos el placeholder
            $('#tb_orden_menu tbody').on('dragend', function (e) {
                if (draggedRow) {
                    draggedRow.show().removeClass('dragging');
                    draggedRow = null;
                }
                $placeholder.remove(); // Se elimina el placeholder
            });

            // Función para actualizar el atributo 'tr-orden' y la celda de orden
            function actualizarOrden() {
                $('#tb_orden_menu tbody tr').each(function (index) {
                    var nuevoOrden = index + 1;
                    $(this).attr('tr-orden', nuevoOrden);
                    // Actualizamos la primera celda (columna "Orden")
                    $(this).find('td:first').text(nuevoOrden);
                });
            }
        }

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