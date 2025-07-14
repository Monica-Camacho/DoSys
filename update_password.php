<?php
session_start();
require_once 'conexion_local.php'; // Usa tu variable $conexion

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $token = $_POST['token'];
    $password = $_POST['password'];
    // ***** VARIABLE CORREGIDA PARA COINCIDIR CON TU LÓGICA ORIGINAL *****
    $password_confirm = $_POST['password_confirm'];

    // 1. Validar que las contraseñas coinciden
    if ($password !== $password_confirm) {
        $_SESSION['error_message'] = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }
    
    // 2. Validar la complejidad de la contraseña
    if (strlen($password) < 8) {
        $_SESSION['error_message'] = "La contraseña debe tener al menos 8 caracteres.";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    // 3. Buscar el token en la tabla `usuarios`
    $sql_search = "SELECT id FROM usuarios WHERE reset_token = ? AND reset_token_expires_at > NOW()";
    
    $stmt_search = $conexion->prepare($sql_search);
    $stmt_search->bind_param("s", $token);
    $stmt_search->execute();
    $result = $stmt_search->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $usuario_id = $user['id'];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 4. Actualizar la contraseña y limpiar el token en la tabla `usuarios`
        $sql_update = "UPDATE usuarios SET password_hash = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("si", $hashed_password, $usuario_id);
        
        if ($stmt_update->execute()) {
            $_SESSION['user_message'] = "¡Tu contraseña ha sido actualizada con éxito! Ya puedes iniciar sesión.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Hubo un error al actualizar tu contraseña. Inténtalo de nuevo.";
            header("Location: reset_password.php?token=" . urlencode($token));
            exit();
        }
    } else {
        $_SESSION['error_message'] = "El enlace de recuperación no es válido o ha expirado. Por favor, solicita uno nuevo.";
        header("Location: forgot_password.php");
        exit();
    }
}
?>