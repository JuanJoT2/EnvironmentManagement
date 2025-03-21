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

    // Verificar si la actualización fue exitosa mediante el parámetro GET 'success'
    if (isset($_GET['success']) && $_GET['success'] === 'true') : ?>
        <script>
            alert("Usuario actualizado exitosamente");
        </script>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" type="text/css" href="../../assets/styles.css">
    
    <!-- Incluir SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Incluir Boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
    
    <header  class="container headerUpdate">
        <div class="logo-container">
            <img src="../../assets/Logo-Sena.jpg" alt="Logo de la empresa" class="logo">
        </div>
        <div class="title">
            <h1>Editar Usuario</h1>
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
        <section class="update-ambiente" id="section-update-usuario">
            <form action="../updateUsuario/<?php echo $usuario['Id_usuario']; ?>" method="POST">
                <label for="nombres">Nombre del Usuario:</label><br>
                <input type="text" id="nombres" name="nombres" value="<?php echo isset($usuario['Nombres']) ? $usuario['Nombres'] : ''; ?>" required><br><br>

                <label for="apellidos">Apellidos del Usuario:</label><br>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo isset($usuario['Apellidos']) ? $usuario['Apellidos'] : ''; ?>" required><br><br>

                <label for="correo">Correo del Usuario:</label><br>
                <input type="email" id="correo" name="correo" value="<?php echo isset($usuario['Correo']) ? $usuario['Correo'] : ''; ?>" required><br><br>

                <label for="clave">Clave:</label><br>
                <input type="password" id="clave" name="clave" value="<?php echo isset($usuario['Clave']) ? $usuario['Clave'] : ''; ?>" required><br>
                <button type="button" class="generate" id="mostrarClave">Mostrar Clave</button><br><br>

                <label for="rol">Rol:</label><br>
                <select id="rol" name="rol" required>
                    <option value="Administrador" <?php echo (isset($usuario['Rol']) && $usuario['Rol'] === 'Administrador') ? 'selected' : ''; ?>>Administrador</option>
                    <option value="Instructor" <?php echo (isset($usuario['Rol']) && $usuario['Rol'] === 'Instructor') ? 'selected' : ''; ?>>Instructor</option>
                </select><br><br>

                <button type="submit">Guardar Cambios</button>
            </form>
        </section>
    </main>

    <footer id="footer">
        <div class="regresar">
            <?php
            $url_regresar = '../usuarios';
            ?>
            <a href="<?php echo $url_regresar; ?>" class="button boton-centrado" id="btn-regresar">Regresar</a>
        </div>
        <div class="salir">
            <a href="../controllers/cerrarSesion.php" id="btn_salir" class="button-admin">Salir</a>
        </div>
        <p class="mb-0 footerSize">© Copyright Gestión de ambientes Sena. All Rights Reserved<br>
        Designed by Sena</p>
    </footer>

    <!-- Generar clave -->
    <script>
        document.getElementById('mostrarClave').addEventListener('click', function() {
            var claveInput = document.getElementById('clave');
            if (claveInput.type === "password") {
                claveInput.type = "text";
                this.textContent = "Ocultar Clave";
            } else {
                claveInput.type = "password";
                this.textContent = "Mostrar Clave";
            }
        });
    </script>

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

    <!-- Boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
