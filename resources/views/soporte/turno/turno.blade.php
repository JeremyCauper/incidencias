@extends('layout.app')
@section('title', 'Panel de Control')

@section('cabecera')
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> -->
    <style>
        /* .fc .fc-daygrid-day-frame {
                                                                min-height: 100% !important;
                                                                height: 80px !important;
                                                                position: relative !important;
                                                            } */
        .fc .fc-toolbar-title {
            text-transform: capitalize;
        }
    </style>
    <script src="{{ secure_asset('front/vendor/full-calendar/full-calendar.min.js') }}?v={{ config('app.version') }}"></script>
@endsection
@section('content')

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-primary mb-3">
                    <strong>Cronograma Mensual Turno Semanal / Apoyo</strong>
                </h6>
                <div>
                    <button class="btn btn-primary" onclick="cargarEventosApi()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row justify-content-center my-2">
                    <div id="content-calendar" class="col-md-9" style="position: relative;">
                        <div class="loader-of-modal">
                            <div style="display:flex; justify-content:center;">
                                <div class="loader"></div>
                            </div>
                        </div>
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        let calendario = null;
        let eventoTemporal = null;
        let anioMemoria = null;
        let añosCargados = {}; // Registro de años ya cargados para evitar duplicados

        let elementoCalendario = document.getElementById('calendar');

        calendario = new FullCalendar.Calendar(elementoCalendario, {
            initialView: 'dayGridMonth',
            selectable: true,
            locale: 'es',
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,next today'
            },
            buttonText: { today: 'Hoy' },
            titleFormat: { year: 'numeric', month: 'long' },
            eventOrder: "start,-duration",
            eventDidMount: function (info) {
                info.el.style.cursor = "pointer";
                info.el.style.height = "28px"; // Cambia la altura
                info.el.style.lineHeight = "28px"; // Centra el texto
            }
        });

        calendario.render();
    </script>

    <button class="d-none" data-mdb-modal-init data-mdb-target="#modal_turno"></button>
    <div class="modal fade" id="modal_turno" aria-labelledby="modal_turno" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-turno" style="position: relative;">
                <input type="hidden" name="id" id="id">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title" id="modal_turnoLabel">Nuevo Turno a Programar
                    </h6>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="py-3 border-bottom border-primary">
                        <h6><strong>SEMANA DE TURNO</strong></h6>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label mb-0" for="sfechaIni">Inicio</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="font-size: .75rem; width: 80px !important;"
                                        id="sfechaIniText"></span>
                                    <input type="date" class="form-control" id="sfechaIni" name="sfechaIni" requested />
                                    <input type="time" class="form-control" id="shoraIni" name="shoraIni" requested />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label mb-0" for="sfechaFin">Final</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="font-size: .75rem; width: 80px !important;"
                                        id="sfechaFinText"></span>
                                    <input type="date" class="form-control" id="sfechaFin" name="sfechaFin" requested />
                                    <input type="time" class="form-control" id="shoraFin" name="shoraFin" requested />
                                </div>
                            </div>
                            <div class="col-12">
                                <select class="select-clear" id="spersonal">
                                    <option value=""></option>
                                    @foreach ($data['usuarios'] as $u)
                                        <option value="{{$u['value']}}" data-value="{{$u['dValue']}}">{{$u['text']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="py-3">
                        <h6><strong>SEMANA DE TURNO - APOYO</strong></h6>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label mb-0" for="afechaIni">Inicio</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="font-size: .75rem; width: 80px !important;"
                                        id="afechaIniText"></span>
                                    <input type="date" class="form-control" id="afechaIni" name="afechaIni" requested />
                                    <input type="time" class="form-control" id="ahoraIni" name="ahoraIni" requested />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label mb-0" for="afechaFin">Final</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="font-size: .75rem; width: 80px !important;"
                                        id="afechaFinText"></span>
                                    <input type="date" class="form-control" id="afechaFin" name="afechaFin" requested />
                                    <input type="time" class="form-control" id="ahoraFin" name="ahoraFin" requested />
                                </div>
                            </div>
                            <div class="col-12">
                                <select class="select-clear" id="apersonal">
                                    <option value=""></option>
                                    @foreach ($data['usuarios'] as $u)
                                        <option value="{{$u['value']}}" data-value="{{$u['dValue']}}">{{$u['text']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm" data-mdb-ripple-init>Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_turno_detalle" tabindex="-1" aria-labelledby="modal_turno_detalleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-primary d-flex justify-content-between my-2">
                        <h5 id="modal_turno_detalleLabel" style="font-size: 1.3rem !important; font-weight: 700;">Detalle
                            Turno</h5>
                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="row"></div>
                    <div class="text-end">
                        <button type="button" class="btn btn-danger px-2 me-2" data-mdb-ripple-init
                            id="btn-eliminar-turno"><i class="far fa-trash-can"></i></button>
                        <button type="button" class="btn btn-primary me-2" data-mdb-ripple-init id="btn-editar-turno"><i
                                class="far fa-pen-to-square"></i></button>
                        <button type="button" class="btn btn-link" data-mdb-ripple-init
                            data-mdb-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        let usuarios = <?=$data['usuarios']?>;
    </script>
    <script src="{{ secure_asset('front/js/soporte/turno/turno.js') }}?v={{ config('app.version') }}"></script>
@endsection