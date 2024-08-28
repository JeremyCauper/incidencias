$(document).ready(function () {
    formatSelect('modal_incidencias');
    formatSelect('modal_viewdetalle');
    formatSelect('modal_assign');
    formatSelect('modal_ordens');

    $('#id_empresa').on('change', function () {
        fillEmpresa($(this).val());
    });

    $('#tip_incidencia').on('change', function () {
        fillProblem($(this).val());
    });

    $('#inc_problem').on('change', function () {
        fillSubProblem($(this).val());
    });

    $('.modal').on('shown.bs.modal', function () {
        $('#fecha_imforme').val(date('Y-m-d'));
        $('#hora_informe').val(date('H:i:s'));
    });

    $('.modal').on('hidden.bs.modal', function () {
        changeCodInc(cod_incidencia);

        fillEmpresa("");
        fillProblem("");
        fillSubProblem("");

        cTable.deleteTable('#createPersonal');
        cTable.deleteTable('#createPersonal1');

        $('#content-seguimiento').html('');
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

const tb_incidencia = new DataTable('#tb_incidencia', {
    scrollX: true,
    scrollY: 300,
    ajax: {
        url: `${__url}/soporte/incidencias-registradas/datatable`,
        dataSrc: function (json) {
            $('b[data-panel="_count"]').html(json.count.count);
            $('b[data-panel="_inc_a"]').html(json.count.inc_a);
            $('b[data-panel="_inc_s"]').html(json.count.inc_s);
            $('b[data-panel="_inc_p"]').html(json.count.inc_p);
            return json.data;
        },
        error: function (xhr, error, thrown) {
            boxAlert.table();
            console.log('Respuesta del servidor:', xhr);
        }
    },
    columns: [
        { data: 'cod_incidencia' },
        { data: 'id_empresa' },
        { data: 'id_sucursal' },
        { data: 'direccion' },
        { data: 'created_at' },
        { data: 'id_tipo_estacion' },
        { data: 'id_tipo_incidencia' },
        {
            data: 'id_problema', render: function (data, type, row) {
                return `${data} / ${row.id_subproblema}`;
            }
        },
        { data: 'estado_informe' },
        { data: 'acciones' }
    ],
    createdRow: function (row, data, dataIndex) {
        $(row).find('td:eq(9)').addClass('td-acciones');
    },
    processing: true
});

function updateTable() {
    tb_incidencia.ajax.reload();
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

document.getElementById('form-incidencias').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_incidencias', 'show');
    const accion = $('#id_inc').val();
    const url = accion ? `edit/${accion}` : `create`;

    var elementos = this.querySelectorAll('[name]');
    var valid = fMananger.validFrom(elementos);
    if (!valid.success)
        return fMananger.formModalLoding('modal_incidencias', 'hide');
    valid.data.data['personal_asig'] = cPersonal.extract();

    $.ajax({
        type: 'POST',
        url: __url + `/soporte/incidencias-registradas/${url}`,
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

document.getElementById('form-ordenes').addEventListener('submit', function (event) {
    event.preventDefault();
    fMananger.formModalLoding('modal_ordens', 'show');
    const atencion = $('#modal_ordens [aria-item="atencion"]').html();

    var elementos = this.querySelectorAll('[name]');
    var valid = fMananger.validFrom(elementos);
    valid.data.data.materiales = cMaterial.extract();

    if (!valid.success)
        return fMananger.formModalLoding('modal_ordens', 'hide');
    var n_orden = valid.data.data.n_orden;
    valid.data.data.check_cod = $('#check_cod').prop('checked');

    $.ajax({
        type: 'POST',
        url: __url + '/ordens/create',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {
            fMananger.formModalLoding('modal_ordens', 'hide');
            console.log(data);
            if (data.success) {
                $('#modal_ordens').modal('hide');
                boxAlert.minbox({
                    h: data.message
                });
                cod_ordenSer = data.data.num_orden;
                if (atencion.toUpperCase() == 'PRESENCIAL')
                    window.open(`${__url}/documentoPdf/${n_orden}`, `Visualizar PDF ${n_orden}`, "width=900, height=800");
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
            fMananger.formModalLoding('modal_ordens', 'hide');
        }
    });
});

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
                        <input type="text" id="docNumber" class="form-control" placeholder="Número de Documento" onchange="search_doc()">
                        <button type="button" class="btn btn-primary px-2" id="btn-conDoc" data-mdb-ripple-init>
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
        });
    }
});


function search_doc() {
    const docNumberI = Swal.getPopup().querySelector('#docNumber');
    const clientNameI = Swal.getPopup().querySelector('#clientName');
    const conDocB = Swal.getPopup().querySelector('#btn-conDoc');

    $.ajax({
        type: 'GET',
        url: `${__url}/ConsultaDni/${docNumberI.value}`,
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
                clientNameI.value = data.data.complet;
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



function showEdit(id) {
    $('#modal_incidencias').modal('show');
    fMananger.formModalLoding('modal_incidencias', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias-registradas/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            $('#contenedor-personal').addClass('d-none');
            fMananger.formModalLoding('modal_incidencias', 'hide');
            $('#id_inc').val(id);
            changeCodInc(data.cod_incidencia);
            $('#id_empresa').val(data.id_empresa).trigger('change.select2');
            fillEmpresa(data.id_empresa);
            $('#id_sucursal').val(data.id_sucursal).trigger('change.select2');
            $('#cod_contact').val(data.id_contacto);
            $('#tel_contac').val(data.telefono).trigger('change.select2');
            $('#nro_doc').val(data.nro_doc);
            $('#nom_contac').val(data.nombres);
            $('#car_contac').val(data.cargo).trigger('change.select2');
            $('#cor_contac').val(data.correo);
            $('#tip_estacion').val(data.id_tipo_estacion).trigger('change.select2');
            $('#priori_inc').val(data.prioridad).trigger('change.select2');
            $('#tip_soport').val(data.id_tipo_soporte).trigger('change.select2');
            $('#tip_incidencia').val(data.id_tipo_incidencia).trigger('change.select2');
            fillProblem(data.id_tipo_incidencia);
            $('#inc_problem').val(data.id_problema).trigger('change.select2');
            fillSubProblem(data.id_problema);
            $('#inc_subproblem').val(data.id_subproblema).trigger('change.select2');
            $('#fecha_imforme').val(data.fecha_informe);
            $('#hora_informe').val(data.hora_informe);
            $('#observasion').val(data.observasion);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'Error al intentar extraer los datos de la incidencia' });
            console.log(jqXHR.responseJSON);
        }
    });
}

async function idelete(id) {
    if (!await boxAlert.confirm('¿Esta seguro de elimniar?, no se podrá revertir los cambios')) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/incidencias-registradas/destroy/${id}`,
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

async function reloadInd(cod, estado) {
    if (!await boxAlert.confirm(`¿Esta seguro de <b>${estado == 2 ? 're' : ''}iniciar</b> la incidencia?`)) return true;
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/incidencias-registradas/initInc/${cod}`,
        data: JSON.stringify({ 'estado': estado }),
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

function showDetail(e, cod) {
    const tr = e.parentNode.parentNode.parentNode.parentNode;
    const tds = tr.querySelectorAll('td');
    $('#modal_viewdetalle [aria-item="cod"]').html(tds[0].innerHTML);
    $('#modal_viewdetalle [aria-item="empresa"]').html(tds[1].innerHTML);
    $('#modal_viewdetalle [aria-item="direccion"]').html(tds[3].innerHTML);
    $('#modal_viewdetalle [aria-item="sucursal"]').html(tds[2].innerHTML);
    $('#modal_viewdetalle').modal('show');
    fMananger.formModalLoding('modal_viewdetalle', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias-registradas/detail/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            fMananger.formModalLoding('modal_viewdetalle', 'hide');
            $('#content-seguimiento').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_viewdetalle', 'hide');
            alert('Error al registrar el usuario');
            console.log(jqXHR.responseJSON);
        }
    });
}

function assign(e, id) {
    const tr = e.parentNode.parentNode.parentNode.parentNode;
    const tds = tr.querySelectorAll('td');
    $('#modal_assign [aria-item="cod"]').html(tds[0].innerHTML);
    $('#modal_assign [aria-item="empresa"]').html(tds[1].innerHTML);
    $('#modal_assign [aria-item="direccion"]').html(tds[3].innerHTML);
    $('#modal_assign [aria-item="sucursal"]').html(tds[2].innerHTML);

    $('#modal_assign').modal('show');
    fMananger.formModalLoding('modal_assign', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias-registradas/show/${id}`,
        contentType: 'application/json',
        success: function (data) {
            fMananger.formModalLoding('modal_assign', 'hide');
            (data.personal_asig).forEach(element => {
                const accion = data.estado_informe == 2 ? false : true;
                cTable.fillTable('#createPersonal1', element, accion);
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            const obj_error = jqXHR.responseJSON;
            boxAlert.box({ i: 'error', t: 'Error al extraer datos de la incidencia', h: obj_error.message });
            console.log(jqXHR.responseJSON);
        }
    });
}

async function createAssign() {
    if (!await boxAlert.confirm('¿Esta seguro de realizar esta accion?')) return true;
    fMananger.formModalLoding('modal_assign', 'show');

    const cod = $('#modal_assign [aria-item="cod"]').html();
    $.ajax({
        type: 'POST',
        url: `${__url}/soporte/incidencias-registradas/editAssign`,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify({
            cod_inc: cod,
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

async function detailOrden(e, cod) {
    const tr = e.parentNode.parentNode.parentNode.parentNode;
    const tds = tr.querySelectorAll('td');
    const atencion = tds[6].innerHTML;
    $('#codInc').val(tds[0].innerHTML);
    $('#modal_ordens [aria-item="empresa"]').html(tds[1].innerHTML);
    $('#modal_ordens [aria-item="direccion"]').html(tds[3].innerHTML);
    $('#modal_ordens [aria-item="sucursal"]').html(tds[2].innerHTML);
    $('#modal_ordens [aria-item="registrado"]').html(tds[4].innerHTML);
    $('#modal_ordens [aria-item="atencion"]').html(atencion);

    $('#modal_ordens').modal('show');
    fMananger.formModalLoding('modal_ordens', 'show');

    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias-registradas/detailOrden/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            fMananger.formModalLoding('modal_ordens', 'hide');
            $('#modal_ordens [aria-item="tecnicos"]').html(data.tecnicos);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error al registrar el usuario');
            console.log(jqXHR.responseJSON);
        }
    });
}


const fileInputFirma = document.getElementById('firma_digital');
const PreviFirma = document.getElementById('PreviFirma');
const removeImgFirma = document.getElementById('removeImgFirma');
const textFirmaDigital = document.getElementById('textFirmaDigital');

document.getElementById('uploadImgFirma').addEventListener('click', () => {
    fileInputFirma.click();
});

fileInputFirma.addEventListener('change', function (event) {
    const file = event.target.files[0];
    const maxFileSize = 10 * 1024 * 1024; // 10MB
    if (file) {
        if (file.size > maxFileSize) {
            alert('El archivo debe ser menor a 10MB');
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            PreviFirma.src = e.target.result;
            PreviFirma.alt = file.name;
            removeImgFirma.style.display = 'block';
            textFirmaDigital.value = btoa(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('createFirma').addEventListener('click', async () => {
    const bodyChildren = Array.from(document.body.children);
    bodyChildren.forEach(child => {
        if (!child.classList.contains('swal2-container')) {
            child.setAttribute('inert', '');
        }
    });

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
        willClose: () => {
            bodyChildren.forEach(child => {
                if (!child.classList.contains('swal2-container')) child.removeAttribute('inert');
            });
        },
        showConfirmButton: false
    });

    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    document.getElementById('clear').addEventListener('click', function () {
        signaturePad.clear();
    });

    document.getElementById('save').addEventListener('click', function () {
        if (signaturePad.isEmpty()) {
            alert("Por favor, dibuja una firma primero.");
        } else {
            var dataURL = signaturePad.toDataURL();
            document.getElementById('textFirmaDigital').value = btoa(dataURL.toString());
            document.getElementById('PreviFirma').src = dataURL.toString();
            document.getElementById('PreviFirma').classList.remove('visually-hidden');
            removeImgFirma.style.display = 'block';
            Swal.close();
        }
    });
});


removeImgFirma.addEventListener('click', () => {
    PreviFirma.src = "";
    PreviFirma.classList.add('visually-hidden');
    textFirmaDigital.value = '';
    removeImgFirma.style.display = 'none';
    fileInputFirma.value = '';
});

function PreviImagenes(data) {
    const bodyChildren = Array.from(document.body.children);
    bodyChildren.forEach(child => {
        if (!child.classList.contains('swal2-container')) {
            child.setAttribute('inert', '');
        }
    });

    Swal.fire({
        title: '<h5 class="card-title text-linkedin">PREVISUALIZACIÓN DE LA IMAGEN CARGADA</h5>',
        html: `<div>
                <img src="${data}" />
            </div>`,
        willClose: () => {
            bodyChildren.forEach(child => {
                if (!child.classList.contains('swal2-container')) child.removeAttribute('inert');
            });
        }
    });
}

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


function changeCodInc(val) {
    $('#cod_inc').val(val);
    $('#cod_inc_text').html(val);
}

function setChangeCodOrden($this) {
    const check = $this.checked;
    $('#n_orden').val(check ? cod_ordenSer : "").attr('disabled', check);
}

function fillEmpresa(val) {
    $('#id_sucursal').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
    if (!val) return false;
    var option = $(`#id_empresa option[value="${val}"]`).attr('select-ruc');
    sucursales[option].forEach(s => {
        $('#id_sucursal').append($('<option>').val(s.id).html(s.sucursal));
    });
    $('#id_sucursal').attr('disabled', false);
}

function fillProblem(val) {
    $('#inc_problem, #inc_subproblem').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
    if (!val) return false;
    obj_problem.forEach(e => {
        if (e.tipo_incidencia == val)
            $('#inc_problem').append($('<option>').val(e.id).text(e.text));
    });
    $('#inc_problem').attr('disabled', false);
}

function fillSubProblem(val) {
    $('#inc_subproblem').html($('<option>').val('').html('-- Seleccione --')).attr('disabled', true);
    if (!val) return false;
    obj_subproblem.forEach(e => {
        if (e.id_problema == val)
            $('#inc_subproblem').append($('<option>').val(e.id).text(e.text));
    });
    $('#inc_subproblem').attr('disabled', false);
}