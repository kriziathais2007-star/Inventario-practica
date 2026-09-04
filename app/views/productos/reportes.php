<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css?v=3">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/table-responsive.css?v=3">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/botones.css?v=3">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <nav class="breadcrumb">
        <span>Inicio</span>
        <i class="fa-solid fa-chevron-right"></i>
        <span>Productos</span>
        <i class="fa-solid fa-chevron-right"></i>
        <span id="breadcrumb-page">Reporte</span>
    </nav>

    <div class="main-content">
        <div class="tabla-header">
            <h2 class="tabla-titulo">Lista de Productos</h2>
            <a href="<?php echo BASE_URL; ?>/productos/registro" class="btn-nuevo">
                <i class="fa-solid fa-plus"></i> Nuevo producto
            </a>
        </div>

        <div class="table-responsive">
            <?php if (empty($productos)): ?>
                <p>No hay productos registrados.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $p): ?>
                            <?php $imgSrc = !empty($p['imagen'] ?? '')
                                ? BASE_URL . '/public/image/productos/' . htmlspecialchars($p['imagen'])
                                : ''; ?>
                            <tr>
                                <td>
                                    <?php if ($imgSrc): ?>
                                        <img src="<?php echo $imgSrc; ?>"
                                             alt="<?php echo htmlspecialchars($p['nombre_producto']); ?>"
                                             class="tabla-img">
                                    <?php else: ?>
                                        <div class="tabla-img-placeholder">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($p['codigo']); ?></td>
                                <td><?php echo htmlspecialchars($p['nombre_producto']); ?></td>
                                <td><?php echo htmlspecialchars($p['descripcion']); ?></td>
                                <td>
                                    <span class="badge-stock <?php echo $p['stock'] <= 5 ? 'badge-stock--low' : ''; ?>">
                                        <?php echo $p['stock']; ?>
                                    </span>
                                </td>
                                <td>S/ <?php echo number_format($p['precio'], 2); ?></td>
                                <td>
                                    <div class="acciones">
                                        <button class="btn-editar"
                                            data-id="<?php echo $p['id_producto']; ?>"
                                            data-codigo="<?php echo htmlspecialchars($p['codigo']); ?>"
                                            data-nombre="<?php echo htmlspecialchars($p['nombre_producto']); ?>"
                                            data-descripcion="<?php echo htmlspecialchars($p['descripcion']); ?>"
                                            data-stock="<?php echo $p['stock']; ?>"
                                            data-precio="<?php echo $p['precio']; ?>"
                                            data-imagen="<?php echo htmlspecialchars($p['imagen'] ?? ''); ?>"
                                            data-imagen-src="<?php echo $imgSrc; ?>">                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn-eliminar" data-id="<?php echo $p['id_producto']; ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Modal editar -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-editar">
        <button class="modal-cerrar" id="modalCerrar">&times;</button>
        <h2 class="modal-titulo">Editar producto</h2>
        <form class="modal-form" id="formEditar" enctype="multipart/form-data">
            <input type="hidden" id="edit-id" name="id_producto">
            <input type="hidden" id="edit-imagen-actual" name="imagen_actual">

            <!-- Preview imagen en el modal -->
            <div class="form-group">
                <label>Imagen</label>
                <div class="img-upload-wrap img-upload-wrap--sm" id="editImgWrap">
                    <img id="editImgPreview" src="" alt="Preview" class="img-preview hidden">
                    <div class="img-placeholder" id="editImgPlaceholder">
                        <i class="fa-solid fa-image"></i>
                        <span>Cambiar imagen</span>
                    </div>
                    <input type="file" id="edit-imagen" name="imagen"
                           accept="image/jpeg,image/png,image/webp,image/gif"
                           class="img-file-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Código</label>
                    <input type="text" id="edit-codigo" name="codigo" required>
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" id="edit-nombre" name="nombre_producto" required>
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <input type="text" id="edit-descripcion" name="descripcion">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" id="edit-stock" name="stock" min="0" required>
                </div>
                <div class="form-group">
                    <label>Precio (S/)</label>
                    <input type="number" id="edit-precio" name="precio" step="0.01" min="0" required>
                </div>
            </div>
            <button type="button" class="btn-guardar-modal" id="btnGuardarEdit">Guardar cambios</button>
        </form>
    </div>
</div>

<script>const BASE_URL = '<?php echo BASE_URL; ?>';</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
<script src="<?php echo BASE_URL; ?>/public/js/productos-main.js"></script>
</body>
</html>
