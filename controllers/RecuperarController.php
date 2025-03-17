    <?php
    require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer/SMTP.php';
    require_once __DIR__ . '/../PHPMailer/Exception.php';
    require_once __DIR__ . '/../models/sendEmail.php'; // Asegúrate de que la ruta sea correcta

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    class RecuperarController {
        private $recuperarModel;

        public function __construct() {
            $this->recuperarModel = new RecuperarModel();
        }

        public function mostrarFormulario() {
            require_once __DIR__ . '/../views/login/recuperarClave.php'; 
        }

        public function enviarCorreo() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header("HTTP/1.0 405 Method Not Allowed");
                echo json_encode(["error" => "Método no permitido."]);
                exit;
            }
        
            if (!isset($_POST['correo']) || empty($_POST['correo'])) {
                echo json_encode(["error" => "El campo de correo es obligatorio."]);
                exit;
            }
        
            $email = trim($_POST['correo']);
            $usuario = $this->recuperarModel->getUserByEmail($email);
        
            if ($usuario) {
                $nuevaClave = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
                $hashedClave = password_hash($nuevaClave, PASSWORD_BCRYPT);
                $this->recuperarModel->actualizarClave($email, $hashedClave);
        
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'redsenaat@gmail.com';
                    $mail->Password = 'eklkrrvvgsmjxazx';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;
        
                    $mail->setFrom('redsenaat@gmail.com', 'Soporte');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'Nueva contraseña';
                    $mail->Body = "<p>Hola,</p>
                                <p>Tu nueva clave es: <strong>$nuevaClave</strong></p>
                                <p>Por favor, inicia sesión y cámbiala cuanto antes.</p>";
        
                    $mail->send();
                    echo json_encode(["success" => "Correo enviado correctamente."]);
                    exit; 
                } catch (Exception $e) {
                    echo json_encode(["error" => "Error al enviar el correo: {$mail->ErrorInfo}"]);
                    exit;
                }
            } else {
                echo json_encode(["error" => "Correo no registrado."]);
                exit;
            }
        }        
    }

    // **Ejecución del controlador**
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controlador = new RecuperarController();
        $controlador->enviarCorreo();
    }
    ?>
