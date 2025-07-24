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

// --- CAMBIO: Se añade 'editar_urgencia' a las acciones válidas ---
if (!$aviso_id || !in_array($accion, ['aprobar', 'rechazar', 'editar_urgencia'])) {
    header('Location: ../organizacion_solicitudes.php');
    exit();
}

// 3. AUTORIZACIÓN: VERIFICAR QUE EL USUARIO PERTENECE A LA ORGANIZACIÓN DEL AVISO
$sql_auth = "SELECT a.organizacion_id FROM avisos a 
             JOIN usuarios_x_organizaciones uo ON a.organizacion_id = uo.organizacion_id
             WHERE a.id = ? AND uo.usuario_id = ?";
$stmt_auth = $conexion->prepare($sql_auth);
$stmt_auth->bind_param("ii", $aviso_id, $usuario_id);
$stmt_auth->execute();
if ($stmt_auth->get_result()->num_rows === 0) {
    $_SESSION['error_message'] = "No tienes permiso para realizar esta acción.";
    header('Location: ../organizacion_solicitudes.php');
    exit();
}
$stmt_auth->close();

// 4. LÓGICA DE LA ACCIÓN
$sql_update = "";
$params = [];
$types = "";
$mensaje_exito = "";

if ($accion === 'aprobar') {
    $nuevo_estatus_id = 2; // 2: Activo
    $sql_update = "UPDATE avisos SET estatus_id = ? WHERE id = ?";
    $params = [$nuevo_estatus_id, $aviso_id];
    $types = "ii";
    $mensaje_exito = "La solicitud ha sido aprobada y ahora está activa.";

} elseif ($accion === 'rechazar') {
    $nuevo_estatus_id = 4; // 4: Rechazado
    $sql_update = "UPDATE avisos SET estatus_id = ? WHERE id = ?";
    $params = [$nuevo_estatus_id, $aviso_id];
    $types = "ii";
    $mensaje_exito = "La solicitud ha sido rechazada.";

// --- INICIO DE NUEVA LÓGICA ---
} elseif ($accion === 'editar_urgencia') {
    $nueva_urgencia_id = filter_input(INPUT_POST, 'urgencia_id', FILTER_VALIDATE_INT);
    if (!$nueva_urgencia_id) {
        $_SESSION['error_message'] = "Nivel de urgencia no válido.";
        header('Location: ../organizacion_solicitudes.php');
        exit();
    }
    
    $sql_update = "UPDATE avisos SET urgencia_id = ? WHERE id = ?";
    $params = [$nueva_urgencia_id, $aviso_id];
    $types = "ii";
    $mensaje_exito = "El nivel de urgencia de la solicitud ha sido actualizado.";
}
// --- FIN DE NUEVA LÓGICA ---

// 5. EJECUTAR ACTUALIZACIÓN
$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param($types, ...$params);

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