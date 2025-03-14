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
            // Depuración
            echo "Error en la preparación de la consulta: " . $this->db->error;
            return null;
        }
    }

    public function login($email, $clave) {
        // Obtener usuario por email
        $user = $this->getUserByEmail($email);

        if ($user) {
            // Verificar la contraseña
            if (password_verify($clave, $user['Clave'])) {
                // Iniciar sesión
                session_start();
                $_SESSION['id'] = $user['Id_usuario'];
                $_SESSION['email'] = $user['Correo'];
                $_SESSION['rol'] = $user['Rol'];
                $_SESSION['aut'] = "SI";

                // Establecer mensaje de éxito
                $_SESSION['alert'] = [
                    'title' => 'Bienvenido ' . ucfirst($user['Rol']),
                    'text' => 'Has ingresado correctamente',
                    'icon' => 'success',
                    'redirect' => $this->getRedirectUrlByRole($user['Rol'])
                ];
            } else {
                // Establecer mensaje de error para clave incorrecta
                $_SESSION['alert'] = [
                    'title' => 'Error',
                    'text' => 'La clave es incorrecta',
                    'icon' => 'error',
                    'redirect' => '../Views/extras/iniciarSesion.php'
                ];
            }
        } else {
            // Establecer mensaje de error para email no encontrado
            $_SESSION['alert'] = [
                'title' => 'Error',
                'text' => 'El email no existe en la base de datos. Regístrese',
                'icon' => 'warning',
                'redirect' => '../Views/extras/iniciarSesion.php'
            ];
        }
    }

    private function getRedirectUrlByRole($role) {
        switch ($user['Rol']) {
            case 'Administrador':
                header("Location: /gestiondeambientes/admin/home");
                //header("Location: /dashboard/gestion%20de%20ambientes/admin/home");
                exit();
            case 'Instructor':
                header("Location: /gestiondeambientes/instructor/home");
                //header("Location: /dashboard/gestion%20de%20ambientes/instructor/home");
                exit();
            case 'Encargado':
                header("Location: /gestiondeambientes/encargado/home");
                //header("Location: /dashboard/gestion%20de%20ambientes/encargado/home");
                exit();
            default:
                header("Location: /gestiondeambientes/login");
                //header("Location: /dashboard/gestion%20de%20ambientes/login");
                exit();
        }
    }
}
?>