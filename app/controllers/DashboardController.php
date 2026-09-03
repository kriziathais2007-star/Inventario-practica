<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

class DashboardController extends Controller {

    public function index(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Estadísticas rápidas para las tarjetas del dashboard
        $stats = ['total' => 0, 'sin_stock' => 0, 'stock_bajo' => 0, 'valor_total' => 0];
        try {
            $db = Database::getConnection();
            $row = $db->query("
                SELECT
                    COUNT(*)                                      AS total,
                    SUM(stock = 0)                                AS sin_stock,
                    SUM(stock > 0 AND stock <= 5)                 AS stock_bajo,
                    SUM(stock * precio)                           AS valor_total
                FROM producto
            ")->fetch(PDO::FETCH_ASSOC);
            if ($row) $stats = $row;
        } catch (Exception $e) { /* tabla aún no creada */ }

        $this->view('dashboard/index', [
            'usuario' => $_SESSION['usuario'],
            'stats'   => $stats,
        ]);
    }
}
