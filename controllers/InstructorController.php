<?php

include_once 'models/InstructorModel.php';

class InstructorController {

        public function home() {
            include 'views/instructor/index.php';
        }

        public function readQR($id) {
            $qr_content = $id;
        
            $instructorModel = new InstructorModel();
            $result = $instructorModel->leerQR($qr_content);
        
            if (!empty($result)) {
                $nombre = htmlspecialchars($result[0]["Nombre"] ?? "No disponible");
        
                // Manejar los computadores asegurando que haya información válida
                $computadores = array_map(function($item) {
                    return [
                        'Serial' => $item['SerialComputador'] ?? 'No registrado',
                        'Marca' => $item['MarcaComputador'] ?? 'No registrado',
                        'Modelo' => $item['ModeloComputador'] ?? 'No registrado',
                        'CheckPc' => $item['CheckPc'] ?? 'No registrado'
                    ];
                }, $result);
        
                // Manejar los demás valores asegurando que existan
                $tv = htmlspecialchars($result[0]["Tvs"] ?? "No registrado");
                $sillas = htmlspecialchars($result[0]["Sillas"] ?? "No registrado");
                $mesas = htmlspecialchars($result[0]["Mesas"] ?? "No registrado");
                $tablero = htmlspecialchars($result[0]["Tableros"] ?? "No registrado");
                $archivador = htmlspecialchars($result[0]["Nineras"] ?? "No registrado");
                $infraestructura = htmlspecialchars($result[0]["CheckInfraestructura"] ?? "No registrado");
                $observacion = htmlspecialchars($result[0]["Observaciones"] ?? "No registrado");
        
                date_default_timezone_set('America/Bogota');
                $fecha_actual = date("d/m/Y");
                $hora_actual = date("H:i");
        
                include 'views/instructor/reportes/index.php';
            } else {
                echo "No se encontró información relacionada para el código QR escaneado.";
            }
        }    

        public function iniciarSesionAmbiente() {
            $data = json_decode(file_get_contents("php://input"), true);
            $id_usuario = $data['id_usuario'];
            $id_ambiente = $data['id_ambiente'];
        
            $instructorModel = new InstructorModel();
            $instructorModel->registrarIngreso($id_usuario, $id_ambiente);
        }
        
        public function cerrarSesionAmbiente() {
            $data = json_decode(file_get_contents("php://input"), true);
            $id_usuario = $data['id_usuario'];
            $id_ambiente = $data['id_ambiente'];
        
            $instructorModel = new InstructorModel();
            $instructorModel->registrarSalida($id_usuario, $id_ambiente);
        }
             
    }

?>
