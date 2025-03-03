<?php
// Conexión a la base de datos y sesión
require_once 'config/db.php';
$db = Database::connect();
session_start();

if (isset($_SESSION['clave'])) {
    $clave = $_SESSION['clave'];

    $query = "SELECT * FROM t_usuarios WHERE Clave = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $clave);
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
        /* Estilos Generales */
        body {
            font-family: Arial, sans-serif;
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
            padding: 2px 0;
            margin-top: auto;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header">
        <img src='../assets/Logo-Sena.jpg' alt='logo'>
        <h1>Gestión de Ambientes de Formación</h1>
    </header>

    <!-- Main Content -->
    <main class="d-flex justify-content-center align-items-center">
        <div class="contenedor-centrado">
            <h3 class="escan">Escanea</h3>

            <div class="background-animation"></div>

            <video id="preview" style="width:100%; display: none;"></video>

            <button class="btn scaner" onclick="scanQR()">Escanear código QR</button>
            <button class="btn btn-danger mt-2" onclick="stopQR()">Detener cámara</button>

            <form id="imageForm" action="" method="post" enctype="multipart/form-data" style="display:none;">
                <input type="file" accept="image/*" name="archivo" id="fileInput" class="form-control">
                <button type="submit" name="submit" class="btn btn-secondary mt-2">Leer QR desde imagen</button>
            </form>

            <div id="fecha-hora" class="mt-3"></div>
            <h1><?php echo $nombre; ?></h1>
            <h2><?php echo $cargo; ?></h2>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="logout-button">
            <a href="/gestiondeambientes/login" class="btn btn-danger">Cerrar sesión</a>
        </div>
        <p>© 2025 Gestión de Ambientes de Formación - Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    
    <script>
        let scanner;

        function scanQR() {
            document.getElementById('preview').style.display = 'block';
            document.getElementById('imageForm').style.display = 'none';

            if (scanner) {
                scanner.stop(); // Detiene cualquier escaneo previo
            }

            scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            scanner.addListener('scan', function (content) {
                alert('Escaneado con éxito: ' + content);
                window.location.href = '/dashboard/gestion%20de%20ambientes/instructor/readQR/' + encodeURIComponent(content);
            });

            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length === 0) {
                    alert("No se encontraron cámaras disponibles. Revisa los permisos del navegador.");
                    console.error("No se encontraron cámaras.");
                    return;
                }

                let rearCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
                let selectedCamera = rearCamera || cameras[0];

                scanner.start(selectedCamera).catch(function (error) {
                    alert("Error al acceder a la cámara: " + error);
                    console.error("Error al iniciar la cámara:", error);
                });

            }).catch(function (e) {
                alert("No se pudo acceder a las cámaras: " + e);
                console.error("Error al obtener cámaras:", e);
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
