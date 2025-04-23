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
    <title>Crear ambientes</title>
    <link rel="stylesheet" type="text/css" href="../../assets/styles.css">
    
    <!-- Incluir SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Incluir Boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>
<body>

    <header class="container headerUpdate">
        <div class="logo-container">
            <img src="../../assets/Logo-Sena.jpg" alt="Logo de la empresa" class="logo">
        </div>
        <div class="title">
            <h1>Agregar Nuevo Ambiente</h1>
        </div>
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
        <section class="create-ambiente" id="section-create-ambiente">
            <form id="createAmbienteForm" method="POST">
                <label for="nombre">Nombre del Ambiente:</label><br>
                <input type="text" id="nombre" name="nombre" required><br><br>

                <label for="torre">Torre:</label><br>
                <select id="torre" name="torre">
                    <option value="Oriental">Oriental</option>
                    <option value="Occidental">Occidental</option>
                </select><br><br>

                <label for="computadores">Computadores:</label><br>
                <input type="number" id="computadores" name="computadores" placeholder="0" ><br><br>

                <label for="tvs">TVs:</label><br>
                <input type="number" id="tvs" name="tvs" placeholder="0"><br><br>

                <label for="sillas">Sillas:</label><br>
                <input type="number" id="sillas" name="sillas" placeholder="0"><br><br>

                <label for="mesas">Mesas:</label><br>
                <input type="number" id="mesas" name="mesas" placeholder="0"><br><br>

                <label for="tableros">Tableros:</label><br>
                <input type="number" id="tableros" name="tableros" placeholder="0"><br><br>

                <label for="nineras">Niñeras:</label><br>
                <input type="number" id="nineras" name="nineras" placeholder="0"><br><br>

                <label for="estado">Estado:</label><br>
                <input type="text" id="estado" name="estado" value="Habilitado" readonly><br><br>

                <label for="observaciones">Observaciones:</label><br>
                <textarea id="observaciones" name="observaciones" rows="4" cols="50"></textarea><br><br>

                <button type="submit">Guardar Ambiente</button>
            </form>
        </section>
    </main>
    

    <footer id="footer">
        <div class="regresar">
            <?php
            $url_regresar = '../ambientes';
            ?>
            <a href="<?php echo $url_regresar; ?>" class="button boton-centrado" id="btn-regresar">Regresar</a>
        </div>
        <p class="mb-0 footerSize">© Copyright Gestión de ambientes Sena. All Rights Reserved<br>
        Designed by Sena</p>
    </footer>

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
                        window.location.href = "../../controllers/cerrarSesion.php";
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
    
    <!-- Alerta -->
    <script>
        document.getElementById('createAmbienteForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            // Enviar solicitud al servidor
            fetch('guardarAmbiente', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar alerta de éxito
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'El ambiente ha sido modificado exitosamente',
                        confirmButtonText: 'OK',
                        confirmButtonColor: "#39a900" 
                    }).then(() => {
                        window.location.href = '../ambientes';
                    });
                } else {
                    // Mostrar alerta de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo modificar el ambiente. Por favor, intenta de nuevo',
                        confirmButtonText: 'OK',
                        confirmButtonClass: 'custom-btn-green' // Clase personalizada para el botón
                    });
                }
            })
            .catch(error => {
                // Mostrar alerta de error de conexión
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'El ambiente ha sido creado exitosamente',
                    confirmButtonText: 'Recargar Pagina',
                    confirmButtonClass: 'custom-btn-green' // Clase personalizada para el botón
                }).then(() => {
                    window.location.href = '../ambientes';
                });
            });
        });
    </script>

    <!-- Script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

<?php

// Limpieza final del buffer
while (ob_get_level() > 0) {
    ob_end_flush();
}
