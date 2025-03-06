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
}
?>
