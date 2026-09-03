<?php
require_once __DIR__ . '/../core/Controller.php';
// Controlador de la vistas Cargos. --- Solo accesible para usuarios que hayan iniciado sesión.
class CargosController extends Controller {
    //Creamos la funcion por defecto para que me redireccione al INDEX -- o a la pagina que deseo por defecto.
    public function index(): void{
        $this->reporte(); //funcion de la vistas reportes();
    } 
    //Este es la funcion que pusiste arriba en $this->reporte();
    public function reporte(): void {
        // Si $_SESSION['usuario'] no existe, lo mandamos al login.
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->soloSuperAdmin();
        // Enviamos los datos a la vista.
        $this->view('cargos/reportes', [
        'usuario' => $_SESSION['usuario'],
        ]);
    }
    public function reportes(): void {
        // Reutilizamos la implementación de reporte() para mantener DRY.
        $this->reporte();
    }
}
?>