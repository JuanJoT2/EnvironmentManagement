<?php
// db.php

class Database {
    public static function connect() {
        // Usa las credenciales proporcionadas por tu hosting
        $db = new mysqli("localhost", "u357463700_ambientes", "CDM2025fabricasoftware", "u357463700_reportes");
        
        // Verifica si hubo un error en la conexión
        if ($db->connect_error) {
            die("Error de conexión: " . $db->connect_error);
        }
        
        // Configura la codificación de caracteres
        $db->query("SET NAMES 'utf8mb4'");
        
        return $db;
    }
}
?>
