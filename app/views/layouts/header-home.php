<!-- ══════════════════════════════════════════════
     NAVBAR PRINCIPAL (página de inicio / login)
══════════════════════════════════════════════ -->
<header class="navbar">
    <div class="navbar-inner">
        <!-- Logo / Marca -->
        <a href="<?php echo BASE_URL; ?>/" class="navbar-brand">
            <i class="fa-solid fa-boxes-stacked"></i>
            <?php echo htmlspecialchars(TITLE_BUSINESS); ?>
        </a>

        <!-- Navegación desktop -->
        <nav class="navbar-links">
            <a href="<?php echo BASE_URL; ?>/login" class="navbar-link navbar-link--btn">
                <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
            </a>
            <a href="<?php echo BASE_URL; ?>/asistencias/" class="navbar-link">
                Registrar Asistencia
            </a>
        </nav>

        <!-- Botón hamburguesa (móvil) -->
        <button class="navbar-hamburger" id="navHamburger" aria-label="Abrir menú">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</header>

<!-- Menú móvil desplegable -->
<div class="mobile-menu" id="mobileMenu">
    <button class="close" id="closeMenu" aria-label="Cerrar menú">&times;</button>
    <a href="<?php echo BASE_URL; ?>/login">
        <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
    </a>
    <a href="<?php echo BASE_URL; ?>/asistencias/">
        <i class="fa-solid fa-clock"></i> Registrar Asistencia
    </a>
</div>
<div class="mobile-overlay" id="mobileOverlay"></div>
