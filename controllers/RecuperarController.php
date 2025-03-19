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
                    $mail->Body = '
                    <!DOCTYPE html>
                    <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
                        <head>
                            <meta charset="UTF-8">
                            <meta content="width=device-width, initial-scale=1" name="viewport">
                            <title>Contraseña provisional</title>
                            <style>
                                body {
                                    margin: 0;
                                    padding: 0;
                                    font-family: Arial, sans-serif;
                                    background-color: #fafafa;
                                }
                                .container {
                                    width: 100%;
                                    max-width: 600px;
                                    margin: auto;
                                    background: #ffffff;
                                    padding: 20px;
                                    text-align: center;
                                }
                                .logo {
                                    width: 155px;
                                }
                                .header {
                                    color: #00324d;
                                }
                                .icon {
                                    width: 50px;
                                }
                                .password {
                                    color: #39a900;
                                    font-size: 24px;
                                    font-weight: bold;
                                }
                                .button {
                                    display: inline-block;
                                    background: #39a900;
                                    color: #ffffff !important;
                                    text-decoration: none;
                                    padding: 15px 30px;
                                    border-radius: 5px;
                                }

                                .button:hover {
                                    transition: 0.8s ease;
                                    background-color:rgb(46, 122, 8);
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <a href="https://oferta.senasofiaplus.edu.co/sofia-oferta/"><img src="https://i.ibb.co/CKsRJ1y/logo-sena-verde-complementario-png-2022.png" alt="Logo SENA" class="logo"></a>
                                <h1 class="header">Contraseña provisional</h1>
                                <img src="https://cdn-icons-png.flaticon.com/512/3064/3064155.png" alt="Icono" class="icon">
                                <p>Has solicitado restablecer tu contraseña en Gestion de Ambientes. Aquí está tu nueva clave generada:</p>
                                <p>Esta contraseña es provisional, por favor recuerda solicitar cambiarla con soporte.</p>
                                <h1 class="password">' . $nuevaClave . '</h1>
                                <a href="#" class="button">Iniciar Sesión</a>
                                <p>Si no solicitaste este cambio, por favor comunicate con soporte.</p>
                                <p>Gracias,</p>
                                <p>Gestion de ambientes</p>
                            </div>
                        </body>
                    </html>';
        
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
