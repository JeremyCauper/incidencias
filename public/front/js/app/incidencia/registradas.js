$(document).ready(function () {
    formatSelect('modal_incidencias');
    formatSelect('modal_assign');
    formatSelect('modal_orden');

    $('#id_empresa').on('change', function () {
        var option = $(`#id_empresa option[value="${$(this).val()}"]`).attr('select-ruc');
        fillSelect(['#sucursal'], sucursales, 'ruc', option, 'id', 'nombre');
    });

    $('#tIncidencia').on('change', function () {
        fillSelect(['#problema', '#sproblema'], obj_problem, 'tipo_incidencia', $(this).val(), 'id', 'text');
    });

    $('#problema').on('change', function () {
        fillSelect(['#sproblema'], obj_subproblem, 'id_problema', $(this).val(), 'id', 'text');
    });

    $('.modal').on('shown.bs.modal', function () {
        $('#fecha_imforme').val(date('Y-m-d'));
        $('#hora_informe').val(date('H:i:s'));
    });

    $('.modal').on('hidden.bs.modal', function () {
        changeCodInc(cod_incidencia);
        fillSelect(['#sucursal', '#problema', '#sproblema']);
        $('#contenedor-personal').addClass('d-none');

        cTable.deleteTable('#createPersonal');
        cTable.deleteTable('#createPersonal1');
    });

    setInterval(() => {
        $('#fecha_f').val(date('Y-m-d')).attr('disabled', true);
        $('#hora_f').val(date('H:i:s')).attr('disabled', true);
    }, 1000);

    $('#selector-material').on('change', function () {
        manCantidad($(this).val(), true);
    })

    $('#createMaterial').on('click', function () {
        manCantidad($(this).val(), true);
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
    autoWidth: true,
    scrollX: true,
    scrollY: 400,
    fixedHeader: true, // Para fijar el encabezado al hacer scroll vertical
    ajax: {
        url: `${__url}/incidencias/registradas/index`,
        dataSrc: function (json) {
            $.each(json.count, function (panel, count) {
                $(`b[data-panel="${panel}"]`).html(count);
            });
            return json.data;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_incidencia' },
        { data: 'empresa' },
        { data: 'sucursal' },
        { data: 'created_at' },
        { data: 'tipo_estacion' },
        { data: 'tipo_incidencia' },
        {
            data: 'problema', render: function (data, type, row) {
                return `${data} / ${row.subproblema}`;
            }
        },
        { data: 'estado_informe' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(7)').addClass('text-center');
        $(row).find('td:eq(8)').addClass('td-acciones');
    },
    processing: true
});

function updateTable() {
    tb_incidencia.ajax.reload();
}

document.getElementById('form-incidencias').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_incidencias', 'show');
    const accion = $('#id_inc').val();
    const url = accion ? `edit/${accion}` : `create`;

    var elementos = this.querySelectorAll('[name]');
    var valid = fMananger.validFrom(elementos);
    if (!valid.success)
        return fMananger.formModalLoding('modal_incidencias', 'hide');
    valid.data.data['personal'] = cPersonal.extract();

    $.ajax({
        type: 'POST',
        url: __url + `/incidencias/registradas/${url}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_incidencias', 'hide');
            if (data.success) {
                cod_incidencia = data.data.cod_inc;
                $('#modal_incidencias').modal('hide');
                boxAlert.minbox({
                    h: data.message
                });
                return updateTable();
            }
            boxAlert.box({
                i: 'error',
                t: 'Ocurrio un error en el processo',
                h: data.message
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({
                i: 'error',
                t: 'Ocurrio un error en el processo',
                h: obj_error.message
            });
            console.log(obj_error);
            fMananger.formModalLoding('modal_incidencias', 'hide');
        }
    });
});

function ShowDetail(e, id) {
    let obj = fMananger.extractDataRow(e);
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
            if (data.success) {
                var seguimiento = data.data.seguimiento;
                var incidencia = data.data.incidencia;

                $(`#modal_detalle [aria-item="observasion"]`).html(incidencia.observasion);

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
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_detalle', 'hide');
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'No se pudo extraer los datos con exito.' });
            console.log(jqXHR);
        }
    });
}

function ShowEdit(id) {
    $('#modal_incidencias').modal('show');
    $('#contenedor-personal').addClass('d-none');
    fMananger.formModalLoding('modal_incidencias', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success)
                boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: data.message });

            const dt = data.data;
            fMananger.formModalLoding('modal_incidencias', 'hide');
            $('#id_inc').val(id);
            changeCodInc(dt.cod_incidencia);
            $('#id_empresa').val(dt.id_empresa).trigger('change.select2');
            var option = $(`#id_empresa option[value="${dt.id_empresa}"]`).attr('select-ruc');
            console.log(option);
            
            fillSelect(['#sucursal'], sucursales, 'ruc', option, 'id', 'nombre');
            $('#sucursal').val(dt.id_sucursal).trigger('change.select2');
            $('#cod_contact').val(dt.id_contacto);
            $('#tel_contac').val(dt.telefono).trigger('change.select2');
            $('#nro_doc').val(dt.nro_doc);
            $('#nom_contac').val(dt.nombres);
            $('#car_contac').val(dt.cargo).trigger('change.select2');
            $('#cor_contac').val(dt.correo);
            $('#tEstacion').val(dt.id_tipo_estacion).trigger('change.select2');
            $('#prioridad').val(dt.prioridad).trigger('change.select2');
            $('#tSoporte').val(dt.id_tipo_soporte).trigger('change.select2');
            $('#tIncidencia').val(dt.id_tipo_incidencia).trigger('change.select2');
            fillSelect(['#problema', '#sproblema'], obj_problem, 'tipo_incidencia', dt.id_tipo_incidencia, 'id', 'text');
            $('#problema').val(dt.id_problema).trigger('change.select2');
            fillSelect(['#sproblema'], obj_subproblem, 'id_problema', dt.id_problema, 'id', 'text');
            $('#sproblema').val(dt.id_subproblema).trigger('change.select2');
            $('#fecha_imforme').val(dt.fecha_informe);
            $('#hora_informe').val(dt.hora_informe);
            $('#observasion').val(dt.observasion);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'Error al intentar extraer los datos de la incidencia' });
            console.log(jqXHR.responseJSON);
        }
    });
}

function ShowAssign(e, id) {
    const obj = fMananger.extractDataRow(e);
    obj.estado = (obj.estado).replaceAll('.7rem;', '.8rem;');

    $.each(obj, function (panel, count) {
        $(`#modal_assign [aria-item="${panel}"]`).html(count);
    });
    $('#modal_assign').modal('show');
    fMananger.formModalLoding('modal_assign', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            if (!data.success)
                boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: data.message });

            const dt = data.data;

            fMananger.formModalLoding('modal_assign', 'hide');
            (dt.personal_asig).forEach(element => {
                const accion = dt.estado_informe == 2 ? false : true;
                cTable.fillTable('#createPersonal1', element.id, accion);
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Error al extraer datos de la incidencia', h: obj_error.message });
            console.log(jqXHR.responseJSON);
        }
    });
}

async function AssignPer() {
    if (!await boxAlert.confirm('¿Esta seguro de realizar esta accion?')) return true;
    fMananger.formModalLoding('modal_assign', 'show');

    const cod = $('#modal_assign [aria-item="codigo"]').html();
    const estado = $(`#modal_assign [aria-item="estado"]`).text().replaceAll(' ', '').toLowerCase();

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
            personal_asig: cPersonal1.extract()
        }),
        success: function (data) {
            console.log(data);

            fMananger.formModalLoding('modal_assign', 'hide');
            if (data.success) {
                cod_incidencia = data.data.cod_inc;
                boxAlert.minbox({ h: data.message });
                updateTable();
                return true;
            }
            boxAlert.box({ i: 'error', t: '¡Ocurrio un error!', h: data.message });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            fMananger.formModalLoding('modal_assign', 'hide');
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: obj_error.message });
            console.log(obj_error);
        }
    });
}

async function DeleteInc(id) {
    if (!await boxAlert.confirm('¿Esta seguro de elimniar?, no se podrá revertir los cambios')) return true;
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
                boxAlert.minbox({ h: data.message });
                updateTable();
                return true;
            }
            boxAlert.box('error', '¡Ocurrio un error!', data.message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: obj_error.message });
            console.log(obj_error);
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

async function StartInc(cod, estado) {
    if (!await boxAlert.confirm(`¿Esta seguro de <b>${estado == 2 ? 're' : ''}iniciar</b> la incidencia?`)) return true;
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
                boxAlert.minbox({ h: data.message });
                updateTable();
                return true;
            }
            boxAlert.box('error', '¡Ocurrio un error!', data.message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: obj_error.message });
            console.log(obj_error);
            $('#modal_incidencias .modal-dialog .modal-content .loader-of-modal').remove();
        }
    });
}

async function OrdenDetail(e, cod) {
    const obj = fMananger.extractDataRow(e);
    obj.estado = (obj.estado).replaceAll('.7rem;', '1rem;');
    console.log(obj);

    $.each(obj, function (panel, count) {
        $(`#modal_orden [aria-item="${panel}"]`).html(count);
    });
    $(`#modal_orden [aria-item="empresaFooter"]`).html(obj.empresa);
    $('#modal_orden [aria-item="tecnicos"]').html('');
    $('#modal_orden').modal('show');
    fMananger.formModalLoding('modal_orden', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/show/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            console.log(data);            
            if (data.success) {
                let personal = data.data.personal_asig;
                $('[aria-item="observacion"]').html(data.data.observasion);
                $('#codInc').val(data.data.cod_incidencia);
                var tecnicos = personal.map(persona => persona.tecnicos);
                
                fMananger.formModalLoding('modal_orden', 'hide');
                $('#modal_orden [aria-item="tecnicos"]').html('<i class="fas fa-user-gear"></i>' + tecnicos.join(', <i class="fas fa-user-gear ms-1"></i>'));
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error al registrar el usuario');
            console.log(jqXHR.responseJSON);
        }
    });
}

document.getElementById('form-orden').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_orden', 'show');
    const atencion = $('#modal_orden [aria-item="atencion"]').html();

    var elementos = this.querySelectorAll('[name]');
    var valid = fMananger.validFrom(elementos);
    valid.data.data.materiales = cMaterial.extract();

    if (!valid.success)
        return fMananger.formModalLoding('modal_orden', 'hide');
    var n_orden = valid.data.data.n_orden;
    valid.data.data.check_cod = $('#check_cod').prop('checked');

    $.ajax({
        type: 'POST',
        url: __url + '/orden/create',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_orden', 'hide');
            console.log(data);
            if (data.success) {
                $('#modal_orden').modal('hide');
                boxAlert.minbox({
                    h: data.message
                });
                cod_ordenSer = data.data.num_orden;
                if (atencion.toUpperCase() == 'PRESENCIAL')
                    window.open(`${__url}/orden/documentopdf/${n_orden}`, `Visualizar PDF ${n_orden}`, "width=900, height=800");
                updateTable();
                return true;
            }
            boxAlert.box({
                i: 'error',
                t: '¡Ocurrio un error!',
                h: data.message
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({
                i: 'error',
                t: 'Ocurrio un error en el processo',
                h: obj_error.message
            });
            console.log(obj_error);
            fMananger.formModalLoding('modal_orden', 'hide');
        }
    });
});

function changeCodInc(val) {
    $('#cod_inc').val(val);
    $('#cod_inc_text').html(val);
}

function setChangeCodOrden($this) {
    const check = $this.checked;
    $('#n_orden').val(check ? cod_orden : "").attr('disabled', check);
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


function manCantidad(params, clear = false) {
    const selector = $('#selector-material');
    const cantidad = $('input[input-cantidad=""]');
    const content_cantidad = $('#content-cantidad');
    if (clear) {
        cantidad.val(0);
        content_cantidad.addClass('disabled');
        if (!params) return false;
        cantidad.val(1);
        content_cantidad.removeClass('disabled');
        manCantidad('');
        return false;
    }
    let number = cantidad.val();
    switch (params) {
        case 'plus':
            number++;
            cantidad.val(number);
            break;

        case 'minus':
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

document.getElementById('doc_clienteFirma').addEventListener('click', async function (event) {
    var rect = this.getBoundingClientRect();
    var beforeWidth = 14;
    var beforeHeight = 14;
    var beforeElementRightOffset = 0.65 * parseFloat(getComputedStyle(document.documentElement).fontSize);
    var beforeElementTopOffset = rect.top + (rect.height / 2) - (beforeHeight / 2);

    if (event.clientX >= rect.right - beforeElementRightOffset - beforeWidth &&
        event.clientX <= rect.right - beforeElementRightOffset &&
        event.clientY >= beforeElementTopOffset &&
        event.clientY <= beforeElementTopOffset + beforeHeight) {
        this.innerHTML = "";
        this.classList.add("doc-fsearch");
        this.classList.remove("doc-fclear");
    } else {
        const bodyChildren = Array.from(document.body.children);
        bodyChildren.forEach(child => {
            if (!child.classList.contains('swal2-container')) {
                child.setAttribute('inert', '');
            }
        });

        Swal.fire({
            title: '<h5 class="text-primary">Buscar cliente</h5>',
            html: `
                <div class="form-group text-start mb-3">
                    <label class="form-label">Nro. de Documento</label>
                    <div class="input-group">
                        <input type="text" id="docNumber" class="form-control" placeholder="Número de Documento">
                        <button type="button" class="btn btn-primary px-2" id="btn-conDoc" data-mdb-ripple-init onclick="search_doc()">
                            <span class="spinner-border spinner-border-sm visually-hidden" role="status" aria-hidden="true"></span>
                            <i class="fas fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group text-start">
                    <label class="form-label">Nom del Cliente</label>
                    <input type="text" id="clientName" class="form-control" placeholder="Nombre del Cliente">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            confirmButtonColor: "#3085d6",
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            didOpen: () => {
                const docNumberInput = Swal.getPopup().querySelector('#docNumber');
                if (docNumberInput) docNumberInput.focus();
            },
            willClose: () => {
                bodyChildren.forEach(child => {
                    if (!child.classList.contains('swal2-container')) child.removeAttribute('inert');
                });
            },
            preConfirm: () => {
                const docNumber = Swal.getPopup().querySelector('#docNumber');
                const clientName = Swal.getPopup().querySelector('#clientName');

                if (!docNumber.value || !clientName.value) {
                    Swal.showValidationMessage(`Por favor ingresa ambos campos`);
                }
                const hideValid = () => { Swal.getPopup().querySelector('.swal2-validation-message').style.display = "none"; }
                docNumber.addEventListener("focus", hideValid);
                clientName.addEventListener("focus", hideValid);

                return { docNumber: docNumber.value, clientName: clientName.value };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const docNumber = result.value.docNumber;
                const clientName = result.value.clientName;
                this.innerHTML = `${docNumber} - ${clientName}`;
                $('#n_doc').val(docNumber);
                $('#nom_cliente').val(clientName);

                this.classList.remove("doc-fsearch");
                this.classList.add("doc-fclear");
            }
            else {
                removeSignature();
            }
        });
    }
});

function search_doc() {
    const docNumberI = Swal.getPopup().querySelector('#docNumber');
    const clientNameI = Swal.getPopup().querySelector('#clientName');
    const conDocB = Swal.getPopup().querySelector('#btn-conDoc');

    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/registradas/searchCliente/${docNumberI.value}`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        beforeSend: function () {
            conDocB.querySelector('span').classList.remove('visually-hidden');
            conDocB.querySelector('i').classList.add('visually-hidden');
        },
        success: function (data) {
            if (data.success) {
                clientNameI.value = data.data.nombre;
                if(data.data.consulta) {
                    $('#id_firmador').val(data.data.id);
                    var firma = data.data.firma_digital;
                    $('#nomFirmaDigital').val(firma);
                    if (firma) {
                        updateSignaturePreview(`${__asset}/images/client/${firma}`);
                    }
                }
            }
            else {
                Swal.showValidationMessage(data.message);
            }
            conDocB.querySelector('span').classList.add('visually-hidden');
            conDocB.querySelector('i').classList.remove('visually-hidden');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            Swal.showValidationMessage(obj_error.message);
            console.log(obj_error);
        }
    });
}