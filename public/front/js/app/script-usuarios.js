const fileInput = document.getElementById('foto_perfil');
const removeButton = document.getElementById('removeButton');
const PreviFPerfil = document.getElementById('PreviFPerfil');
const txtFotoPerfil = document.getElementById('txtFotoPerfil');

document.getElementById('uploadButton').addEventListener('click', () => {
    fileInput.click();
});

fileInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    const maxFileSize = 20 * 1024 * 1024; // 20MB
    if (file) {
        if (file.size > maxFileSize) {
            alert('El archivo debe ser menor a 20MB');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            PreviFPerfil.src = e.target.result;
            PreviFPerfil.alt = file.name;
            removeButton.style.display = 'block';
            document.getElementById('txtFotoPerfil').value = btoa(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

removeButton.addEventListener('click', () => {
    PreviFPerfil.src = PreviFPerfil.getAttribute("imagedefault");
    txtFotoPerfil.value = '';
    removeButton.style.display = 'none';
    fileInput.value = '';
});


const fileInputFirma = document.getElementById('firma_digital');
const PreviFirma = document.getElementById('PreviFirma');
const removeImgFirma = document.getElementById('removeImgFirma');
const textFirmaDigital = document.getElementById('textFirmaDigital');

document.getElementById('uploadImgFirma').addEventListener('click', () => {
    fileInputFirma.click();
});

fileInputFirma.addEventListener('change', function(event) {
    const file = event.target.files[0];
    const maxFileSize = 10 * 1024 * 1024; // 10MB
    if (file) {
        if (file.size > maxFileSize) {
            alert('El archivo debe ser menor a 10MB');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            PreviFirma.src = e.target.result;
            PreviFirma.alt = file.name;
            removeImgFirma.style.display = 'block';
            textFirmaDigital.value = btoa(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('createFirma').addEventListener('click', async () => {
    Swal.fire({
        title: '<h6 class="text-primary">CREAR FIRMA DIGITAL</h6>',
        html: `
            <div>
                <div class="content-signature-pad">
                    <canvas id="signature-pad" width="400" height="250" style="border: 2px dashed #ccc;"></canvas>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary btn-sm" id="save">Guardar</button>
                    <button class="btn btn-danger btn-sm" id="clear">Limpiar</button>
                    <button class="btn btn-info btn-sm" onclick="Swal.close()">Cerrar</button>
                </div>
            </div>`,
        showConfirmButton: false
    });
    resizeWindow();

    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    document.getElementById('clear').addEventListener('click', function() {
        signaturePad.clear();
    });

    document.getElementById('save').addEventListener('click', function() {
        if (signaturePad.isEmpty()) {
            alert("Por favor, dibuja una firma primero.");
        } else {
            var dataURL = signaturePad.toDataURL();
            document.getElementById('textFirmaDigital').value = btoa(dataURL.toString());
            document.getElementById('PreviFirma').src = dataURL.toString();
            removeImgFirma.style.display = 'block';
            Swal.close();
        }
    });
});

window.addEventListener('resize', resizeWindow);
function resizeWindow() {
    var canvas = document.getElementById('signature-pad');
    if (window.matchMedia('(max-width: 545px)').matches) {
        canvas.width = 300;
        canvas.height = 175;
    }
    else {
        canvas.width = 400;
        canvas.height = 250;
    }
    if (window.matchMedia('(max-width: 382px)').matches) {
        canvas.width = 200;
        canvas.height = 140;
    }
}


removeImgFirma.addEventListener('click', () => {
    PreviFirma.src = PreviFirma.getAttribute("imagedefault");
    textFirmaDigital.value = '';
    removeImgFirma.style.display = 'none';
    fileInputFirma.value = '';
});

function PreviImagenes(data) {
    Swal.fire({
        title: '<h5 class="card-title text-linkedin">PREVISUALIZACIÓN DE LA IMAGEN CARGADA</h5>',
        html: `<div>
                <img src="${data}" />
            </div>`
    });
}