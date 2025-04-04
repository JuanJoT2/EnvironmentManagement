<?php
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
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
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
            display: none;
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
            ?>
            <div class="datetime">
                <div class="fecha">
                    <p>Fecha actual: <?php echo $fechaActual; ?></p>
                </div>
                <div class="hora">
                    <p>Hora actual: <?php echo $horaActual; ?></p>
                </div>
            </div>
        </div>
    </header>

    <main class="container my-4">
        <div class="admin-panel-container p-4">
            <h3 class="text-center mb-4 subtitle">Administrador</h3>
            <div class="row g-3">
                <?php
                $urls = [
                    '/gestiondeambientes/admin/ambientes' => 'Gestión de Ambientes',
                    '/gestiondeambientes/admin/reportes' => 'Gestión de Reportes',
                    '/gestiondeambientes/admin/tvs' => 'Televisores (TVs)',
                    // '/gestiondeambientes/admin/sillas' => 'Sillas',
                    // '/gestiondeambientes/admin/mesas' => 'Mesas',
                    '/gestiondeambientes/admin/tableros' => 'Tableros',
                    // '/gestiondeambientes/admin/nineras' => 'Niñeras',
                    '/gestiondeambientes/usuarios/usuarios' => 'Gestión de Usuarios',
                    '/gestiondeambientes/admin/computadores' => 'Computadores',
                ];

                foreach ($urls as $url => $label) {
                    echo '<div class="col-md-4">';
                    echo '<a href="' . $url . '" class="columns">' . $label . '</a>';
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

    <!-- Script cerrar sesión -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".logout").addEventListener("click", function (e) {
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

    <!-- Script para el footer -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let footer = document.getElementById("footer");
            let bodyHeight = document.body.offsetHeight;
            let windowHeight = window.innerHeight;

            if (bodyHeight < windowHeight) {
                footer.style.position = "fixed";
                footer.style.bottom = "0";
                footer.style.width = "100%";
                footer.style.display = "block"; // Mostramos el footer solo cuando es necesario
            } else {
                footer.style.position = "relative";
                footer.style.display = "block"; // Siempre visible si hay suficiente contenido
            }
        });
    </script>

    <!-- Script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
