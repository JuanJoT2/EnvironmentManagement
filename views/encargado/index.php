<?php
    // Conectamos la base de datos
    require_once 'config/db.php';
    $db = Database::connect();
    
    session_start();

    // Si la sesión no está activa, redirigir al login
    if (!isset($_SESSION['aut']) || $_SESSION['aut'] !== "SI") {
        session_unset();
        session_destroy();
        echo "<script>
            sessionStorage.clear();
            localStorage.clear();
            window.location.href = '/gestiondeambientes/login';
        </script>";
        exit();
    }

    // Evitar caché del navegador
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: 0");

    // Comprobar si el usuario está autenticado
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email']; 

        // Consultar datos del usuario usando el correo
        $query = "SELECT Nombres, Apellidos, Rol FROM t_usuarios WHERE Correo = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $nombre = "Nombre no encontrado";
            $cargo = "Cargo no encontrado";
        } else {
            $row = $result->fetch_assoc();
            $nombre = $row['Nombres'] . ' ' . $row['Apellidos'];
            $cargo = $row['Rol'];
        }

        $stmt->close();
        $db->close();
    } else {
        $nombre = "Nombre no proporcionado";
        $cargo = "Cargo no proporcionado";
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lector de Código QR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Khojki:wght@400..700&display=swap');
        /* Estilos Generales */
        body {
            font-family: "Noto Serif Khojki", serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background-color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            width: 100%;
            border-radius: 10px;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header img {
            height: 40px;
            margin-right: 10px;
        }

        /* Contenedor Principal */
        .contenedor-centrado {
            width: 90%;
            max-width: 500px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Animación del escáner */
        @keyframes scanAnimation {
            0% { background-position: -100% 50%; }
            100% { background-position: 200% 50%; }
        }

        .background-animation {
            width: 100%;
            height: 200px;
            background-image: url(https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExb3g0bDM0ZzZndnBsb2M5aGc5bnM5MDM2NnBkdGducno5c2x1Y3kyZyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/taVCVuunNzQjBKTrYn/giphy.gif);
            animation: scanAnimation 4s linear infinite;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 10px;
        }

        /* Botón de escaneo */
        .scaner {
            background-color: #28a745;
            color: white;
            width: 100%;
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .logout-button {
            position: relative;
            top: 20px;
            bottom: 10px;
            text-align: right;
            right: 10px;
        }

        .scaner:hover {
            background-color:#078e27; 
            transition: 0.4s ease;
            color: white;
        }

        /* Estilos del video */
        #preview {
            max-width: 300px;
            margin: 20px auto;
            border: 10px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }

        /* Footer */
        .footer {
            background-color: #f8f9fa;
            width: 100%;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        #btn_salir{
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .salir{
            position: relative;
            top: 20px;
            bottom: 10px;
            text-align: right;
            right: 10px;
        }

    </style>
</head>
<body>
    <header class="header">
        <img src='../assets/Logo-Sena.jpg' alt='logo'>
        <h1>Gestión de Ambientes de Formación</h1>
    </header>
    
    <main class="d-flex justify-content-center align-items-center">
        <div class="contenedor-centrado">
            <h3 class="escan">Escanea</h3>
            <div class="background-animation"></div>
            <video id="preview" style="width:100%; display: none;"></video>
            <button class="btn scaner" onclick="scanQR()">Escanear código QR</button>
            <button class="btn btn-danger mt-2" onclick="stopQR()">Detener cámara</button>

            <h1><?php echo $nombre; ?></h1>
            <h2><?php echo $cargo; ?></h2>  
        </div>
    </main>

    <footer class="footer">
        <div class="salir">
            <a href="../../controllers/cerrarSesion.php" id="btn_salir" class="button-admin">Cerrar sesión</a>
        </div>
        <p>© 2025 Gestión de Ambientes de Formación - Todos los derechos reservados.</p>
    </footer>

    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <!-- Script cerrar sesión -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".salir").addEventListener("click", function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Se cerrará tu sesión.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, cerrar sesión"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../controllers/cerrarSesion.php";
                    }
                });
            });
        });
    </script>

    <!-- Destruir sesión -->
    <script>
        // Función para borrar historial y prevenir el acceso con "Atrás"
        function bloquearHistorial() {
            history.pushState(null, "", location.href);
            window.onpopstate = function () {
                history.pushState(null, "", location.href);
            };
        }

        // Bloquear historial al cargar la página
        document.addEventListener("DOMContentLoaded", function () {
            bloquearHistorial();

            // Verificar si la sesión ha sido cerrada
            if (!sessionStorage.getItem("autenticado")) {
                window.location.href = "/gestiondeambientes/login";
            }
        });

        // Guardar estado en sessionStorage al iniciar sesión
        sessionStorage.setItem("autenticado", "SI");
    </script>

    <script>
        let scanner;
        function scanQR() {
            document.getElementById('preview').style.display = 'block';
            scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            scanner.addListener('scan', function (content) {
                alert('Escaneado con éxito: ' + content);
                window.location.href = '/encargado/readQR/' + encodeURIComponent(content);
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    let rearCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
                    scanner.start(rearCamera || cameras[0]);
                } else {
                    alert("No se encontraron cámaras disponibles.");
                }
            }).catch(function (e) {
                alert("Error al acceder a la cámara: " + e);
            });
        }
        function stopQR() {
            if (scanner) {
                scanner.stop();
                document.getElementById('preview').style.display = 'none';
            }
        }
    </script>
</body>
</html>
