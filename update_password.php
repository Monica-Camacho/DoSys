<?php
require 'conexion_local.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        die("Las contraseñas no coinciden.");
    }

    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE reset_token = ? AND reset_token_expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Hashear la nueva contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Actualizar contraseña y limpiar el token para que no se reutilice
        $stmt_update = $conexion->prepare("UPDATE usuarios SET password_hash = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE reset_token = ?");
        $stmt_update->bind_param("ss", $hashed_password, $token);
        
        if ($stmt_update->execute()) {
            echo "Tu contraseña ha sido actualizada exitosamente. Redirigiendo al inicio de sesión...";
            header("refresh:3;url=login.php"); // Asegúrate que 'login.php' es tu página de inicio de sesión
        } else {
            echo "Ocurrió un error al actualizar tu contraseña.";
        }
    } else {
        echo "Token inválido o expirado.";
    }
}
?>