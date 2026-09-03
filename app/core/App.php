<?php
require_once __DIR__ . '/Router.php';
// App es el punto de arranque de toda la aplicación.
// Su único trabajo es preparar el entorno (sesiones) y lanzar el Router.
// Es lo primero que se ejecuta después de que app/index.php carga la configuración.
class App {

    // Inicia la sesión y arranca el Router.
    // Se llama una sola vez desde app/index.php.
    public function run(): void {
        // Iniciamos las sesiones de PHP aquí para que estén disponibles
        // en todos los controladores sin tener que llamar session_start() en cada uno.
        session_start();

        // Creamos el Router y le decimos que procese la petición actual.
        $router = new Router();
        $router->run();
    }
}
