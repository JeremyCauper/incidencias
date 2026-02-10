$(document).ready(function () {
    configControls([
        // Formulario incidencias datos de la empresas
        {
            control: '#empresa',
            requested: true
        },
        {
            control: '#sucursal',
            requested: true,
            disabled: true
        },
        {
            control: '#nro_doc',
            addLabel: '<span class="texto">Dni</span>',
            controlType: "ndoc",
        },
        {
            control: '#nom_contac',
            addLabel: 'Nombre'
        },
        {
            control: '#tel_contac',
            addLabel: 'Telefono'
        },
        {
            control: '#car_contac',
            addLabel: 'Cargo'
        },
        {
            control: '#cor_contac',
            addLabel: 'Correo',
            controlType: "email",
            mxl: 250
        },
        // Datos Incidencia
        {
            control: '#tEstacion',
            addLabel: 'Tipo Estacion',
            requested: true
        },
        {
            control: '#tIncidencia',
            addLabel: 'Tipo Incidencia',
            requested: true
        },
        {
            control: '#tSoporte',
            addLabel: 'Tipo Soporte',
            requested: true
        },
        {
            control: '#problema',
            requested: true,
            disabled: true
        },
        {
            control: '#sproblema',
            addLabel: 'Sub Problema',
            requested: true,
            disabled: true
        },
        {
            control: '#fecha_imforme',
            type: "date",
            requested: true
        },
        {
            control: '#hora_informe',
            type: "time",
            requested: true
        },
        {
            control: '#observacion',
        },

        //Formulario Orden
        // {
        //     control: '#n_orden',
        //     requested: true
        // },
        {
            control: ['#observaciones', '#recomendacion'],
            requested: true
        },
        {
            control: '#fecha_f',
            addLabel: 'Fecha Fin',
        },
        {
            control: '#hora_f',
            addLabel: 'Hora Fin',
        },
        {
            control: '#codigo_aviso',
            requested: true,
            mxl: 50,
        },
    ]);

    $('#empresa').on('change', function () {
        const empresa = empresas[$(this).val()] ?? "";
        if ($(this).val()) {
            const contrato = [['badge-danger', 'Sin Contrato'], ['badge-success', 'En Contrato']];
            const ident = empresa.contrato;
            $('#modal_incidencias [aria-item="contrato"]').html(contrato[empresa.contrato][1]).addClass(contrato[ident][0]).removeClass(contrato[ident ? 0 : 1][0]);
        } else {
            $('#modal_incidencias [aria-item="contrato"]').html('');
        }
        CS_sucursal.selecionar(empresa.ruc);
    });

    $('#tSoporte').on('change', function () {
        CS_problema.selecionar($(this).val());
    });

    $('#tIncidencia').on('change', function () {
        if (!$('#id_inc').val().length) {
            if ($(this).val() == "1") {
                cPersonal.createRow(personal);
            } else {
                cPersonal.deleteRow(personal);
            }
        }
    });

    $('#problema').on('change', function () {
        CS_sproblema.selecionar(() => { return obj_problem[$(this).val()]?.codigo ?? null; });
    });

    // Evento cuando el input de DNI pierde el foco
    $('#nro_doc').blur(async function () {
        const $this = $(this);
        const valorActual = $this.val();

        if (valorActual == valorAnterior) return false;
        cancelarConsultarDoc();
        valorAnterior = valorActual;
        // Si está vacío, reseteamos todo
        if (!valorActual) {
            const nombreContacto = await obj_eContactos.find(c => c.id_contact == $('#cod_contact').val());
            if ($('#cod_contact').val() && nombreContacto.nombres == $('#nom_contac').val()) {
                return true;
            }
            return setContacto();
        }

        const contacto = obj_eContactos.find(c => c.nro_doc == valorActual);
        if (contacto) {
            setContacto({
                id: contacto.id_contact,
                consultado: contacto.consultado,
                telefonos: contacto.telefonos,
                cargo: contacto.cargo,
                correo: contacto.correo,
                nro_doc: contacto.nro_doc,
                nom_contac: contacto.nombres
            });
            valorAnterior = contacto.nro_doc;
            consultarApiSiNecesario(contacto.consultado, $this, () => { ignorarCambio = true; });
            return true;
        }
        consultarApiSiNecesario("0", $this);
    });

    // Evento cuando cambia el valor del select nom_contac
    $('#nom_contac').on('change', async function () {
        cancelarConsultarDoc();
        if (ignorarCambio) return setTimeout(() => { ignorarCambio = false }, 100);
        const nombreSeleccionado = $(this).val();

        if (!nombreSeleccionado) {
            setContacto({
                functionContacto: () => {
                    recargarSelectize('#nom_contac', obj_eContactos.map(c => ({
                        value: c.nombres,
                        text: c.nombres
                    })));
                    valorAnterior = '';
                }
            });
            validContac(this);
            return false;
        }
        validContac(this);
        const contacto = obj_eContactos.find(c => c.nombres == nombreSeleccionado);
        if (contacto) {
            setContacto({
                id: contacto.id_contact,
                consultado: contacto.consultado,
                telefonos: contacto.telefonos,
                cargo: contacto.cargo,
                correo: contacto.correo,
                nro_doc: contacto.nro_doc,
                nom_contac: contacto.nombres
            });
            valorAnterior = contacto.nro_doc;
            consultarApiSiNecesario(contacto.consultado, $('#nro_doc'));
        }
    });

    function setContacto(option = {}) {
        $('#cod_contact').val(option.id || '');
        $('#consultado_api').val(option.consultado || '');
        $('#car_contac').val(option.cargo || '').trigger('change');
        $('#cor_contac').val(option.correo || '');
        $('#nro_doc').val(option.nro_doc || '').attr('disabled', eval(option.consultado) ? true : false);
        CS_tel_contac.llenar(option.telefonos || []);
        CS_nom_contac.setValue(option.nom_contac || '');
        // Callback opcional
        option.functionContacto?.();
    }

    async function consultarApiSiNecesario(consultado, $dni, funcionCambio = null) {
        if ($dni.val().length != 8) return false;
        if (!eval(consultado)) {
            const datos = await consultarDniInput($dni);
            if (datos?.success) {
                const nuevoTexto = datos.data.RazonSocialCliente;
                funcionCambio?.();
                CS_nom_contac.addOption({ value: nuevoTexto, text: nuevoTexto });
                CS_nom_contac.setValue(nuevoTexto);
                $('#consultado_api').val(1);
                $('#nro_doc').attr('disabled', true);
            }
        } else {
            $('[for="nro_doc"]').removeClass('d-flex justify-content-between mt-1')
                .find('span[data-con="consulta"]').remove();
        }
    }

    $('.modal').on('shown.bs.modal', function () {
        switch ($(this).attr('id')) {
            case 'modal_incidencias':
                $('#fecha_imforme').val(date('Y-m-d'));
                $('#hora_informe').val(date('H:i:s'));
                if ($('#tIncidencia').val() == "1" && $('#id_inc').val() == "") {
                    cPersonal.createRow(personal);
                }
                break;

            default:
                break;
        }
    });

    $('.modal').on('hidden.bs.modal', function () {
        $('#nom_contac').val('');
        $('#modal_incidencias').find('[aria-item="codigo"], [aria-item="contrato"]').html('');
        changeCodInc(cod_incidencia);
        CS_sucursal.selecionar();
        CS_tel_contac.llenar();
        CS_tIncidencia.llenar();
        cancelarConsultarDoc();

        setTimeout(() => {
            CS_nom_contac.setValue('');
            CS_nom_contac.enable();
            recargarSelectize('#nom_contac', obj_eContactos.map(c => ({
                value: c.nombres,
                text: c.nombres
            })));
        }, 100);
        // $('#contenedor-personal').removeClass('d-none');
        cPersonal.deleteTable();
        cPersonal1.deleteTable();
        cMaterial.deleteTable();
        removeClienteDataFirm();
        CheckCodOrden();
    });
    CheckCodOrden();

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

    $('[ctable-create="createPersonal"]').on('click', function () {
        // 1. Buscamos el ancestro más cercano con la clase .modal-body
        var $modalBody = $(this).closest('.modal-body');

        // 2. Animamos el scroll hasta el final del contenedor
        $modalBody.animate({
            scrollTop: $modalBody.prop("scrollHeight")
        }, 500); // 500ms es la velocidad de la animación
    });

    $('#button-cod-orden').on('click', function () {
        const check = eval($(this).attr('check-cod')) ? false : true;
        CheckCodOrden(check);
    })

    $('#tSoporte').val(1).trigger('change');
    fObservador('.content-wrapper', () => {
        tb_incidencia.columns.adjust().draw();
    });
});

let valorAnterior;
let ignorarCambio = false;
let sucursal = null;
let empresa = null;
let incidencia_temp = null;

function CheckCodOrden(check = true) {
    $('#button-cod-orden').attr('check-cod', check).html(check ? 'Cod. Sistema' : 'Cod. Tecnico');
    $('#n_orden').val(check ? cod_orden : "").attr('disabled', check);
}

function recargarSelectize(selector, opciones = []) {
    const selectize = $(selector)[0].selectize;
    if (!selectize) {
        console.warn(`No se encontró Selectize en "${selector}"`);
        return;
    }
    // Limpiar todo
    selectize.clear();
    selectize.clearOptions();

    // Agregar nuevas opciones
    selectize.addOption(opciones);
    selectize.refreshOptions(false);
}


const CS_nom_contac = $('#nom_contac').selectize({
    create: true,
    persist: false,
    createOnBlur: true,
    openOnFocus: false,
    plugins: ["clear_button"],
})[0].selectize;

const CS_sucursal = new CSelect(['#sucursal'], {
    dataSet: sucursales,
    filterField: 'ruc',
    optionText: 'nombre',
    optionValidation: [
        { clave: 'status', operation: '===', value: 0, badge: 'Inac.' },
    ]
});

const CS_tel_contac = new CSelect(['#tel_contac'], {
    dataSet: [],
    filterField: 'id',
    optionText: 'telefono'
});

const CS_tIncidencia = new CSelect(['#tIncidencia'], {
    dataSet: tipo_incidencia,
    filterField: 'id',
    optionText: function (data) {
        return `<label class="badge badge-${data.color} me-2">${data.tipo}</label>${data.descripcion}`;
    },
    optionValidation: [
        { clave: 'estatus', operation: '===', value: 0, badge: 'Inac.' },
        { clave: 'eliminado', operation: '===', value: 1, badge: 'Elim.' },
    ],
    optionSelected: 'selected'
});

const CS_problema = new CSelect(['#problema', '#sproblema'], {
    dataSet: obj_problem,
    filterField: 'tipo_soporte',
    optionText: function (data) {
        return `${data.codigo} - ${data.descripcion}`;
    },
    optionValidation: [
        { clave: 'estatus', operation: '===', value: 0, badge: 'Inac.' },
        { clave: 'eliminado', operation: '===', value: 1, badge: 'Elim.' },
    ]
});

const CS_sproblema = new CSelect(['#sproblema'], {
    dataSet: obj_subproblem,
    filterField: 'codigo_problema',
    optionText: function (data) {
        return `${getBadgePrioridad(data.prioridad)} ${data.descripcion}`;
    },
    optionValidation: [
        { clave: 'estatus', operation: '===', value: 0, badge: 'Inac.' },
        { clave: 'eliminado', operation: '===', value: 1, badge: 'Elim.' },
    ]
});

const cMaterial = new CTable('createMaterial', {
    dataSet: materiales,
    dom: '<"row"<"col-lg-8"s><"col-lg-3 col-6"C><"col-1"B>>',
    table: {
        thead: ['PRODUCTO / MATERIAL', 'CANTIDAD'],
        tbody: [
            { data: 'producto' },
            { data: 'cantidad' }
        ]
    },
    count: 'cantidad',
    extract: ['id_material', 'id_usuario', 'cantidad'],
    select: {
        value: "id_material",
        text: function (data) {
            return `${data.producto}`; // <span class="badge badge-success ms-2">Cant. ${data.cantidad}</span>
        },
        validation: [
            { clave: 'estatus', operation: '===', value: 0, badge: 'Inac.' },
            { clave: 'eliminado', operation: '===', value: 1, badge: 'Elim.' },
        ]
    }
});

const config_personal = {
    dataSet: usuarios,
    table: {
        thead: ['Tecnicos'],
        tbody: [
            {
                data: 'doc', render: function (data, type, row) {
                    return `${data} - ${row.nombre}`;
                }
            }
        ]
    },
    extract: ['id'],
    select: {
        value: 'id',
        text: function (data) {
            return `${data.doc} - ${data.nombre}`;
        },
        validation: [
            { clave: 'estatus', operation: '===', value: 0, badge: 'Inac.' },
            { clave: 'eliminado', operation: '===', value: 1, badge: 'Elim.' },
        ]
    }
}

const cPersonal = new CTable('createPersonal', config_personal);
const cPersonal1 = new CTable('createPersonal1', config_personal);

function updateTable() {
    tb_incidencia.ajax.reload();
}
mostrar_acciones(tb_incidencia);


function searchTable(search) {
    const biblio = ['', 'asignada', 'sin asignar', 'en proceso'];
    tb_incidencia.column([1]).search(biblio[search]).draw();
}

document.getElementById('form-incidencias').addEventListener('submit', async function (event) {
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

    const hayRegistro = Object.values(personal).some(item => item.registro === 1);
    if (hayRegistro && $('#estado_info').val() == '2' && !await boxAlert.confirm({
        t: '¿Estas de suguro de guardar los cambios?',
        h: 'La incidencia está en <b>proceso</b>, si añade el personal no se podrá revertir los cambios.'
    })) return fMananger.formModalLoding('modal_incidencias', 'hide');

    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/incidencias/registradas/${url}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            if (!data.success)
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            $('#modal_incidencias').modal('hide');
            if (data.data.hasOwnProperty('cod_inc'))
                cod_incidencia = data.data.cod_inc;
            boxAlert.box({ i: data.icon, t: data.title, h: data.message })
            updateTable();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let message = datae.message;
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    message = formatRequired(datae.required);
                }
                if (datae.hasOwnProperty('unique')) {
                    message = formatUnique(datae.unique);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_incidencias', 'hide');
        }
    });
});

function ShowDetail(e, cod) {
    $('#modal_detalle').modal('show');
    fMananger.formModalLoding('modal_detalle', 'show', true);
    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias/registradas/detail/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            var seguimiento = data.data.seguimiento;
            var inc = data.data.incidencia;

            sucursal = sucursales[inc.id_sucursal];
            empresa = empresas[inc.ruc_empresa];

            llenarInfoModal('modal_detalle', {
                codigo: inc.cod_incidencia,
                estado: getBadgeIncidencia(inc.estado_informe, '.75', true, true),
                razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                direccion: '<i class="fas fa-location-dot me-2"></i>' + empresa.direccion,
                sucursal: sucursal.nombre,
                dir_sucursal: sucursal.direccion,
                soporte: tipo_soporte[inc.id_tipo_soporte].descripcion,
                problema: `${obj_problem[inc.id_problema].codigo} - ${obj_problem[inc.id_problema].descripcion}`,
                subproblema: getBadgePrioridad(obj_subproblem[inc.id_subproblema].prioridad, .75) + obj_subproblem[inc.id_subproblema].descripcion,
                observacion: inc.observacion || '<span class="fst-italic">No hay observaciones adicionales registradas para este incidente.</span>',
            });

            fMananger.formModalLoding('modal_detalle', 'hide');
            llenarInfoTipoInc('modal_detalle', data.data);
            llenarInfoSeguimientoInc('modal_detalle', seguimiento);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_detalle', 'hide');
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

function ShowEdit(cod) {
    $('#modal_incidencias').modal('show');
    // $('#contenedor-personal').addClass('d-none');
    fMananger.formModalLoding('modal_incidencias', 'show', true);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias/registradas/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            const actualizarEstatus = (tipoInc, data) => {
                let tipoi = [], estado = true;
                const limitante = data[data.length - 1].id_tipo_inc;

                Object.entries(tipoInc).forEach(([key, e]) => {
                    let new_e = { ...e };
                    new_e.estatus = estado ? 0 : 1;
                    if (new_e.id == limitante) estado = false;
                    tipoi.push(new_e);
                });
                return tipoi;
            }
            const dt = data.data;
            if (eval(dt.estado_informe) == 2) {
                let new_tipo_incidencia = actualizarEstatus(tipo_incidencia, dt.tipo_incidencia);
                CS_tIncidencia.llenar(new_tipo_incidencia);
            }
            fMananger.formModalLoding('modal_incidencias', 'hide');
            $('#id_inc').val(dt.id_incidencia);
            $('#estado_info').val(dt.estado_informe);
            changeCodInc(dt.cod_incidencia);
            $('#empresa').val(dt.ruc_empresa).trigger('change');
            CS_sucursal.selecionar(dt.ruc_empresa);
            $('#sucursal').val(dt.id_sucursal).trigger('change');
            if (dt.contacto) {
                $('#cod_contact').val(dt.contacto.id_contact);
                $('#consultado_api').val(dt.contacto.consultado);
                $('#nro_doc').val(dt.contacto.nro_doc).attr('disabled', eval(dt.contacto.consultado) ? true : false);
                setTimeout(() => {
                    ignorarCambio = true;
                    CS_nom_contac.setValue(dt.contacto.nombres);
                    const contacto = obj_eContactos.find(c => c.id_contact === dt.contacto.id_contact);
                    if (contacto) {
                        CS_tel_contac.llenar(contacto.telefonos);
                        $('#tel_contac').val(dt.id_telefono).trigger('change');
                    }
                }, 100);
                $('#car_contac').val(dt.contacto.cargo).trigger('change');
                $('#cor_contac').val(dt.contacto.correo);
            }
            $('#tEstacion').val(dt.id_tipo_estacion).trigger('change');
            $('#tIncidencia').val(dt.tipo_incidencia[dt.tipo_incidencia.length - 1].id_tipo_inc).trigger('change');
            $('#tSoporte').val(dt.id_tipo_soporte).trigger('change');
            CS_problema.selecionar(dt.id_tipo_soporte);
            $('#problema').val(dt.id_problema).trigger('change');
            CS_sproblema.selecionar(() => { return obj_problem[dt.id_problema]?.codigo ?? null; });
            $('#sproblema').val(dt.id_subproblema).trigger('change');
            $('#fecha_imforme').val(dt.fecha_informe);
            $('#hora_informe').val(dt.hora_informe);
            $('#observacion').val(dt.observacion);

            const accion = dt.estado_informe == 2 ? false : true;
            cPersonal.fillTable((dt.personal_asig).map(obj => obj.id), { del: accion });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

function habilitarCodAviso(accion) {
    // var selector_material = $('#createMaterial').parent().parent();
    var content_cantidad = $('#content-codAviso');
    var codAviso = $('#codAviso');
    if (accion) {
        // selector_material.addClass('col-lg-6').removeClass('col-lg-9');
        content_cantidad.removeClass('d-none');
        return codAviso.attr('name', 'codAviso');
    }
    // selector_material.addClass('col-lg-9').removeClass('col-lg-6');
    content_cantidad.addClass('d-none');
    codAviso.removeAttr('name');
}


function ShowAssign(e, cod) {
    $('#modal_assign').modal('show');
    fMananger.formModalLoding('modal_assign', 'show', true);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias/registradas/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            console.log(data);

            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }
            var inc = data.data;
            incidencia_temp = inc;

            sucursal = sucursales[inc.id_sucursal];
            empresa = empresas[inc.ruc_empresa];

            llenarInfoModal('modal_assign', {
                codigo: inc.cod_incidencia,
                estado: getBadgeIncidencia(inc.estado_informe, '.75', true, true),
                razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                direccion: '<i class="fas fa-location-dot me-2"></i>' + empresa.direccion,
                sucursal: sucursal.nombre,
                dir_sucursal: sucursal.direccion,
                soporte: tipo_soporte[inc.id_tipo_soporte].descripcion,
                problema: `${obj_problem[inc.id_problema].codigo} - ${obj_problem[inc.id_problema].descripcion}`,
                subproblema: getBadgePrioridad(obj_subproblem[inc.id_subproblema].prioridad, .75) + obj_subproblem[inc.id_subproblema].descripcion,
                observacion: inc.observacion || '<span class="fst-italic">No hay observaciones adicionales registradas para este incidente.</span>',
            });

            fMananger.formModalLoding('modal_assign', 'hide');
            const accion = inc.estado_informe == 2 ? false : true;
            cPersonal1.fillTable((inc.personal_asig).map(obj => obj.id), { del: accion });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

async function AssignPer() {
    if (!await boxAlert.confirm({ h: incidencia_temp.estado_informe == 2 ? 'Incidencia en Proceso, sí añade el personal no podrá revertir los cambios.' : `Está apunto de asignar personal a la incidencia.` })) return true;
    fMananger.formModalLoding('modal_assign', 'show');

    const personal = cPersonal1.extract();
    if (!personal) return fMananger.formModalLoding('modal_assign', 'hide');

    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/incidencias/registradas/assignPer`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify({
            cod_inc: incidencia_temp.cod_incidencia,
            estado: incidencia_temp.estado_informe == 2 ? false : true,
            personal_asig: personal
        }),
        success: function (data) {
            fMananger.formModalLoding('modal_assign', 'hide');
            if (data.success) {
                $(`#modal_assign [aria-item="estado"]`).html(getBadgeIncidencia(data.data.estado));
                cPersonal1.data = data.data.personal;
                cPersonal1.updateTable({ del: (data.data.estado == 2 ? false : true) });
                updateTable();
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let message = datae.message;
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    message = formatRequired(datae.required);
                }
                if (datae.hasOwnProperty('unique')) {
                    message = formatUnique(datae.unique);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: message });
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
        url: `${__url}/soporte/incidencias/registradas/destroy/${id}`,
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
    if (!await boxAlert.confirm({ h: `Esta apunto de <b class="text-warning"><i class="fas fa-${estado == 2 ? 'clock-rotate-left"></i> re' : 'stopwatch"></i> '}iniciar</b> la incidencia` })) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/incidencias/registradas/startInc`,
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
            if (data.success || data.status == 202) {
                updateTable()
            }
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let message = datae.message;
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    message = formatRequired(datae.required);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: message });
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

async function OrdenDetail(e, cod) {
    $('#modal_orden [aria-item="tecnicos"]').html('');
    $('#modal_orden').modal('show');
    fMananger.formModalLoding('modal_orden', 'show', true);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias/registradas/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                let inc = data.data;
                if (inc.cod_orden) {
                    $('#modal_orden').modal('hide');
                    cod_orden = inc.new_cod_orden;
                    if (inc.id_tipo_incidencia == 2) {
                        const url = `${__url}/soporte/orden/exportar-documento?documento=pdf&codigo=${inc.cod_orden}`;
                        if (esCelular()) {
                            cargarIframeDocumento(url + '&tipo=movil');
                        } else {
                            window.open(url, `Visualizar PDF ${inc.cod_orden}`, "width=900, height=800");
                        }
                    }
                    updateTable();
                    boxAlert.box({ i: 'info', t: 'Atencion', h: `Ya se emitió un orden de servicio con el siguiente codigo <b>${inc.cod_orden}</b>.` });
                    return true;
                }

                let personal = inc.personal_asig;
                sucursal = sucursales[inc.id_sucursal];
                empresa = empresas[inc.ruc_empresa];
                incidencia_temp = inc;

                $('#codInc').val(inc.cod_incidencia);
                var tecnicos = personal.map(persona => persona.tecnicos);
                habilitarCodAviso(empresa.codigo_aviso);

                llenarInfoModal('modal_orden', {
                    codigo: inc.cod_incidencia,
                    registrado: inc.created_at,
                    tecnicos: personal.map(persona => `<span class="badge bg-light px-3 ms-2 rounded-pill" style="border: 1px solid rgb(50 68 93 / 70%);color: rgb(50 68 93);">${persona.tecnicos}</span>`).join(''),
                    // tecnicos: '<i class="fas fa-user-gear"></i>' + tecnicos.join(', <i class="fas fa-user-gear ms-1"></i>'),
                    razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                    direccion: '<i class="fas fa-location-dot me-2"></i>' + empresa.direccion,
                    sucursal: sucursal.nombre,
                    dir_sucursal: sucursal.direccion,
                    soporte: tipo_soporte[inc.id_tipo_soporte].descripcion,
                    problema: `${obj_problem[inc.id_problema].codigo} - ${obj_problem[inc.id_problema].descripcion}`,
                    subproblema: getBadgePrioridad(obj_subproblem[inc.id_subproblema].prioridad, .75) + obj_subproblem[inc.id_subproblema].descripcion,
                    observacion: inc.observacion || '<span class="fst-italic">No hay observaciones adicionales registradas para este incidente.</span>',
                    empresaFooter: `${empresa.ruc} - ${empresa.razon_social}`
                });
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

    var cod_aviso = empresa.codigo_aviso;
    if ($('[ctable-contable="#createMaterial"]').children().length && !$('#codAviso').val() && cod_aviso) {
        if (!await boxAlert.confirm({ h: `El campo <b>Código Aviso</b> está vacío.` })) return $('#codAviso').focus();
    }

    fMananger.formModalLoding('modal_orden', 'show');
    var valid = validFrom(this);
    if (!valid.success)
        return fMananger.formModalLoding('modal_orden', 'hide');
    valid.data.data.materiales = cMaterial.extract();
    valid.data.data.cod_sistema = eval($('#button-cod-orden').attr('check-cod'));
    const data = valid.data.data;

    if (data.firma_digital && !data.n_doc && !data.nom_cliente) {
        fMananger.formModalLoding('modal_orden', 'hide');
        return boxAlert.box({ i: 'warning', t: 'Atencion', h: 'Agregaste una firma del cliente, se necesita agregar los datos del cliente.' });
    }



    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/orden/create`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(data),
        success: function (data) {
            let dt = data.data;
            console.log(data);
            if (data.success || data.status == 202) {
                $('#modal_orden').modal('hide');
                cod_orden = dt.new_cod_orden;
                if (incidencia_temp.id_tipo_incidencia == 2) {
                    const url = `${__url}/soporte/orden/exportar-documento?documento=pdf&codigo=${dt.old_cod_orden}`;
                    if (esCelular()) {
                        cargarIframeDocumento(url + '&tipo=movil');
                    } else {
                        window.open(url, `Visualizar PDF ${dt.old_cod_orden}`, "width=900, height=800");
                    }
                }
            }
            updateTable();
            boxAlert.box({ i: data.icon, t: data.title, h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            let message = datae.message;
            if (jqXHR.status == 422) {
                if (datae.hasOwnProperty('required')) {
                    message = formatRequired(datae.required);
                }
            }
            boxAlert.box({ i: datae.icon, t: datae.title, h: message });
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_orden', 'hide');
        }
    });
});

function AddCodAviso(e, cod) {
    $('#modal_addcod').modal('show');
    fMananger.formModalLoding('modal_addcod', 'show', true);

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias/registradas/detail/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success) {
                return boxAlert.box({ i: data.icon, t: data.title, h: data.message });
            }

            const inc = data.data.incidencia;
            sucursal = sucursales[inc.id_sucursal];
            empresa = empresas[inc.ruc_empresa];
            incidencia_temp = inc;

            $('#cod_incidencia').val(cod);
            $('#cod_orden_ser').val(inc.cod_orden);
            fMananger.formModalLoding('modal_addcod', 'hide');

            llenarInfoModal('modal_addcod', {
                codigo: inc.cod_incidencia,
                estado: getBadgeIncidencia(inc.estado_informe),
                razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                direccion: '<i class="fas fa-location-dot me-2"></i>' + empresa.direccion,
                sucursal: sucursal.nombre,
                dir_sucursal: sucursal.direccion,
            });
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
    var valid = validFrom(this);

    if (!valid.success)
        return fMananger.formModalLoding('modal_addcod', 'hide');

    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/orden/editCodAviso`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_addcod', 'hide');
            if (data.success) {
                $('#modal_addcod').modal('hide');
                if (incidencia_temp.id_tipo_incidencia == 2) {
                    const url = `${__url}/soporte/orden/exportar-documento?documento=pdf&codigo=${data.data.cod_orden}`;
                    if (esCelular()) {
                        cargarIframeDocumento(url + '&tipo=movil');
                    } else {
                        window.open(url, `Visualizar PDF ${data.data.cod_orden}`, "width=900, height=800");
                    }
                }
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
        url: `${__url}/soporte/incidencias/registradas/searchCliente/${dni.val()}`,
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