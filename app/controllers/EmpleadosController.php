<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Empleado.php';

// Controlador para el módulo de empleados.
class EmpleadosController extends Controller {

    public function index(): void {
        $this->reporte();
    }

    public function reporte(): void {
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
        $this->soloSuperAdmin();

        // Cargamos el modelo y obtenemos los datos de empleados.
        $modelo = new Empleado();
        $variable_empleados = $modelo->obtenerEmpleados();

        require_once __DIR__ . '/../models/Cargo.php';
        $cargo = new Cargo();
        $variable_cargo = $cargo->obtenerCargos();

        // Enviamos los datos a la vista.
        $this->view('empleados/reportes', [
            'usuario'     => $_SESSION['usuario'],
            'empleados'   => $variable_empleados,
            'lista_cargo' => $variable_cargo
        ]);
    }

    public function reportes(): void {
        $this->reporte();
    }

    public function eliminar_empleado():void{
        require_once __DIR__ . '/../models/Empleado.php'; //llamamos al models
        $idEmpleado = $_POST['id_empleadito'];
        $empleado = new Empleado(); //Instanciamos la clase o objeto
        $resultado = $empleado->eliminarPorIdEmpleado($idEmpleado); 
        //echo "ID__" . $idEmpleado;
        header('Content-Type: application/json');
        if($resultado){ //Si es verdadero, ejecuta esto
            echo json_encode(['eliminar'=>true]);
        }else{ //Si es falso, ejectura esto
            echo json_encode(['eliminar'=>false]);
        }
    } 

    //EmpleadosController -> Mostrar la vista de REGISTRO+
    public function registro(): void {
        require_once __DIR__ . '/../models/Cargo.php';
        if (!isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "/login");
            exit();
        }
        //Instanciar el objeto del MODELO CARGO
        $cargo = new Cargo();
        $variable_cargo = $cargo->obtenerCargos();
        // Enviamos los datos a la vista.
        $this->view('empleados/registro', [
            'usuario' => $_SESSION['usuario'],
            'lista_cargo' => $variable_cargo
        ]);
    }

    public function editar_empleado(): void {
        $datos = [
            'id_empleado' => $_POST['id_empleado'],
            'nombre'      => $_POST['nombre'],
            'apellido'    => $_POST['apellido'],
            'dni'         => $_POST['dni'],
            'celular'     => $_POST['celular'],
            'correo'      => $_POST['correo'],
            'id_cargo'    => $_POST['id_cargo']
        ];
        $empleado = new Empleado();
        $resultado = $empleado->editarEmpleado($datos);
        header('Content-Type: application/json');
        echo json_encode($resultado);
    }

    public function guardar():void{
        //Preparar o convertir los datos en array
        $datos = [
            'nombre'  =>  $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'dni' => $_POST['dni'],
            'celular' => $_POST['celular'],
            'correo' => $_POST['correo'],
            'id_cargo' => $_POST['cargo']
        ];
        //Instanciar o crear el objeto
        $empleado = new Empleado();
        $resultado = $empleado->guardarEmpleados($datos);
        if($resultado['ok'] == false){  //si es Verdadero
            header('Location: '. BASE_URL .'/empleados/reporte');
        }else{
            header('Location: '. BASE_URL .'/empleados/registro');
        }
    }
    
}
