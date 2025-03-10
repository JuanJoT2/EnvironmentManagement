<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
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
            <h1>Agregar Nuevo Usuario</h1>
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
        <section class="create-ambiente" id="section-create-usuario">
            <form action="createAmbiente" method="POST">
                <label for="nombres">Nombre del usuario:</label><br>
                <input type="text" id="nombres" name="nombres" required><br><br>

                <label for="apellidos">Apellidos del usuario:</label><br>
                <input type="text" id="apellidos" name="apellidos" required><br>

                <label for="correo">Correo del usuario:</label><br>
                <input type="text" id="correo" name="correo" required><br>

                <label for="clave">Contraseña:</label><br>
                <input type="text" id="clave" name="clave" readonly><br>
                <button type="button" id="generarClave">Generar Contraseña</button>
                <br><br>

                <label for="rol">Rol:</label><br>
                <select id="rol" name="rol" required>
                    <option value="Administrador">Administrador</option>
                    <option value="Instructor">Instructor</option>
                </select><br><br>
                <button type="submit">Crear Usuario</button>
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
            <button id="btn_salir">Salir</button>
        </div>
        <p class="mb-0 footerSize">© Copyright Gestión de ambientes Sena. All Rights Reserved<br>
        Designed by Sena</p>
    </footer>

    <!-- Generar clave -->
    <script>
        document.getElementById("generarClave").addEventListener("click", function() {
            // Generar una contraseña aleatoria de 4 dígitos
            var claveGenerada = Math.floor(Math.random() * 10000).toString().padStart(4, "0");
            
            // Mostrar la contraseña en el campo de entrada
            document.getElementById("clave").value = claveGenerada;
        });
    </script>

    <!-- Boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
