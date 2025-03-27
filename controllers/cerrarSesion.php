<?php
session_start();
include_once '../models/InstructorModel.php';

// Verificar si el usuario y el ambiente están en sesión antes de eliminarlos
if (isset($_SESSION['id_usuario']) && isset($_SESSION['id_ambiente'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_ambiente = $_SESSION['id_ambiente'];

    // Registrar la hora de salida antes de destruir la sesión
    $instructorModel = new InstructorModel();
    $instructorModel->registrarSalida($id_usuario, $id_ambiente);
}

// Destruir todas las variables de sesión
$_SESSION = array();

// Eliminar la cookie de sesión en el navegador (si existe)
if (!isset($_SESSION['aut']) || $_SESSION['aut'] !== "SI") {
    session_unset();
    session_destroy();

    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
        $tiempo_transcurrido = $_POST['tiempo_sesion'] ?? 0;

        $stmt = $db->prepare("UPDATE t_usuarios SET tiempo_sesion = ? WHERE Id_usuario = ?");
        $stmt->bind_param('ii', $tiempo_transcurrido, $id_usuario);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>
        sessionStorage.clear();
        localStorage.clear();
        window.location.href = '/gestiondeambientes/login';
    </script>";
    exit();
}
?>
