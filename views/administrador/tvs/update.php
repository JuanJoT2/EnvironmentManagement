<?php
    session_start();

    // Deshabilitar caché del navegador
    header("Cache-Control: no-cache, no-store, must-revalidate"); 
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
    // Verificar si la actualización fue exitosa mediante el parámetro GET 'success'
    if (isset($_GET['success']) && $_GET['success'] === 'true') : ?>
        <script>
            alert("Usuario actualizado exitosamente");
        </script>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administrativo</title>
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
            <h1>Editar Televisor</h1>
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
            <form action="../updateTvs/<?php echo $televisor['Id_televisor']; ?>" method?="POST">
              
                <label for="marca">Marca:</label><br>
                <input type="text" id="marca" name="marca" value="<?php echo isset($televisor['Marca']) ? $televisor['Marca'] : ''; ?>"><br><br>

                <label for="modelo">Modelo:</label><br>
                <input type="text" id="modelo" name="modelo" value="<?php echo isset($televisor['Modelo']) ? $televisor['Modelo'] : ''; ?>"><br><br>

                <label for="serial">Serial:</label><br>
                <input type="text" id="serial" name="serial" value="<?php echo isset($televisor['Serial']) ? $televisor['Serial'] : ''; ?>"><br><br>

                <label for="placaInventario">Placa de inventario:</label><br> <!-- Corregido: 'placainventario' en lugar de 'placainvetario' -->
                <input type="text" id="placaInventario" name="placaInventario" value="<?php echo isset($televisor['PlacaInventario']) ? $televisor['PlacaInventario'] : ''; ?>"><br><br> <!-- Corregido: 'Placainventario' en lugar de 'Placainvetario' -->

                <label for="id_ambiente">Ambientes:</label>
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

                <label for="observaciones">Observaciones:</label><br>
                <textarea id="observaciones" name="observaciones" rows="4" cols="50"><?php echo isset($televisor['Observaciones']) ? $televisor['Observaciones'] : ''; ?></textarea><br><br>

                <button type="submit">Guardar Cambios</button>
            </form>
        </section>
    </main>

    <footer id="footer">
        <div class="regresar">
            <?php
            $url_regresar = '../tvs';
            ?>
            <a href="<?php echo $url_regresar; ?>" class="button boton-centrado" id="btn-regresar">Regresar</a>
        </div>
        <div class="salir">
            <button id="btn_salir">Salir</button>
        </div>
        <p class="mb-0 footerSize">© Copyright Gestión de ambientes Sena. All Rights Reserved<br>
        Designed by Sena</p>
    </footer>

    <!-- Script boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>
</html>