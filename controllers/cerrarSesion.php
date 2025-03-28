<?php
    // session_start();
    // include_once '../models/InstructorModel.php';

    // // Verificar si el usuario y el ambiente están en sesión antes de eliminarlos
    // if (isset($_SESSION['id_usuario']) && isset($_SESSION['id_ambiente'])) {
    //     $id_usuario = $_SESSION['id_usuario'];
    //     $id_ambiente = $_SESSION['id_ambiente'];

    //     // Registrar la hora de salida antes de destruir la sesión
    //     $instructorModel = new InstructorModel();
    //     $instructorModel->registrarSalida($id_usuario, $id_ambiente);
    // }

    // // Destruir todas las variables de sesión
    // $_SESSION = array();

    // // Eliminar la cookie de sesión en el navegador (si existe)
    // if (!isset($_SESSION['aut']) || $_SESSION['aut'] !== "SI") {
    //     session_unset();
    //     session_destroy();

    //     if (isset($_SESSION['id_usuario'])) {
    //         $id_usuario = $_SESSION['id_usuario'];
    //         $tiempo_transcurrido = $_POST['tiempo_sesion'] ?? 0;

    //         $stmt = $db->prepare("UPDATE t_usuarios SET tiempo_sesion = ? WHERE Id_usuario = ?");
    //         $stmt->bind_param('ii', $tiempo_transcurrido, $id_usuario);
    //         $stmt->execute();
    //         $stmt->close();
    //     }

    //     echo "<script>
    //         sessionStorage.clear();
    //         localStorage.clear();
    //         window.location.href = '/gestiondeambientes/login';
    //     </script>";
    //     exit();
    // }

    session_start();
    include_once '../models/InstructorModel.php';
    include_once '../config/db.php';  // Asegúrate de que la conexión a la base de datos esté incluida

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
    session_unset();
    session_destroy();

    // Si la sesión está autenticada y quieres actualizar el tiempo transcurrido:
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];

        // Aquí asumimos que el tiempo transcurrido lo envíes por POST desde el frontend
        $tiempo_transcurrido = $_POST['tiempo_sesion'] ?? 0;

        // Conexión a la base de datos para actualizar el tiempo de sesión
        $conn = Database::connect();
        $stmt = $conn->prepare("UPDATE t_usuarios SET tiempo_sesion = ? WHERE Id_usuario = ?");
        $stmt->bind_param('ii', $tiempo_transcurrido, $id_usuario);
        $stmt->execute();
        $stmt->close();
    }

    // Limpiar sessionStorage y localStorage, y redirigir al login
    echo "<script>
        sessionStorage.clear();
        localStorage.clear();
        window.location.href = '/gestiondeambientes/login';
    </script>";
    exit();
?>
