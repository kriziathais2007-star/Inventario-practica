<?php

// Controller es la clase base de la que heredan todos los controladores del sistema.
// Al heredar de esta clase, cada controlador obtiene herramientas comunes
// sin tener que escribirlas de nuevo. Por ejemplo, el método view() para cargar vistas.
// Principio que aplica: DRY (Don't Repeat Yourself) — no repitas código.
class Controller {

    // Redirige al dashboard si el usuario no es superadmin.
    // Úsalo al inicio de cualquier método que solo deba ver el superadmin.
    protected function soloSuperAdmin(): void {
        if (($_SESSION['usuario']['roles'] ?? '') !== 'superadmin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    // Carga una vista y le pasa datos desde el controlador.
    // $vista: ruta relativa a app/views/ (ejemplo: 'auth/login')
    // $datos: array asociativo con variables que la vista puede usar
    protected function view(string $vista, array $datos = []): void {
        // extract() convierte cada clave del array en una variable independiente.
        // Ejemplo: ['error' => 'texto'] crea la variable $error = 'texto'
        // Así la vista puede usar $error directamente en vez de $datos['error'].
        extract($datos);

        // Cargamos el archivo de la vista usando la ruta absoluta.
        // __DIR__ apunta a app/core/, luego subimos un nivel con /../ para llegar a app/views/
        require_once __DIR__ . '/../views/' . $vista . '.php';
    }
}
