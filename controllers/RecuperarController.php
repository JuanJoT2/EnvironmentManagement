<?php
require_once 'models/RecuperarModel.php';

class RecuperarController {
    private $recuperarModel;

    public function __construct() {
        $this->recuperarModel = new RecuperarModel();
    }

    // Muestra el formulario de recuperación de contraseña
    public function mostrarFormulario() {
        require_once 'views/login/recuperarClave.php';
    }

    // Envía el correo con el enlace de recuperación
    public function enviarCorreo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['correo'];
            $usuario = $this->recuperarModel->getUserByEmail($email);

            if ($usuario) { 
                // Generar un token único para la recuperación
                $token = bin2hex(random_bytes(32)); // Un token de 64 caracteres
                $this->recuperarModel->guardarToken($email, $token);

                // Crear el enlace para restablecer la contraseña
                $enlace = URL . "recuperar_clave/restablecer/" . $token;
                $asunto = "Recuperación de contraseña";
                $mensaje = "Hola, para restablecer tu contraseña, haz clic en el siguiente enlace: " . $enlace;
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

    // Restablecer la contraseña
    public function restablecerClave($token) {
        // Verificar si el token es válido
        $usuario = $this->recuperarModel->verificarToken($token);

        if ($usuario) {
            // Mostrar el formulario para cambiar la contraseña
            require_once 'views/login/cambiarClave.php';
        } else {
            echo "Token inválido o expirado.";
        }
    }

    // Actualizar la contraseña
    public function actualizarClave() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nuevaClave = $_POST['nuevaClave'];
            $token = $_POST['token'];
            $usuario = $this->recuperarModel->verificarToken($token);

            if ($usuario) {
                // Actualizar la contraseña en la base de datos
                $this->recuperarModel->actualizarClave($usuario['Correo'], $nuevaClave);
                echo "Contraseña actualizada exitosamente.";
            } else {
                echo "Token inválido o expirado.";
            }
        }
    }
}
?>
