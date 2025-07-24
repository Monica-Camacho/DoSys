<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// 1. VERIFICACIONES DE SEGURIDAD INICIAL
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}
$admin_id = $_SESSION['id'];

// Obtener la empresa_id y el rol del usuario logueado
$sql_permisos = "SELECT ue.empresa_id, u.rol_id
                 FROM usuarios_x_empresas ue
                 JOIN usuarios u ON ue.usuario_id = u.id
                 WHERE ue.usuario_id = ?";
$stmt_permisos = $conexion->prepare($sql_permisos);
$stmt_permisos->bind_param("i", $admin_id);
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();

if ($resultado_permisos->num_rows === 0) {
    $_SESSION['error_message'] = "No tienes permiso para realizar esta acción o no estás asociado a una empresa.";
    header('Location: ../empresa_usuarios.php');
    exit();
}

$permisos = $resultado_permisos->fetch_assoc();
$empresa_id = $permisos['empresa_id'];
$rol_admin = $permisos['rol_id'];
$stmt_permisos->close();

// Solo los administradores (rol_id = 1) de la empresa pueden gestionar usuarios
if ($rol_admin != 1) {
    $_SESSION['error_message'] = "No tienes permiso para gestionar usuarios de la empresa.";
    header('Location: ../empresa_usuarios.php');
    exit();
}

$action = $_POST['action'] ?? ''; // 'edit' o 'delete'
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

if (!$user_id) {
    $_SESSION['error_message'] = "ID de usuario no proporcionado o inválido para la acción.";
    header('Location: ../empresa_usuarios.php');
    exit();
}

// Verificar que el usuario a gestionar pertenece a la empresa del admin
$sql_check_ownership = "SELECT u.id FROM usuarios u JOIN usuarios_x_empresas uxe ON u.id = uxe.usuario_id WHERE u.id = ? AND uxe.empresa_id = ?";
$stmt_check_ownership = $conexion->prepare($sql_check_ownership);
$stmt_check_ownership->bind_param("ii", $user_id, $empresa_id);
$stmt_check_ownership->execute();
if ($stmt_check_ownership->get_result()->num_rows === 0) {
    $_SESSION['error_message'] = "No tienes permiso para gestionar este usuario.";
    header('Location: ../empresa_usuarios.php');
    exit();
}
$stmt_check_ownership->close();

$conexion->begin_transaction();

try {
    if ($action === 'edit') {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido_paterno = trim($_POST['apellido_paterno'] ?? '');
        $apellido_materno = trim($_POST['apellido_materno'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? ''; // Opcional
        $rol_id = filter_input(INPUT_POST, 'rol_id', FILTER_VALIDATE_INT);

        // Validaciones
        if (empty($nombre) || empty($apellido_paterno) || empty($email) || !$rol_id) {
            $_SESSION['error_message'] = "Todos los campos obligatorios deben ser completados.";
            header('Location: ../empresa_usuario_editar.php?id=' . $user_id);
            exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "El formato del correo electrónico no es válido.";
            header('Location: ../empresa_usuario_editar.php?id=' . $user_id);
            exit();
        }
        if (!empty($password) && strlen($password) < 8) {
            $_SESSION['error_message'] = "La nueva contraseña debe tener al menos 8 caracteres.";
            header('Location: ../empresa_usuario_editar.php?id=' . $user_id);
            exit();
        }

        // Verificar si el nuevo email ya existe para otro usuario (excluyendo al usuario actual)
        $stmt_email_check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt_email_check->bind_param("si", $email, $user_id);
        $stmt_email_check->execute();
        if ($stmt_email_check->get_result()->num_rows > 0) {
            $_SESSION['error_message'] = "El correo electrónico '$email' ya está registrado por otro usuario.";
            header('Location: ../empresa_usuario_editar.php?id=' . $user_id);
            exit();
        }
        $stmt_email_check->close();

        // Actualizar tabla 'usuarios'
        $sql_update_user = "UPDATE usuarios SET email = ?, rol_id = ? WHERE id = ?";
        $params = "sii";
        $values = [$email, $rol_id, $user_id];

        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql_update_user = "UPDATE usuarios SET email = ?, password_hash = ?, rol_id = ? WHERE id = ?";
            $params = "ssii";
            $values = [$email, $password_hash, $rol_id, $user_id];
        }
        $stmt_update_user = $conexion->prepare($sql_update_user);
        $stmt_update_user->bind_param($params, ...$values);
        $stmt_update_user->execute();
        $stmt_update_user->close();

        // Actualizar tabla 'personas_perfil'
        $sql_update_profile = "UPDATE personas_perfil SET nombre = ?, apellido_paterno = ?, apellido_materno = ? WHERE usuario_id = ?";
        $stmt_update_profile = $conexion->prepare($sql_update_profile);
        $stmt_update_profile->bind_param("sssi", $nombre, $apellido_paterno, $apellido_materno, $user_id);
        $stmt_update_profile->execute();
        $stmt_update_profile->close();

        $conexion->commit();
        $_SESSION['success_message'] = "Usuario actualizado con éxito.";
        header('Location: ../empresa_usuarios.php');

    } elseif ($action === 'delete') {
        // Eliminar de usuarios_x_empresas
        $sql_delete_uxe = "DELETE FROM usuarios_x_empresas WHERE usuario_id = ? AND empresa_id = ?";
        $stmt_delete_uxe = $conexion->prepare($sql_delete_uxe);
        $stmt_delete_uxe->bind_param("ii", $user_id, $empresa_id);
        $stmt_delete_uxe->execute();
        $stmt_delete_uxe->close();
        
        // Eliminar de personas_perfil (CASCADE en la FK de usuarios a personas_perfil debería manejar esto, pero lo hacemos explícito para seguridad)
        $sql_delete_profile = "DELETE FROM personas_perfil WHERE usuario_id = ?";
        $stmt_delete_profile = $conexion->prepare($sql_delete_profile);
        $stmt_delete_profile->bind_param("i", $user_id);
        $stmt_delete_profile->execute();
        $stmt_delete_profile->close();

        // Finalmente, eliminar de la tabla 'usuarios'
        $sql_delete_user = "DELETE FROM usuarios WHERE id = ?";
        $stmt_delete_user = $conexion->prepare($sql_delete_user);
        $stmt_delete_user->bind_param("i", $user_id);
        $stmt_delete_user->execute();
        $stmt_delete_user->close();

        $conexion->commit();
        $_SESSION['success_message'] = "Usuario eliminado con éxito.";
        header('Location: ../empresa_usuarios.php');

    } else {
        $_SESSION['error_message'] = "Acción no válida.";
        header('Location: ../empresa_usuarios.php');
    }
} catch (mysqli_sql_exception $exception) {
    $conexion->rollback();
    // Para depuración: error_log($exception->getMessage());
    $_SESSION['error_message'] = "Ocurrió un error en la operación: " . $exception->getMessage();
    if ($action === 'edit') {
        header('Location: ../empresa_usuario_editar.php?id=' . $user_id);
    } else {
        header('Location: ../empresa_usuarios.php');
    }
} finally {
    $conexion->close();
    exit();
}
?>
