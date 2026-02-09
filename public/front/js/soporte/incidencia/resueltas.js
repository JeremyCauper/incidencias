$(document).ready(function () {
    $('#empresa').on('change', function () {
        CS_sucursal.selecionar($(this).val());
    });

    $('#fProblema').on('change', function () {
        var problem = obj_problem[$(this).val()]?.codigo;
        CS_subproblema.selecionar(problem);
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

const CS_sucursal = new CSelect(['#sucursal'], {
    dataSet: sucursales,
    filterField: 'ruc',
    optionText: 'nombre',
    optionValidation: [
        { clave: 'status', operation: '===', value: 0, badge: 'Inac.' },
    ]
});

const CS_subproblema = new CSelect(['#fSubProblema'], {
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

function updateTable() {
    tb_orden.ajax.reload();
}
mostrar_acciones(tb_orden);

function filtroBusqueda() {
    var empresa = $(`#empresa`).val();
    var sucursal = $('#sucursal').val();
    var fechas = $('#dateRango').val().split('  al  ');
    var fProblema = $('#fProblema').val();
    var fSubProblema = $('#fSubProblema').val();
    var nuevoUrl = `${__url}/soporte/incidencias/resueltas/index?ruc=${empresa}&sucursal=${sucursal}&fProblema=${fProblema}&fSubProblema=${fSubProblema}&fechaIni=${fechas[0]}&fechaFin=${fechas[1]}`;

    tb_orden.ajax.url(nuevoUrl).load();
}


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
                codigo_orden: inc.cod_orden,
                estado: getBadgeIncidencia(inc.estado_informe, '.75', true, true),
                razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                direccion: empresa.direccion,
                sucursal: sucursal.nombre,
                dir_sucursal: sucursal.direccion,
                soporte: tipo_soporte[inc.id_tipo_soporte].descripcion,
                problema: obj_problem[inc.id_problema].descripcion,
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

function OrdenPdf(cod) {
    const url = `${__url}/soporte/orden/exportar-documento?documento=pdf&codigo=${cod}`;
    if (esCelular()) {
        cargarIframeDocumento(url + '&tipo=movil');
    } else {
        window.open(url, `Visualizar PDF ${cod}`, "width=900, height=800");
    }
}

function OrdenTicket(cod) {
    const url = `${__url}/soporte/orden/exportar-documento?documento=ticket&codigo=${cod}`;
    if (esCelular()) {
        cargarIframeDocumento(url + '&tipo=movil');
    } else {
        window.open(url, `Visualizar TICKET ${cod}`, "width=650, height=800");
    }
}

function CompartirWhatsApp(tecnico, cod) {
    const linkPdf = `${__url}/soporte/orden/exportar-documento?documento=pdf&codigo=${cod}`;
    const saludo = saludoPorHora(); // o sin timeZone para hora local
    let mensaje = `${saludo} 👋, le saluda *${tecnico}* de *RC Ingenieros SAC* 🛠️\nLe comparto el enlace para la descarga de su *Orden de Servicio* 📄:\n\n👉 ${linkPdf}\n\nQuedamos atentos a cualquier consulta 📞`;

    const url = "https://api.whatsapp.com/send?text=" + encodeURIComponent(mensaje);
    window.open(url, "_blank");
}

function AddSignature(cod) {
    $('#modal_firmas').modal('show');
    fMananger.formModalLoding('modal_firmas', 'show', true);
    $('[name="cod_orden"]').val(cod);
    $.ajax({
        type: 'GET',
        url: `${__url}/soporte/incidencias/resueltas/showSignature/${cod}`,
        contentType: 'application/json',
        success: function (data) {
            console.log(data);


            if (data.success) {
                var contact = data.data.contacto;
                if (contact) {
                    if (contact.firma_digital)
                        $('#PrevizualizarFirma').attr('src', `${__asset}/images/client/${contact.firma_digital}`).removeClass('visually-hidden');
                    $('#search_signature').val(`${contact.nro_doc} - ${contact.nombre_cliente}`).attr({ 'disabled': '' });
                    $('#id_firmador').val(contact.id);
                }
                var inc = data.data.incidencia;

                sucursal = sucursales[inc.id_sucursal];
                empresa = empresas[inc.ruc_empresa];

                llenarInfoModal('modal_firmas', {
                    codigo: inc.cod_incidencia,
                    codigo_orden: cod,
                    estado: getBadgeIncidencia(inc.estado_informe, '.75', true, true),
                    razon_social: `${empresa.ruc} - ${empresa.razon_social}`,
                    direccion: empresa.direccion,
                    sucursal: sucursal.nombre,
                    dir_sucursal: sucursal.direccion,
                });
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
    const data = valid.data.data;

    if (data.firma_digital && !data.n_doc && !data.nom_cliente) {
        fMananger.formModalLoding('modal_firmas', 'hide');
        return boxAlert.box({ i: 'warning', t: 'Atencion', h: 'Agregaste una firma del cliente, se necesita agregar los datos del cliente.' });
    }

    $.ajax({
        type: 'POST',
        url: __url + '/soporte/orden/addSignature',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': __token,
        },
        data: JSON.stringify(data),
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