$(document).ready(function () {
    $('#empresa').on('change', function () {
        fillSelect(['#sucursal'], sucursales, 'ruc', $(this).val(), 'id', 'nombre', 'status');
    });

    $('#dateRango').daterangepicker({
        showDropdowns: true,
        startDate: date('Y-m-01'),
        endDate: date('Y-m-d'),
        maxDate: date('Y-m-d'),
        opens: "center",
        cancelClass: "btn-link",
        locale: {
            format: 'YYYY-MM-DD',
            separator: '  al  ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cerrar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Rango personalizado',
            daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            firstDay: 1 // Comienza la semana en lunes
        }
    });

    fObservador('.content-wrapper', () => {
        tb_orden.columns.adjust().draw();
    });
});

function updateTable() {
    tb_orden.ajax.reload();
}
mostrar_acciones('tb_orden');

function filtroBusqueda() {
    var empresa = $(`#empresa`).val();
    var sucursal = $('#sucursal').val();
    var fechas = $('#dateRango').val().split('  al  ');
    var nuevoUrl = `${__url}/incidencias/resueltas/index?ruc=${empresa}&sucursal=${sucursal}&fechaIni=${fechas[0]}&fechaFin=${fechas[1]}`;
    
    tb_orden.ajax.url(nuevoUrl).load();
}


function ShowDetail(e, id) {
    let obj = extractDataRow(e);
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
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_detalle', 'hide');
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'No se pudo extraer los datos con exito.' });
            console.log(jqXHR);
        }
    });
}

function OrdenPdf(cod) {
    window.open(`${__url}/orden/documentopdf/${cod}`, `Visualizar PDF ${cod}`, "width=900, height=800");
}

function OrdenTicket(cod) {
    window.open(`${__url}/orden/documentoticket/${cod}`, `Visualizar TICKET ${cod}`, "width=650, height=800");
}

function AddSignature(e, cod) {
    $('#modal_firmas').modal('show');
    fMananger.formModalLoding('modal_firmas', 'show');
    let obj = extractDataRow(e);
    $.each(obj, function (panel, count) {
        $(`#modal_firmas [aria-item="${panel}"]`).html(count);
    });
    $('[name="cod_orden"]').val(cod);
    $.ajax({
        type: 'GET',
        url: `${__url}/incidencias/resueltas/showSignature/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            
            if (data.success) {
                var datos = data.data;
                if (datos) {
                    if (datos.firma_digital)
                        $('#PrevizualizarFirma').attr('src', `${__asset}/images/client/${datos.firma_digital}`).removeClass('visually-hidden');
                    $('#search_signature').val(`${datos.nro_doc} - ${datos.nombre_cliente}`).attr({'disabled': ''});
                    $('#id_firmador').val(datos.id);
                }
            }
            fMananger.formModalLoding('modal_firmas', 'hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fMananger.formModalLoding('modal_firmas', 'hide');
            boxAlert.box({ i: 'error', t: 'Ocurrio un error en el processo', h: 'No se pudo extraer los datos con exito.' });
            console.log(jqXHR);
        }
    });
}

document.getElementById('form-firmas').addEventListener('submit', async function (event) {
    event.preventDefault();

    if (!await boxAlert.confirm({ h: `Después no se podrá modificar los datos ingresados.` })) return true;

    fMananger.formModalLoding('modal_firmas', 'show');
    var valid = validFrom(this);

    if (!valid.success)
        return fMananger.formModalLoding('modal_firmas', 'hide');

    $.ajax({
        type: 'POST',
        url: __url + '/orden/addSignature',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(valid.data.data),
        success: function (data) {

            fMananger.formModalLoding('modal_firmas', 'hide');
            if (data) {
                $('#modal_firmas').modal('hide');
                boxAlert.minbox({
                    h: "La firma se añadió con exito."
                });
                updateTable();
                return true;
            }
            boxAlert.box({
                i: 'error',
                t: '¡Ocurrio un error!',
                h: "ocurrio un error inesperado"
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
            fMananger.formModalLoding('modal_firmas', 'hide');
        }
    });
});

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

/*////////////////////////////////////////
/     SCRIPT BUSCAR DOC DEL CLIENTE      /
////////////////////////////////////////*/

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
                dni.attr({'disabled': ""});
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
            const obj_error = jqXHR.responseJSON;
            console.log(obj_error);
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