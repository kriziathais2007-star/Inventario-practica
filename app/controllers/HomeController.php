<?php
require_once __DIR__ . '/../core/Controller.php';

// La raíz del proyecto redirige directo al login.
// Si ya hay sesión activa, el LoginController se encarga de mandar al dashboard.
class HomeController extends Controller {

    public function index(): void {
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
