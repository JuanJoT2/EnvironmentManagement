<<<<<<< HEAD
<?php

$conexion = new mysqli("localhost", "root", "", "reportesambientes");
//$conexion = new mysqli("localhost", "u357463700_admin_ambiente", "5BeEH;pI", "u357463700_repoambientes");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!$conexion->set_charset("utf8mb4")) {
    die("Error al configurar el conjunto de caracteres: " . $conexion->error);
=======
<?php 

class Conexion{
    public function getConexion(){
        $host = "localhost"; 
        $dbName = "reportesambientes";
        $user = "root";
        $pass = "";
        $conexion = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

        return $conexion;
    }
>>>>>>> 7ff67ecefcebbca162f57bd3d395b4ace2e044d5
}
?>
