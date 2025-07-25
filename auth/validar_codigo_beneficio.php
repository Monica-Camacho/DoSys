<?php
header('Content-Type: application/json');
require_once '../config.php';
require_once '../conexion_local.php';
session_start();

try {
    // 1. VERIFICACIONES DE SEGURIDAD
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id'])) {
        throw new Exception("Acceso no autorizado.");
    }
    $admin_id = $_SESSION['id'];

    // Obtener la empresa_id del usuario logueado
    $stmt_permisos = $conexion->prepare("SELECT empresa_id FROM usuarios_x_empresas WHERE usuario_id = ?");
    $stmt_permisos->bind_param("i", $admin_id);
    $stmt_permisos->execute();
    $resultado_permisos = $stmt_permisos->get_result();
    if ($resultado_permisos->num_rows === 0) {
        throw new Exception("Usuario no asociado a ninguna empresa.");
    }
    $empresa_id = $resultado_permisos->fetch_assoc()['empresa_id'];
    $stmt_permisos->close();
    
    // 2. OBTENER Y VALIDAR CÓDIGO
    $codigo_canje = trim($_POST['codigo_canje'] ?? '');
    if (empty($codigo_canje)) {
        throw new Exception("El campo del código no puede estar vacío.");
    }

    // 3. CONSULTAR EL CÓDIGO Y VERIFICAR PROPIEDAD Y ESTADO
    $sql_check = "SELECT db.id AS donante_beneficio_id, db.estado, ea.titulo, ea.empresa_id
                  FROM donantes_beneficios db
                  JOIN empresas_apoyos ea ON db.apoyo_id = ea.id
                  WHERE db.codigo_canje = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("s", $codigo_canje);
    $stmt_check->execute();
    $beneficio = $stmt_check->get_result()->fetch_assoc();
    $stmt_check->close();

    // Validaciones
    if (!$beneficio) {
        echo json_encode(['success' => false, 'message' => 'El código no es válido o no existe.']);
        exit();
    }
    if ($beneficio['empresa_id'] != $empresa_id) {
        echo json_encode(['success' => false, 'message' => 'Este código de beneficio no pertenece a tu empresa.']);
        exit();
    }
    if ($beneficio['estado'] === 'Canjeado') {
        echo json_encode(['success' => false, 'message' => 'Este código ya fue canjeado anteriormente.']);
        exit();
    }
    if ($beneficio['estado'] === 'Expirado') {
        echo json_encode(['success' => false, 'message' => 'Este beneficio ha expirado.']);
        exit();
    }
    if ($beneficio['estado'] !== 'Disponible') {
        echo json_encode(['success' => false, 'message' => 'Este beneficio no está disponible para canje.']);
        exit();
    }

    // 4. SI TODO ES CORRECTO, ACTUALIZAR A 'CANJEADO'
    $sql_update = "UPDATE donantes_beneficios SET estado = 'Canjeado', fecha_canje = NOW() WHERE id = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("i", $beneficio['donante_beneficio_id']);
    
    if ($stmt_update->execute()) {
        echo json_encode(['success' => true, 'message' => '¡Éxito! Se ha canjeado el beneficio: "' . htmlspecialchars($beneficio['titulo']) . '".']);
    } else {
        throw new Exception("No se pudo actualizar el estado del beneficio.");
    }
    $stmt_update->close();

} catch (Exception $e) {
    http_response_code(500);
    error_log("Error en validar_codigo_beneficio.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Ocurrió un error en el servidor.']);
} finally {
    if (isset($conexion)) {
        $conexion->close();
    }
}
?>