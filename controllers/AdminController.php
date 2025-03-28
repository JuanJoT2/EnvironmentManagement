<?php

include_once 'models/AdminModel.php';

// Apartado de controlador para LOGIN------------------------------------------------------------------------
class AdminController {

    public function home() {
        include 'views/administrador/index.php';
    }
    
    // Apartado de controlador para Ambientes-------------------------------------------------------------------------

    public function ambientes() {
        include 'views/administrador/ambientes/index.php';
    }

    public function createAmbiente() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $torre = $_POST["torre"];
            $computadores = $_POST["computadores"];
            $checkPcs = isset($_POST["checkPcs"]) ? 1 : 0;
            $tvs = $_POST["tvs"];
            $checkTvs = isset($_POST["checkTvs"]) ? 1 : 0;
            $sillas = $_POST["sillas"];
            $checkSillas = isset($_POST["checkSillas"]) ? 1 : 0;
            $mesas = $_POST["mesas"];
            $checkMesas = isset($_POST["checkMesas"]) ? 1 : 0;
            $tableros = $_POST["tableros"];
            $checkTableros = isset($_POST["checkTableros"]) ? 1 : 0;
            $nineras = $_POST["nineras"];
            $checkNineras = isset($_POST["checkNineras"]) ? 1 : 0;
            $checkInfraestructura = isset($_POST["checkInfraestructura"]) ? 1 : 0;
            $estado = 1; // Por defecto se crea activo
            $observaciones = $_POST["observaciones"];

            $adminModel = new AdminModel();
            $result = $adminModel->guardarAmbiente($nombre, $torre, $computadores, $checkPcs, $tvs, $checkTvs, $sillas, $checkSillas, $mesas, $checkMesas, $tableros, $checkTableros, $nineras, $checkNineras, $checkInfraestructura, $estado, $observaciones);

            if ($result) {
                // Lógica para generar el contenido del QR
                $contenido_qr = "Nombre: $nombre\nTorre: $torre\nComputadores: $computadores\nTVs: $tvs\nSillas: $sillas\nMesas: $mesas\nTableros: $tableros\nNineras: $nineras\nInfraestructura: $checkInfraestructura\nObservaciones: $observaciones";
                
                // Lógica para generar el código QR
                $qrCodeAPIURL = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($contenido_qr) .'&rand=' . uniqid();
                echo json_encode(["success" => true]);
                header("Location: ../ambientes");
                exit();
            } else {
                echo json_encode(["success" => false]);
                header("Location: index.php?error=Error al crear el ambiente");
                exit();
            }
        } else {
            include 'views/administrador/ambientes/create.php';
        }
    }

    public function updateAmbiente($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $torre = $_POST["torre"];
            $observaciones = $_POST["observaciones"];

            $adminModel = new AdminModel();
            $result = $adminModel->modificarAmbiente($id, $nombre, $torre, $observaciones);

            if ($result) {
                header("Location: ../ambientes");
                exit();
            } else {
                header("Location: index.php?error=Error al actualizar el ambiente&id=$id");
                exit();
            }
        } else {
            $adminModel = new AdminModel();
            $ambiente = $adminModel->obtenerAmbientePorId($id);
            include 'views/administrador/ambientes/update.php';
        }
    }

    public function inhabilitarAmbiente($id) {
        $adminModel = new AdminModel();
        $result = $adminModel->inhabilitarAmbiente($id);

        if ($result) {
            echo "<script>alert('Ambiente inhabilitado exitosamente');</script>";
        } else {
            echo "<script>alert('Error al inhabilitar el ambiente');</script>";
        }
        
        header("Location: ../ambientes");
        exit();
    }

    public function habilitarAmbiente($id) {
        $adminModel = new AdminModel();
        $result = $adminModel->habilitarAmbiente($id);

        if ($result) {
            echo "<script>alert('Ambiente habilitado exitosamente');</script>";
        } else {
            echo "<script>alert('Error al habilitar el ambiente');</script>";
        }
        
        header("Location: ../ambientes");
        exit();
    }
    // Apartado de controlador para COMPUTADORES-----------------------------------------------------------------------

    public function computadores(){
        require 'views/administrador/computadores/index.php';
    }

    public function createComputador() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tipo = $_POST["tipo"];
            $marca = $_POST["marca"];
            $modelo = $_POST["modelo"];
            $serial = $_POST["serial"];
            $placaInventario = $_POST["placaInventario"];
            $id_ambiente = $_POST["id_ambiente"];
            $checkPc = isset($_POST["checkPc"]) ? 1 : 0;
            $hardware = isset($_POST["hardware"]) ? 1 : 0;
            $software = isset($_POST["software"]) ? 1 : 0;
            $observaciones = $_POST["observaciones"];
    
            $adminModel = new AdminModel();
            $result = $adminModel->guardarComputador($tipo, $marca, $modelo, $serial, $placaInventario, $id_ambiente, $checkPc, $hardware, $software, $observaciones);
    
            // Enviar respuesta JSON dependiendo del resultado
            if ($result) {
                echo json_encode(['success' => true]);  // Respuesta de éxito en formato JSON
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo crear el computador.']);  // Respuesta de error en formato JSON
            }
            exit();  // Importante para evitar que se siga ejecutando el script
        } else {
            include 'views/administrador/computadores/create.php';
        }
    }
    
    public function updateComputador($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tipo = $_POST["tipo"];
            $marca = $_POST["marca"];
            $modelo = $_POST["modelo"];
            $serial = $_POST["serial"];
            $placaInventario = $_POST["placaInventario"];
            $nuevoIdAmbiente = isset($_POST["id_ambiente"]) ? $_POST["id_ambiente"] : null; // Corregido para manejar el caso en que id_ambiente no esté definido
            $checkPc = isset($_POST["checkPc"]) ? 1 : 0;
            $hardware = isset($_POST["hardware"]) ? 1 : 0;
            $software = isset($_POST["software"]) ? 1 : 0;
            $observaciones = isset($_POST["observaciones"]) ? $_POST["observaciones"] : ''; // Corregido para manejar el caso en que observaciones no esté definido

            $adminModel = new AdminModel();
            $result = $adminModel->modificarComputador($id, $tipo, $marca, $modelo, $serial, $placaInventario, $nuevoIdAmbiente, $checkPc, $hardware, $software, $observaciones);

            if ($result) {
                // Si la actualización fue exitosa
                echo "
                <link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css' rel='stylesheet'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'El computador se ha actualizado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#39a900'
                        }).then(() => {
                            window.location.href = '../computadores';
                        });
                    });
                </script>";
                exit();
            } else {
                // Si hay un error en la actualización
                echo "
                <link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css' rel='stylesheet'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al actualizar el computador.',
                            icon: 'error',
                            confirmButtonText: 'Intentar de nuevo',
                            confirmButtonColor: '#d33'
                        }).then(() => {
                            window.location.href = './$id';
                        });
                    });
                </script>";
                exit();
            }
        } else {
            // Obtener los datos del computador existente
            $adminModel = new AdminModel();
            $computador = $adminModel->obtenerComputadorPorId($id);

            if ($computador) {
                // Obtener los datos del ambiente asociado al computador
                $idAmbiente = $computador['Id_ambiente'];
        
                // Obtener los datos del ambiente
                $ambiente = $adminModel->obtenerAmbientePorId($idAmbiente);
        
                // Renderizar el formulario de actualización con los datos del computador y del ambiente
                include 'views/administrador/computadores/update.php';
            } else {
                // Manejar el caso en que el computador no existe
                echo "Error: El computador especificado no existe.";
                exit();
            }
        }
    }

    // Apartado de controlador para Televisores (tv's)----------------------------------------------------------------
    public function tvs() {
        include 'views/administrador/tvs/index.php';
    }
    
    public function createTvs() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $marca = $_POST["marca"];
            $modelo = $_POST["modelo"];
            $serial = $_POST["serial"];
            $placaInventario = $_POST["placaInventario"];
            $id_ambiente = $_POST["id_ambiente"];
            $checkTv = isset($_POST["checkTv"]) ? 1 : 0;
            $observaciones = $_POST["observaciones"];

            $adminModel = new AdminModel();
            $result = $adminModel->guardarTelevisor($marca, $modelo, $serial, $placaInventario, $id_ambiente, $checkTv, $observaciones);

     
            if ($result) {
                echo json_encode(['success' => true]); 
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo crear el Televisor.']);  
            }
            exit();
        }   else {
            include 'views/administrador/tvs/create.php';
        }
    }

    public function updateTvs($id) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $marca = $_POST["marca"];
                $modelo = $_POST["modelo"];
                $serial = $_POST["serial"];
                $placaInventario = $_POST["placaInventario"];
                $nuevoIdAmbiente = isset($_POST["id_ambiente"]) ? $_POST["id_ambiente"] : null; // // Corregido para manejar el caso en que id_ambiente no esté definido
                $checkTv = isset($_POST["checkTv"]) ? 1 : 0; 
                $observaciones = isset($_POST["observaciones"]) ? $_POST["observaciones"] : ''; // Corregido para manejar el caso en que observaciones no esté definido

                $adminModel = new AdminModel();
                $result = $adminModel->modificarTelevisor($id, $marca, $modelo, $serial, $placaInventario, $nuevoIdAmbiente, $checkTv, $observaciones);

                if ($result) {
                    // Redirigir a la lista de computadores si la actualización fue exitosa
                    header("Location: ../tvs");
                    exit();
                } else {
                    // Manejar el caso en el que ocurra un error al actualizar el televisor
                    header("Location: index.php?error=Error al actualizar el televisor&id=$id");
                    exit();
                }
            } else {
                // Obtener los datos del televisor existente
                $adminModel = new AdminModel();
                $televisor = $adminModel->obtenerTelevisorPorId($id);

                if ($televisor) {
                    // Obtener los datos del ambiente asociado al televisor
                    $idAmbiente = $televisor['Id_ambiente'];
                
                    // Obtener los datos del ambiente
                    $ambiente = $adminModel->obtenerAmbientePorId($idAmbiente);

                    // Renderizar el formulario de actualización con los datos del televisor y del ambiente
                    include 'views/administrador/tvs/update.php';
                } else {
                    // Manejar el caso en que el televisor no exista
                    echo "Error: El televisor especificado no existe.";
                }
            }
    }

    // Apartado de controlador para Tableros

    public function tableros() {
        include 'views/administrador/tableros/index.php';
    }

    public function createTablero() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $marca = $_POST["marca"];
            $placaInventario = $_POST["placaInventario"];
            $id_ambiente = $_POST["id_ambiente"];
            $checkTablero = isset($_POST["checkTablero"]) ? 1 : 0;
            $observaciones = $_POST["observaciones"];

            $adminModel = new AdminModel();
            $result = $adminModel->guardarTablero($marca, $placaInventario, $id_ambiente, $checkTablero, $observaciones);

            // Enviar respuesta JSON dependiendo del resultado
            if ($result) {
                echo json_encode(['success' => true]);  // Respuesta de éxito en formato JSON
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo crear el Tablero.']);  // Respuesta de error en formato JSON
            }
            exit();  // Importante para evitar que se siga ejecutando el script
            }   else {
            include 'views/administrador/tableros/create.php';
        }
        
    }

    public function updateTablero($id) {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $marca = $_POST["marca"];
            $placaInventario = $_POST["placaInventario"];
            $nuevoIdAmbiente = isset($_POST["id_ambiente"]) ? $_POST["id_ambiente"] : null;
            $observaciones = isset($_POST["observaciones"]) ? $_POST["observaciones"] : '';

            $adminModel = new AdminModel();
            $result = $adminModel->modificarTablero($id, $marca, $placaInventario, $nuevoIdAmbiente, $checkTablero, $observaciones);

            if ($result) {
                // Redirigir a la lista del tablero si la actualización fue exitosa
                header("Location: ../tableros");
                exit();
            } else {
                // Manejar el caso en que ocurra un error al actualizar el tablero
                header("Location: index.php?error=Error al actualizar el tablero&id=$id");
                exit();
            }
        } else {
            // Obtener los datos del tabkero existente
            $adminModel = new AdminModel();
            $tablero = $adminModel->obtenerTableroPorId($id);

            if ($tablero) {
                // Obtener los datos del ambiente asociado al computador
                $idAmbiente = $tablero['Id_ambiente'];
        
                // Obtener los datos del ambiente
                $ambiente = $adminModel->obtenerAmbientePorId($idAmbiente);
        
                // Renderizar el formulario de actualización con los datos del computador y del ambiente
                include 'views/administrador/tableros/update.php';
            } else {
                // Manejar el caso en que el computador no existe
                echo "Error: El computador especificado no existe.";
            }
        }
    }

    // Apartado de controlador para REPORTES--------------------------------------------------------------------------

    public function reportes() {
        include 'views/administrador/reportes/index.php';
    }

    public function generateQR($id) {
        $id_ambiente = $id;

        $contenido_qr = $id_ambiente;

        $qrCodeAPIURL = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($contenido_qr);

        // Muestra el QR en la página
        echo "<img src='" . $qrCodeAPIURL . "' alt='QR Code'>";

    }

    public function enviarInforme() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["observacion"])) {
            $observacion = $_POST["observacion"];
            $id_usuario = $_SESSION["id_usuario"];
            $id_ambiente = $_POST["id_ambiente"];
    
            // Llamar al método para agregar el informe en el modelo
            $adminModel = new AdminModel();
            $result = $adminModel->insertarReporte($observacion, $id_usuario, $id_ambiente);
    
            if ($result) {
                header("Location: ../reportes");
            } else {
                echo "<script>alert('Error al agregar el reporte');</script>";
            }
            exit();
        } else {
            echo "Error: Método de solicitud incorrecto.";
        }
    }
    
    public function agregarReporte($observacion, $id_usuario, $id_ambiente) {
        $adminModel = new AdminModel();
        $result = $adminModel->insertarReporte($observacion, $id_usuario, $id_ambiente);
        if ($result) {
            // Redireccionar de vuelta a la página de reportes del instructor
            header("Location: ../reportes");
            exit();
        } else {
            // Manejar el caso en que ocurra un error al agregar el reporte
            echo "<script>alert('Error al agregar el reporte');</script>";
            exit();
        }
    }

    // Historial de REPORTES

    public function historial() {
        include 'views/administrador/reportes/historial.php';
    }

    public function mostrarReporte() {

    }
}
?>