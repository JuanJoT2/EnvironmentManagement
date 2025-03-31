<?php

require_once 'models/UsuariosModel.php'; // Asegúrate de que la ruta y el nombre del archivo sean correctos

class UsuariosController {

    public function usuarios() {
        include 'views/administrador/usuarios/index.php';
    }

    // Método para crear un nuevo usuario
    public function createUsuario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombres = $_POST["nombres"];
            $apellidos = $_POST["apellidos"];
            $correo = $_POST["correo"];
            $rol = $_POST["rol"];
            
            // Usar la contraseña del formulario si existe, de lo contrario generar una nueva
            $clave = !empty($_POST["clave"]) ? $_POST["clave"] : str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    
            $usuariosModel = new UsuariosModel();
            $result = $usuariosModel->guardarUsuario($nombres, $apellidos, $clave, $correo, $rol);
    
            if ($result) {
                echo json_encode(["success" => true, "message" => "Usuario creado exitosamente"]);
            } else {
                echo json_encode(["success" => false, "error" => "Error al crear el usuario"]);
            }
            exit();
        } else {
            include 'views/administrador/usuarios/create.php';
        }
    }

    // Método para actualizar un usuario existente
    public function updateUsuario($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombres = $_POST["nombres"];
            $apellidos = $_POST["apellidos"];
            $clave = $_POST["clave"]; // Puede estar vacía
            $correo = $_POST["correo"];
            $rol = $_POST["rol"];
    
            $usuariosModel = new UsuariosModel();
            $result = $usuariosModel->modificarUsuario($id, $nombres, $apellidos, $clave, $correo, $rol);
    
            if ($result) {
                // Si la actualización fue exitosa
                echo "
                <link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css' rel='stylesheet'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'El usuario se ha actualizado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#39a900'
                        }).then(() => {
                            window.location.href = '../usuarios';
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
                            text: 'Error al actualizar el usuario.',
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
            $usuariosModel = new UsuariosModel();
            $usuario = $usuariosModel->obtenerUsuarioPorId($id);
            include 'views/administrador/usuarios/update.php';
        }
    }    

    public function inhabilitarUsuario($id) {
        $usuariosModel = new UsuariosModel(); // Corregido a UsuariosModel
        $result = $usuariosModel->inhabilitarUsuario($id);
    
        if ($result) {
            echo "<script>alert('Usuario inhabilitado exitosamente');</script>";
        } else {
            echo "<script>alert('Error al inhabilitar el usuario');</script>";
        }
        
        header("Location: ../usuarios");
        exit();
    }
    
    public function habilitarUsuario($id) {
        $usuariosModel = new UsuariosModel(); // Corregido a UsuariosModel
        $result = $usuariosModel->habilitarUsuario($id);
    
        if ($result) {
            echo "<script>alert('Usuario habilitado exitosamente');</script>";
        } else {
            echo "<script>alert('Error al habilitar el usuario');</script>";
        }
        
        header("Location: ../usuarios");
        exit();
    }
}
?>
