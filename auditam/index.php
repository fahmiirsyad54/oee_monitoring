<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR Scanner Demo</title>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/AdminLTE.min.css">
</head>
<body>
<style>
    canvas {
        display: none;
    }
    hr {
        margin-top: 32px;
    }
    input[type="file"] {
        display: block;
        margin-bottom: 16px;
    }
    div {
        margin-bottom: 16px;
    }
</style>
<h1 class="text-center">Scan QR Code Machine</h1>
<div class="text-center">
    <b>Device has camera: </b>
    <span id="cam-has-camera"></span>
    <br>
    <video muted playsinline id="qr-video"></video>
    <canvas id="debug-canvas"></canvas>
</div>

<script type="module">
    import QrScanner from "./scanqr/qr-scanner.min.js";
    QrScanner.WORKER_PATH = './scanqr/qr-scanner-worker.min.js';

    const video = document.getElementById('qr-video');
    const debugCheckbox = document.getElementById('debug-checkbox');
    const debugCanvas = document.getElementById('debug-canvas');
    const debugCanvasContext = debugCanvas.getContext('2d');
    const camHasCamera = document.getElementById('cam-has-camera');
    const camQrResult = document.getElementById('cam-qr-result');
    const fileSelector = document.getElementById('file-selector');
    const fileQrResult = document.getElementById('file-qr-result');

    function setResult(label, result) {
        var host_name =  window.location.pathname;
        // label.textContent = result;
        // label.style.color = 'teal';
        // clearTimeout(label.highlightTimeout);
        // label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
        window.location.href = 'http://' + window.location.hostname + '/tpmsystem2/autonomus_qrcode/add/'+result;
    }

    // ####### Web Cam Scanning #######

    QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);

    const scanner = new QrScanner(video, result => setResult(camQrResult, result));
    scanner.start();

    document.getElementById('inversion-mode-select').addEventListener('change', event => {
        scanner.setInversionMode(event.target.value);
    });

    // ####### File Scanning #######

    fileSelector.addEventListener('change', event => {
        const file = fileSelector.files[0];
        if (!file) {
            return;
        }
        QrScanner.scanImage(file)
            .then(result => setResult(fileQrResult, result))
            .catch(e => setResult(fileQrResult, e || 'No QR code found.'));
    });


    // ####### debug mode related code #######

    debugCheckbox.addEventListener('change', () => setDebugMode(debugCheckbox.checked));

    function setDebugMode(isDebug) {
        const worker = scanner._qrWorker;
        worker.postMessage({
            type: 'setDebug',
            data: isDebug
        });
        if (isDebug) {
            debugCanvas.style.display = 'block';
            worker.addEventListener('message', handleDebugImage);
        } else {
            debugCanvas.style.display = 'none';
            worker.removeEventListener('message', handleDebugImage);
        }
    }

    function handleDebugImage(event) {
        const type = event.data.type;
        if (type === 'debugImage') {
            const imageData = event.data.data;
            if (debugCanvas.width !== imageData.width || debugCanvas.height !== imageData.height) {
                debugCanvas.width = imageData.width;
                debugCanvas.height = imageData.height;
            }
            debugCanvasContext.putImageData(imageData, 0, 0);
        }
    }
</script>
</body>
</html>