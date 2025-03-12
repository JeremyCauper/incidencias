$(document).ready(function () {
    const controles = [
        // Formulario turno datos de la empresas
        {
            control: [
                '#sfechaIni',
                '#sfechaFin',
                '#afechaIni',
                '#afechaFin'
            ],
            config: {
                type: "date",
                require: true
            }
        },
        {
            control: ['#spersonal', '#apersonal'],
            config: {
                require: true
            }
        }
    ];

    controles.forEach(control => {
        defineControllerAttributes(control.control, control.config);
    });

    formatSelect('modal_turno');

    $('#sfechaIni').on('change', function () {
    });

    $('#nro_doc').blur(async function () {
    });

    $('.modal').on('shown.bs.modal', function () {
    });

    $('.modal').on('hidden.bs.modal', function () {
    });
});

let diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
let calendario = null;
let eventoTemporal = null;
let anioMemoria = null;
let añosCargados = {}; // Registro de años ya cargados para evitar duplicados

document.addEventListener('DOMContentLoaded', function () {
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
        titleFormat: { year: 'numeric', month: 'long' }
    });

    calendario.render();
    anioMemoria = calendario.getDate().getFullYear();
    cargarEventosApi(); // Cargar eventos del año inicial

    calendario.on('dateClick', function (info) {
        $('#sfechaIni').val(info.dateStr).trigger('change');
        $('#modal_turno').modal('show');
    });

    calendario.on('datesSet', function () {
        let añoActual = calendario.getDate().getFullYear();
        if (añoActual !== anioMemoria) {
            anioMemoria = añoActual;
            // Solo cargar si aún no se han cargado los eventos para este año
            if (!añosCargados[añoActual]) {
                cargarEventosApi();
            }
        }
    });

    calendario.on('eventClick', function (info) {
        alert(`Evento: ${info.event.title}\nInicio: ${info.event.start.toISOString().split('T')[0]}\nFin: ${info.event.end ? info.event.end.toISOString().split('T')[0] : 'No definido'}`);
    });    

    // Manejar cambios en los inputs de fecha
    $('#sfechaIni, #sfechaFin, #afechaIni, #afechaFin').on('change', function () {
        actualizarNombreDia(this);
        if (this.id === 'sfechaIni') {
            establecerFechas(this.value);
        }
    });

    // Guardar evento permanente
    $('#guardarEvento').on('click', function () {
        if (eventoTemporal) {
            agregarEventoRango(eventoTemporal.start, eventoTemporal.end, 0);
            eventoTemporal = null;
        }
        $('#modal_turno').modal('hide');
    });

    function cargarEventosApi(añoActual = calendario.getDate().getFullYear()) {
        // Marcar el año como cargado para evitar duplicados en futuros cambios
        añosCargados[añoActual] = true;
        calendarLoding('show');
        $.ajax({
            type: 'GET',
            url: `${__url}/asignacion-turno/index?anio=${añoActual}`,
            contentType: 'application/json',
            success: function (data) {
                data.forEach(e => {
                    // Puedes agregar una propiedad "id" o "apiEvent" en cada evento si fuera necesario
                    agregarEventoRango(e.fecha_ini_s, e.fecha_fin_s, 0);
                    agregarEventoRango(e.fecha_ini_a, e.fecha_fin_a, 1);
                });
            },
            error: function (jqXHR) {
                boxAlert.box({ i: 'error', t: 'Error', h: 'No se pudo cargar los datos.' });
                console.log(jqXHR);
            },
            complete: function () {
                calendarLoding('hide');
            }
        });
    }
});

function establecerFechas(fechaInicio) {
    $('#sfechaFin').val(calcularFecha(fechaInicio, 7)).trigger('change');
    let fechaSabado = obtenerProximoSabado(fechaInicio);
    $('#afechaIni').val(fechaSabado).trigger('change');
    $('#afechaFin').val(calcularFecha(fechaSabado, 2)).trigger('change');
}

function agregarEventoRango(fechaInicio, fechaFin, tipo = 0) {
    let setting = [
        ['#4d7ed0', '#013489', 'Turno Semanal'],
        ['#dcad4e', '#885b01', 'Turno de Apoyo']
    ][tipo];

    calendario.addEvent({
        title: setting[2],
        start: fechaInicio,
        end: calcularFecha(fechaFin, 1),
        allDay: true,
        backgroundColor: setting[0],
        borderColor: setting[1]
    });
}

function actualizarNombreDia(elemento) {
    let fecha = new Date($(elemento).val());
    let diaSemana = fecha.getDay();
    $(`#${elemento.id}Text`).html(diasSemana[diaSemana]);
}

function obtenerProximoSabado(fechaStr) {
    let fecha = new Date(fechaStr);
    let diasParaSabado = (5 - fecha.getDay()) % 7;
    fecha.setDate(fecha.getDate() + diasParaSabado);
    return fecha.toISOString().split('T')[0];
}

function calcularFecha(fecha, diasSumar) {
    let nuevaFecha = new Date(fecha);
    nuevaFecha.setDate(nuevaFecha.getDate() + diasSumar);
    return nuevaFecha.toISOString().split('T')[0];
}

function calendarLoding(accion) {
    const contenedorCalendario = $('#content-calendar');
    if (accion === 'show') {
        contenedorCalendario.prepend(`<div class="loader-of-modal"><div style="display:flex; justify-content:center;"><div class="loader"></div></div></div>`);
    } else if (accion === 'hide') {
        contenedorCalendario.children('.loader-of-modal').remove();
    }
}

document.getElementById('form-turno').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_turno', 'show');

    let url = $('#id').val() ? 'actualizar' : 'registrar';
    let valid = validFrom(this);

    if (!valid.success) {
        return fMananger.formModalLoding('modal_turno', 'hide');
    }

    $.ajax({
        type: 'POST',
        url: `${__url}/asignacion-turno/${url}`,
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': __token },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            let datos = valid.data.data;
            $('#modal_turno').modal('hide');
            agregarEventoRango(datos.sfechaIni, datos.sfechaFin, 0);
            agregarEventoRango(datos.afechaIni, datos.afechaFin, 1);
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR) {
            console.log(jqXHR);
            let errorData = jqXHR.responseJSON;
            boxAlert.box({ i: errorData.icon, t: errorData.title, h: errorData.message });
        },
        complete: function () {
            fMananger.formModalLoding('modal_turno', 'hide');
        }
    });
});