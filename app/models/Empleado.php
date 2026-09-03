<?php 
//Llamamos a la conexión de la base de datos.
require_once __DIR__ . '/../core/Database.php';
//Creamos el modelo o clase llamada Empleado (SINGULAR).
class Empleado{
    // La propiedad $db guardará la conexión PDO.
    // Le decimos que solo puede ser de tipo PDO (tipado estricto).
    // modificador de acceso("private") significa que solo se puede usar dentro de esta clase.
    private PDO $db;

    //Al crear el modelo, obtenemos la conexion automaticamente.
    public function __construct(){
        // Database::getConnection() nos regresa la conexión PDO que creamos en core/Database.php.
        // Al guardarla en $this->db, cualquier método de esta clase puede usarla.
        $this->db = Database::getConnection();
    }
    //Creamos el modulo para llamar todo los datos de la tabla EMPLEADOS
    //public function getAll():array
    public function obtenerEmpleados():array {
        // variable $sql para almacenar
        $sql = "SELECT * FROM empleado
                INNER JOIN cargo 
                ON empleado.id_cargo = cargo.id_cargo 
                ORDER BY empleado.id_cargo DESC 
                "; 
        // statement = declaración
        $stmt = $this->db->prepare($sql);
        // Ejecutamos la declaración ($stmt)
        $stmt->execute();
        //Retornamos los datos
        return $stmt->fetchAll();
    }

    //Creamos un modulo para llamar a UN empleado por DNI.
    public function buscarPorDni(String $dni){
        // variable $sql para almacenar  
        $sql = "SELECT * FROM empleado WHERE dni = ?"; 
        // statement = declaración
        $stmt = $this->db->prepare($sql);
        // Ejecutamos la declaración ($stmt)
        $stmt->execute([$dni]);
        //Retornamos los datos -- fetch -> devuelve 1 valor - 1 dato
        return $stmt->fetch();
    }
    //Creamos un modulo para eliminar a un empleado por ID
    public function eliminarPorIdEmpleado(String $codigo){
        $sql = "DELETE FROM empleado WHERE id_empleado = ?"; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$codigo]);
        return $stmt;
    }

    //Creamos un modulo para guardar empleados
    public function guardarEmpleados(array $datos):array{
        //Validar DNI unico
         $sql1 = "SELECT id_empleado 
                 FROM empleado
                 WHERE dni = :dni";    
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->execute(['dni' => $datos['dni']]);
        if($stmt1->fetch()){
            return ['ok' => false, 'mensaje' => 'Ya existe un empleado con ese DNI'];
        }
        //Validar Correo Unico
        $sql2 = "SELECT id_empleado 
                 FROM empleado
                 WHERE correo = :correo";    
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute(['correo' => $datos['correo']]);
        if($stmt2->fetch()){
            return ['ok' => false, 'mensaje' => 'Ya existe un empleado con ese Correo'];
        }

        //INSERTAMOS LOS DATOS
        $sql = "INSERT INTO empleado
                (nombre,apellido,dni,celular,correo,id_cargo)
                VALUES(:n,:a,:d,:ce,:co,:i_c)";    
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'n'  =>  $datos['nombre'],
            'a' => $datos['apellido'],
            'd' => $datos['dni'],
            'ce' => $datos['celular'],
            'co' => $datos['correo'] ,
            'i_c' => $datos['id_cargo']
        ]);
        return ['ok'=>true,'mensaje'=>'Empleado Registrado'];
    }

    public function editarEmpleado(array $datos): array {
        $sql1 = "SELECT id_empleado FROM empleado WHERE dni = :dni AND id_empleado != :id";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->execute(['dni' => $datos['dni'], 'id' => $datos['id_empleado']]);
        if ($stmt1->fetch()) {
            return ['ok' => false, 'mensaje' => 'Ya existe otro empleado con ese DNI'];
        }

        $sql2 = "SELECT id_empleado FROM empleado WHERE correo = :correo AND id_empleado != :id";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute(['correo' => $datos['correo'], 'id' => $datos['id_empleado']]);
        if ($stmt2->fetch()) {
            return ['ok' => false, 'mensaje' => 'Ya existe otro empleado con ese Correo'];
        }

        $sql = "UPDATE empleado SET nombre=:n, apellido=:a, dni=:d, celular=:ce, correo=:co, id_cargo=:i_c WHERE id_empleado=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'n'   => $datos['nombre'],
            'a'   => $datos['apellido'],
            'd'   => $datos['dni'],
            'ce'  => $datos['celular'],
            'co'  => $datos['correo'],
            'i_c' => $datos['id_cargo'],
            'id'  => $datos['id_empleado']
        ]);
        return ['ok' => true, 'mensaje' => 'Empleado actualizado'];
    }

}

