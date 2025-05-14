<!DOCTYPE html>
<html lang="es" data-mdb-theme="dark" class="h-100 overflow-hidden">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link href="{{secure_asset('front/vendor/mdboostrap/css/all.min6.0.0.css')}}" rel="stylesheet">
    <!-- MDB -->
    <link href="{{secure_asset('front/vendor/mdboostrap/css/mdb.min7.2.0.css')}}" rel="stylesheet">
    <!-- JQuery -->
    <script src="{{ secure_asset('front/vendor/jquery/jquery.min.js') }}"></script>
    <title>Visor PDF</title>

    <style>
        body {
            height: inherit;
        }

        #pdf-container {
            /* display: flex; */
            flex-direction: column;
            align-items: center;
            /* width: 100%; */
        }

        #canvas-container {
            /* width: 100%; */
            /* overflow: hidden; */
            margin-top: 46px;
            margin-bottom: 60px;
        }

        #pdf-canvas {
            /* max-width: 100%; */
            height: auto;
        }

        @media (max-width: 768px) {
            #pdf-container {
                height: 100vh;
                justify-content: space-evenly;
            }
        }
    </style>
</head>

<!-- overflow-auto d-flex align-items-center justify-content-center  -->

<body class="overflow-auto mt-5">
    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-body fixed-top" style="height: 46px !important;">
            <div class="container-fluid">
                <div>
                    <button type="button" class="btn btn-link btn-sm btn-floating" id="zoomOut" data-mdb-ripple-init>
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <button type="button" class="btn btn-link btn-sm btn-floating" id="zoomIn" data-mdb-ripple-init>
                        <i class="fas fa-search-plus"></i>
                    </button>
                    <button type="button" class="btn btn-link btn-sm btn-floating" id="reload_zoom"
                        data-mdb-ripple-init>
                        <i class="fas fa-arrow-rotate-right"></i>
                    </button>
                </div>
                <button type="button" id="descargar" class="btn btn-link btn-sm btn-floating" data-mdb-ripple-init>
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </nav>
        <!-- Navbar -->
    </header>
    <div id="pdf-container" class="d-flex">
        <div id="canvas-container" class="text-center">
            <canvas id="pdf_canvas" class="w-100"></canvas>
        </div>
    </div>
    <footer>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-body py-2 fixed-bottom">
            <div class="btn-group btn-group-sm m-auto py-1" id="top-bar" role="group" aria-label="Basic example">
                <button type="button" class="btn" id="prev" data-mdb-ripple-init>
                    <i class="fas fa-arrow-circle-left"></i>
                </button>
                <span>
                    <span><input type="number" class="form-control" id="page_num_input"
                            style="width: 40px; display:inline;" value="1" min="1" /></span> / <span
                        id="page_count"></span>
                </span>
                <button type="button" class="btn" id="next" data-mdb-ripple-init>
                    <i class="fas fa-arrow-circle-right"></i>
                </button>
            </div>
        </nav>
        <!-- Navbar -->
    </footer>

    <!-- MDB -->
    <script type="text/javascript" src="{{secure_asset('front/vendor/mdboostrap/js/mdb.umd.min7.2.0.js')}}"></script>
    <script src="{{ secure_asset('front/vendor/pdfjs/pdf-js/pdf.min.js') }}"></script>
    <script src="{{ secure_asset('front/vendor/pdfjs/pdf-js/pdf.worker.min.js') }}"></script>
    <script>
        // 1) Cadena Base64 del PDF (asegúrate de inyectarlo correctamente)
        const base64pdf = "{{ $base64_pdf }}";  // Asegúrate de que esta variable esté correctamente configurada.

        try {
            if (base64pdf) {
                // 2) Convierte Base64 a Uint8Array
                function base64ToUint8Array(b64) {
                    const raw = atob(b64);                   // decodifica Base64
                    const arr = new Uint8Array(raw.length);
                    for (let i = 0; i < raw.length; i++) {
                        arr[i] = raw.charCodeAt(i);             // cada carácter a su byte correspondiente
                    }
                    return arr;
                }

                // 4) Carga y renderiza la primera página
                const pdfData = base64ToUint8Array(base64pdf);
                let pdfDoc = null;
                let pageNum = 1;
                let pageRendering = false;
                let pageNumPending = null;
                let scale = 1;  // Define el zoom inicial

                pdfjsLib.getDocument({ data: pdfData }).promise
                    .then((pdfDoc_) => {
                        pdfDoc = pdfDoc_;
                        // document.getElementById('loading-message').style.display = 'none';
                        document.getElementById('top-bar').style.display = 'block';
                        document.getElementById('page_count').textContent = pdfDoc.numPages;
                        renderPage(pageNum);
                    })
                    .catch((err) => {
                        console.error('Error al cargar el PDF:', err);
                        // document.getElementById('loading-message').style.display = 'none';
                        document.getElementById('pdf-container').innerHTML = '<div class="error">No se pudo cargar el PDF.</div>';
                    });

                function renderPage(num) {
                    pageRendering = true;
                    pdfDoc.getPage(num).then((page) => {
                        var canvas = document.getElementById('pdf_canvas');
                        var container = document.getElementById('pdf-container');

                        var viewport = page.getViewport({ scale: scale });
                        // viewport.height = 679;
                        // viewport.transform[5] = 350;
                        // viewport.width = contenedor.clientWidth;
                        // viewport.viewBox[2] = contenedor.clientWidth;
                        // viewport.viewBox[3] = contenedor.clientHeight;
                        var ctx = canvas.getContext('2d');

                        canvas.height = viewport.height; // viewport.height
                        canvas.width = viewport.width; // viewport.width
                        if (scale > 1) {
                            canvas.classList.remove('w-100');
                            container.classList.remove('d-flex');
                        } else {
                            canvas.classList.add('w-100');
                            container.classList.add('d-flex');
                        }

                        var renderContext = {
                            canvasContext: ctx,
                            viewport: viewport
                        };
                        var renderTask = page.render(renderContext);
                        renderTask.promise.then(() => {
                            pageRendering = false;
                            if (pageNumPending !== null) {
                                renderPage(pageNumPending);
                                pageNumPending = null;
                            }
                        });
                    });
                    document.getElementById('page_num_input').value = num;
                }

                // Función para renderizar la siguiente página
                function onPrevPage() {
                    if (pageNum <= 1) {
                        return;
                    }
                    pageNum--;
                    renderPage(pageNum);
                }

                document.getElementById('prev').addEventListener('click', onPrevPage);

                // Función para renderizar la página anterior
                function onNextPage() {
                    if (pageNum >= pdfDoc.numPages) {
                        return;
                    }
                    pageNum++;
                    renderPage(pageNum);
                }

                document.getElementById('next').addEventListener('click', onNextPage);

                // Zoom en y zoom out
                function zoomOut() {
                    scale -= 0.1;
                    console.log(pageNum);

                    renderPage(pageNum);
                }

                document.getElementById("zoomOut").addEventListener('click', zoomOut);

                function zoomIn() {
                    scale += 0.1;
                    renderPage(pageNum);
                }

                document.getElementById('zoomIn').addEventListener('click', zoomIn);

                function reload_zoom() {
                    scale = 1;
                    renderPage(pageNum);
                }

                document.getElementById('reload_zoom').addEventListener('click', reload_zoom);

                function descargar() {
                    const url = `{{ secure_url('') }}/{{ $urlDescarga }}?documento={{ $documento }}&codigo={{ $codigo }}`;
                    const link = document.createElement('a');
                    link.href = url + '&tipo=descarga';
                    link.download = ''; // puedes darle un nombre, e.g., 'orden-123.pdf'
                    link.style.display = 'none';

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }

                document.getElementById('descargar').addEventListener('click', descargar);
            } else {
                document.getElementById('pdf-container').innerHTML = '<div class="error">El PDF no está configurado correctamente.</div>';
            }
        } catch (error) {
            document.getElementById('pdf-container').innerHTML = '<div class="error">' + error + '</div>';
        }
    </script>
    <script>
        document.addEventListener('contextmenu', function (event) {
            event.preventDefault();
        });
    </script>
</body>

</html>