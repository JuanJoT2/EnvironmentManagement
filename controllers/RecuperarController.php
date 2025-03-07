<?php
require_once 'models/RecuperarModel.php';

class RecuperarController {
    private $recuperarModel;

    public function __construct() {
        $this->recuperarModel = new RecuperarModel();
    }

    public function mostrarFormulario() {
        require_once 'views/login/recuperarClave.php';
    }

    public function enviarCorreo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['correo'];
            $usuario = $this->recuperarModel->getUserByEmail($email);

            if ($usuario) { 
                $token = bin2hex(random_bytes(32));
                $this->recuperarModel->guardarToken($email, $token);

                $enlace = "http://tudominio.com/reset_password.php?token=" . $token;
                $asunto = "Recuperación de contraseña";
                $mensaje = "Hola, para restablecer tu contraseña haz clic en el siguiente enlace: " . $enlace;
                $cabeceras = "From: no-reply@tudominio.com\r\n";

                if (mail($email, $asunto, $mensaje, $cabeceras)) {
                    echo "Correo de recuperación enviado.";
                } else {
                    echo "Error al enviar el correo.";
                }
            } else {
                echo "Correo no registrado.";
            }
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            echo "Método no permitido.";
        }
    }
}
?>
