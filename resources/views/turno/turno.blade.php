@extends('layout.app')
@section('title', 'Panel de Control')

@section('cabecera')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'prev,next today'
                },
                buttonText: {
                    today: 'Hoy'
                },
                dayHeaderContent: function (info) {
                    const days = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                    return days[info.date.getDay()];
                },
                titleFormat: { year: 'numeric', month: 'long' },
                viewDidMount: function (view) {
                    let titulo = document.querySelector('.fc-toolbar-title');
                    if (titulo) {
                        let partes = titulo.innerText.split(' ');
                        let mesNombre = meses.find(m => partes[0].toLowerCase().includes(m.toLowerCase()));
                        if (mesNombre) {
                            titulo.innerText = titulo.innerText.replace(partes[0], mesNombre);
                        }
                    }
                }
            });
            calendar.render();

            calendar.on('dateClick', function (info) {
                $('#fecha').val(info.dateStr);
                console.log('clicked on ' + info.dateStr);
            });
        });

    </script>
@endsection
@section('content')

    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title col-form-label-sm text-primary mb-3">
                    <strong>Listado de Usuarios</strong>
                </h6>
                <div>
                    <input type="date" class="from-control" id="fecha">
                </div>
                <div id='calendar'></div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection