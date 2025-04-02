$(document).ready(function () {
    const controles = [
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

    formatSelect('modal_orden');

    $('.modal').on('shown.bs.modal', function () {
        $('#fecha_imforme').val(date('Y-m-d'));
        $('#hora_informe').val(date('H:i:s'));
        changeCodOrdenV()
    });

    $('.modal').on('hidden.bs.modal', function () {
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

    $('#button-cod-orden').on('click', function () {
        const check = eval($(this).attr('check-cod')) ? false : true;
        CheckCodOrden(check);
    });

    fObservador('.content-wrapper', () => {
        tb_incidencias.columns.adjust().draw();
        tb_visitas.columns.adjust().draw();
    });
});
function CheckCodOrden(check = true) {
    $('#button-cod-orden').attr('check-cod', check).html(check ? 'Cod. Sistema' : 'Cod. Tecnico');
    $('#n_orden').val(check ? cod_orden : "").attr('disabled', check);
}

const cMaterial = new CTable('#createMaterial', {
    thead: ['#', 'PRODUCTO / MATERIAL', 'CANTIDAD'],
    tbody: [
        { data: 'id_material' },
        { data: 'producto' },
        { data: 'cantidad' }
    ],
    extract: ['id_material', 'cantidad']
});

function updateTableInc() {
    tb_incidencias.ajax.reload();
}
mostrar_acciones('tb_incidencias');

function ShowDetailInc(e, id) {
    let obj = extractDataRow(e, 'tb_incidencias');
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
            var sucursal = sucursales[incidencia.id_sucursal];

            $(`#modal_detalle [aria-item="direccion"]`).html(sucursal.direccion);
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

let sucursal = null;
let empresa = null;
let incidencia_temp = null;
async function OrdenDetail(e, cod) {
    const obj = extractDataRow(e, 'tb_incidencias');
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
            console.log(data);
            if (data.success) {
                let dt = data.data;
                if (dt.cod_orden) {
                    $('#modal_orden').modal('hide');
                    cod_orden = dt.new_cod_orden;
                    if (dt.id_tipo_incidencia == 2)
                        window.open(`${__url}/orden/documentopdf/${dt.cod_orden}`, `Visualizar PDF ${dt.cod_orden}`, "width=900, height=800");
                    updateTable();
                    boxAlert.box({ i: 'info', t: 'Atencion', h: `Ya se emitió un orden de servicio con el siguiente codigo <b>${dt.cod_orden}</b>.` });
                    return true;
                }

                let personal = dt.personal_asig;
                sucursal = sucursales[dt.id_sucursal];
                empresa = empresas[dt.ruc_empresa];
                incidencia_temp = dt;

                $(`#modal_orden [aria-item="direccion"]`).html(sucursal.direccion);
                $('#modal_orden [aria-item="observacion"]').html(dt.observacion);
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

document.getElementById('form-orden').addEventListener('submit', async function (event) {
    event.preventDefault();

    var cod_aviso = empresa.codigo_aviso;
    if ($('[ctable-contable="#createMaterial"]').children().length && !$('#codAviso').val() && cod_aviso) {
        if (!await boxAlert.confirm({ h: `El campo <b>Código Aviso</b> está vacío.` })) return $('#codAviso').focus();
    }

    fMananger.formModalLoding('modal_orden', 'show');
    var valid = validFrom(this);
    valid.data.data.materiales = cMaterial.extract();

    if (!valid.success)
        return fMananger.formModalLoding('modal_orden', 'hide');
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
            let dt = data.data;
            console.log(data);
            if (data.success || data.status == 202) {
                $('#modal_orden').modal('hide');
                cod_orden = dt.new_cod_orden;
                if (incidencia_temp.id_tipo_incidencia == 2)
                    window.open(`${__url}/orden/documentopdf/${dt.old_cod_orden}`, `Visualizar PDF ${dt.old_cod_orden}`, "width=900, height=800");
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
    const obj = extractDataRow(e, 'tb_incidencias');
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
                updateTableInc();
                return true;
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
    });
});

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

function updateTableVis() {
    tb_visitas.ajax.reload();
}
mostrar_acciones('tb_visitas');

function ShowDetailVis(e, id) {
    $('#modal_seguimiento_visitasp').find('.modal-body').addClass('d-none');
    $('#modal_seguimiento_visitasp').modal('show');
    fMananger.formModalLoding('modal_seguimiento_visitasp', 'show');
    $('#content-seguimiento-vis').html('');
    $.ajax({
        type: 'GET',
        url: `${__url}/visitas/programadas/detail/${id}`,
        contentType: 'application/json',
        success: function (data) {

            if (data.success) {
                var seguimiento = data.data.seguimiento;
                var visita = data.data.visita;

                $(`#modal_seguimiento_visitasp [aria-item="empresa"]`).html(visita.empresa);
                $(`#modal_seguimiento_visitasp [aria-item="direccion"]`).html(visita.direccion);
                $(`#modal_seguimiento_visitasp [aria-item="sucursal"]`).html(visita.sucursal);

                fMananger.formModalLoding('modal_seguimiento_visitasp', 'hide');
                seguimiento.sort((a, b) => new Date(a.date) - new Date(b.date));
                seguimiento.forEach(function (element) {
                    $('#content-seguimiento-vis').append(`
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
                $('#modal_seguimiento_visitasp').find('.modal-body').removeClass('d-none');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_seguimiento_visitasp', 'hide');
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
        }
    });
}

async function StartVisita(id, estado) {
    if (!await boxAlert.confirm({ h: `Esta apunto de <b class="text-warning"><i class="fas fa-${estado == 2 ? 'clock-rotate-left"></i> re' : 'stopwatch"></i> '}iniciar</b> la visita` })) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/visitas/programadas/startVisita`,
        data: JSON.stringify({
            'id': id,
            // 'estado': estado
        }),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        beforeSend: boxAlert.loading,
        success: function (data) {
            if (data.success || data.status == 202) {
                updateTableVis()
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

function OrdenVisita(e, id) {
    $('#modal_orden_visita').modal('show');
    fMananger.formModalLoding('modal_orden_visita', 'show');
    $.ajax({
        type: 'GET',
        url: `${__url}/visitas/programadas/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (data.success) {
                let dt = data.data;
                if (dt.cod_ordenv) {
                    $('#modal_orden').modal('hide');
                    cod_ordenv = dt.new_cod_ordenv;
                    if (dt.id_tipo_incidencia == 2)
                        window.open(`${__url}/orden/documentopdf/${dt.cod_ordenv}`, `Visualizar PDF ${dt.cod_ordenv}`, "width=900, height=800");
                    updateTableInc();
                    updateTableVis();
                    boxAlert.box({ i: 'info', t: 'Atencion', h: `Ya se emitió un orden de visita con el siguiente codigo <b>${dt.cod_ordenv}</b>.` });
                    return true;
                }
                var visita = data.data;
                sucursal = sucursales[dt.id_sucursal];
                empresa = empresas[sucursal.ruc];

                $('[name="id_visita_orden"]').val(id);
                $(`#modal_orden_visita [aria-item="registrado"]`).html(dt.seguimiento[0].created_at);
                $(`#modal_orden_visita [aria-item="empresa"]`).html(`${empresa.ruc} - ${empresa.razon_social}`);
                $(`#modal_orden_visita [aria-item="direccion"]`).html(sucursal.direccion);
                $(`#modal_orden_visita [aria-item="sucursal"]`).html(sucursal.nombre);
                var tecnicos = visita.personal_asig.map(persona => persona.tecnicos);
                $('#modal_orden_visita [aria-item="tecnicos"]').html('<i class="fas fa-user-gear"></i>' + tecnicos.join(', <i class="fas fa-user-gear ms-1"></i>'));
                fMananger.formModalLoding('modal_orden_visita', 'hide');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            const datae = jqXHR.responseJSON;
            boxAlert.box({ i: datae.icon, t: datae.title, h: datae.message });
            if (jqXHR.status == 404) {
                setTimeout(() => { $('#modal_orden_visita').modal('hide'); }, 500);
                return false;
            }
            fMananger.formModalLoding('modal_orden_visita', 'hide');
        }
    });
}

document.getElementById('form-orden-visita').addEventListener('submit', async function (event) {
    event.preventDefault();

    fMananger.formModalLoding('modal_orden_visita', 'show');

    var valid = validFrom(this);
    valid.data.data.islas = MRevision.extract();
    const old_cod_ordenv = valid.data.data.cod_ordenv;

    if (!valid.success)
        return fMananger.formModalLoding('modal_orden_visita', 'hide');
    $.ajax({
        type: 'POST',
        url: __url + '/orden-visita/create',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            if (data.success) {
                $('#modal_orden_visita').modal('hide');
                changeCodOrdenV(data.data.cod_ordenv);
                window.open(`${__url}/orden-visita/documentopdf/${old_cod_ordenv}`, `Visualizar PDF ${old_cod_ordenv}`, "width=900, height=800");
                updateTableVis()
                return true;
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
        },
        complete: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_orden_visita', 'hide');
        }
    });
});

function changeCheck($this) {
    let contentInput = $($this).parent();
    let icon = contentInput.find('span').find('i');
    if ($($this).val()) {
        icon.addClass('text-success');
    } else {
        icon.removeClass('text-success');
    }
}

function changeCodOrdenV(val = cod_ordenv) {
    $('[name="cod_ordenv"]').val(val);
    $('#modal_orden_visita [aria-item="codigo"]').text(val);
    cod_ordenv = val;
}

function resetTable() {
    tb_incidencias.columns.adjust().draw();
    tb_visitas.columns.adjust().draw();
}