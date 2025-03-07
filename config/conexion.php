<?php

$conexion = new mysqli("localhost", "root", "", "reportesambientes");
//$conexion = new mysqli("localhost", "u357463700_admin_ambiente", "5BeEH;pI", "u357463700_repoambientes");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!$conexion->set_charset("utf8mb4")) {
    die("Error al configurar el conjunto de caracteres: " . $conexion->error);
}
?>
