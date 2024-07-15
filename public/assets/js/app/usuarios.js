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
        title: "CREAR FIRMA DIGITAL",
        html: `<canvas id="signature-pad" width="400" height="200" style="border: 2px dashed #dee2e6; border-radius: 7px; padding:5px; min-width: 160px"></canvas>
                <button class="btn btn-primary btn-sm" id="save">Guardar</button>
                <button class="btn btn-danger btn-sm" id="clear">Limpiar</button>
                <button class="btn btn-info btn-sm" onclick="Swal.close()">Cerrar</button>`,
        showConfirmButton: false
    });

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