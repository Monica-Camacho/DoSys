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
$accion = $_POST['accion'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($accion) || empty($password) || !in_array($accion, ['desactivar', 'reactivar'])) {
    $_SESSION['error_message'] = "Solicitud no válida.";
    header('Location: ../organizacion_configuracion.php');
    exit();
}

// 2. OBTENER DATOS Y PERMISOS DEL ADMINISTRADOR
$sql_admin = "SELECT u.password_hash, u.rol_id, uo.organizacion_id 
              FROM usuarios u 
              JOIN usuarios_x_organizaciones uo ON u.id = uo.usuario_id 
              WHERE u.id = ?";
$stmt_admin = $conexion->prepare($sql_admin);
$stmt_admin->bind_param("i", $admin_id);
$stmt_admin->execute();
$admin = $stmt_admin->get_result()->fetch_assoc();
$stmt_admin->close();

if (!$admin || $admin['rol_id'] != 1) {
    $_SESSION['error_message'] = "No tienes permiso para realizar esta acción.";
    header('Location: ../organizacion_configuracion.php');
    exit();
}

// 3. VERIFICAR CONTRASEÑA
if (!password_verify($password, $admin['password_hash'])) {
    $_SESSION['error_message'] = "La contraseña es incorrecta.";
    header('Location: ../organizacion_configuracion.php');
    exit();
}

// 4. EJECUTAR ACCIÓN
$organizacion_id = $admin['organizacion_id'];
$nuevo_estado = ($accion === 'desactivar') ? 'Inactiva' : 'Activa';
$mensaje_exito = ($accion === 'desactivar') ? 'Tu organización ha sido desactivada.' : 'Tu organización ha sido reactivada con éxito.';

$sql_update = "UPDATE organizaciones_perfil SET estado = ? WHERE id = ?";
$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param("si", $nuevo_estado, $organizacion_id);

if ($stmt_update->execute()) {
    $_SESSION['success_message'] = $mensaje_exito;
    // Si se desactiva, es buena idea cerrar la sesión del usuario.
    if ($accion === 'desactivar') {
        session_destroy();
        header('Location: ../login.php?status=deactivated');
        exit();
    }
} else {
    $_SESSION['error_message'] = "Ocurrió un error al actualizar el estado de la organización.";
}

$stmt_update->close();
$conexion->close();

// 5. REDIRIGIR
header('Location: ../organizacion_configuracion.php');
exit();
?>