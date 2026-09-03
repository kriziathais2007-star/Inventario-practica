<?php

// El Router es el encargado de leer la URL del navegador y decidir
// qué controlador y qué método ejecutar.
// Es como un recepcionista: recibe la petición y la manda al área correcta.
// Sin el Router, tendríamos que crear un archivo PHP por cada página del sistema.
class Router {

    // Este método se llama una sola vez al inicio de cada petición.
    // Lee la URL, la analiza y ejecuta el controlador correspondiente.
    public function run(): void {
        // Leemos la URL que el .htaccess nos pasa como parámetro ?url=
        // Si no hay nada (raíz del proyecto), usamos cadena vacía.
        $url = $_GET['url'] ?? '';

        // Limpiamos la URL: quitamos caracteres peligrosos y las barras / del inicio y final.
        // Ejemplo: "  /empleados/ver/5/  " queda como "empleados/ver/5"
        $url = filter_var(trim($url, '/'), FILTER_SANITIZE_URL);

        // Dividimos la URL por el caracter / para obtener sus partes.
        // Ejemplo: "empleados/ver/5" se convierte en ['empleados', 'ver', '5']
        $partes = explode('/', $url);

        // SEGMENTO 0: nombre del controlador
        // Si no hay nada en la URL, usamos HomeController por defecto.
        // ucfirst() pone en mayúscula la primera letra: "login" -> "Login"
        // strtolower() convierte todo a minúscula primero, por si acaso.
        $nombreController = !empty($partes[0])
            ? ucfirst(strtolower($partes[0])) . 'Controller'
            : 'HomeController';

        // SEGMENTO 1: nombre del método a ejecutar
        // Si no hay segundo segmento, ejecutamos "index" por defecto.
        $metodo = !empty($partes[1]) ? $partes[1] : 'index';

        // Compatibilidad temporal: si alguien usa 'reportes.php' en la URL,
        // lo convertimos en 'reportes'. Esto no es la forma ideal, pero evita
        // errores en caso de enlaces antiguos.
        $metodo = preg_replace('/\.php$/i', '', $metodo);

        // SEGMENTOS 2 en adelante: parámetros opcionales
        // Ejemplo: "empleados/ver/5" -> $params = ['5']
        $params = array_slice($partes, 2);

        // Armamos la ruta al archivo del controlador
        $archivo = __DIR__ . '/../controllers/' . $nombreController . '.php';

        // Verificamos que el archivo exista antes de cargarlo
        if (!file_exists($archivo)) {
            $this->abortar(404);
            return;
        }

        require_once $archivo;

        // Verificamos que la clase exista dentro del archivo
        if (!class_exists($nombreController)) {
            $this->abortar(404);
            return;
        }

        // Creamos una instancia del controlador
        $controller = new $nombreController();

        // Verificamos que el método exista dentro de la clase
        if (!method_exists($controller, $metodo)) {
            $this->abortar(404);
            return;
        }

        // Ejecutamos el método y le pasamos los parámetros (si hay).
        // El operador ... (spread) desempaca el array como argumentos separados.
        $controller->$metodo(...$params);
    }

    // Método que se llama cuando algo no se encuentra.
    // Establece el código HTTP correcto y muestra un mensaje simple.
    private function abortar(int $codigo): void {
        http_response_code($codigo);
        echo "<h1>Error $codigo - Página no encontrada</h1>";
    }
}
