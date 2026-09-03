<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Nuevo Producto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/registro-producto.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <nav class="breadcrumb">
        <span>Inicio</span>
        <i class="fa-solid fa-chevron-right"></i>
        <span>Productos</span>
        <i class="fa-solid fa-chevron-right"></i>
        <span id="breadcrumb-page">Nuevo producto</span>
    </nav>

    <div class="rp-wrapper">

        <!-- Columna izquierda: imagen -->
        <div class="rp-img-col">
            <div class="rp-img-drop" id="imgDrop">
                <img id="imgPreview" src="" alt="Preview" class="rp-img-preview hidden">
                <div class="rp-img-placeholder" id="imgPlaceholder">
                    <div class="rp-img-icon">
                        <i class="fa-solid fa-image"></i>
                    </div>
                    <p class="rp-img-label">Arrastra una imagen<br>o haz clic para seleccionar</p>
                    <p class="rp-img-hint">JPG · PNG · WEBP &nbsp;·&nbsp; máx. 2 MB</p>
                </div>
                <input type="file" id="imgInput" accept="image/jpeg,image/png,image/webp,image/gif" class="rp-img-input">
            </div>

            <!-- Botón quitar imagen -->
            <button type="button" class="rp-img-remove hidden" id="btnQuitarImg">
                <i class="fa-solid fa-xmark"></i> Quitar imagen
            </button>
        </div>

        <!-- Columna derecha: formulario -->
        <div class="rp-form-col">
            <div class="rp-header">
                <h1 class="rp-title">Nuevo producto</h1>
                <a href="<?php echo BASE_URL; ?>/productos" class="rp-back">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

            <form id="formRegistro" novalidate>

                <div class="rp-field">
                    <label for="codigo">
                        Código de barras / SKU
                        <span class="rp-required">*</span>
                    </label>
                    <div class="rp-input-wrap">
                        <i class="fa-solid fa-barcode rp-input-icon"></i>
                        <input type="text" id="codigo" name="codigo"
                               placeholder="Ej: 7750060180016"
                               autocomplete="off">
                    </div>
                    <p class="rp-field-error" id="err-codigo"></p>
                </div>

                <div class="rp-field">
                    <label for="nombre_producto">
                        Nombre del producto
                        <span class="rp-required">*</span>
                    </label>
                    <div class="rp-input-wrap">
                        <i class="fa-solid fa-box rp-input-icon"></i>
                        <input type="text" id="nombre_producto" name="nombre_producto"
                               placeholder="Ej: Leche evaporada 400g">
                    </div>
                    <p class="rp-field-error" id="err-nombre"></p>
                </div>

                <div class="rp-field">
                    <label for="descripcion">Descripción <span class="rp-optional">(opcional)</span></label>
                    <textarea id="descripcion" name="descripcion" rows="2"
                              placeholder="Marca, presentación, detalles…"></textarea>
                </div>

                <div class="rp-row">
                    <div class="rp-field">
                        <label for="stock">
                            Stock inicial
                            <span class="rp-required">*</span>
                        </label>
                        <div class="rp-input-wrap">
                            <i class="fa-solid fa-cubes rp-input-icon"></i>
                            <input type="number" id="stock" name="stock"
                                   min="0" value="0" placeholder="0">
                        </div>
                        <p class="rp-field-error" id="err-stock"></p>
                    </div>

                    <div class="rp-field">
                        <label for="precio">
                            Precio (S/)
                            <span class="rp-required">*</span>
                        </label>
                        <div class="rp-input-wrap">
                            <i class="fa-solid fa-tag rp-input-icon"></i>
                            <input type="number" id="precio" name="precio"
                                   step="0.01" min="0" value="0.00" placeholder="0.00">
                        </div>
                        <p class="rp-field-error" id="err-precio"></p>
                    </div>
                </div>

                <div class="rp-actions">
                    <button type="submit" class="rp-btn-guardar" id="btnGuardar">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Guardar producto</span>
                    </button>
                    <a href="<?php echo BASE_URL; ?>/productos" class="rp-btn-cancelar">
                        Cancelar
                    </a>
                </div>

            </form>
        </div>

    </div><!-- /.rp-wrapper -->
</main>

<!-- Toast de notificación -->
<div class="rp-toast" id="rpToast" role="alert" aria-live="polite">
    <i class="rp-toast-icon fa-solid" id="rpToastIcon"></i>
    <span id="rpToastMsg"></span>
</div>

<script>const BASE_URL = '<?php echo BASE_URL; ?>';</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script src="<?php echo BASE_URL; ?>/public/js/registro-producto.js"></script>
</body>
</html>
