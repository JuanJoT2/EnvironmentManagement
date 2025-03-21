<?php
include_once 'config/db.php';



    class AdminModel {  

        // Apartado de Modelo para AMBIENTES---------------------------------------------------------------------------

        public function guardarAmbiente($nombre, $torre, $computadores, $checkPcs, $tvs, $checkTvs, $sillas, $checkSillas, $mesas, $checkMesas, $tableros, $checkTableros, $nineras, $checkNineras, $checkInfraestructura, $estado, $observaciones) {
            $conn = Database::connect(); // Conectar a la base de datos
    
            $sql = "INSERT INTO t_ambientes (Nombre, Torre, Computadores, CheckPcs, Tvs, CheckTvs, Sillas, CheckSillas, Mesas, CheckMesas, Tableros, CheckTableros, Nineras, CheckNineras, CheckInfraestructura, Estado, Observaciones)
                    VALUES ('$nombre', '$torre', $computadores, $checkPcs, $tvs, $checkTvs, $sillas, $checkSillas, $mesas, $checkMesas, $tableros, $checkTableros, $nineras, $checkNineras, $checkInfraestructura, '$estado', '$observaciones')";
    
            if ($conn->query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }
        
        public function modificarAmbiente($id, $nombre, $torre, $observaciones) {
            $conn = Database::connect();
            $sql = "UPDATE t_ambientes SET Nombre='$nombre', Torre='$torre', Observaciones='$observaciones' WHERE Id_ambiente=$id";
    
            if ($conn->query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }
    
        public function inhabilitarAmbiente($id) {
            $conn = Database::connect();
            $sql = "UPDATE t_ambientes SET Estado = 'Inhabilitado' WHERE Id_ambiente='$id'";
            
            if ($conn->query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }
    
        public function habilitarAmbiente($id) {
            $conn = Database::connect();
            $sql = "UPDATE t_ambientes SET Estado = 'Habilitado' WHERE Id_ambiente='$id'";
            
            if ($conn->query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }
    
        public function obtenerAmbientePorId($id) {
            $conn = Database::connect();
            $sql = "SELECT * FROM t_ambientes WHERE Id_ambiente='$id'";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        }
        
        // Apartado de Modelo para COMPUTADORES------------------------------------------------------------------------

        public function guardarComputador($tipo, $marca, $modelo, $serial, $placaInventario, $id_ambiente, $checkPc, $hardware, $software, $observaciones) {                        
            $conn = Database::connect();

            $sql = "INSERT INTO t_computadores (Tipo, Marca, Modelo, Serial, PlacaInventario, Id_ambiente, CheckPc, Hardware, Software, Observaciones)
                    VALUES ('$tipo', '$marca', '$modelo', '$serial', '$placaInventario', $id_ambiente, $checkPc, '$hardware', '$software', '$observaciones')";

            if ($conn->query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }

        public function modificarComputador($id, $tipo, $marca, $modelo, $serial, $placaInventario, $nuevoIdAmbiente, $checkPc, $hardware, $software, $observaciones) {
            $conn = Database::connect();

            // Verificar la existencia del nuevo ID de ambiente solo si se proporciona
            if ($nuevoIdAmbiente !== null) {
                $verificarExistencia = $conn->query("SELECT Id_ambiente FROM t_ambientes WHERE Id_ambiente = '$nuevoIdAmbiente'");
                if ($verificarExistencia->num_rows > 0) {
                    // Si el nuevo ID de ambiente existe, proceder con la actualización del computador
                    $sql = "UPDATE t_computadores SET Tipo='$tipo', Marca='$marca', Modelo='$modelo', Serial='$serial', PlacaInventario='$placaInventario', Id_ambiente='$nuevoIdAmbiente', CheckPc='$checkPc', Hardware='$hardware', Software='$software', Observaciones='$observaciones' WHERE Id_computador='$id'";
                    if ($conn->query($sql) === TRUE) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    // Si el nuevo ID de ambiente no existe, manejar el error o devolver false según sea necesario
                    return false;
                }
            } else {
                // Si no se proporciona un nuevo ID de ambiente, actualizar el computador sin verificar la existencia
                $sql = "UPDATE t_computadores SET Tipo='$tipo', Marca='$marca', Modelo='$modelo', Serial='$serial', PlacaInventario='$placaInventario', Id_ambiente=NULL, CheckPc='$checkPc', Hardware='$hardware', Software='$software', Observaciones='$observaciones' WHERE Id_computador='$id'";
                if ($conn->query($sql) === TRUE) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        public function obtenerComputadorPorId($id) {
            $conn = Database::connect();
            $sql = "SELECT * FROM t_computadores WHERE Id_computador='$id'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        }

        // Apartado de Modelo para TELEVISORES (TV's)

        public function guardarTelevisor($marca, $modelo, $serial, $placaInventario, $id_ambiente, $checkTv, $observaciones) {
            $conn = Database::connect();

            $sql = "INSERT INTO t_televisores (Marca, Modelo, Serial, PlacaInventario, Id_ambiente, CheckTv, Observaciones)
                    VALUES ('$marca', '$modelo', '$serial', '$placaInventario', '$id_ambiente', '$checkTv', '$observaciones')";

            if ($conn->query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }

        public function obtenerTelevisorPorId($id) {
            $conn = Database::connect();

            $sql = "SELECT * FROM t_televisores WHERE Id_televisor = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        }

        public function modificarTelevisor($id, $marca, $modelo, $serial, $placaInventario, $nuevoIdAmbiente, $checkTv, $observaciones) {
            $conn = Database::connect();

            if (!$conn) {
                die("Error de conexión a la base de datos: " . $conn->connect_error);
            }

            // Verificar la existencia del nuevo ID del ambiente solo si le proporciona
            if ($nuevoIdAmbiente !== null) {
                $verificarExistencia = $conn->query("SELECT Id_ambiente FROM t_ambientes WHERE Id_ambiente = '$nuevoIdAmbiente'");
                if ($verificarExistencia->num_rows > 0 ) {
                    // Si el nuevo ID de ambiente existe, proceder con la actualización del televisor
                    $sql = "UPDATE t_televisores SET Marca=?, Modelo=?, Serial=?, PlacaInventario=?, Id_ambiente=?, CheckTv=?, Observaciones=? WHERE Id_televisor=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssissi", $marca, $modelo, $serial, $placaInventario, $nuevoIdAmbiente, $checkTv, $observaciones, $id);

                    if ($stmt->execute()) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    // Si el nuevo ID del ambiente no existe, manejar el error o devolver flaso según sea necesario
                    return false;
                }
            } else {
                // Si no se proporciona un nuevo ID de ambiente, actualizar el televisor sin verificar la existencia
                $sql = "UPDATE t_televisores SET Marca='$marca', Modelo='$modelo', Serial='$serial', PlacaInventario='$placaInventario', Id_ambiente=NULL, CheckTv='$checkTv', Observaciones='$observaciones' WHERE Id_televisor='$id'";
                if ($conn->query($sql) === TRUE) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        // Apartado de Modelo para TABLEROS 

        public function guardarTablero($marca, $placaInventario, $id_ambiente, $checkTablero, $observaciones) {
            $conn = Database::connect();

            $sql = "INSERT INTO t_tableros (Marca, PlacaInventario, Id_ambiente, CheckTablero, Observaciones)
                    VALUES ('$marca', '$placaInventario', '$id_ambiente', '$checkTablero', '$observaciones')";

            if($conn->query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }

        public function obtenerTableroPorId($id) {
            $conn = Database::connect();
            $sql = "SELECT * FROM t_tableros WHERE Id_tablero='$id'";
            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        }

        public function modificarTablero($id, $marca, $placaInventario, $nuevoIdAmbiente, $checkTablero, $observaciones) {
            $conn = Database::connect();

            // Verificar la existencia del nuevo ID de ambiente solo si se proporciona
            if ($nuevoIdAmbiente !== null) {
                $verificarExistencia = $conn->query("SELECT Id_ambiente FROM t_ambientes WHERE Id_ambiente = '$nuevoIdAmbiente'");
                if ($verificarExistencia->num_rows > 0) {
                    // Si el nuevo ID del ambiente existe, proceder con la actualización del tablero
                    $sql = "UPDATE t_tableros SET Marca='$marca', PlacaInventario='$placaInventario', Id_ambiente='$nuevoIdAmbiente', CheckTablero='$checkTablero', Observaciones='$observaciones' WHERE Id_tablero='$id'";
                    if ($conn->query($sql) === TRUE) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    // Si el nuevo ID de ambiente no existe, manejar el error al devolver falso según sea necesario
                    return false;
                }
            } else {
                // Si no se proporciona un nuevo ID de ambiente. actualizar el tablero sin verificar la existencia
                $sql = "UPDATE t_tableros SET Marca='$marca', PlacaInventario='$placaInventario', Id_ambiente='$nuevoIdAmbiente', CheckTablero='$checkTablero', Observaciones='$observaciones' WHERE Id_tablero='$id'";
                if ($conn->query($sql) === TRUE) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        // Apartado de Modelo para Reportes----------------------------------------------------------------------------

        public function insertarReporte($observacion, $id_usuario, $id_ambiente) {
            $conn = Database::connect();
            $conn = Database::connect();
            $fechaHora = date("Y-m-d H:i:s"); // Obtenemos la fecha y hora actual
            
            // Insertar la observación en la tabla de reportes
            $query = "INSERT INTO t_reportes (FechaHora, Id_usuario, Id_ambiente, Estado, Observaciones) VALUES ('$fechaHora', '$id_usuario', '$id_ambiente', 'Pendiente', '$observacion')";
            $result = $conn->query($query);
            $conn->close();
            $result = $conn->query($query);
            $conn->close();
            return $result;
        }

}


?>