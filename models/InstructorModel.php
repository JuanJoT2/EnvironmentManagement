<?php

// instructorModel.php

include_once 'config/db.php';

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
}

?>
