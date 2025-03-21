<?php
    // Conectar a la base de datos
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableros</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    
    <!-- Incluir Boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

</head>
<body>

    <header class="container headerUpdate">
        <div class="logo-container">
            <img src="../assets/Logo-Sena.jpg" alt="Logo de la empresa" class="logo">
        </div>
        <div class="title">
            <h1>Gestion de Ambientes de formacion</h1>
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

    <nav class ="container aspects">
        <div class="container">
            <button class="toggle-vis indicators" data-column="0">Marca</button>
            <button class="toggle-vis indicators" data-column="1">Placa de Inventario</button>
            <button class="toggle-vis indicators" data-column="2">Id ambiente</button>
            <button class="toggle-vis indicators" data-column="3">Acción</button>
        </div>
    </nav>

    <main class="container contenido">
        <section class="ambiente" id="section-ambiente">

            <div class="subtitulo-ambiente">
                <h2>Administración de tableros</h2>
            </div>

            <div class="descripcion-ambiente">
                <p>Gestión de ambientes de formación</p>
            </div>

            <div class="tabla-ambientes tabla-scroll">
                <table class="table table-striped table_id" border="1" id="tabla-ambientes">
                    <thead class="aspects">
                        <tr>
                            <th>Marca</th>
                            <th>Placa de Inventario</th>
                            <th>Ambiente</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Consulta en la Base de datos
                            $query = "SELECT  c.Id_tablero, c.Marca, c.PlacaInventario, a.Nombre, c.Observaciones 
                            FROM t_tableros AS c
                            INNER JOIN t_ambientes AS a ON c.Id_ambiente = a.Id_ambiente";


                            if (!empty($filtros)) {
                                $query .= " WHERE " . implode(" AND ", $filtros);
                            }

                            $result = $db->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['Marca'] . "</td>";
                                    echo "<td>" . $row['PlacaInventario'] . "</td>";
                                    echo "<td>" . $row['Nombre'] . "</td>";
                                    echo "<td>";
                                    $url_update_t = 'updateTablero/';
                                    echo "<a href='" . $url_update_t . $row['Id_tablero'] . "' class=boton-modificar><img src='../assets/editar.svg'></a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                // Si no hay filas en el resultado, mostrar un mensaje de que no hay registros
                                echo "<tr><td colspan='6'> No hay registros </td></tr>";
                            }

                            // Cerrar la conexion a la base de datos
                            $db->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="filtro-y-crear">
                <div class="crear-ambiente">
                    <?php
                        // Construir la URL adecuada para el boton de "Gestión de ambientes"
                        $url_create = 'createTablero/';
                    ?>
                    <ul>
                        <li><a href="<?php echo $url_create; ?>" id="btn-create">Crear nuevo tablero</a></li>
                    </ul>
                </div>
            </div>

        </section>
    </main>

    <footer id="footer">
        <div class="regresar">
            <?php
                $url_regresar = 'home';
            ?>
            <a href="<?php echo $url_regresar; ?>"class="button boton-centrado" id="btn-regresar">Regresar</a>
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

    <!-- Datatable -->
    <script>
        $(document).ready(function() {
            var table = $('#tabla-ambientes').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                paging: true,
                pageLength: 10 // Mostrar 10 registros por página
            });

            // Configuración de los botones para mostrar/ocultar columnas
            $('.toggle-vis').on('click', function(e) {
                e.preventDefault();

                // Obtenemos el índice de la columna correspondiente al botón
                var columnIdx = parseInt($(this).attr('data-column'));

                // Obtenemos el estado de visibilidad de la columna y lo invertimos
                var column = table.column(columnIdx);
                column.visible(!column.visible());
            });
        });
    </script>

    <!-- Boostrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>