$(document).ready(function () {
    const controles = [
        // Formulario incidencias datos de la empresas
        {
            control: '#empresa',
            config: {
                require: true
            }
        },
        {
            control: '#sucursal',
            config: {
                require: true,
                disabled: true
            }
        },
        {
            control: '#tel_contac',
            config: {}
        },
        {
            control: '#nro_doc',
            config: {
                "control-type": "int",
                mnl: 8,
                mxl: 11,
                errorMessage: "El numero de DNI es invalido.",
                mask: { reg: "99999999999" }
            }
        },
        {
            control: '#nom_contac',
            config: {
                mxl: 250,
            }
        },
        {
            control: '#car_contac',
            config: {}
        },
        {
            control: '#cor_contac',
            config: {
                "control-type": "email",
                mxl: 250
            }
        },
        // Datos Incidencia
        {
            control: ['#tEstacion', '#prioridad', '#tSoporte', '#tIncidencia'],
            config: {
                require: true
            }
        },
        {
            control: ['#problema', '#sproblema'],
            config: {
                require: true,
                disabled: true
            }
        },
        {
            control: '#fecha_imforme',
            config: {
                type: "date",
                require: true
            }
        },
        {
            control: '#hora_informe',
            config: {
                type: "time",
                require: true
            }
        },
        {
            control: '#observacion',
            config: {}
        },

        //Formulario Orden
        {
            control: '#n_orden',
            config: {
                require: true
            }
        },
        {
            control: ['#observaciones', '#recomendacion'],
            config: {
                require: true
            }
        },
        {
            control: ['#fecha_f', '#hora_f'],
            config: {}
        },
        {
            control: '#codigo_aviso',
            config: {
                require: true,
                mxl: 50,
            }
        },
    ];

    controles.forEach(control => {
        defineControllerAttributes(control.control, control.config);
    });

    formatSelect('modal_incidencias');
    formatSelect('modal_assign');
    formatSelect('modal_orden');

    $('#empresa').on('change', function () {
        const empresa = empresas[$(this).val()] ?? "";
        if ($(this).val()) {
            const contrato = [['danger', 'Sin Contrato'], ['success', 'En Contrato']];
            $('#modal_incidencias [aria-item="contrato"]').attr('class', `ms-3 badge badge-${contrato[empresa.contrato][0]} badge-lg`).html(contrato[empresa.contrato][1]);
        } else {
            $('#modal_incidencias [aria-item="contrato"]').attr('class', 'd-none');
        }
        fillSelect(['#sucursal'], sucursales, 'ruc', empresa.ruc, 'id', 'nombre');
    });

    $('#tIncidencia').on('change', function () {
        fillSelect(['#problema', '#sproblema'], obj_problem, 'tipo_incidencia', $(this).val(), 'id', 'text');
    });

    $('#problema').on('change', function () {
        fillSelect(['#sproblema'], obj_subproblem, 'id_problema', $(this).val(), 'id', 'text');
    });

    $('#tel_contac').on('change', function () {
        if (!$(this).val()) {
            $('#car_contac').val('').trigger('change.select2');
            $('#nro_doc, #nom_contac, #cor_contac').val('');
            return false;
        }

        const contacto = obj_eContactos[$(this).val()] || null;
        if (contacto) {
            $('#cod_contact').val(contacto.id_contact);
            $('#tel_contac').val(contacto.telefono).trigger('change.select2');
            $('#nro_doc').val(contacto.nro_doc);
            $('#nom_contac').val(contacto.nombres);
            $('#car_contac').val(contacto.cargo).trigger('change.select2');
            $('#cor_contac').val(contacto.correo);
        }
    });

    $('#nro_doc').blur(async function () {
        let datos = await consultarDniInput($(this));
        if (datos.success) {
            $('#nom_contac').val(datos.data.completo);
        }
    });

    $('.modal').on('shown.bs.modal', function () {
        $('#nom_contac').val('');
        $('#content-cantidad input[type="number"]').val(1);
        $('#fecha_imforme').val(date('Y-m-d'));
        $('#hora_informe').val(date('H:i:s'));
    });

    $('.modal').on('hidden.bs.modal', function () {
        $('#modal_incidencias [aria-item="codigo"]').attr('class', 'd-none');
        changeCodInc(cod_incidencia);
        fillSelect(['#sucursal', '#problema', '#sproblema']);
        $('#contenedor-personal').removeClass('d-none');
        $('#observacion').val('');
        $('#modal_incidencias [aria-item="contrato"]').attr('class', 'd-none');

        cPersonal.deleteTable();
        cPersonal1.deleteTable();
        removeClienteDataFirm();
    });

    setInterval(() => {
        $('#fecha_f').val(date('Y-m-d')).attr('disabled', true);
        $('#hora_f').val(date('H:i:s')).attr('disabled', true);
    }, 1000);

    $('#createMaterial').on('change', function () {
        manCantidad();
    })

    $('[ctable-create="#createMaterial"]').on('click', function () {
        manCantidad();
    });

    $('#button-cod-orden').on('click', function () {
        const check = eval($(this).attr('check-cod')) ? false : true;
        $(this).attr('check-cod', check).html(check ? 'Cod. Sistema' : 'Cod. Tecnico');
        $('#n_orden').val(check ? cod_orden : "").attr('disabled', check);
    })

    fObservador('.content-wrapper', () => {
        tb_incidencia.columns.adjust().draw();
    });
});

const cMaterial = new CTable('#createMaterial', {
    thead: ['#', 'PRODUCTO / MATERIAL', 'CANTIDAD'],
    tbody: [
        { data: 'id_material' },
        { data: 'producto' },
        { data: 'cantidad' }
    ],
    extract: ['id_material', 'cantidad']
});

const cPersonal = new CTable('#createPersonal', {
    thead: ['#', 'Nro. Documento', 'Nombres y Apellidos'],
    tbody: [
        { data: 'id' },
        { data: 'doc' },
        { data: 'nombre' }
    ],
    extract: ['id']
});

const cPersonal1 = new CTable('#createPersonal1', {
    thead: ['#', 'Nro. Documento', 'Nombres y Apellidos'],
    tbody: [
        { data: 'id' },
        { data: 'doc' },
        { data: 'nombre' }
    ],
    extract: ['id']
});

const tb_incidencia = new DataTable('#tb_incidencia', {
    scrollX: true,
    scrollY: 400,
    ajax: {
        url: `${__url}/incidencias/registradas/index`,
        dataSrc: function (json) {
            $.each(json.conteo_data, function (panel, count) {
                $(`b[data-panel="${panel}"]`).html(count);
            });
            fillSelectContac(json.contact);
            return json.data;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'incidencia' },
        { data: 'estado' },
        {
            data: 'empresa', render: function (data, type, row) {
                let empresa = empresas[data];
                return `${empresa.ruc} - ${empresa.razon_social}`;
            }
        },
        {
            data: 'sucursal', render: function (data, type, row) {
                return sucursales[data].nombre;
            }
        },
        { data: 'registrado' },
        {
            data: 'tipo_estacion', render: function (data, type, row) {
                return tipo_estacion[data].descripcion;
            }
        },
        {
            data: 'tipo_incidencia', render: function (data, type, row) {
                return tipo_incidencia[data].descripcion;
            }
        },
        {
            data: 'problema', render: function (data, type, row) {
                return `${obj_problem[data].text} / ${obj_subproblem[row.subproblema].text}`;
            }
        },
        { data: 'acciones' }
    ],
    order: [[4, 'desc']],
    createdRow: function (row, data, dataIndex) {
        const row_bg = ['row-bg-warning', 'row-bg-info', 'row-bg-primary', '', 'row-bg-danger'];
        $(row).find('td:eq(0), td:eq(1), td:eq(4), td:eq(5), td:eq(6), td:eq(8)').addClass('text-center');
        $(row).find('td:eq(8)').addClass(`td-acciones`);
        $(row).addClass(row_bg[data.estado_informe]);
    },
    processing: true
});

function updateTable() {
    tb_incidencia.ajax.reload();
}
mostrar_acciones('tb_incidencia');


function searchTable(search) {
    const biblio = ['', 'asignada', 'sin asignar', 'en proceso'];
    tb_incidencia.column([1]).search(biblio[search]).draw();
}

document.getElementById('form-incidencias').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_incidencias', 'show');
    const url = $('#id_inc').val() ? `actualizar` : `registrar`;

    var valid = validFrom(this);
    if (!valid.success)
        return fMananger.formModalLoding('modal_incidencias', 'hide');

    const personal = cPersonal.extract();
    if (!personal) {
        return fMananger.formModalLoding('modal_incidencias', 'hide');
    }
    valid.data.data['personal'] = personal;

    $.ajax({
        type: 'POST',
        url: __url + `/incidencias/registradas/${url}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            $('#modal_incidencias').modal('hide');
            cod_incidencia = data.data.cod_inc;
            boxAlert.box({ i: data.icon, t: data.title, h: data.message })
            updateTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let mensaje = "";
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    mensaje = formatRequired(datae.required);
                }
                if (datae.hasOwnProperty('unique')) {
                    mensaje = formatUnique(datae.unique);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_incidencias', 'hide');
        }
    });
});

function ShowDetail(e, id) {
    let obj = extractDataRow(e);
    obj.estado = (obj.estado).replaceAll('.7rem;', '.8rem;');

    $.each(obj, function (panel, count) {
        $(`#modal_detalle [aria-item="${panel}"]`).html(count);
    });
    $('#modal_detalle').modal('show');
    fMananger.formModalLoding('modal_detalle', 'show');
    $('#content-seguimiento').html('');
    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/detail/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            var seguimiento = data.data.seguimiento;
            var incidencia = data.data.incidencia;

            $(`#modal_detalle [aria-item="observacion"]`).html(incidencia.observacion);

            fMananger.formModalLoding('modal_detalle', 'hide');
            seguimiento.sort((a, b) => new Date(a.date) - new Date(b.date));
            seguimiento.forEach(function (element) {
                $('#content-seguimiento').append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="${element.img}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                            <div class="ms-3">
                                <p class="fw-bold mb-1">${element.nombre}</p>
                                <p class="text-muted" style="font-size: .73rem;font-family: Roboto; margin-bottom: .2rem;">${element.text}</p>
                                <p class="text-muted mb-0" style="font-size: .73rem;font-family: Roboto;">${element.contacto}</p>
                            </div>
                        </div>
                        <span class="badge rounded-pill badge-primary">${element.date}</span>
                    </li>`);
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_detalle', 'hide');
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

function ShowEdit(id) {
    $('#modal_incidencias').modal('show');
    $('#contenedor-personal').addClass('d-none');
    fMananger.formModalLoding('modal_incidencias', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }

            const dt = data.data;
            fMananger.formModalLoding('modal_incidencias', 'hide');
            $('#id_inc').val(dt.id_incidencia);
            changeCodInc(dt.cod_incidencia);
            $('#empresa').val(dt.ruc_empresa).trigger('change');

            fillSelect(['#sucursal'], sucursales, 'ruc', dt.ruc_empresa, 'id', 'nombre');
            $('#sucursal').val(dt.id_sucursal).trigger('change');
            $('#cod_contact').val(dt.id_contacto);
            $('#tel_contac').val(dt.telefono).trigger('change');
            $('#nro_doc').val(dt.nro_doc);
            $('#nom_contac').val(dt.nombres);
            $('#car_contac').val(dt.cargo).trigger('change');
            $('#cor_contac').val(dt.correo);
            $('#tEstacion').val(dt.id_tipo_estacion).trigger('change');
            $('#prioridad').val(dt.prioridad).trigger('change');
            $('#tSoporte').val(dt.id_tipo_soporte).trigger('change');
            $('#tIncidencia').val(dt.id_tipo_incidencia).trigger('change');
            fillSelect(['#problema', '#sproblema'], obj_problem, 'tipo_incidencia', dt.id_tipo_incidencia, 'id', 'text');
            $('#problema').val(dt.id_problema).trigger('change');
            fillSelect(['#sproblema'], obj_subproblem, 'id_problema', dt.id_problema, 'id', 'text');
            $('#sproblema').val(dt.id_subproblema).trigger('change');
            $('#fecha_imforme').val(dt.fecha_informe);
            $('#hora_informe').val(dt.hora_informe);
            $('#observacion').val(dt.observacion);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

function habilitarCodAviso(accion) {
    var selector_material = $('#createMaterial').parent().parent();
    var content_cantidad = $('#content-codAviso');
    var codAviso = $('#codAviso');
    if (accion) {
        selector_material.addClass('col-lg-6').removeClass('col-lg-9');
        content_cantidad.removeClass('d-none');
        return codAviso.attr('name', 'codAviso');
    }
    selector_material.addClass('col-lg-9').removeClass('col-lg-6');
    content_cantidad.addClass('d-none');
    codAviso.removeAttr('name');
}


function ShowAssign(e, cod) {
    const obj = extractDataRow(e);
    obj.estado = (obj.estado).replaceAll('.7rem;', '.8rem;');

    $.each(obj, function (panel, count) {
        $(`#modal_assign [aria-item="${panel}"]`).html(count);
    });
    $('#modal_assign').modal('show');
    fMananger.formModalLoding('modal_assign', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            const dt = data.data;
            fMananger.formModalLoding('modal_assign', 'hide');
            (dt.personal_asig).forEach(element => {
                const accion = dt.estado_informe == 2 ? false : true;
                cPersonal1.fillTable(element.id, accion);
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

async function AssignPer() {
    if (!await boxAlert.confirm({ h: `Está apunto de asignar personal a la incidencia` })) return true;
    fMananger.formModalLoding('modal_assign', 'show');

    const cod = $('#modal_assign [aria-item="codigo"]').html();
    const estado = $(`#modal_assign [aria-item="estado"]`).text().replaceAll(' ', '').toLowerCase();

    const personal = cPersonal1.extract();
    if (!personal) {
        return fMananger.formModalLoding('modal_assign', 'hide');
    }

    $.ajax({
        type: 'POST',
        url: `${__url}/incidencias/registradas/assignPer`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify({
            cod_inc: cod,
            estado: estado == 'enproceso' ? false : true,
            personal_asig: personal
        }),
        success: function (data) {
            var estadoInfo = [
                { 'c': 'warning', 't': 'Sin Asignar' },
                { 'c': 'info', 't': 'Asignada' },
                { 'c': 'primary', 't': 'En Proceso' }
            ];
            fMananger.formModalLoding('modal_assign', 'hide');
            if (data.success) {
                cod_incidencia = data.data.cod_inc;
                $(`#modal_assign [aria-item="estado"]`).find('label').attr("class", `badge badge-${estadoInfo[data.data.estado]['c']}`).html(estadoInfo[data.data.estado]['t']); //.html(`<label class="badge badge-${estadoInfo[data.data.estado]['c']}" style="font-size: .8rem;">${estadoInfo[data.data.estado]['t']}</label>`);
                cPersonal1.data = data.data.personal;
                updateTable();
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let mensaje = "";
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    mensaje = formatRequired(datae.required);
                }
                if (datae.hasOwnProperty('unique')) {
                    mensaje = formatUnique(datae.unique);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_assign', 'hide');
        }
    });
}

async function DeleteInc(id) {
    if (!await boxAlert.confirm({ t: '¿Estas de suguro de eliminar la incidencia?', h: 'no se podrá no se podrá revertir está operación.' })) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/incidencias/registradas/destroy/${id}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        beforeSend: boxAlert.loading,
        success: function (data) {
            if (data.success) {
                updateTable()
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

async function StartInc(cod, estado) {
    if (!await boxAlert.confirm({ h: `Esta apunto de <b><i class="fas fa-${estado == 2 ? 'clock-rotate-left"></i> re' : 'stopwatch"></i> '}iniciar</b> la incidencia` })) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/incidencias/registradas/startInc`,
        data: JSON.stringify({
            'codigo': cod,
            'estado': estado
        }),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        beforeSend: boxAlert.loading,
        success: function (data) {
            if (data.success) {
                updateTable()
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let mensaje = "";
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    mensaje = formatRequired(datae.required);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

async function OrdenDetail(e, cod) {
    const obj = extractDataRow(e);
    obj.estado = (obj.estado).replaceAll('.7rem;', '1rem;');

    $.each(obj, function (panel, count) {
        $(`#modal_orden [aria-item="${panel}"]`).html(count);
    });
    $(`#modal_orden [aria-item="empresaFooter"]`).html(obj.empresa);
    $('#modal_orden [aria-item="tecnicos"]').html('');
    $('#modal_orden').modal('show');
    fMananger.formModalLoding('modal_orden', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                let dt = data.data;
                let personal = dt.personal_asig;
                let empresa = empresas[dt.ruc_empresa]
                $('[aria-item="observacion"]').html(dt.observacion);
                $('#codInc').val(dt.cod_incidencia);
                var tecnicos = personal.map(persona => persona.tecnicos);
                habilitarCodAviso(empresa.codigo_aviso);

                $('#modal_orden [aria-item="tecnicos"]').html('<i class="fas fa-user-gear"></i>' + tecnicos.join(', <i class="fas fa-user-gear ms-1"></i>'));
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_orden', 'hide');
        }
    });
}

function fillSelectContac(data) {
    $('#tel_contac').html('<option value=""></option>');
    Object.entries(data).forEach(([key, e]) => {
        $('#tel_contac').append($('<option>').val(e.telefono).text(e.telefono));
    });
    obj_eContactos = data;
}

function validContac(_this) {
    let tel_contac = $('#tel_contac');
    let nro_doc = $('#nro_doc');
    let nom_contac = $('#nom_contac');
    let cor_contac = $('#cor_contac');
    let car_contac = $('#car_contac');
    let val = (tel_contac.val() || nro_doc.val() || nom_contac.val() || car_contac.val() || cor_contac.val()) ? true : false;

    var validacion = (control, accion, requerido) => {
        let id = control.attr('id');
        let label = $(`[for="${id}"]`);
        if (accion) {
            label.addClass('required');
            control.attr('require', requerido);
        } else {
            label.removeClass('required');
            control.removeAttr('require');
        }
    }
    validacion(tel_contac, val, 'Telefono Contacto');
    validacion(nom_contac, val, 'Nombre Contacto');
    validacion(car_contac, val, 'Cargo Contacto');
}

document.getElementById('form-orden').addEventListener('submit', async function (event) {
    event.preventDefault();

    if ($('[ctable-contable="#createMaterial"]').children().length && !$('#codAviso').val())
        if (!await boxAlert.confirm({ h: `El campo 'Código Aviso' está vacío.` })) return $('#codAviso').focus();

    fMananger.formModalLoding('modal_orden', 'show');
    const atencion = $('#modal_orden [aria-item="atencion"]').html();

    var valid = validFrom(this);
    valid.data.data.materiales = cMaterial.extract();

    if (!valid.success)
        return fMananger.formModalLoding('modal_orden', 'hide');
    var n_orden = valid.data.data.n_orden;
    valid.data.data.cod_sistema = eval($('#button-cod-orden').attr('check-cod'));

    $.ajax({
        type: 'POST',
        url: __url + '/orden/create',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            console.log(data);

            if (data.success) {
                $('#modal_orden').modal('hide');
                cod_ordenSer = data.data.num_orden;
                if (atencion.toUpperCase() == 'PRESENCIAL')
                    window.open(`${__url}/orden/documentopdf/${n_orden}`, `Visualizar PDF ${n_orden}`, "width=900, height=800");
                updateTable();
                return true;
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let mensaje = "";
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    mensaje = formatRequired(datae.required);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_orden', 'hide');
        }
    });
});

function AddCodAviso(e, cod) {
    const obj = extractDataRow(e);
    obj.estado = (obj.estado).replaceAll('.7rem;', '.8rem;');

    $.each(obj, function (panel, count) {
        $(`#modal_addcod [aria-item="${panel}"]`).html(count);
    });
    $('#modal_addcod').modal('show');
    fMananger.formModalLoding('modal_addcod', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/detail/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }

            const dt = data.data;
            $('#cod_incidencia').val(cod);
            $('#cod_orden_ser').val(dt.incidencia.cod_orden);
            fMananger.formModalLoding('modal_addcod', 'hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

document.getElementById('form-addcod').addEventListener('submit', async function (event) {
    event.preventDefault();

    if (!await boxAlert.confirm({ h: `Después no se podrá modificar el codigo de aviso ingresado.` })) return true;
    
    fMananger.formModalLoding('modal_addcod', 'show');
    const atencion = $('#modal_orden [aria-item="atencion"]').html();
    var valid = validFrom(this);

    if (!valid.success)
        return fMananger.formModalLoding('modal_addcod', 'hide');

    $.ajax({
        type: 'POST',
        url: __url + '/orden/editCodAviso',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_addcod', 'hide');
            if (data.success) {
                $('#modal_addcod').modal('hide');
                if (atencion.toUpperCase() == 'PRESENCIAL')
                    window.open(`${__url}/orden/documentopdf/${data.cod_orden}`, `Visualizar PDF ${data.cod_orden}`, "width=900, height=800");
                updateTable();
                return true;
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_addcod', 'hide');
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
});

function changeCodInc(val) {
    $('#cod_inc').val(val);
    $('#cod_inc_text').html(val);
}

/*////////////////////////////////////////
/       SCRIPT CREAR FIRMA DIGITAL       /
////////////////////////////////////////*/

// Elementos del DOM
const fileInputFirma = document.getElementById('firma_digital');
const previFirma = document.getElementById('PreviFirma');
const removeImgFirma = document.getElementById('removeImgFirma');
const textFirmaDigital = document.getElementById('textFirmaDigital');
const uploadImgFirmaBtn = document.getElementById('uploadImgFirma');
const createFirmaBtn = document.getElementById('createFirma');

// Configuración
const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB

// Función para manejar la carga de imágenes
function handleFileInput(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (file.size > MAX_FILE_SIZE) {
        alert('El archivo debe ser menor a 10MB');
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        const imageSrc = e.target.result;
        previFirma.src = imageSrc;
        previFirma.alt = file.name;
        removeImgFirma.style.display = 'block';
        textFirmaDigital.value = btoa(imageSrc);
    };
    reader.readAsDataURL(file);
}

// Función para crear la firma digital
async function createDigitalSignature() {
    setInertOnElements(true);

    Swal.fire({
        title: '<h6 class="text-primary">CREAR FIRMA DIGITAL</h6>',
        html: `
            <div>
                <div class="content-signature-pad">
                    <canvas id="signature-pad" height="180" width="260" style="border: 2px dashed #ccc;"></canvas>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary btn-sm" id="save">Guardar</button>
                    <button class="btn btn-danger btn-sm" id="clear">Limpiar</button>
                    <button class="btn btn-info btn-sm" onclick="Swal.close()">Cerrar</button>
                </div>
            </div>`,
        willClose: () => setInertOnElements(false),
        showConfirmButton: false
    });

    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    document.getElementById('clear').addEventListener('click', () => signaturePad.clear());

    document.getElementById('save').addEventListener('click', () => {
        if (signaturePad.isEmpty()) {
            alert("Por favor, dibuja una firma primero.");
            return;
        }

        const dataURL = signaturePad.toDataURL();
        updateSignaturePreview(dataURL);
        Swal.close();
    });
}

// Función para actualizar la vista previa de la firma
function updateSignaturePreview(dataURL) {
    previFirma.src = dataURL;
    previFirma.classList.remove('visually-hidden');
    removeImgFirma.style.display = 'block';
    if (!$('#nomFirmaDigital').val())
        textFirmaDigital.value = btoa(dataURL);
}

// Función para eliminar la firma cargada
function removeSignature() {
    previFirma.src = '';
    previFirma.classList.add('visually-hidden');
    textFirmaDigital.value = '';
    removeImgFirma.style.display = 'none';
    fileInputFirma.value = '';
}

// Función para establecer o quitar el atributo "inert" en elementos del DOM
function setInertOnElements(enable) {
    const bodyChildren = Array.from(document.body.children);
    bodyChildren.forEach(child => {
        if (!child.classList.contains('swal2-container')) {
            enable ? child.setAttribute('inert', '') : child.removeAttribute('inert');
        }
    });
}

// Función para previsualizar imágenes
function previewImage(data) {
    setInertOnElements(true);

    Swal.fire({
        title: '<h5 class="card-title text-linkedin">PREVISUALIZACIÓN DE LA IMAGEN CARGADA</h5>',
        html: `<div><img src="${data}" /></div>`,
        willClose: () => setInertOnElements(false)
    });
}

// Eventos
uploadImgFirmaBtn.addEventListener('click', () => fileInputFirma.click());
fileInputFirma.addEventListener('change', handleFileInput);
createFirmaBtn.addEventListener('click', createDigitalSignature);
removeImgFirma.addEventListener('click', removeSignature);

function manCantidad(params = '') {
    const selector = $('#createMaterial');
    const cantidad = $('input[input-cantidad=""]');
    const content_cantidad = $('#content-cantidad');

    if (!selector.val()) {
        content_cantidad.addClass('disabled');
        return cantidad.val('');
    }

    if (!cantidad.val()) {
        cantidad.val(1);
        content_cantidad.removeClass('disabled');
    }
    let number = cantidad.val();
    switch (params) {
        case '+':
            number++;
            cantidad.val(number);
            break;

        case '-':
            if (number > 1) {
                number--;
                cantidad.val(number);
            }
            break;

        case 'press':
            if (number < 1 || !number) {
                cantidad.val(1);
            }
            else {
                cantidad.val(parseInt(number));
            }
            break;
    }
    let option = selector.children(`option[value="${selector.val()}"]`);
    let obj = JSON.parse(atob(option.attr('data-value')));
    obj.cantidad = number;
    option.attr('data-value', btoa(JSON.stringify(obj)));
}

$('#search_signature').on('change', function () {
    const regex = /^[0-9]{8}$/;
    const dni = $(this);
    const search_signature_text = $('.search_signature_text');
    if (!regex.test(dni.val())) return false;
    search_signature_text.html('<i class="fas fa-magnifying-glass"></i>').removeAttr('signature-clear');

    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/searchCliente/${dni.val()}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        beforeSend: function () {
            search_signature_text.html('<div class="spinner-border text-primary" role="status" style="width: 19px;height: 19px;"></div>');
        },
        success: function (data) {
            if (data.success) {
                var datos = data.data;
                dni.val(`${datos.documento} - ${datos.nombre}`);
                $('#n_doc').val(datos.documento);
                $('#nom_cliente').val(datos.nombre);
                if (datos.consulta) {
                    $('#id_firmador').val(datos.id);
                    var firma = datos.firma_digital;
                    $('#nomFirmaDigital').val(firma);
                    if (firma) {
                        updateSignaturePreview(`${__asset}/images/client/${firma}`);
                    }
                }
                dni.attr({ 'disabled': "" });
            } else {
                if (dni.val() == "00000000") {
                    dni.val(`00000000 - Clientes Varios`);
                    $('#n_doc').val('00000000');
                    $('#nom_cliente').val('Clientes Varios');
                }
            }
            search_signature_text.html('<i class="fas fa-xmark"></i>').attr({ 'signature-clear': "" });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
})

$(".search_signature_text").on("click", function () {
    removeClienteDataFirm();
});

function removeClienteDataFirm() {
    var search_signature_text = $(".search_signature_text");
    if (search_signature_text.attr('signature-clear') !== undefined) {
        $('#search_signature').val('').removeAttr('disabled');
        search_signature_text.html('<i class="fas fa-magnifying-glass"></i>').removeAttr('signature-clear');
        $('#n_doc, #nom_cliente, #id_firmador, #nomFirmaDigital').val('');
        removeSignature();
    }
}