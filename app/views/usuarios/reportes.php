<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Usuarios</title>
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
        <span id="breadcrumb-page">Usuarios</span>
    </nav>

    <div class="main-content">
        <div class="tabla-header">
            <h2 class="tabla-titulo">Usuarios del sistema</h2>
            <a href="<?php echo BASE_URL; ?>/login/registro" class="btn-nuevo">
                <i class="fa-solid fa-plus"></i> Nuevo usuario
            </a>
        </div>

        <div class="table-responsive">
            <?php if (empty($usuarios)): ?>
                <p>No hay usuarios registrados.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Fecha de registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?php echo $u['id_usuario']; ?></td>
                                <td><?php echo htmlspecialchars($u['nombre_usuario']); ?></td>
                                <td>
                                    <span class="badge-rol badge-rol--<?php echo $u['roles']; ?>">
                                        <?php echo $u['roles']; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($u['fecha_registro']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
</body>
</html>
