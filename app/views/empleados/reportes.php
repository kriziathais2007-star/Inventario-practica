<!DOCTYPE html>
<html lang="Es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Empleados</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/table-responsive.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/botones.css">
</head>

<body>

    <?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

    <!-- CONTENIDO PRINCIPAL -->
    <main>
        <nav class="breadcrumb">
            <span>Dashboard</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Empleados</span>
            <i class="fa-solid fa-chevron-right"></i>
            <span id="breadcrumb-page">Reportes</span>
        </nav>
        <div class="main-content">
            <div class="table-responsive">
                <?php if (empty($empleados)) : ?>
                    <p>No hay registro</p>
                <?php else: ?>
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>DNI</th>
                                <th>Celular</th>
                                <th>Correo</th>
                                <th>Cargo</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($empleados as $empleaditos): ?>
                                <tr>
                                    <td><?php echo $empleaditos['id_empleado'] ?></td>
                                    <td><?php echo $empleaditos['nombre'] ?></td>
                                    <td><?php echo htmlspecialchars($empleaditos['apellido']) ?></td>
                                    <td><?php echo htmlspecialchars($empleaditos['dni']) ?></td>
                                    <td><?php echo htmlspecialchars($empleaditos['celular']) ?></td>
                                    <td><?php echo htmlspecialchars($empleaditos['correo']) ?></td>
                                    <td><?php echo htmlspecialchars($empleaditos['nombre_cargo']) ?></td>
                                    <td><?php echo htmlspecialchars($empleaditos['fecha_registro']) ?></td>
                                    <td>
                                        <button class="btn-editar"
                                            data-id="<?php echo $empleaditos['id_empleado'] ?>"
                                            data-nombre="<?php echo htmlspecialchars($empleaditos['nombre']) ?>"
                                            data-apellido="<?php echo htmlspecialchars($empleaditos['apellido']) ?>"
                                            data-dni="<?php echo htmlspecialchars($empleaditos['dni']) ?>"
                                            data-celular="<?php echo htmlspecialchars($empleaditos['celular']) ?>"
                                            data-correo="<?php echo htmlspecialchars($empleaditos['correo']) ?>"
                                            data-id_cargo="<?php echo $empleaditos['id_cargo'] ?>">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button  class="btn-eliminar"
                                        data-id="<?php echo $empleaditos['id_empleado'] ?>" >
                                        <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Modal Editar Empleado -->
    <div class="modal-overlay" id="modalEditarOverlay">
        <div class="modal-editar">
            <button class="modal-cerrar" id="modalCerrar">&times;</button>
            <h2 class="modal-titulo">Editar empleado</h2>
            <form class="modal-form">
                <input type="hidden" id="edit-id" name="id_empleado">
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-nombre">Nombre</label>
                        <input type="text" id="edit-nombre" name="nombre">
                    </div>
                    <div class="form-group">
                        <label for="edit-apellido">Apellido</label>
                        <input type="text" id="edit-apellido" name="apellido">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-dni">DNI</label>
                        <input type="text" id="edit-dni" name="dni">
                    </div>
                    <div class="form-group">
                        <label for="edit-celular">Celular</label>
                        <input type="text" id="edit-celular" name="celular">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit-correo">Correo</label>
                        <input type="email" id="edit-correo" name="correo">
                    </div>
                    <div class="form-group">
                        <label for="edit-cargo">Cargo</label>
                        <select id="edit-cargo" name="id_cargo">
                            <?php foreach ($lista_cargo as $cargitos): ?>
                                <option value="<?php echo $cargitos['id_cargo']; ?>">
                                    <?php echo htmlspecialchars($cargitos['nombre_cargo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="button" class="btn-guardar-modal">
                    Guardar cambios
                </button>
            </form>
        </div>
    </div>

    <script>
        let BASE_URL = '<?php echo BASE_URL; ?>'
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/js/empleados-main.js"></script>
</body>

</html>