<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// 1. VERIFICACIONES DE SEGURIDAD
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}

// 2. OBTENER DATOS Y VALIDAR
$usuario_id = $_SESSION['id'];
$aviso_id = filter_input(INPUT_POST, 'aviso_id', FILTER_VALIDATE_INT);
$accion = $_POST['accion'] ?? '';

if (!$aviso_id || !in_array($accion, ['aprobar', 'rechazar'])) {
    header('Location: ../organizacion_solicitudes.php');
    exit();
}

// 3. AUTORIZACIÓN: VERIFICAR QUE EL USUARIO PERTENECE A LA ORGANIZACIÓN DEL AVISO
// Esto previene que una organización modifique avisos de otra.
$sql_auth = "SELECT a.organizacion_id FROM avisos a 
             JOIN usuarios_x_organizaciones uo ON a.organizacion_id = uo.organizacion_id
             WHERE a.id = ? AND uo.usuario_id = ?";
$stmt_auth = $conexion->prepare($sql_auth);
$stmt_auth->bind_param("ii", $aviso_id, $usuario_id);
$stmt_auth->execute();
if ($stmt_auth->get_result()->num_rows === 0) {
    // Si no hay resultado, el usuario no está autorizado para esta acción.
    $_SESSION['error_message'] = "No tienes permiso para realizar esta acción.";
    header('Location: ../organizacion_solicitudes.php');
    exit();
}
$stmt_auth->close();

// 4. LÓGICA DE LA ACCIÓN
$nuevo_estatus_id = 0;
$mensaje_exito = "";

if ($accion === 'aprobar') {
    $nuevo_estatus_id = 2; // 2: Activo
    $mensaje_exito = "La solicitud ha sido aprobada y ahora está activa.";
} elseif ($accion === 'rechazar') {
    $nuevo_estatus_id = 4; // 4: Rechazado
    $mensaje_exito = "La solicitud ha sido rechazada.";
}

// 5. EJECUTAR ACTUALIZACIÓN
$sql_update = "UPDATE avisos SET estatus_id = ? WHERE id = ?";
$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param("ii", $nuevo_estatus_id, $aviso_id);

if ($stmt_update->execute()) {
    $_SESSION['success_message'] = $mensaje_exito;
} else {
    $_SESSION['error_message'] = "Ocurrió un error al procesar la solicitud.";
}
$stmt_update->close();
$conexion->close();

// 6. REDIRIGIR
header('Location: ../organizacion_solicitudes.php');
exit();
?>