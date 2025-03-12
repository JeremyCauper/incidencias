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
    </style>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
@endsection
@section('content')

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Cronograma Mensual Turno Semanal / Apoyo</strong>
                </h6>
                <div>
                    <button class="btn btn-primary px-2" onclick="cargarEventosApi()" data-mdb-ripple-init role="button">
                        <i class="fas fa-rotate-right"></i>
                    </button>
                </div>
                <div class="row justify-content-center my-2">
                    <div id="content-calendar" class="col-md-8" style="position: relative;">
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

    <button class="d-none" data-mdb-modal-init data-mdb-target="#modal_turno"></button>
    <div class="modal fade" id="modal_turno" aria-labelledby="modal_turno" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="form-turno" style="position: relative;">
                <input type="hidden" name="id" id="id">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Nuevo Turno a Programar
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
                                    <input class="form-control" id="sfechaIni" />
                                    <input class="form-control" id="shoraIni" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label mb-0" for="sfechaFin">Final</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="font-size: .75rem; width: 80px !important;"
                                        id="sfechaFinText"></span>
                                    <input class="form-control" id="sfechaFin" />
                                    <input class="form-control" id="shoraFin" />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-0" for="spersonal">Personal Semanal</label>
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
                                    <input class="form-control" id="afechaIni" />
                                    <input class="form-control" id="ahoraIni" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label mb-0" for="afechaFin">Final</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="font-size: .75rem; width: 80px !important;"
                                        id="afechaFinText"></span>
                                    <input class="form-control" id="afechaFin" />
                                    <input class="form-control" id="ahoraFin" />
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label mb-0" for="apersonal">Personal de Apoyo</label>
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modal_turno_detalleLabel">Detalle Turno</h5>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        let usuarios = <?=$data['usuarios']?>;
    </script>
    <script src="{{ asset('front/js/app/turno/turno.js') }}"></script>
@endsection