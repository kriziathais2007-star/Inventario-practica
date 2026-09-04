<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Venta / Salida</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/inventario.css?v=3">
</head>
<body>

    <!-- Topbar -->
    <div class="inv-topbar">
        <a href="<?php echo BASE_URL; ?>/dashboard" class="inv-back">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
        <span class="inv-mode-badge inv-mode-badge--salida">
            <i class="fa-solid fa-cash-register"></i> Salida
        </span>
        <a href="<?php echo BASE_URL; ?>/inventario/entrada" class="inv-switch-link">
            <i class="fa-solid fa-arrow-left"></i> Ir a entrada
        </a>
    </div>

    <!-- Card principal -->
    <div class="inv-card">

        <!-- Producto escaneado -->
        <div class="inv-product" id="productBox">
            <div class="inv-product-img" id="productImg">
                <i class="fa-solid fa-tag"></i>
            </div>
            <div class="inv-product-info">
                <div class="inv-product-name" id="productName">Esperando escaneo…</div>
                <div class="inv-product-meta" id="productStock"></div>
            </div>
            <div class="inv-product-counter" id="contador"></div>
        </div>

        <!-- Input de código -->
        <div class="inv-input-wrap">
            <i class="fa-solid fa-barcode inv-input-icon"></i>
            <input type="text" id="codigo"
                   placeholder="Escanea o escribe el código de barras"
                   autocomplete="off" autofocus>
        </div>

        <!-- Mensaje resultado -->
        <div class="inv-mensaje" id="mensaje"></div>

        <!-- Botón cámara -->
        <button type="button" class="inv-btn-camara" id="btnCamara">
            <i class="fa-solid fa-camera"></i> Usar cámara
        </button>

        <!-- Visor de cámara -->
        <div class="inv-camera-wrap" id="cameraContainer">
            <video id="cameraVideo" autoplay playsinline muted></video>
            <div class="inv-camera-overlay">
                <div class="inv-camera-frame"></div>
            </div>
            <button type="button" class="inv-btn-cerrar-camara" id="btnCerrarCamara">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <p class="inv-hint">
            Cada escaneo descuenta <strong>−1</strong>. Varios escaneos del mismo producto se acumulan.
        </p>

    </div>

    <script>const BASE_URL = '<?php echo BASE_URL; ?>';</script>
    <script src="https://unpkg.com/@zxing/library@0.20.0/umd/index.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/js/inventario-salida.js"></script>
</body>
</html>
