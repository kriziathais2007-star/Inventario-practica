<?php
// Script de diagnóstico — elimínalo después de usarlo.
// Abre: http://localhost/employee-attendance-system-main/diagnostico.php

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

echo "<style>body{font-family:monospace;padding:20px;} .ok{color:green} .err{color:red} .warn{color:orange}</style>";
echo "<h2>Diagnóstico del sistema</h2>";

// 1. Constantes de BD
echo "<h3>1. Configuración (.env)</h3>";
echo "DB_HOST: <b>" . DB_HOST . "</b><br>";
echo "DB_PORT: <b>" . DB_PORT . "</b><br>";
echo "DB_NAME: <b>" . DB_NAME . "</b><br>";
echo "DB_USER: <b>" . DB_USER . "</b><br>";
echo "DB_PASS: <b>" . (DB_PASS ? '(tiene valor)' : '(vacío)') . "</b><br>";
echo "BASE_URL: <b>" . BASE_URL . "</b><br>";

// 2. Conexión a la BD
echo "<h3>2. Conexión a la base de datos</h3>";
try {
    $db = Database::getConnection();
    echo "<span class='ok'>✅ Conexión exitosa</span><br>";
} catch (Exception $e) {
    echo "<span class='err'>❌ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</span><br>";
    exit;
}

// 3. Tabla usuario
echo "<h3>3. Tabla 'usuario'</h3>";
try {
    $stmt = $db->query("SELECT id_usuario, nombre_usuario, roles, LEFT(clave,20) as clave_inicio FROM usuario");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($usuarios)) {
        echo "<span class='warn'>⚠️ La tabla existe pero no tiene usuarios.</span><br>";
    } else {
        echo "<table border='1' cellpadding='6'>";
        echo "<tr><th>id</th><th>nombre_usuario</th><th>roles</th><th>clave (primeros 20 chars)</th><th>¿Es hash bcrypt?</th></tr>";
        foreach ($usuarios as $u) {
            $esBcrypt = str_starts_with($u['clave_inicio'], '$2y$') || str_starts_with($u['clave_inicio'], '$2a$');
            echo "<tr>";
            echo "<td>{$u['id_usuario']}</td>";
            echo "<td><b>{$u['nombre_usuario']}</b></td>";
            echo "<td>{$u['roles']}</td>";
            echo "<td>{$u['clave_inicio']}...</td>";
            echo "<td>" . ($esBcrypt ? "<span class='ok'>Sí (hash)</span>" : "<span class='warn'>No (texto plano)</span>") . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<span class='err'>❌ Error: " . htmlspecialchars($e->getMessage()) . " — ¿Ejecutaste el SQL?</span><br>";
}

// 4. Probar password_verify con admin123
echo "<h3>4. Prueba de login manual</h3>";
try {
    $stmt = $db->prepare("SELECT clave FROM usuario WHERE nombre_usuario = 'admin'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo "<span class='err'>❌ No existe el usuario 'admin'.</span><br>";
    } else {
        $clave_bd = $row['clave'];
        $test     = 'admin123';
        $verifyHash  = password_verify($test, $clave_bd);
        $verifyPlain = ($test === $clave_bd);
        echo "Clave en BD: <b>" . htmlspecialchars(substr($clave_bd, 0, 30)) . "...</b><br>";
        echo "password_verify('admin123', hash): " . ($verifyHash  ? "<span class='ok'>✅ PASA</span>" : "<span class='err'>❌ FALLA</span>") . "<br>";
        echo "Comparación directa ('admin123' === clave): " . ($verifyPlain ? "<span class='ok'>✅ PASA</span>" : "<span class='err'>❌ FALLA</span>") . "<br>";

        if (!$verifyHash && !$verifyPlain) {
            echo "<br><span class='err'>❌ Ninguno pasa — la contraseña en la BD no corresponde a 'admin123'.</span><br>";
            echo "<b>→ Abre <a href='crear_admin.php'>crear_admin.php</a> para regenerar el usuario.</b><br>";
        } else {
            echo "<br><span class='ok'>✅ El login debería funcionar. El problema está en otra parte.</span><br>";
        }
    }
} catch (Exception $e) {
    echo "<span class='err'>Error: " . htmlspecialchars($e->getMessage()) . "</span><br>";
}

echo "<br><hr><p style='color:red'><b>⚠️ Elimina este archivo (diagnostico.php) después de usarlo.</b></p>";
