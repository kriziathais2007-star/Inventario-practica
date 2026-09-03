<?php
require_once __DIR__ . '/../core/Controller.php';

// Controlador del módulo de asistencias.
class AsistenciasController extends Controller {

    // Página pública: los empleados marcan asistencia sin login.
    // Ejemplo: /asistencias
    public function index(): void {
        $this->view('asistencias/index');
    }

    public function buscar(): void{
        require_once __DIR__ . '/../models/Empleado.php'; //llamamos al models
        $dni_variable = $_POST['dni']; //Almacenamos en un variable el DNI
        $empleado = new Empleado(); //Instanciamos la clase o objeto
        //Utilizamos la funcion que creamos en models/empleados
        $resultado = $empleado->buscarPorDni($dni_variable); 
        // echo "AQUI ESTA MI DNI ".$dni_variable;
        //print_r($resultado);

        // === CONVERTIRMOS AL FORMATO DE ARRAY A JSON ===
        // Le decimos al navegador que la respuesta sea JSON
        header('Content-Type: application/json');
        // Consultamos si el "$resultado" haya encontrado a un empleado
        if($resultado){
            echo json_encode(['encontrado'=>true, 'empleado' => $resultado]);
        }else{
            echo json_encode(['encontrado'=>false]);
        }
    }

    public function registradito(): void{
        require_once __DIR__ . '/../models/Asistencia.php'; //llamamos al models
        $idEmpleado_variable = $_POST['id_empleadito']; //Almacenamos en un variable el DNI
        $asistencia = new Asistencia(); //Instanciamos la clase o objeto
        $tipo = $asistencia->registrar((int)$idEmpleado_variable);
        header('Content-Type: application/json');
        echo json_encode(['registrado' => true, 'tipo' => $tipo]);
    }

    // Reporte de asistencias: requiere sesión activa.
    // Ejemplo: /asistencias/reporte
    public function reporte(): void { //esto de aqui es un funcion reporte() pero yo estoy creando no se vincula con ningun archivo
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->view('asistencias/reportes', [
            'usuario' => $_SESSION['usuario'],
        ]);
    }

    // Alias para que funcione también /asistencias/reportes
    public function reportes(): void {  //este reportes se vincula con el archivo asistencias/reportes.php (tienen que ser el mismo nombre) y lo que hace es llamar a la funcion reporte() para mostrar la vista reportes.php
        $this->reporte();
    }

    // Ejemplo: /asistencias/ejemplo_hoja
    public function ejemplo_hoja(): void {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->view('asistencias/ejemplo_hoja', [
            'usuario' => $_SESSION['usuario'],
        ]);
    }
}
