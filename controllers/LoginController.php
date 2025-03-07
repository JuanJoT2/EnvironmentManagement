<?php
require_once 'models/LoginModel.php';

class LoginController {
    private $loginModel;

    public function __construct() {
        $this->loginModel = new LoginModel();
    }

    public function home() {
        require_once 'views/login/index.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['login'];
            $password = $_POST['password'];

            $user = $this->loginModel->getUserByEmail($email);

            // Verificación temporal de contraseña en texto plano
            if ($user && $password === $user['Clave']) {
<<<<<<< HEAD
                // Iniciar sesión y redirigir según el rol
=======
                // Iniciar sesión
>>>>>>> 7ff67ecefcebbca162f57bd3d395b4ace2e044d5
                session_start();
                $_SESSION['user'] = $user;
                $_SESSION['clave'] = $password;

<<<<<<< HEAD
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
                
            } else {
                echo "Correo o clave incorrecta";
=======
                // Establecer el mensaje de alerta en la sesión
                $_SESSION['alert'] = [
                    'title' => 'Bienvenido ' . ucfirst($user['Rol']),
                    'text' => 'Has ingresado correctamente',
                    'icon' => 'success',
                    'redirect' => $this->getRedirectUrlByRole($user['Rol'])
                ];

                // Redirigir a la página correspondiente según el rol
                header("Location: " . $_SESSION['alert']['redirect']);
                exit();
            } else {
                // Establecer el mensaje de error de login
                $_SESSION['alert'] = [
                    'title' => 'Error',
                    'text' => 'Correo o clave incorrecta',
                    'icon' => 'error',
                    'redirect' => '/gestiondeambientes/login'
                ];
                header("Location: /gestiondeambientes/login");
                exit();
>>>>>>> 7ff67ecefcebbca162f57bd3d395b4ace2e044d5
            }
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            echo "Método no permitido.";
        }
    }

<<<<<<< HEAD
=======
    private function getRedirectUrlByRole($role) {
        switch ($role) {
            case 'Administrador':
                return '/gestiondeambientes/admin/home';
            case 'Instructor':
                return '/gestiondeambientes/instructor/home';
            case 'Encargado':
                return '/gestiondeambientes/encargado/home';
            default:
                return '/gestiondeambientes/login';
        }
    }

>>>>>>> 7ff67ecefcebbca162f57bd3d395b4ace2e044d5
    public function logout() {
        session_start();
        session_unset();
        session_destroy();

        // Headers para deshabilitar la caché
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

<<<<<<< HEAD
        //header("Location: /dashboard/gestion%20de%20ambientes/login"); // Redirigir al inicio de sesión después de cerrar sesión
=======
>>>>>>> 7ff67ecefcebbca162f57bd3d395b4ace2e044d5
        header("Location: /gestiondeambientes/login"); // Redirigir al inicio de sesión después de cerrar sesión
        exit();
    }
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 7ff67ecefcebbca162f57bd3d395b4ace2e044d5
