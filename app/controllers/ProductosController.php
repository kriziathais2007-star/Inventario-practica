<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';

class ProductosController extends Controller {

    public function index(): void {
        $this->reporte();
    }

    // Listar todos los productos
    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->soloSuperAdmin();

        $modelo    = new Producto();
        $productos = $modelo->obtenerProductos();

        $this->view('productos/reportes', [
            'usuario'   => $_SESSION['usuario'],
            'productos' => $productos,
        ]);
    }

    public function reportes(): void {
        $this->reporte();
    }

    // Vista de registro de nuevo producto
    public function registro(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->soloSuperAdmin();

        $this->view('productos/registro', [
            'usuario' => $_SESSION['usuario'],
            'error'   => null,
            'datos'   => [],
        ]);
    }

    // Guardar nuevo producto (POST)
    public function guardar(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->soloSuperAdmin();

        $datos = [
            'codigo'          => trim($_POST['codigo']          ?? ''),
            'nombre_producto' => trim($_POST['nombre_producto'] ?? ''),
            'descripcion'     => trim($_POST['descripcion']     ?? ''),
            'stock'           => (int)($_POST['stock']          ?? 0),
            'precio'          => (float)($_POST['precio']       ?? 0),
            'imagen'          => null,
        ];

        // Validación básica
        if (empty($datos['codigo']) || empty($datos['nombre_producto'])) {
            $this->view('productos/registro', [
                'usuario' => $_SESSION['usuario'],
                'error'   => 'El código y el nombre son obligatorios.',
                'datos'   => $datos,
            ]);
            return;
        }

        // Procesar imagen si se subió
        if (!empty($_FILES['imagen']['name'])) {
            $resultado_img = $this->subirImagen($_FILES['imagen']);
            if (!$resultado_img['ok']) {
                $this->view('productos/registro', [
                    'usuario' => $_SESSION['usuario'],
                    'error'   => $resultado_img['mensaje'],
                    'datos'   => $datos,
                ]);
                return;
            }
            $datos['imagen'] = $resultado_img['nombre'];
        }

        $modelo    = new Producto();
        $resultado = $modelo->guardar($datos);

        if (!$resultado['ok']) {
            // Eliminar imagen subida si el INSERT falló
            if ($datos['imagen']) {
                $ruta = __DIR__ . '/../../public/image/productos/' . $datos['imagen'];
                if (file_exists($ruta)) unlink($ruta);
            }
            $this->view('productos/registro', [
                'usuario' => $_SESSION['usuario'],
                'error'   => $resultado['mensaje'],
                'datos'   => $datos,
            ]);
            return;
        }

        header('Location: ' . BASE_URL . '/productos');
        exit;
    }

    // Editar producto (POST AJAX)
    public function editar(): void {
        $datos = [
            'id_producto'     => (int)($_POST['id_producto']      ?? 0),
            'codigo'          => trim($_POST['codigo']            ?? ''),
            'nombre_producto' => trim($_POST['nombre_producto']   ?? ''),
            'descripcion'     => trim($_POST['descripcion']       ?? ''),
            'stock'           => (int)($_POST['stock']            ?? 0),
            'precio'          => (float)($_POST['precio']         ?? 0),
            'imagen'          => null,
        ];

        // Procesar nueva imagen si se subió
        if (!empty($_FILES['imagen']['name'])) {
            $resultado_img = $this->subirImagen($_FILES['imagen']);
            if ($resultado_img['ok']) {
                // Borrar imagen anterior si existía
                $imgAnterior = trim($_POST['imagen_actual'] ?? '');
                if ($imgAnterior) {
                    $ruta = __DIR__ . '/../../public/image/productos/' . $imgAnterior;
                    if (file_exists($ruta)) unlink($ruta);
                }
                $datos['imagen'] = $resultado_img['nombre'];
            }
        }

        $modelo    = new Producto();
        $resultado = $modelo->editar($datos);

        header('Content-Type: application/json');
        echo json_encode($resultado);
    }

    // ── Subir imagen (uso interno) ───────────────────────────
    private function subirImagen(array $file): array {
        $permitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxBytes   = 2 * 1024 * 1024; // 2 MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'mensaje' => 'Error al subir el archivo.'];
        }
        if ($file['size'] > $maxBytes) {
            return ['ok' => false, 'mensaje' => 'La imagen no puede superar 2 MB.'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $permitidos)) {
            return ['ok' => false, 'mensaje' => 'Solo se permiten imágenes JPG, PNG, WEBP o GIF.'];
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombre   = uniqid('prod_', true) . '.' . strtolower($ext);
        $destino  = __DIR__ . '/../../public/image/productos/' . $nombre;

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            return ['ok' => false, 'mensaje' => 'No se pudo guardar la imagen en el servidor.'];
        }

        return ['ok' => true, 'nombre' => $nombre];
    }

    // Guardar nuevo producto vía AJAX (POST, responde JSON)
    public function guardarAjax(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'mensaje' => 'Sesión expirada.']);
            return;
        }
        if (($_SESSION['usuario']['roles'] ?? '') !== 'superadmin') {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'mensaje' => 'Sin permisos.']);
            return;
        }

        $datos = [
            'codigo'          => trim($_POST['codigo']          ?? ''),
            'nombre_producto' => trim($_POST['nombre_producto'] ?? ''),
            'descripcion'     => trim($_POST['descripcion']     ?? ''),
            'stock'           => (int)($_POST['stock']          ?? 0),
            'precio'          => (float)($_POST['precio']       ?? 0),
            'imagen'          => null,
        ];

        if (empty($datos['codigo']) || empty($datos['nombre_producto'])) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'mensaje' => 'El código y el nombre son obligatorios.']);
            return;
        }

        if (!empty($_FILES['imagen']['name'])) {
            $resultado_img = $this->subirImagen($_FILES['imagen']);
            if (!$resultado_img['ok']) {
                header('Content-Type: application/json');
                echo json_encode($resultado_img);
                return;
            }
            $datos['imagen'] = $resultado_img['nombre'];
        }

        $resultado = (new Producto())->guardar($datos);

        if (!$resultado['ok'] && $datos['imagen']) {
            $ruta = __DIR__ . '/../../public/image/productos/' . $datos['imagen'];
            if (file_exists($ruta)) unlink($ruta);
        }

        header('Content-Type: application/json');
        echo json_encode($resultado);
    }

    // Eliminar producto (POST AJAX)
    public function eliminar(): void {
        $id = (int)($_POST['id_producto'] ?? 0);
        (new Producto())->eliminar($id);

        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
    }
}
