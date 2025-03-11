$(document).ready(function () {
    const controles = [
        // Formulario incidencias datos de la empresas
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

    formatSelect('modal_incidencias');

    $('#tel_contac').on('change', function () {
    });

    $('#nro_doc').blur(async function () {
    });

    $('.modal').on('shown.bs.modal', function () {
    });

    $('.modal').on('hidden.bs.modal', function () {
    });
});

// let fechaInicial = null;
let dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
let calendar = null;
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true, // Permitir selección de fechas
        locale: 'es',
        headerToolbar: {
            left: 'title',
            center: '',
            right: 'prev,next today'
        },
        buttonText: {
            today: 'Hoy'
        },
        titleFormat: { year: 'numeric', month: 'long' },
    });
    calendar.render();

    calendar.on('dateClick', function (info) {
        var fechaIni = info.dateStr;
        $('#sfechaIni').val(fechaIni).trigger('change');
        $('#sfechaFin').val(CalcularFecha(fechaIni, 7)).trigger('change');

        var fechaSabado = obtenerSabado(fechaIni);
        $('#afechaIni').val(fechaSabado).trigger('change');
        $('#afechaFin').val(CalcularFecha(fechaSabado, 2)).trigger('change');
        
        $('#modal_turno').modal('show');
    });
});

function AgregarRango(fechaInicial, fechaFinal, tipo = 0) {
    let colores = [
        ['#4d7ed0', '#013489'],
        ['#dcad4e', '#885b01']
    ][tipo];

    // Agregar el rango seleccionado como un "evento"
    calendar.addEvent({
        title: 'Selección',
        start: fechaInicial,
        end: CalcularFecha(fechaFinal, 1),
        allDay: true,
        backgroundColor: colores[0],
        borderColor: colores[1]
    });
}

function setNombreDia(_this) {
    let $this = $(_this);
    let fecha = new Date($this.val());
    let diaSemana = fecha.getDay();
    console.log(diaSemana);
    
    $(`#${$this.attr('id')}Text`).html(dias[diaSemana]);
}

function obtenerSabado(fechaStr) {
    let fecha = new Date(fechaStr);
    let diaSemana = fecha.getDay();
    
    // Calcular cuántos días faltan para el próximo sábado
    let diasFaltantes = (6 - diaSemana + 6) % 7; 
    fecha.setDate(fecha.getDate() + diasFaltantes);

    // Formatear la fecha a "YYYY-MM-DD"
    return fecha.toISOString().split('T')[0];
}

function CalcularFecha(fecha, suma) {
    let newfecha = new Date(fecha); // Crear el objeto Date
    newfecha.setDate(newfecha.getDate() + suma); // Sumar un día
    // Formatear la nueva newfecha en "YYYY-MM-DD"
    let fechaFin = newfecha.toISOString().split('T')[0];

    return fechaFin;
}