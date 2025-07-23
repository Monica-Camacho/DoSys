<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// 1. VERIFICACIONES DE SEGURIDAD
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit();
}

// 2. OBTENER DATOS Y AUTORIZACIÓN
$usuario_id = $_SESSION['id'];
$donacion_id = filter_input(INPUT_POST, 'donacion_id', FILTER_VALIDATE_INT);
$accion = $_POST['accion'] ?? '';

if (!$donacion_id || !$accion) {
    // Redirigir si faltan datos
    header('Location: ../organizacion_donantes.php');
    exit();
}

// Obtener el ID de la organización del usuario logueado
$sql_org = "SELECT organizacion_id FROM usuarios_x_organizaciones WHERE usuario_id = ?";
$stmt_org = $conexion->prepare($sql_org);
$stmt_org->bind_param("i", $usuario_id);
$stmt_org->execute();
$resultado_org = $stmt_org->get_result();
if ($resultado_org->num_rows === 0) {
    // Si el usuario no pertenece a una organización, no puede gestionar nada.
    header('Location: ../persona_dashboard.php');
    exit();
}
$organizacion_id = $resultado_org->fetch_assoc()['organizacion_id'];
$stmt_org->close();

// 3. ACTUALIZAR EL ESTATUS DE LA DONACIÓN
$nuevo_estatus_id = null;
$mensaje_exito = "";

switch ($accion) {
    case 'aprobar':
        $nuevo_estatus_id = 2; // 2: Aprobado
        $mensaje_exito = "Donación aprobada con éxito. El donante está en proceso de entrega.";
        break;
    case 'rechazar':
        $nuevo_estatus_id = 4; // 4: No Concretado
        $mensaje_exito = "La donación ha sido marcada como no concretada.";
        break;
    case 'recibir':
        $nuevo_estatus_id = 3; // 3: Recibido
        $mensaje_exito = "¡Excelente! La donación ha sido marcada como recibida.";
        break;
    default:
        // Si la acción no es válida, no hacer nada.
        header('Location: ../organizacion_donantes.php');
        exit();
}

// Se prepara la consulta para actualizar el estatus y la fecha de validación
$sql_update = "UPDATE donaciones SET estatus_id = ?, fecha_validacion = NOW() WHERE id = ?";
$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param("ii", $nuevo_estatus_id, $donacion_id);

if ($stmt_update->execute()) {
    $_SESSION['success_message'] = $mensaje_exito;
} else {
    $_SESSION['error_message'] = "Ocurrió un error al actualizar la donación.";
}

$stmt_update->close();
$conexion->close();

// Redirigir de vuelta a la página de gestión
header('Location: ../organizacion_donantes.php');
exit();
?>