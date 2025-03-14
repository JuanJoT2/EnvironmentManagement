<?php
require_once 'config/db.php';

$db = Database::connect();

// Obtener todas las contraseñas almacenadas en texto plano
$result = $db->query("SELECT Id_usuario, Clave FROM t_usuarios");

while ($user = $result->fetch_assoc()) {
    // Si la contraseña ya está encriptada (contiene '$2y$'), la ignoramos
    if (password_get_info($user['Clave'])['algo']) {
        continue;
    }

    // Encriptar la contraseña
    $hashedPassword = password_hash($user['Clave'], PASSWORD_DEFAULT);

    // Actualizar la contraseña en la base de datos
    $stmt = $db->prepare("UPDATE t_usuarios SET Clave = ? WHERE Id_usuario = ?");
    $stmt->bind_param("si", $hashedPassword, $user['Id_usuario']);
    $stmt->execute();
    $stmt->close();
}

echo "✅ Todas las contraseñas han sido encriptadas correctamente.";
?>
