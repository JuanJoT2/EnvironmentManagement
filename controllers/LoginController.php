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

                $rol = $_SESSION['rol'];

                echo "
                <link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css' rel='stylesheet'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: '¡Bienvenido $rol!',
                            text: 'Inicio de sesión exitoso.',
                            icon: 'success',
                            confirmButtonText: 'Continuar',
                            confirmButtonColor: '#39a900'
                        }).then(() => {
                            window.location.href = '" . $_SESSION['alert']['redirect'] . "';
                        });
                    });
                </script>";
                exit();
            } else {
                echo "
                <style>
                        @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');        
                        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap');

                        :root {
                            --primary-color: #46b04a;
                            --secondary-color: #086735;
                            --font-titles: 'Poppins', sans-serif;
                            --font-textos: 'Raleway', sans-serif;
                            --font-complement: 'Poppins', sans-serif;
                            --bg-degrade: linear-gradient(to right, #086735, #46b04a);
                            --font-general: 'Poppins', sans-serif;
                        }

                        h1, h2, h3, h4, h5, h6 {
                            font-family: var(--font-general);
                        }

                        a, p, label, input, select, textarea, li, button, div, .swal2-html-container {
                            font-family: var(--font-textos);
                        }

                        .swal2-confirm.swal2-styled {
                            padding: 12px 60px;
                            background: var(--primary-color);
                            border-radius: 20px;
                            border: transparent;
                            color: #fff;
                            transition: .4s;
                            box-shadow: 0 0 0 transparent;
                        }
                        .swal2-confirm.swal2-styled:focus {
                            box-shadow: 0 0 0 transparent;
                            outline: none;
                        }
                        .swal2-confirm.swal2-styled:hover {
                            background: var(--secondary-color);
                            border-radius: 4px;
                        }
                </style>
                ";

                echo "
                <link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css' rel='stylesheet'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js'></script>
                ";

                echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Atención',
                            text: 'Correo o contraseña incorrecta',
                            icon: 'warning',
                            confirmButtonText: 'Volver',
                            confirmButtonColor: '#d33'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/gestiondeambientes/login';
                            }
                        });
                    });
                </script>";
            }
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            echo "Método no permitido.";
        }
    }
}
?>
