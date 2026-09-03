<?php
Class Database{ // Clase para conectarse con la base de datos.
    //Guardamos la conexión con el modificador de acceso STATIC
    // El ? significa que la variable puede ser de tipo PDO o NULL.
    private static ?PDO $connection = null;
    // Este método devuelve la conexión a la base de datos
    public static function getConnection(): PDO{
        //Si en caso no encuentra la conexion     
         if(self::$connection === null){
            // Armamos la conexión con los datos de .ENV
            //$dns es una variable
            $dns="mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8mb4"; 
            // CREAMOS LA CONEXION DE PDO
            self::$connection = new PDO($dns, DB_USER, DB_PASS, [
                //Si hay error, lanza una excepción en vez de fallar.
                  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                //Los resultados de las consultas vienen como arrays asociativas.
                  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
         } //Creamos un retorno de valores
        return self::$connection;
    }
}