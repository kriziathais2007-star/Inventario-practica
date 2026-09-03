<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

class UsuariosController extends Controller {

    public function index(): void {
        $this->reporte();
    }

    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->soloSuperAdmin();

        $db   = Database::getConnection();
        // No mostramos la clave por seguridad
        $stmt = $db->prepare("SELECT id_usuario, nombre_usuario, roles, fecha_registro FROM usuario ORDER BY id_usuario ASC");
        $stmt->execute();
        $usuarios = $stmt->fetchAll();

        $this->view('usuarios/reportes', [
            'usuario'  => $_SESSION['usuario'],
            'usuarios' => $usuarios,
        ]);
    }

    public function reportes(): void {
        $this->reporte();
    }
}
