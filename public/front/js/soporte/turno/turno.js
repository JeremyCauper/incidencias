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
            control: [
                '#shoraIni',
                '#shoraFin',
                '#ahoraIni',
                '#ahoraFin'
            ],
            config: {
                type: "time",
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
        calcularHoras();
    });

    function calcularHoras() {
        $('#shoraIni').val('18:00');
        $('#ahoraIni').val('13:00');
        $('#shoraFin, #ahoraFin').val('08:20');
    }
    calcularHoras()
});

document.addEventListener('DOMContentLoaded', function () {
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
            calendario.removeAllEvents();

            // Solo cargar si aún no se han cargado los eventos para este año
            if (!añosCargados[añoActual]) {
                cargarEventosApi();
            } else {
                Object.entries(añosCargados[añoActual]).forEach(([key, e]) => {
                    agregarEventoRango(e.id, e.fecha_ini_s, e.fecha_fin_s, e.hora_ini_s, e.hora_fin_s, 0);
                    agregarEventoRango(e.id, e.fecha_ini_a, e.fecha_fin_a, e.hora_ini_a, e.hora_fin_a, 1);
                });
            }
        }
    });

    calendario.on('eventClick', function (info) {
        abrirModalDetalle(info.event);
    });

    // Manejar cambios en los inputs de fecha
    $('#sfechaIni, #sfechaFin, #afechaIni, #afechaFin').on('change', function () {
        actualizarNombreDia(this);
        if (this.id === 'sfechaIni') {
            establecerFechas(this.value);
        }
    });

    fObservador('.content-wrapper', () => {
        calendario.updateSize();
    });
});

function cargarEventosApi(añoActual = anioMemoria) {
    // Marcar el año como cargado para evitar duplicados en futuros cambios
    calendarLoding('show');
    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/asignacion-turno/index?anio=${añoActual}`,
        contentType: 'application/json',
        success: function (data) {
            añosCargados[añoActual] = data;

            calendario.removeAllEvents();
            Object.entries(data).forEach(([key, e]) => {
                // Puedes agregar una propiedad "id" o "apiEvent" en cada evento si fuera necesario
                agregarEventoRango(e.id, e.fecha_ini_s, e.fecha_fin_s, e.hora_ini_s, e.hora_fin_s, 0);
                agregarEventoRango(e.id, e.fecha_ini_a, e.fecha_fin_a, e.hora_ini_a, e.hora_fin_a, 1);
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

function establecerFechas(fechaInicio) {
    $('#sfechaFin').val(calcularFecha(fechaInicio, 7)).trigger('change');
    let fechaSabado = obtenerProximoSabado(fechaInicio);
    $('#afechaIni').val(fechaSabado).trigger('change');
    $('#afechaFin').val(calcularFecha(fechaSabado, 2)).trigger('change');
}

function agregarEventoRango(_id, fechaInicio, fechaFin, horaInicio, horaFin, tipo = 0) {
    let setting = [
        ['#4d7ed0', '#013489', 'TS', `event-s-${_id}`],
        ['#dcad4e', '#885b01', 'TA', `event-a-${_id}`]
    ][tipo];

    calendario.addEvent({
        id: setting[3],
        title: setting[2] + `: ${horaInicio} - ${horaFin}`,
        start: `${fechaInicio}T${horaInicio}`,
        end: `${calcularFecha(fechaFin, 1)}T${horaFin}`,
        allDay: true, // Cambiar a false para que respete el orden de horas
        backgroundColor: setting[0],
        borderColor: setting[0]
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
        url: `${__url}/soporte/asignacion-turno/${url}`,
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': __token },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            let datos = valid.data.data;
            $('#modal_turno').modal('hide');
            cargarEventosApi();
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

function abrirModalDetalle(eventInfo) {
    $('#modal_turno_detalle').modal('show');
    let info = (eventInfo.id).split('-');
    let datos = añosCargados[anioMemoria][info[2]];

    $('#btn-editar-turno').attr('onclick', `editarTurno(${info[2]})`).addClass('d-none');
    $('#btn-eliminar-turno').attr('onclick', `eliminarTurno(${info[2]})`).addClass('d-none');
    if (info[1] == 's') {
        $('#btn-editar-turno, #btn-eliminar-turno').removeClass('d-none');
    }
    $('#modal_turno_detalleLabel').html(`Detalle ${info[1] == 's' ? 'Turno Semanal' : 'Turno de Apoyo'}`);
    $('#modal_turno_detalle .modal-body .row').html(`
        <div class="col-12 my-2">
            <label class="form-label mb-0"><i class="far fa-calendar-check me-1"></i> FECHA</label>
            <span class="input-group-text w-100 px-0 border-0" style="font-size: .85rem;"><b class="me-1">Inicio:</b> ${datos['fecha_ini_' + info[1]]} <b class="ms-3 me-1">Fin:</b> ${datos['fecha_fin_' + info[1]]}</span>
        </div>
        <div class="col-12 my-2">
            <label class="form-label mb-0"><i class="fas fa-clock me-1"></i> HORA</label>
            <span class="input-group-text w-100 px-0 border-0" style="font-size: .85rem;"><b class="me-1">Inicio:</b> ${datos['hora_ini_' + info[1]]} pm <b class="ms-3 me-1">Fin:</b> ${datos['hora_fin_' + info[1]]} am</span>
        </div>
        <div class="col-12 my-2">
            <label class="form-label mb-0"><i class="fas fa-user-check me-1"></i> TECNICO</label>
            <span class="input-group-text w-100 px-0 border-0" style="font-size: .85rem;">${usuarios[datos['personal_' + info[1]]].text}</span>
        </div>
        `);
}

async function editarTurno(id) {
    $('#modal_turno_detalle').modal('hide');
    setTimeout(() => {
        $('#modal_turno').modal('show');
        $('#modal_turnoLabel').html('Editar Turno');
        let datos = añosCargados[anioMemoria][id];

        console.log(datos);


        $("#id").val(id);
        $("#sfechaIni").val(datos.fecha_ini_s).trigger('change');
        $("#shoraIni").val(datos.hora_ini_s);
        $("#sfechaFin").val(datos.fecha_fin_s).trigger('change');
        $("#shoraFin").val(datos.hora_fin_s);
        $("#spersonal").val(datos.personal_s).trigger('change');
        $("#afechaIni").val(datos.fecha_ini_a).trigger('change');
        $("#ahoraIni").val(datos.hora_ini_a);
        $("#afechaFin").val(datos.fecha_fin_a).trigger('change');
        $("#ahoraFin").val(datos.hora_fin_a);
        $("#apersonal").val(datos.personal_a).trigger('change');
    }, 500);
}

async function eliminarTurno(id) {
    $('#modal_turno_detalle').modal('hide');
    if (!await boxAlert.confirm({ t: '¿Estas de suguro de eliminar el turno?', h: 'no se podrá no se podrá revertir está operación.' })) return true;

    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/asignacion-turno/eliminar`,
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': __token },
        data: JSON.stringify({
            id: id
        }),
        beforeSend: boxAlert.loading,
        success: function (data) {
            if (data.success) {
                cargarEventosApi()
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}