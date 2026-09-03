<?php
define("TITLE_BUSINESS", "INVENTARIO");

// Leemos el archivo .env que está en la raíz del proyecto.
$envFile = dirname(__DIR__, 2) . '/.env';
if (file_exists($envFile)) {
    // Recorremos cada línea del archivo .env, omitiendo saltos (\n) y líneas vacías.
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        // Ignoramos las líneas que son comentarios (empiezan con #)
        if (str_starts_with(trim($line), '#')) continue;
        // Separamos la clave del valor (ej: DB_HOST=localhost)
        [$key, $value] = explode("=", $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// PHP no puede leer archivos .env de forma nativa, por eso los leemos manualmente arriba.
define("DB_HOST", $_ENV['DB_HOST']     ?? 'localhost');
define("DB_PORT", $_ENV['DB_PORT']     ?? '3306');
define("DB_NAME", $_ENV['DB_DATABASE'] ?? '');
define("DB_USER", $_ENV['DB_USERNAME'] ?? 'root');
define("DB_PASS", $_ENV['DB_PASSWORD'] ?? '');

// URL base del proyecto. Se usa para hacer redirecciones correctas con header().
// Debe coincidir con la variable APP_URL de tu archivo .env
define("BASE_URL", $_ENV['APP_URL'] ?? 'http://localhost');
