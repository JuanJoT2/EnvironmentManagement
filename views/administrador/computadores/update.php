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
    if(isset($_GET['success']) && $_GET['success'] === 'true'): ?>
        <script>
            alert("Computador actualizado exitosamente");
        </script>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Computador</title>
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
            <h1>Editar Computador</h1>
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
        <section class="update-ambiente" id="section-update-ambiente">
            <form action="../updateComputador/<?php echo $computador['Id_computador']; ?>" method="POST">
                <label for="tipo">Tipo:</label><br>
                <select id="tipo" name="tipo" required>
                    <option value="Desktop" <?php if(isset($computador['Tipo']) && $computador['Tipo'] === 'Desktop') echo 'selected'; ?>>Desktop</option>
                    <option value="Laptop" <?php if(isset($computador['Tipo']) && $computador['Tipo'] === 'Laptop') echo 'selected'; ?>>Laptop</option>
                </select><br><br>

                <label for="marca">Marca:</label><br>
                <input type="text" id="marca" name="marca" value="<?php echo isset($computador['Marca']) ? $computador['Marca'] : ''; ?>"><br><br>

                <label for="modelo">Modelo:</label><br>
                <input type="text" id="modelo" name="modelo" value="<?php echo isset($computador['Modelo']) ? $computador['Modelo'] : ''; ?>"><br><br>
        
                <label for="serial">Serial:</label><br>
                <input type="text" id="serial" name="serial" value="<?php echo isset($computador['Serial']) ? $computador['Serial'] : ''; ?>"><br><br>

                <label for="placaInventario">Placa de inventario:</label><br> <!-- Corregido: 'placainventario' en lugar de 'placainvetario' -->
                <input type="text" id="placaInventario" name="placaInventario" value="<?php echo isset($computador['PlacaInventario']) ? $computador['PlacaInventario'] : ''; ?>"><br><br> <!-- Corregido: 'Placainventario' en lugar de 'Placainvetario' -->

                <label for="hardware">Hardware:</label><br>
                <select id="hardware" name="hardware" value="<?php echo isset($computador['Hardware']) ? $computador['Hardware'] : ''; ?>">

                    <option value="">Seleccione...</option>

                    <?php
                        include_once '../../config/db.php'; 

                        $conn = Database::connect(); 

                        $serial = isset($computador['Serial']) ? $computador['Serial'] : '';

                        $sql = "SELECT Hardware FROM t_computadores WHERE Serial = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $serial);
                        $stmt->execute();
                        $resultado = $stmt->get_result();

                        if ($fila = $resultado->fetch_assoc()) {
                            $estadoHardware = $fila['Hardware'];
                            echo '<option value="1" ' . ($estadoHardware == 1 ? 'selected' : '') . '>Funcional</option>';
                            echo '<option value="0" ' . ($estadoHardware == 0 ? 'selected' : '') . '>No Funcional</option>';
                        } else {
                            echo '<option value="">No se encontró información</option>';
                        }

                        $stmt->close();
                        $conn->close();
                    ?>

                </select><br><br>


                <label for="software">Software:</label><br>
                <input type="number" id="software" name="software" value="<?php echo isset($computador['Software']) ? $computador['Software'] : ''; ?>"><br><br>

                <label for="id_ambiente">ID del Ambiente:</label>
                <select id="id_ambiente" name="id_ambiente" value="<?php echo $ambiente['Id_ambiente']; ?>" readonly>
                
                    <option value="">Seleccione...</option>
                    <?php
                    
                        include_once '../../config/db.php'; // Incluir el archivo de conexión a la base de datos
                        $conn = Database::connect(); // Conectar a la base de datos
                        $sql = "SELECT Id_ambiente, Nombre FROM t_ambientes";
                        $resultado = $conn->query($sql);
                        if ($resultado->num_rows > 0) {
                            // Iterar sobre los resultados y generar opciones para el menú desplegable
                            while ($fila = $resultado->fetch_assoc()) {
                                echo '<option value="' . $fila['Id_ambiente'] . '">' . $fila['Nombre'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No se encontraron ambientes disponibles</option>';
                        }

                    ?>
                </select><br><br>
                <button type="submit">Guardar Cambios</button>
            </form>
        </section>
    </main>

    <footer id="footer">
        <div class="regresar">
            <?php
            $url_regresar = '../computadores';
            ?>
            <a href="<?php echo $url_regresar; ?>" class="button boton-centrado" id="btn-regresar">Regresar</a>
        </div>
        <div class="salir">
            <a href="../controllers/cerrarSesion.php" id="btn_salir" class="button-admin">Salir</a>
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

    <!-- Script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
