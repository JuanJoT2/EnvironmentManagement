<?php
require_once 'config/db.php';

class LoginModel {
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
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            echo "Error en la consulta: " . $this->db->error;
            return null;
        }
    }

    public function registerUser($email, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO t_usuarios (Correo, Clave, Rol) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param('sss', $email, $hashedPassword, $role);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        } else {
            echo "Error al registrar usuario: " . $this->db->error;
            return false;
        }
    }

    public function login($email, $clave) {
        $user = $this->getUserByEmail($email);

        if ($user && password_verify($clave, $user['Clave'])) {
            session_start();
            $_SESSION['id'] = $user['Id_usuario'];
            $_SESSION['email'] = $user['Correo'];
            $_SESSION['rol'] = $user['Rol'];
            $_SESSION['aut'] = "SI";

            $_SESSION['alert'] = [
                'title' => 'Bienvenido ' . ucfirst($user['Rol']),
                'text' => 'Has ingresado correctamente',
                'icon' => 'success',
                'redirect' => $this->getRedirectUrlByRole($user['Rol'])
            ];
            return true;
        } else {
            $_SESSION['alert'] = [
                'title' => 'Error',
                'text' => 'Correo o clave incorrecta',
                'icon' => 'error',
                'redirect' => '../Views/extras/iniciarSesion.php'
            ];
            return false;
        }
    }

    private function getRedirectUrlByRole($role) {
        $routes = [
            'Administrador' => '/gestiondeambientes/admin/home',
            'Instructor' => '/gestiondeambientes/instructor/home',
            'Encargado' => '/gestiondeambientes/encargado/home'
        ];
        return $routes[$role] ?? '/gestiondeambientes/login';
    }
}
?>
