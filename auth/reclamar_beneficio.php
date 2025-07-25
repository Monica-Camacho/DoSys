<?php
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// 1. VERIFICAR AUTENTICACIÓN
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit();
}
$usuario_id = $_SESSION['id'];

// 2. OBTENER Y VALIDAR ID DEL BENEFICIO
$beneficio_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$beneficio_id) {
    $_SESSION['error_message'] = "Solicitud no válida.";
    header('Location: ../persona_beneficios.php');
    exit();
}

// 3. VERIFICAR QUE EL BENEFICIO PERTENEZCA AL USUARIO Y ESTÉ DISPONIBLE
$stmt_check = $conexion->prepare("SELECT id FROM donantes_beneficios WHERE id = ? AND usuario_id = ? AND estado = 'Disponible' AND codigo_canje IS NULL");
$stmt_check->bind_param("ii", $beneficio_id, $usuario_id);
$stmt_check->execute();
$resultado_check = $stmt_check->get_result();

if ($resultado_check->num_rows === 0) {
    $_SESSION['error_message'] = "No se puede reclamar este beneficio.";
    header('Location: ../persona_beneficios.php');
    exit();
}
$stmt_check->close();

// 4. GENERAR CÓDIGO Y ACTUALIZAR
$codigo_canje = strtoupper('CNJ-' . bin2hex(random_bytes(4))); // Ej: CNJ-A8D3F1B9

$sql_update = "UPDATE donantes_beneficios SET codigo_canje = ? WHERE id = ?";
$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bind_param("si", $codigo_canje, $beneficio_id);

if ($stmt_update->execute()) {
    $_SESSION['success_message'] = "¡Código reclamado con éxito! Ya puedes verlo en tus beneficios.";
} else {
    $_SESSION['error_message'] = "Ocurrió un error al generar el código. Inténtalo de nuevo.";
}

$stmt_update->close();
$conexion->close();

// 5. REDIRIGIR
header('Location: ../persona_beneficios.php');
exit();
?>