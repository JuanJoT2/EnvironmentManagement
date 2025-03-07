<?php
require_once 'config/db.php';

class RecuperarModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM t_usuarios WHERE Correo = ?");
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();
            $stmt->close();
            return $usuario;
        }
        return null;
    }

    public function guardarToken($email, $token) {
        $stmt = $this->db->prepare("UPDATE t_usuarios SET TokenRecuperacion = ?, ExpiracionToken = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE Correo = ?");
        if ($stmt) {
            $stmt->bind_param('ss', $token, $email);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function verificarToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM t_usuarios WHERE TokenRecuperacion = ? AND ExpiracionToken > NOW()");
        if ($stmt) {
            $stmt->bind_param('s', $token);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();
            $stmt->close();
            return $usuario;
        }
        return null;
    }

    public function actualizarClave($email, $nuevaClave) {
        $hashedClave = password_hash($nuevaClave, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE t_usuarios SET Clave = ?, TokenRecuperacion = NULL, ExpiracionToken = NULL WHERE Correo = ?");
        if ($stmt) {
            $stmt->bind_param('ss', $hashedClave, $email);
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>
