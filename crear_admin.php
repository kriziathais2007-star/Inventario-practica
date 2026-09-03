<?php
// ============================================================
// Script de un solo uso para crear el usuario superadmin.
// 1. Abre en el navegador: http://localhost/employee-attendance-system-main/crear_admin.php
// 2. Una vez creado, ELIMINA este archivo.
// ============================================================

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

$usuario    = 'admin';
$contrasena = 'admin123';
$rol        = 'superadmin';

try {
    $db = Database::getConnection();

    // Verificar si ya existe
    $stmt = $db->prepare("SELECT id_usuario FROM usuario WHERE nombre_usuario = ?");
    $stmt->execute([$usuario]);

    if ($stmt->fetch()) {
        // Ya existe → actualizamos la contraseña y el rol
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $db->prepare("UPDATE usuario SET clave = ?, roles = ? WHERE nombre_usuario = ?")
           ->execute([$hash, $rol, $usuario]);
        echo "<h2 style='color:green;font-family:sans-serif'>✅ Usuario '<b>{$usuario}</b>' actualizado correctamente.</h2>";
    } else {
        // No existe → lo creamos
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $db->prepare("INSERT INTO usuario (nombre_usuario, clave, roles) VALUES (?, ?, ?)")
           ->execute([$usuario, $hash, $rol]);
        echo "<h2 style='color:green;font-family:sans-serif'>✅ Usuario '<b>{$usuario}</b>' creado correctamente.</h2>";
    }

    echo "<p style='font-family:sans-serif'>Contraseña: <b>{$contrasena}</b> &nbsp;|&nbsp; Rol: <b>{$rol}</b></p>";
    echo "<p style='font-family:sans-serif;color:red'><b>⚠️ Elimina este archivo ahora.</b></p>";
    echo "<p style='font-family:sans-serif'><a href='http://localhost/employee-attendance-system-main/login'>→ Ir al login</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color:red;font-family:sans-serif'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</h2>";
}
