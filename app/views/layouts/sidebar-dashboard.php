<?php
$rutaActual   = explode('/', trim($_GET['url'] ?? 'dashboard', '/'))[0] ?: 'dashboard';
$esSuperAdmin = ($_SESSION['usuario']['roles'] ?? '') === 'superadmin';
?>

<!-- TOPBAR (solo visible en móvil) -->
<div class="topbar">
    <div class="title-business">
        <span><?php echo htmlspecialchars($usuario['nombre_usuario'] ?? 'Usuario'); ?></span>
    </div>
    <div class="btn-menu">
        <button class="hamburger" aria-label="Abrir menú">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</div>

<!-- OVERLAY -->
<div class="overlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo"><?php echo htmlspecialchars(TITLE_BUSINESS); ?></div>
    <ul>

        <!-- DASHBOARD -->
        <li>
            <a href="<?php echo BASE_URL; ?>/dashboard"
               class="<?php echo $rutaActual === 'dashboard' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-house"></i>
                <span>Inicio</span>
            </a>
        </li>

        <!-- INVENTARIO (todos los usuarios) -->
        <li class="<?php echo $rutaActual === 'inventario' ? 'dropdown show' : 'dropdown'; ?>">
            <a href="#" class="dropbtn <?php echo $rutaActual === 'inventario' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-warehouse"></i>
                <span>Inventario</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL; ?>/inventario/entrada">
                    <i class="fa-solid fa-arrow-up"></i>
                    Entrada de stock
                </a>
                <a href="<?php echo BASE_URL; ?>/inventario/salida">
                    <i class="fa-solid fa-arrow-down"></i>
                    Venta / Salida
                </a>
            </div>
        </li>

        <?php if ($esSuperAdmin): ?>

        <!-- PRODUCTOS (solo superadmin) -->
        <li class="<?php echo $rutaActual === 'productos' ? 'dropdown show' : 'dropdown'; ?>">
            <a href="#" class="dropbtn <?php echo $rutaActual === 'productos' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-box"></i>
                <span>Productos</span>
                <i class="fa-solid fa-chevron-down arrow"></i>
            </a>
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL; ?>/productos">
                    <i class="fa-solid fa-list"></i>
                    Reporte
                </a>
                <a href="<?php echo BASE_URL; ?>/productos/registro">
                    <i class="fa-solid fa-plus"></i>
                    Nuevo producto
                </a>
            </div>
        </li>

        <!-- USUARIOS (solo superadmin) -->
        <li>
            <a href="<?php echo BASE_URL; ?>/usuarios"
               class="<?php echo $rutaActual === 'usuarios' ? 'activo' : ''; ?>">
                <i class="fa-solid fa-users-gear"></i>
                <span>Usuarios</span>
            </a>
        </li>

        <?php endif; ?>

        <!-- CERRAR SESIÓN -->
        <li class="nav-logout">
            <a href="<?php echo BASE_URL; ?>/logout" id="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar sesión</span>
            </a>
        </li>
    </ul>
</aside>

<script src="<?php echo BASE_URL; ?>/public/js/dropdown.js"></script>
