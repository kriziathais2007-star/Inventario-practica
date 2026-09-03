<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Login.php';

class LoginController extends Controller {

    // GET/POST /login
    public function index(): void {
        if (isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['user'] ?? '');
            $clave   = trim($_POST['pass'] ?? '');

            if (empty($usuario) || empty($clave)) {
                $error = "Completa todos los campos, por favor.";
            } else {
                $resultado = (new Login())->login($usuario, $clave);
                if ($resultado) {
                    $_SESSION['usuario'] = $resultado;
                    header('Location: ' . BASE_URL . '/dashboard');
                    exit;
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            }
        }

        $this->view('auth/login', ['error' => $error, 'modo' => 'login']);
    }

    // GET/POST /login/registro — SOLO accesible para superadmin logueado
    public function registro(): void {
        // Solo un superadmin puede crear cuentas
        if (($_SESSION['usuario']['roles'] ?? '') !== 'superadmin') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario        = trim($_POST['user']  ?? '');
            $clave          = trim($_POST['pass']  ?? '');
            $claveConfirmar = trim($_POST['pass2'] ?? '');
            $rol            = trim($_POST['rol']   ?? 'admin');

            // Validar que el rol sea uno permitido
            if (!in_array($rol, ['admin', 'superadmin'])) {
                $rol = 'admin';
            }

            if (empty($usuario) || empty($clave) || empty($claveConfirmar)) {
                $error = "Completa todos los campos, por favor.";
            } elseif (strlen($usuario) < 3) {
                $error = "El nombre de usuario debe tener al menos 3 caracteres.";
            } elseif (strlen($clave) < 6) {
                $error = "La contraseña debe tener al menos 6 caracteres.";
            } elseif ($clave !== $claveConfirmar) {
                $error = "Las contraseñas no coinciden.";
            } else {
                $resultado = (new Login())->registrar($usuario, $clave, $rol);
                if ($resultado['ok']) {
                    header('Location: ' . BASE_URL . '/usuarios');
                    exit;
                } else {
                    $error = $resultado['mensaje'];
                }
            }
        }

        $this->view('auth/login', ['error' => $error, 'modo' => 'registro']);
    }
}
