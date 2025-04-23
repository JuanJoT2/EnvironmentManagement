<?php

// PRIMERA LÍNEA ABSOLUTA DEL ARCHIVO (sin espacios/saltos antes)
declare(strict_types=1);

// Buffer de salida con verificación
if (!ob_get_level()) {
    ob_start();
}

// Manejo de sesión seguro
session_start();

// Verificación de autenticación
if (!isset($_SESSION['aut']) || $_SESSION['aut'] !== "SI") {
    session_unset();
    session_destroy();
    if (!headers_sent()) {
        header("Location: /gestiondeambientes/login");
    }
    ob_end_clean();
    exit();
}

// Verificación de carga única: Asegurarnos de que la página solo se carga una vez
if (isset($GLOBALS['ADMIN_PANEL_LOADED']) && $GLOBALS['ADMIN_PANEL_LOADED'] === true) {
    // Si ya está cargado, no hacemos nada más y no mostramos ningún error
    exit();
}

$GLOBALS['ADMIN_PANEL_LOADED'] = true;  // Marca la página como cargada.

// Cabeceras de control de caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Verificación final de salida no deseada
if (ob_get_length() > 0) {
    $buffer = ob_get_contents();
    if (strpos($buffer, '<!DOCTYPE') !== false) {
        ob_clean();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/styles.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Khojki:wght@400..700&display=swap');
        
        body {
            font-family: "Noto Serif Khojki", serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #fff;
        }
        header {
            background-color: #fff;
        }
        .admin-panel-container {
            background-color: #f4f4f4;
            border-radius: 10px;
            padding: 20px !important;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
            margin-bottom: 0;
            padding-bottom: 40px;
        }
        .subtitle{
            font-size: 25px;
            padding: 5px;
        }
        .columns {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 120px;
            width: 100%;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            transition: 0.4s ease;
        }
        .columns:hover {
            background-color: #218838;
            color: white;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 15px;
        }
        main {
            flex-grow: 1;
        }
                        
    </style>
    
</head>
<body class="d-flex flex-column min-vh-100">

    <header class="text-center py-3">
        <img src="../assets/Logo-Sena.jpg" alt="Logo de la empresa" class="mb-2" style="width: 80px;">
        <h1 class="fw-bold">Gestión de Ambientes de Formación</h1>
        <div class="datetime">
            <?php
                date_default_timezone_set('America/Bogota');
                $fechaActual = date("d/m/Y");
                $horaActual = date("h:i a");
                echo "<p>Fecha actual: $fechaActual </p>";
                echo "<p> - </p>";
                echo "<p>Hora actual: $horaActual</p>";
            ?>
        </div>
    </header>

    <main class="container my-4 flex-grow-1">
        <div class="admin-panel-container p-4">
            <h3 class="text-center mb-4 subtitle">Administrador</h3>
            <div class="row g-3">
                <?php
                $urls = [
                    '/admin/ambientes' => 'Gestión de Ambientes',
                    '/admin/reportes' => 'Gestión de Reportes',
                    '/admin/tvs' => 'Televisores (TVs)',
                    '/admin/tableros' => 'Tableros',
                    '/usuarios/usuarios' => 'Gestión de Usuarios',
                    '/admin/computadores' => 'Computadores',
                ];

                foreach ($urls as $url => $label) {
                    echo '<div class="col-md-4">';
                    echo '<a href="' . htmlspecialchars($url) . '" class="columns">' . htmlspecialchars($label) . '</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </main>

    <footer id="footer">
        <a href="../controllers/cerrarSesion.php" class="btn btn-danger mb-2 logout">Cerrar sesión</a>
        <p class="mb-0">© Copyright Gestión de ambientes Sena. All Rights Reserved</p>
        <p>Designed by Sena</p>
    </footer>

    <!-- Limpiar historial -->
    <script>
        // Bloquear historial
        document.addEventListener("DOMContentLoaded", function() {
            history.pushState(null, "", location.href);
            window.onpopstate = function() {
                history.pushState(null, "", location.href);
            };

            // Manejar logout
            document.querySelector(".logout")?.addEventListener("click", function(e) {
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
    
    <!-- Footer -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const footer = document.getElementById("footer");
            const main = document.querySelector("main");
        
            function posicionarFooter() {
                // Altura total de la ventana
                const alturaVentana = window.innerHeight;
        
                // Altura real de la página (contenido)
                const alturaContenido = document.body.offsetHeight;
        
                if (alturaContenido < alturaVentana) {
                    // El contenido no llena la pantalla, forzamos el footer al fondo
                    footer.style.position = "absolute";
                    footer.style.bottom = "0";
                    footer.style.left = "0";
                    footer.style.width = "100%";
                } else {
                    // El contenido sobrepasa la altura, dejamos el flujo normal
                    footer.style.position = "static";
                }
            }
        
            posicionarFooter();
            window.addEventListener("resize", posicionarFooter);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
<?php

// Limpieza final del buffer
while (ob_get_level() > 0) {
    ob_end_flush();
}
