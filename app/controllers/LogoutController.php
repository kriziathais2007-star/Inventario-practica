<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Controller.php';

// Controlador para cerrar la sesión del usuario.
class LogoutController extends Controller {

    // Se ejecuta cuando el usuario entra a /logout
    public function index(): void {
        // Eliminamos todos los datos guardados en $_SESSION
        session_destroy();

        // Redirigimos al login
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
