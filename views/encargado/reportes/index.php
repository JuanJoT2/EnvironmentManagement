<?php

    require_once 'config/db.php';
        $db = Database::connect();
        session_start();

        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['id_usuario'])) {
            echo "<script>alert('Sesión no iniciada'); window.location.href = '/gestiondeambientes/login';</script>";
            exit();
        }

        // Obtener el ID del usuario desde la sesión
        $id_usuario = $_SESSION['id_usuario'];

        // Obtener los datos del usuario
        $query = "SELECT Id_usuario, Nombres, Apellidos, Rol FROM t_usuarios WHERE Id_usuario = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo "<script>alert('Usuario no encontrado'); window.location.href = '/gestiondeambientes/login';</script>";
            exit();
        } else {
            $row = $result->fetch_assoc();
            $usuario = $row['Nombres'] . ' ' . $row['Apellidos'];       
            $cargo = $row['Rol'];

            // Mostrar el ID del usuario
            // echo "ID del usuario: " . $usuario;
        }

    $query = "SELECT Id_ambiente FROM t_ambientes WHERE Nombre = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $nombre);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id_ambiente = $row["Id_ambiente"];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        header('Content-Type: application/json');

        // Verificar que las observaciones estén definidas y no estén vacías
        if (isset($_POST['observaciones'])) {
            $observaciones = $_POST['observaciones'];

            // Datos adicionales
            $fechaHora = date('Y-m-d H:i:s');
            $estado = 1; // Estado inicial del reporte

            // Insertar el reporte en la base de datos
            $stmt = $db->prepare("INSERT INTO t_reportes (FechaHora, Id_usuario, Id_ambiente, Estado, Observaciones) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('siiss', $fechaHora, $id_usuario, $id_ambiente, $estado, $observaciones);

            if ($stmt->execute()) {
                // Aquí se asume que $observaciones es un array con las observaciones y sus respectivos seriales
                foreach ($_POST['observacion'] as $serial => $observacion) {
                    if (!empty($observacion)) {
                        // Actualizar la observación en la tabla t_computadores
                        $updateStmt = $db->prepare("UPDATE t_computadores SET Observaciones = ? WHERE Serial = ?");
                        $updateStmt->bind_param('ss', $observacion, $serial);
                        $updateStmt->execute();
                        $updateStmt->close();
                    }
                }

                echo json_encode(["success" => true, "message" => "Reporte insertado correctamente y observaciones actualizadas"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al insertar el reporte"]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "No se recibieron observaciones"]);
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['historial'])) {

        header('Content-Type: application/json');

        // Obtener el historial de observaciones
        $query = "SELECT Serial, Observaciones FROM t_computadores WHERE id_ambiente = ? AND Observaciones IS NOT NULL";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $id_ambiente);
        $stmt->execute();
        $result = $stmt->get_result();

        $observaciones = [];
        while ($row = $result->fetch_assoc()) {
            $observaciones[] = $row['Serial'] . ': ' . $row['Observaciones'];
        }

        echo json_encode(["success" => true, "observaciones" => $observaciones]);
        exit();
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Relacionada</title>
    <link rel="stylesheet" href="../../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: white;
            color: #333;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            justify-content: space-between;
        }

        .header img {
            height: 40px;
            margin-right: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.2em;
        }

        .container {
            max-width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sublist {
            display: none;
        }

        .hardware-list {
            display: none;
            margin-top: 20px;
        }

        h1 {
            color: #333;
            margin-top: 0;
            font-size: 1.5em;
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .label {
            font-weight: bold;
        }

        .date-time {
            text-align: center;
        }

        .instrucciones {
            padding: 15px;
            font-size: 0.9em;
            text-align: center;
        }

        .submit-btn {
            text-align: center;
            margin-top: 20px;
        }

        .submit-btn input[type='submit'], .submit-btn button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .submit-btn input[type='submit']:hover, .submit-btn button:hover {
            background-color: #45a049;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 2px solid #000;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        .close-btn:before {
            content: '✖';
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>
    
    <main>
        <form id="observacionForm" action="" method="POST">
            <div class="header">
                <img src="../../assets/Logo-Sena.jpg" alt="logo">
                <h1>Gestión de Ambiente</h1>
            </div>
            <div class="container">
                <p class="date-time">Fecha: <?php echo $fecha_actual; ?> Hora: <?php echo $hora_actual; ?></p>
                <h1 class="titulo"> <?php echo $nombre; ?></h1>
                <ul>
                    <li class="expandable">
                        <span class="expand">+</span>
                        <span class="label">Infraestructura:</span>
                        <ul class="sublist"></ul>
                    </li>
                    <li class="expandable">
                        <span class="expand">+</span>
                        <span class="label">Mobiliario:</span>
                        <ul class="sublist">
                            <span class="label">Sillas:</span>
                            <span class="value"><?php echo $sillas; ?></span><br>
                            <span class="label">Mesas:</span>
                            <span class="value"><?php echo $mesas; ?></span><br>
                            <span class="label">Tableros:</span>
                            <span class="value"><?php echo $tablero; ?></span>
                        </ul>
                    </li>
                </ul>
                <div class="submit-btn">
                    <input type="submit" value="Enviar">
                </div>
            </div>
        </form>
    </main>

    <footer id="footer">
    <a href="../controllers/cerrarSesion.php" class="btn btn-danger mb-2 logout">Cerrar sesión</a>
    <p>© Gestión de ambientes Sena. All Rights Reserved</p>
    <p>Designed by Sena</p>
    </footer>

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
                footer.style.display = "block";
            } else {
                footer.style.position = "relative";
                footer.style.display = "block";
            }
        });
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
                        window.location.href = "../../controllers/cerrarSesion.php";
                    }
                });
            });
        });
    </script>

    <!-- Enviar cerrar sesión -->
    <script>
        let startTime = Date.now();
        window.addEventListener("beforeunload", function () {
            let elapsedTime = Math.floor((Date.now() - startTime) / 1000);
            navigator.sendBeacon('/gestiondeambientes/reportes.php', 
                new URLSearchParams({ tiempo_sesion: elapsedTime })
            );
        });
    </script>

    <!-- Destruir sesión -->
    <script>
        function bloquearHistorial() {
            history.pushState(null, "", location.href);
            window.onpopstate = function () {
                history.pushState(null, "", location.href);
            };
        }

        document.addEventListener("DOMContentLoaded", function () {
            bloquearHistorial();
            if (!sessionStorage.getItem("autenticado")) {
                window.location.href = "/gestiondeambientes/login";
            }
        });

        sessionStorage.setItem("autenticado", "SI");
    </script>


    <div class="submit-btn">
            <button onclick="mostrarHistorial()">Historial de Observaciones</button>
            <button onclick="redirigirConQR()">Ir al Panel Administrativo</button>
        </div>

    <!-- Popup para mostrar el historial -->
    <div class="popup" id="historialPopup">
        <div class="popup-content">
            <button class="close-btn" onclick="cerrarPopup()"></button>
            <h2>Historial de Observaciones</h2>
            <ul id="historialList">
                <!-- Aquí se mostrará el historial de observaciones -->
            </ul>
        </div>
    </div>

    <script>
        function redirigirConQR() {
            let qrId = new URLSearchParams(window.location.search).get('id'); // Obtener ID del QR de la URL actual
            if (qrId) {
                window.location.href = `admin_panel.php?id=${qrId}`;
            } else {
                alert("No se encontró el ID del QR.");
            }
        }
    </script>

    <script>

        function mostrarHistorial() {
                // Hacer una solicitud AJAX para obtener el historial de observaciones
                fetch('?historial=1')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Construir la lista de observaciones
                            const historialList = document.getElementById('historialList');
                            historialList.innerHTML = ''; // Limpiar la lista antes de agregar nuevas observaciones
                            data.observaciones.forEach(observation => {
                                const listItem = document.createElement('li');
                                listItem.textContent = observation;
                                historialList.appendChild(listItem);
                            });
                            // Mostrar el popup
                            const historialPopup = document.getElementById('historialPopup');
                            historialPopup.style.display = 'block';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener el historial:', error);
                    });
        }

        // Función para cerrar el popup
        function cerrarPopup() {
            const historialPopup = document.getElementById('historialPopup');
            historialPopup.style.display = 'none';
        }
                
        function toggleList(element) {
            var sublist = element.parentElement.querySelector(".sublist");
            sublist.style.display = sublist.style.display === "none" ? "block" : "none";
            element.innerText = sublist.style.display === "none" ? "+" : "-";
        }

        function toggleObservationField(checkboxId, observationId) {
            var checkbox = document.getElementById(checkboxId);
            var observationField = document.getElementById(observationId);
            observationField.style.display = checkbox.checked ? "none" : "block";
        }

        function filterList(input, listClassName) {
            var filter = input.value.toUpperCase();
            var ul = document.getElementsByClassName(listClassName)[0];
            var li = ul.getElementsByTagName('li');
            for (var i = 0; i < li.length; i++) {
                var span = li[i].getElementsByTagName("span")[2];
                var txtValue = span.textContent || span.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }

        // Llamar a toggleObservationField para cada checkbox al cargar la página
        window.addEventListener('load', function() {
            <?php foreach($computadores as $computador): ?>
                toggleObservationField('checkpc<?php echo $computador['Serial']; ?>', 'observacion<?php echo $computador['Serial']; ?>');
            <?php endforeach; ?>
        });

        document.getElementById('observacionForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            var observaciones = [];

            <?php foreach($computadores as $computador): ?>
                var checkbox = document.getElementById('checkpc<?php echo $computador['Serial']; ?>');
                var observationField = document.getElementById('observacion<?php echo $computador['Serial']; ?>');
                if (!checkbox.checked && observationField.value) {
                    observaciones.push('<?php echo $computador['Serial']; ?>: ' + observationField.value);
                }
            <?php endforeach; ?>

            formData.append('observaciones', observaciones.join('; '));

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message,
                        confirmButtonText: 'OK',
                        confirmButtonClass: 'custom-btn-class'
                    }).then(() => {
                        window.location.reload(); // Recargar la página
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Se guardo el reporte',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            });

        });
    </script>

</body>
</html>


