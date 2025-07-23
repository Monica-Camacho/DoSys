<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// 1. VERIFICACIONES DE SEGURIDAD
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}
$admin_id = $_SESSION['id'];
$usuario_a_modificar_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
$accion = $_POST['accion'] ?? '';

if (!$usuario_a_modificar_id || empty($accion)) {
    header('Location: ../organizacion_usuarios.php');
    exit();
}

// 2. AUTORIZACIÓN: VERIFICAR QUE EL USUARIO LOGUEADO SEA ADMINISTRADOR
$sql_auth = "SELECT rol_id FROM usuarios WHERE id = ?";
$stmt_auth = $conexion->prepare($sql_auth);
$stmt_auth->bind_param("i", $admin_id);
$stmt_auth->execute();
$resultado_auth = $stmt_auth->get_result();
$rol_admin = $resultado_auth->fetch_assoc()['rol_id'] ?? null;
$stmt_auth->close();

if ($rol_admin != 1) { // 1 = Administrador
    $_SESSION['error_message'] = "No tienes permiso para realizar esta acción.";
    header('Location: ../organizacion_usuarios.php');
    exit();
}

// 3. EJECUTAR ACCIÓN
if ($accion === 'eliminar_suave') {
    // Acción de borrado suave (desactivar)
    $sql_update = "UPDATE usuarios SET estado = 'Inactivo' WHERE id = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("i", $usuario_a_modificar_id);

    if ($stmt_update->execute()) {
        $_SESSION['success_message'] = "El usuario ha sido desactivado correctamente.";
    } else {
        $_SESSION['error_message'] = "Ocurrió un error al desactivar al usuario.";
    }
    $stmt_update->close();

} elseif ($accion === 'editar') {
    // Acción de edición de rol y estado
    $nuevo_rol_id = filter_input(INPUT_POST, 'rol_id', FILTER_VALIDATE_INT);
    $nuevo_estado = $_POST['estado'] ?? '';

    // Validar que los datos recibidos son correctos
    if ($nuevo_rol_id && in_array($nuevo_estado, ['Activo', 'Inactivo', 'Pendiente'])) {
        $sql_update = "UPDATE usuarios SET rol_id = ?, estado = ? WHERE id = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("isi", $nuevo_rol_id, $nuevo_estado, $usuario_a_modificar_id);

        if ($stmt_update->execute()) {
            $_SESSION['success_message'] = "El usuario ha sido actualizado correctamente.";
        } else {
            $_SESSION['error_message'] = "Ocurrió un error al actualizar al usuario.";
        }
        $stmt_update->close();
    } else {
        $_SESSION['error_message'] = "Datos de rol o estado no válidos.";
    }
}

$conexion->close();

// 4. REDIRIGIR
header('Location: ../organizacion_usuarios.php');
exit();
?>