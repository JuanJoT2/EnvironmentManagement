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

            if ($this->loginModel->login($email, $password)) {
                header("Location: " . $_SESSION['alert']['redirect']);
                exit();
            } else {
                echo "
                <link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css' rel='stylesheet'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js'></script>
                <script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Correo o clave incorrecta',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '../Views/extras/iniciarSesion.php';
                    });
                </script>
                ";
            }
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            echo "Método no permitido.";
        }
    }
}
?>
