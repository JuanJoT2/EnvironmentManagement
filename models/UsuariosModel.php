<?php

require_once 'config/db.php';

class UsuariosModel{

    public function guardarUsuario($nombres, $apellidos, $clave, $correo, $rol) {
        $conn = Database::connect();
    
        // Encriptar la clave con bcrypt
        $claveHash = password_hash($clave, PASSWORD_BCRYPT);
    
        $sql = "INSERT INTO t_usuarios (Nombres, Apellidos, Clave, Correo, Rol)
                VALUES (?, ?, ?, ?, ?)";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombres, $apellidos, $claveHash, $correo, $rol);
    
        return $stmt->execute();  // Retorna true o false
    }
    
    public function modificarUsuario($id, $nombres, $apellidos, $clave, $correo, $rol) {
        $conn = Database::connect();
    
        // Verificar si la clave fue enviada
        if (!empty($clave)) {
            $claveEncriptada = password_hash($clave, PASSWORD_BCRYPT);
        } else {
            // Obtener la clave actual si no se cambió
            $sqlClave = "SELECT Clave FROM t_usuarios WHERE Id_usuario = ?";
            $stmtClave = $conn->prepare($sqlClave);
            $stmtClave->bind_param("i", $id);
            $stmtClave->execute();
            $result = $stmtClave->get_result();
            $row = $result->fetch_assoc();
            $claveEncriptada = $row['Clave']; // Mantiene la clave actual
        }
    
        // Actualizar datos
        $sql = "UPDATE t_usuarios SET Nombres = ?, Apellidos = ?, Clave = ?, Correo = ?, Rol = ? WHERE Id_usuario = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            return false;
        }
    
        $stmt->bind_param("sssssi", $nombres, $apellidos, $claveEncriptada, $correo, $rol, $id);
        $result = $stmt->execute();
    
        $stmt->close();
        $conn->close();
    
        return $result;
    }    
    
    public function obtenerUsuarioPorId($id) {
        $conn = Database::connect();
        $sql = "SELECT * FROM t_usuarios WHERE Id_usuario=?";
    
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
    
        // Vincular los parámetros
        $stmt->bind_param("i", $id);
    
        // Ejecutar la consulta
        $stmt->execute();
    
        // Obtener el resultado
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
    
    public function inhabilitarUsuario($id) {
        $conn = Database::connect();
        $sql = "UPDATE t_usuarios SET Estado = 'Inhabilitado' WHERE Id_usuario='$id'";
        
        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function habilitarUsuario($id) {
        $conn = Database::connect();
        $sql = "UPDATE t_usuarios SET Estado = 'Habilitado' WHERE Id_usuario='$id'";
        
        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}
?>