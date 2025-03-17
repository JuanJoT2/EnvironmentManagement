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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
</head>

<body>
    
    <header class="container ambiNav">
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
            <button class="toggle-vis indicators" data-column="0">Id</button>
            <button class="toggle-vis indicators" data-column="1">Nombre</button>
            <button class="toggle-vis indicators" data-column="2">Torre</button>
            <button class="toggle-vis indicators" data-column="3">Computadores</button>
            <button class="toggle-vis indicators" data-column="4">Tvs</button>
            <button class="toggle-vis indicators" data-column="5">Sillas</button>
            <button class="toggle-vis indicators" data-column="6">Mesas</button>
            <button class="toggle-vis indicators" data-column="7">Tableros</button>
            <button class="toggle-vis indicators" data-column="8">Niñeras</button>
            <button class="toggle-vis indicators" data-column="9">Accion</button>
        </div>
    </nav>

    <main class="container contenido">
        <section class="ambiente" id="section-ambiente">

            <div class="subtitulo-ambiente">
                <h2>Ambientes</h2>
            </div>

            <div class="descripcion-ambiente">
                <p>Gestión de ambientes de formación</p>
            </div>

            <div class="tabla-ambientes tabla-scroll">
                <table class="table table-striped table_id" border="1" id="tabla-ambientes">
                    <thead class="aspects">
                        <tr class="indicadores">
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Torre</th>
                        <th>Computadores</th>
                        <th>Tvs</th>
                        <th>Sillas</th>
                        <th>Mesas</th>
                        <th>Tableros</th>
                        <th>Niñeras</th>
                        <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM t_ambientes";

                        if (!empty($filtros)) {
                            $query .= " WHERE " . implode(" AND ", $filtros);
                        }

                        $result = $db->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['Id_ambiente'] . "</td>";
                                echo "<td>" . $row['Nombre'] . "</td>";
                                echo "<td>" . $row['Torre'] . "</td>";
                                echo "<td>" . $row['Computadores'] . "</td>";
                                echo "<td>" . $row['Tvs'] . "</td>";
                                echo "<td>" . $row['Sillas'] . "</td>";
                                echo "<td>" . $row['Mesas'] . "</td>";
                                echo "<td>" . $row['Tableros'] . "</td>";
                                echo "<td>" . $row['Nineras'] . "</td>";
                                echo "<td>";
                                if ($row['Estado'] !== 'Inhabilitado') {
                                    $url_update = 'updateAmbiente/';
                                    echo "<a href='" . $url_update . $row['Id_ambiente'] . "' class='boton-modificar'><img src='../assets/editar.svg'></a>";

                                    $url_update = 'generateQR/';
                                    echo "<a href='" . $url_update . $row['Id_ambiente'] . "' class='boton-generar-qr' boton-accion ><img src='../assets/qr-code.svg'></a>";
                                } else {
                                    echo "<a href='#' onclick='confirmarHabilitar(" . $row['Id_ambiente'] . ")' class='boton-habilitar boton-accion'><img src='../assets/habilitar.svg'></a>";
                                }
                                if ($row['Estado'] !== 'Inhabilitado') {
                                    echo "<a href='#' onclick='confirmarInhabilitar(" . $row['Id_ambiente'] . ")' class='boton-inhabilitar boton-accion'><img src='../assets/inhabilitar1.svg'></a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11' class='text-center'>No hay registros</td></tr>";
                        }

                        $db->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="filtro-y-crear">
                <div class="crear-ambiente">
                    <?php
                    $url_create = 'createAmbiente/';
                    ?>
                    <ul>
                        <li><a href="<?php echo $url_create; ?>" id="btn-create">Crear Nuevo Ambiente</a></li>
                    </ul>
                </div>
            </div>

        </section>
    </main>

    <!-- Footer  -->

    <footer class="text-center p-3 bg-dark text-white mt-4">

        <div class="regresar">
            <?php
                $url_regresar = 'home';
            ?>
            <a href="<?php echo $url_regresar; ?>"class="button boton-centrado" id="btn-regresar">Regresar</a>
        </div>

        <div class="salir">
            <a href="../controllers/cerrarSesion.php" id="btn_salir" class="button-admin">Salir</a>
        </div>
        <p>© 2025 Gestión de Ambientes de Formación - Todos los derechos reservados.</p>
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

    <!-- Script datatable -->
    <script>
        $(document).ready(function() {
            var table = $('#tabla-ambientes').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                paging: true,
                pageLength: 10
            });
            $('.toggle-vis').on('click', function(e) {
                e.preventDefault();
                var columnIdx = $(this).attr('data-column');
                var column = table.column(columnIdx);
                column.visible(!column.visible());

                // Esperar un poco y luego ajustar las columnas
                setTimeout(function() {
                    table.columns.adjust().draw();
                }, 200);
            });
        });
    </script>

    <!-- Script ihnabilitar ambientes -->
    <script>
        function confirmarInhabilitar(id) {
            if (confirm("¿Estás seguro de que deseas inhabilitar este ambiente?")) {
                window.location.href = "inhabilitarAmbiente/" + id;
            }
        }
        function confirmarHabilitar(id) {
            if (confirm("¿Estás seguro de que deseas habilitar este ambiente?")) {
                window.location.href = "habilitarAmbiente/" + id;
            }
        }
    </script>

    <!-- Boostrap -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

