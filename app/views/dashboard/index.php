<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <nav class="breadcrumb">
        <span>Inicio</span>
        <i class="fa-solid fa-chevron-right"></i>
        <span id="breadcrumb-page">Dashboard</span>
    </nav>

    <!-- Tarjetas de estadísticas -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon--purple">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo (int)($stats['total'] ?? 0); ?></div>
                <div class="stat-label">Productos registrados</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--green">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">S/ <?php echo number_format((float)($stats['valor_total'] ?? 0), 0); ?></div>
                <div class="stat-label">Valor total en stock</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--red">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo (int)($stats['sin_stock'] ?? 0); ?></div>
                <div class="stat-label">Sin stock</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--blue">
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo (int)($stats['stock_bajo'] ?? 0); ?></div>
                <div class="stat-label">Stock bajo (≤ 5)</div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="quick-actions">
        <a href="<?php echo BASE_URL; ?>/inventario/entrada" class="action-card">
            <div class="action-card-icon action-card-icon--green">
                <i class="fa-solid fa-arrow-up"></i>
            </div>
            <div>
                <div class="action-card-title">Entrada de stock</div>
                <div class="action-card-sub">Escanear para agregar unidades</div>
            </div>
        </a>
        <a href="<?php echo BASE_URL; ?>/inventario/salida" class="action-card">
            <div class="action-card-icon action-card-icon--red">
                <i class="fa-solid fa-arrow-down"></i>
            </div>
            <div>
                <div class="action-card-title">Venta / Salida</div>
                <div class="action-card-sub">Escanear para descontar stock</div>
            </div>
        </a>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
</body>
</html>
