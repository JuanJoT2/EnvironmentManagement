<?php

// instructorModel.php

require(__DIR__ . '/../config/db.php');

class instructorModel {

    public function leerQR($qr_content) {
        $conn = Database::connect();
        $sql = "SELECT t_ambientes.*, 
                       t_computadores.Serial as SerialComputador, 
                       t_computadores.CheckPc as CheckPcs, 
                       t_computadores.Marca as MarcaComputador, 
                       t_computadores.Modelo as ModeloComputador
                FROM t_ambientes
                LEFT JOIN t_computadores ON t_ambientes.Id_Ambiente = t_computadores.Id_Ambiente
                WHERE t_ambientes.id_ambiente = '$qr_content'";
    
        $result = $conn->query($sql);
    
        if (!$result) {
            die("Error en la consulta SQL: " . $conn->error);
        }
    
        $ambientes = [];
    
        while ($row = $result->fetch_assoc()) {
            $ambientes[] = [
                'Nombre' => $row['Nombre'],
                'SerialComputador' => $row['SerialComputador'] ?? null,
                'MarcaComputador' => $row['MarcaComputador'] ?? null,
                'ModeloComputador' => $row['ModeloComputador'] ?? null,
                'CheckPc' => $row['CheckPcs'] ?? null,
                'Tvs' => $row['Tvs'],
                'Sillas' => $row['Sillas'],
                'Mesas' => $row['Mesas'],
                'Tableros' => $row['Tableros'],
                'Nineras' => $row['Nineras'],
                'CheckInfraestructura' => $row['CheckInfraestructura'],
                'Observaciones' => $row['Observaciones']
            ];
        }
    
        return $ambientes;
    }

    public function registrarIngreso($id_usuario, $id_ambiente) {
        $conn = Database::connect();
        $hora_ingreso = date("Y-m-d H:i:s");
    
        error_log("Intentando registrar ingreso: Usuario $id_usuario, Ambiente $id_ambiente, Hora: $hora_ingreso");
    
        $sql = "INSERT INTO t_tiempos (Id_usuario, Id_ambiente, Hora_ingreso) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            error_log("Error en la preparación de la consulta: " . $conn->error);
            return false;
        }
    
        $stmt->bind_param("iis", $id_usuario, $id_ambiente, $hora_ingreso);
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            error_log("Ingreso registrado correctamente.");
        } else {
            error_log("Error al registrar el ingreso.");
        }
    
        $stmt->close();
    }       
    
    public function registrarSalida($id_usuario, $id_ambiente) {
        $conn = Database::connect();
        $hora_salida = date("Y-m-d H:i:s");
    
        $sql = "UPDATE t_tiempos SET Hora_salida = ? WHERE Id_usuario = ? AND Id_ambiente = ? AND Hora_salida IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $hora_salida, $id_usuario, $id_ambiente);
        $stmt->execute();
        $stmt->close();
    }
}

?>
