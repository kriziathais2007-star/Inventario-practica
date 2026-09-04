<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE_BUSINESS; ?> - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/dashboard.css?v=4">
</head>
<body>

<?php include __DIR__ . '/../layouts/sidebar-dashboard.php'; ?>

<main>
    <nav class="breadcrumb">
        <span>Inicio</span>
        <i class="fa-solid fa-chevron-right"></i>
        <span id="breadcrumb-page">Dashboard</span>
    </nav>

    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon--a">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo (int)($stats['total'] ?? 0); ?></div>
                <div class="stat-label">Productos</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--b">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">S/ <?php echo number_format((float)($stats['valor_total'] ?? 0), 0); ?></div>
                <div class="stat-label">Valor en stock</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--c">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo (int)($stats['sin_stock'] ?? 0); ?></div>
                <div class="stat-label">Sin stock</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon--d">
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo (int)($stats['stock_bajo'] ?? 0); ?></div>
                <div class="stat-label">Stock bajo ( -5)</div>
            </div>
        </div>
    </div>

    <div class="quick-actions">
        <a href="<?php echo BASE_URL; ?>/inventario/entrada" class="action-card">
            <div class="action-card-icon action-card-icon--in">
                <i class="fa-solid fa-cart-plus"></i>
            </div>
            <div>
                <div class="action-card-title">Entrada de stock</div>
                <div class="action-card-sub">Agregar unidades al inventario</div>
            </div>
        </a>
        <a href="<?php echo BASE_URL; ?>/inventario/salida" class="action-card">
            <div class="action-card-icon action-card-icon--out">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <div class="action-card-title">Venta / Salida</div>
                <div class="action-card-sub">Descontar unidades del inventario</div>
            </div>
        </a>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
</body>
</html>

