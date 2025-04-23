<?php
require 'config/db.php';

class RecuperarModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function mostrarFormulario() {
        require_once __DIR__ . '/../views/login/recuperarClave.php'; 
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM t_usuarios WHERE Correo = ?");
        $stmt->bind_param('s', $email);
        
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        return $usuario;
    }

    public function actualizarClave($email, $nuevaClave) {
        $stmt = $this->db->prepare("UPDATE t_usuarios SET Clave = ? WHERE Correo = ?");
        $stmt->bind_param('ss', $nuevaClave, $email);

        $stmt->execute();
        $stmt->close();
    }    
}
?>
