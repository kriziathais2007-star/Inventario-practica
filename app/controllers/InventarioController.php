<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';

class InventarioController extends Controller {

    public function index(): void {
        $this->entrada();
    }

    // Pantalla de ENTRADA de stock (escanear para sumar stock)
    public function entrada(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->view('inventario/entrada', ['usuario' => $_SESSION['usuario']]);
    }

    // Pantalla de SALIDA de stock / ventas (escanear para descontar)
    public function salida(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->view('inventario/salida', ['usuario' => $_SESSION['usuario']]);
    }

    // AJAX: buscar producto por código de barras
    public function buscar(): void {
        $codigo  = trim($_POST['codigo'] ?? '');
        $modelo  = new Producto();
        $product = $modelo->buscarPorCodigo($codigo);

        header('Content-Type: application/json');
        if ($product) {
            echo json_encode(['encontrado' => true, 'producto' => $product]);
        } else {
            echo json_encode(['encontrado' => false]);
        }
    }

    // AJAX: sumar stock al producto escaneado
    public function agregarStock(): void {
        $id_producto = (int)($_POST['id_producto'] ?? 0);
        $cantidad    = (int)($_POST['cantidad']    ?? 1);

        $modelo    = new Producto();
        $producto  = $modelo->aumentarStock($id_producto, $cantidad);

        header('Content-Type: application/json');
        if ($producto) {
            echo json_encode(['ok' => true, 'producto' => $producto]);
        } else {
            echo json_encode(['ok' => false, 'mensaje' => 'Producto no encontrado.']);
        }
    }

    // AJAX: restar stock (venta)
    public function vender(): void {
        $id_producto = (int)($_POST['id_producto'] ?? 0);
        $cantidad    = (int)($_POST['cantidad']    ?? 1);

        $modelo    = new Producto();
        $resultado = $modelo->disminuirStock($id_producto, $cantidad);

        header('Content-Type: application/json');
        echo json_encode($resultado);
    }

    // Vista de reporte de productos (requiere sesión)
    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $modelo    = new Producto();
        $productos = $modelo->obtenerProductos();

        $this->view('inventario/reporte', [
            'usuario'   => $_SESSION['usuario'],
            'productos' => $productos,
        ]);
    }
}
