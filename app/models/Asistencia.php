<?php
require_once __DIR__ . '/../core/Database.php';
// Zona horaria de Perú
date_default_timezone_set('America/Lima');

class Asistencia
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Registra la asistencia del empleado.
    // Si ya tiene un registro de ENTRADA hoy sin salida, registra la SALIDA.
    // Si ya tiene entrada Y salida hoy, no hace nada y devuelve 'ya_completo'.
    // Devuelve: 'entrada' | 'salida' | 'ya_completo'
    public function registrar(int $id_empleado): string
    {
        // Buscamos si ya existe un registro de hoy para este empleado
        $sqlBuscar = "SELECT id_asistencia, hora_salida
                      FROM asistencia
                      WHERE id_empleado = ? AND fecha = CURDATE()
                      ORDER BY id_asistencia DESC
                      LIMIT 1";
        $stmt = $this->db->prepare($sqlBuscar);
        $stmt->execute([$id_empleado]);
        $registro = $stmt->fetch();

        if (!$registro) {
            // No hay registro hoy → INSERT de entrada
            $sqlInsert = "INSERT INTO asistencia (fecha, hora_entrada, hora_salida, estado, id_empleado)
                          VALUES (CURDATE(), NOW(), NULL, 'asistio', ?)";
            $this->db->prepare($sqlInsert)->execute([$id_empleado]);
            return 'entrada';
        }

        if ($registro['hora_salida'] === null) {
            // Tiene entrada pero no salida → registrar salida
            $sqlSalida = "UPDATE asistencia SET hora_salida = NOW() WHERE id_asistencia = ?";
            $this->db->prepare($sqlSalida)->execute([$registro['id_asistencia']]);
            return 'salida';
        }

        // Ya tiene entrada y salida hoy
        return 'ya_completo';
    }
}
