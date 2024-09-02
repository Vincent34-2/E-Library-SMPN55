<?php
$file = filter_input(INPUT_GET, 'file', FILTER_SANITIZE_STRING);

if (!$file) {
    echo "Invalid file";
    exit;
}

// Ensure the file path is safe and secure
$filePath = '../../assets/pdfs/' . $file;

if (!file_exists($filePath)) {
    echo "File not found";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Viewer</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        #pdf-container {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }
        canvas {
            display: block;
            margin: auto;
            border: 1px solid #000;
            background: #fff;
        }
    </style>
</head>
<body>
    <div id="pdf-container">
        <canvas id="pdf-canvas"></canvas>
    </div>

    <script src="../../assets/pdfjs/pdf.mjs"></script>
    <script>
        const url = '<?php echo htmlspecialchars($filePath); ?>';

        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = '../../assets/pdfjs/pdf.worker.mjs';

        let pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1.5,
            canvas = document.getElementById('pdf-canvas'),
            ctx = canvas.getContext('2d');

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                const renderTask = page.render(renderContext);
                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            renderPage(pageNum);
        });
    </script>
</body>
</html>
