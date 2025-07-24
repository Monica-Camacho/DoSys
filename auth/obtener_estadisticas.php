<?php
// Se establece el tipo de contenido a JSON para la respuesta.
header('Content-Type: application/json');
require_once '../config.php';
require_once '../conexion_local.php';

// Se envuelve toda la lógica en un bloque try...catch para manejar errores.
try {
    if ($conexion->connect_error) {
        throw new Exception('Error de conexión a la base de datos: ' . $conexion->connect_error);
    }

    $estadisticas = [
        'totales' => ['donaciones' => 0, 'donantes' => 0, 'organizaciones' => 0, 'empresas' => 0],
        'por_tipo' => ['sangre' => 0, 'medicamentos' => 0, 'dispositivos' => 0],
        'por_mes' => ['meses' => [], 'cantidades' => []],
        'donaciones_por_estado' => [],
        'top_organizaciones' => []
    ];

    // --- 1. CÁLCULO DE TOTALES ---
    $resultado = $conexion->query("SELECT COUNT(*) AS total FROM donaciones WHERE estatus_id = 3");
    if ($resultado) $estadisticas['totales']['donaciones'] = (int) $resultado->fetch_assoc()['total'];

    $resultado = $conexion->query("SELECT COUNT(DISTINCT donante_id) AS total FROM donaciones WHERE estatus_id = 3");
    if ($resultado) $estadisticas['totales']['donantes'] = (int) $resultado->fetch_assoc()['total'];

    $resultado = $conexion->query("SELECT COUNT(*) AS total FROM organizaciones_perfil WHERE estado = 'Activa'");
    if ($resultado) $estadisticas['totales']['organizaciones'] = (int) $resultado->fetch_assoc()['total'] ?? 0;

    $resultado = $conexion->query("SELECT COUNT(*) AS total FROM empresas_perfil WHERE estado = 'Activa'");
    if ($resultado) $estadisticas['totales']['empresas'] = (int) $resultado->fetch_assoc()['total'] ?? 0;

    // --- 2. CÁLCULO DE DONACIONES POR TIPO ---
    $sql_por_tipo = "SELECT CASE WHEN a.categoria_id IS NOT NULL THEN a.categoria_id WHEN d.fecha_caducidad IS NOT NULL THEN 2 WHEN d.item_detalle IN ('Nuevo', 'Usado - Buen estado', 'Usado - Regular') THEN 3 ELSE 1 END AS categoria_final, COUNT(*) AS total FROM donaciones d LEFT JOIN avisos a ON d.aviso_id = a.id WHERE d.estatus_id = 3 GROUP BY categoria_final";
    $resultado_por_tipo = $conexion->query($sql_por_tipo);
    if ($resultado_por_tipo) {
        while ($fila = $resultado_por_tipo->fetch_assoc()) {
            if ($fila['categoria_final'] == 1) $estadisticas['por_tipo']['sangre'] = (int) $fila['total'];
            if ($fila['categoria_final'] == 2) $estadisticas['por_tipo']['medicamentos'] = (int) $fila['total'];
            if ($fila['categoria_final'] == 3) $estadisticas['por_tipo']['dispositivos'] = (int) $fila['total'];
        }
    }

    // --- 3. CÁLCULO DE DONACIONES POR MES ---
    $meses_es = ["", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
    $datos_meses = [];
    for ($i = 5; $i >= 0; $i--) {
        $fecha = new DateTime("first day of -$i month");
        $mes_nombre = $meses_es[(int)$fecha->format('n')];
        $anio_mes_key = $fecha->format('Y-m');
        $estadisticas['por_mes']['meses'][] = $mes_nombre;
        $datos_meses[$anio_mes_key] = 0;
    }
    $sql_por_mes = "SELECT DATE_FORMAT(fecha_validacion, '%Y-%m') AS anio_mes, COUNT(*) AS total FROM donaciones WHERE estatus_id = 3 AND fecha_validacion >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) GROUP BY anio_mes";
    $resultado_por_mes = $conexion->query($sql_por_mes);
    if ($resultado_por_mes) {
        while ($fila = $resultado_por_mes->fetch_assoc()) {
            if (isset($datos_meses[$fila['anio_mes']])) {
                $datos_meses[$fila['anio_mes']] = (int) $fila['total'];
            }
        }
    }
    $estadisticas['por_mes']['cantidades'] = array_values($datos_meses);

    // --- 4. CÁLCULO DE DONACIONES POR ESTADO ---
    $sql_por_estado = "SELECT dir.estado, COUNT(d.id) AS total_donaciones FROM donaciones d JOIN avisos a ON d.aviso_id = a.id JOIN organizaciones_perfil op ON a.organizacion_id = op.id JOIN direcciones dir ON op.direccion_id = dir.id WHERE d.estatus_id = 3 GROUP BY dir.estado ORDER BY total_donaciones DESC";
    $resultado_por_estado = $conexion->query($sql_por_estado);
    if ($resultado_por_estado) {
        $estadisticas['donaciones_por_estado'] = $resultado_por_estado->fetch_all(MYSQLI_ASSOC);
    }

    // --- 5. CÁLCULO DEL TOP 5 DE ORGANIZACIONES ---
    $sql_top_orgs = "SELECT op.nombre_organizacion, COUNT(d.id) AS total_donaciones FROM donaciones d JOIN avisos a ON d.aviso_id = a.id JOIN organizaciones_perfil op ON a.organizacion_id = op.id WHERE d.estatus_id = 3 GROUP BY op.nombre_organizacion ORDER BY total_donaciones DESC LIMIT 5";
    $resultado_top_orgs = $conexion->query($sql_top_orgs);
    if ($resultado_top_orgs) {
        $estadisticas['top_organizaciones'] = $resultado_top_orgs->fetch_all(MYSQLI_ASSOC);
    }

    // Si todo va bien, se envía el JSON con los datos.
    echo json_encode($estadisticas);

} catch (Exception $e) {
    // Si ocurre cualquier error, se captura aquí.
    http_response_code(500); // Código de error del servidor.
    
    // Se registra el error real en el log del servidor para que tú puedas depurarlo.
    error_log("Error en obtener_estadisticas.php: " . $e->getMessage());
    
    // Se envía una respuesta JSON de error genérica al frontend.
    echo json_encode(['error' => 'Ocurrió un error al consultar los datos.']);

} finally {
    // Se asegura que la conexión se cierre siempre.
    if (isset($conexion)) {
        $conexion->close();
    }
}
?>