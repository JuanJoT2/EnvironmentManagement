<?php

// Usa las credenciales proporcionadas por tu hosting
$conexion = new mysqli("localhost", "u357463700_ambientes", "CDM2025fabricasoftware", "u357463700_reportes");

// Verifica si hubo un error en la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configura el conjunto de caracteres para la conexión
if (!$conexion->set_charset("utf8mb4")) {
    die("Error al configurar el conjunto de caracteres: " . $conexion->error);
}

?>
