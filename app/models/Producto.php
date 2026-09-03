<?php
require_once __DIR__ . '/../core/Database.php';

class Producto {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->asegurarColumnaImagen();
    }

    // Agrega la columna 'imagen' si no existe — se ejecuta una sola vez y no hace nada si ya está.
    private function asegurarColumnaImagen(): void {
        try {
            $existe = $this->db->query(
                "SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME   = 'producto'
                   AND COLUMN_NAME  = 'imagen'"
            )->fetchColumn();

            if (!$existe) {
                $this->db->exec(
                    "ALTER TABLE producto ADD COLUMN imagen VARCHAR(300) DEFAULT NULL"
                );
            }
        } catch (Exception $e) {
            // Silencioso — si falla la verificación no bloqueamos el sistema
        }
    }

    // Todos los productos
    public function obtenerProductos(): array {
        $stmt = $this->db->prepare("SELECT * FROM producto ORDER BY nombre_producto ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar por código de barras
    public function buscarPorCodigo(string $codigo): array|false {
        $stmt = $this->db->prepare("SELECT * FROM producto WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetch();
    }

    // Guardar nuevo producto
    public function guardar(array $datos): array {
        $stmt = $this->db->prepare("SELECT id_producto FROM producto WHERE codigo = ?");
        $stmt->execute([$datos['codigo']]);
        if ($stmt->fetch()) {
            return ['ok' => false, 'mensaje' => 'Ya existe un producto con ese código.'];
        }

        $stmt = $this->db->prepare(
            "INSERT INTO producto (codigo, nombre_producto, descripcion, stock, precio, imagen)
             VALUES (:codigo, :nombre, :descripcion, :stock, :precio, :imagen)"
        );
        $stmt->execute([
            'codigo'      => $datos['codigo'],
            'nombre'      => $datos['nombre_producto'],
            'descripcion' => $datos['descripcion'] ?? '',
            'stock'       => (int)($datos['stock'] ?? 0),
            'precio'      => (float)($datos['precio'] ?? 0),
            'imagen'      => $datos['imagen'] ?? null,
        ]);

        return ['ok' => true, 'mensaje' => 'Producto registrado correctamente.'];
    }

    // Editar producto
    public function editar(array $datos): array {
        // Verificar código duplicado en otro producto
        $stmt = $this->db->prepare(
            "SELECT id_producto FROM producto WHERE codigo = ? AND id_producto != ?"
        );
        $stmt->execute([$datos['codigo'], $datos['id_producto']]);
        if ($stmt->fetch()) {
            return ['ok' => false, 'mensaje' => 'Ese código ya lo usa otro producto.'];
        }

        if (!empty($datos['imagen'])) {
            // Hay imagen nueva — actualizar todo incluyendo imagen
            $sql    = "UPDATE producto
                       SET codigo=:codigo, nombre_producto=:nombre, descripcion=:descripcion,
                           stock=:stock, precio=:precio, imagen=:imagen
                       WHERE id_producto=:id";
            $params = [
                'codigo'      => $datos['codigo'],
                'nombre'      => $datos['nombre_producto'],
                'descripcion' => $datos['descripcion'] ?? '',
                'stock'       => (int)$datos['stock'],
                'precio'      => (float)$datos['precio'],
                'imagen'      => $datos['imagen'],
                'id'          => $datos['id_producto'],
            ];
        } else {
            // Sin imagen nueva — dejar la que ya tenía
            $sql    = "UPDATE producto
                       SET codigo=:codigo, nombre_producto=:nombre, descripcion=:descripcion,
                           stock=:stock, precio=:precio
                       WHERE id_producto=:id";
            $params = [
                'codigo'      => $datos['codigo'],
                'nombre'      => $datos['nombre_producto'],
                'descripcion' => $datos['descripcion'] ?? '',
                'stock'       => (int)$datos['stock'],
                'precio'      => (float)$datos['precio'],
                'id'          => $datos['id_producto'],
            ];
        }

        $this->db->prepare($sql)->execute($params);
        return ['ok' => true, 'mensaje' => 'Producto actualizado.'];
    }

    // Eliminar producto
    public function eliminar(int $id): void {
        // Obtener imagen antes de eliminar para borrar el archivo
        $stmt = $this->db->prepare("SELECT imagen FROM producto WHERE id_producto = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && !empty($row['imagen'])) {
            $ruta = __DIR__ . '/../../public/image/productos/' . $row['imagen'];
            if (file_exists($ruta)) unlink($ruta);
        }
        $this->db->prepare("DELETE FROM producto WHERE id_producto = ?")->execute([$id]);
    }

    // Aumentar stock (entrada)
    public function aumentarStock(int $id_producto, int $cantidad): array|false {
        $stmt = $this->db->prepare("SELECT * FROM producto WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $producto = $stmt->fetch();
        if (!$producto) return false;

        $nuevoStock = $producto['stock'] + $cantidad;
        $this->db->prepare("UPDATE producto SET stock = ? WHERE id_producto = ?")
                 ->execute([$nuevoStock, $id_producto]);

        return array_merge($producto, ['stock' => $nuevoStock]);
    }

    // Disminuir stock (venta/salida)
    public function disminuirStock(int $id_producto, int $cantidad = 1): array {
        $stmt = $this->db->prepare("SELECT * FROM producto WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $producto = $stmt->fetch();

        if (!$producto) {
            return ['ok' => false, 'mensaje' => 'Producto no encontrado.'];
        }
        if ($producto['stock'] < $cantidad) {
            return ['ok' => false, 'mensaje' => 'Stock insuficiente.', 'producto' => $producto];
        }

        $nuevoStock = $producto['stock'] - $cantidad;
        $this->db->prepare("UPDATE producto SET stock = ? WHERE id_producto = ?")
                 ->execute([$nuevoStock, $id_producto]);

        return ['ok' => true, 'producto' => array_merge($producto, ['stock' => $nuevoStock])];
    }
}
