<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Marcar Asistencia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/asistencias-main.css">
</head>
<body>

    <!-- PANEL IZQUIERDO -->
    <div class="panel-left">
        <div class="logo">
            <img src="<?php echo BASE_URL; ?>/public/image/senati.png" alt="Logo empresa">
        </div>

        <div class="avatar-box">
            <img src="<?php echo BASE_URL; ?>/public/image/foto_prueba.png" alt="Empleado">
        </div>

        <p class="empleado-nombre" id="empleado-nombre">— — —</p>
    </div>

    <!-- PANEL DERECHO -->
    <div class="panel-right">
        <div class="reloj" id="reloj">00:00:00</div>
        <div class="barcode-wrap">
            <i class="fa-solid fa-barcode barcode-icon"></i>
            <input
                type="text"
                id="codigo"
                placeholder="Escanea o ingresa tu código"
                autocomplete="off"
                autofocus
            >
        </div>
        <div class="mensaje" id="mensaje">
            <p id="msj"></p>
        </div>        
    </div>

    <script>
        let BASE_URL = '<?php echo BASE_URL; ?>'
    </script>
    <script src="<?php echo BASE_URL; ?>/public/js/asistencias-main.js"></script>
</body>
</html>
