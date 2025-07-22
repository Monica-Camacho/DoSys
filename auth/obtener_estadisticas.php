<?php
// Se establece el tipo de contenido a JSON para la respuesta.
header('Content-Type: application/json');
require_once '../config.php';
require_once '../conexion_local.php';

// Se añade una verificación para asegurar que la conexión a la BD fue exitosa.
if ($conexion->connect_error) {
    // Si hay un error, se detiene el script y se envía un mensaje claro.
    http_response_code(500); // Código de error del servidor
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $conexion->connect_error]);
    exit();
}

// Array final que se convertirá en JSON, inicializado con valores por defecto.
$estadisticas = [
    'totales' => [
        'donaciones' => 0,
        'donantes' => 0,
        'empresas' => 0
    ],
    'por_tipo' => [
        'sangre' => 0,
        'medicamentos' => 0,
        'dispositivos' => 0
    ],
    'por_mes' => [
        'meses' => [],
        'cantidades' => []
    ]
];

// --- 1. CÁLCULO DE TOTALES ---
// Total de donaciones completadas (estatus 3)
$resultado_total_donaciones = $conexion->query("SELECT COUNT(*) AS total FROM donaciones WHERE estatus_id = 3");
if ($resultado_total_donaciones) {
    $estadisticas['totales']['donaciones'] = (int) $resultado_total_donaciones->fetch_assoc()['total'];
}

// Total de donantes únicos que han completado una donación
$resultado_total_donantes = $conexion->query("SELECT COUNT(DISTINCT donante_id) AS total FROM donaciones WHERE estatus_id = 3");
if ($resultado_total_donantes) {
    $estadisticas['totales']['donantes'] = (int) $resultado_total_donantes->fetch_assoc()['total'];
}

// Total de organizaciones/empresas aliadas activas
$resultado_total_empresas = $conexion->query("SELECT COUNT(*) AS total FROM organizaciones_perfil WHERE estatus_id = 1");
if ($resultado_total_empresas) {
    $estadisticas['totales']['empresas'] = (int) $resultado_total_empresas->fetch_assoc()['total'];
}


// --- 2. CÁLCULO DE DONACIONES POR TIPO ---
// Se une 'donaciones' con 'avisos' y se usa un CASE para determinar la categoría.
$sql_por_tipo = "SELECT
                    CASE
                        -- Primero, intenta obtener la categoría del aviso (para donaciones específicas)
                        WHEN a.categoria_id IS NOT NULL THEN a.categoria_id
                        -- Si no hay aviso (donación general), se infiere la categoría
                        WHEN d.fecha_caducidad IS NOT NULL THEN 2 -- ID de Medicamentos
                        WHEN d.item_detalle IN ('Nuevo', 'Usado - Buen estado', 'Usado - Regular') THEN 3 -- ID de Dispositivos
                        ELSE 1 -- Lo que queda, se asume como Sangre
                    END AS categoria_final,
                    COUNT(*) AS total
                FROM
                    donaciones d
                LEFT JOIN
                    avisos a ON d.aviso_id = a.id
                WHERE
                    d.estatus_id = 3
                GROUP BY
                    categoria_final";

$resultado_por_tipo = $conexion->query($sql_por_tipo);
if ($resultado_por_tipo) {
    while ($fila = $resultado_por_tipo->fetch_assoc()) {
        if ($fila['categoria_final'] == 1) $estadisticas['por_tipo']['sangre'] = (int) $fila['total'];
        if ($fila['categoria_final'] == 2) $estadisticas['por_tipo']['medicamentos'] = (int) $fila['total'];
        if ($fila['categoria_final'] == 3) $estadisticas['por_tipo']['dispositivos'] = (int) $fila['total'];
    }
}


// --- 3. CÁLCULO DE DONACIONES POR MES (ÚLTIMOS 6 MESES) ---
$meses_es = ["", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
$datos_meses = [];

// Se preparan los últimos 6 meses en el array
for ($i = 5; $i >= 0; $i--) {
    $fecha = new DateTime("first day of -$i month");
    $mes_nombre = $meses_es[(int)$fecha->format('n')];
    $anio_mes_key = $fecha->format('Y-m');
    $estadisticas['por_mes']['meses'][] = $mes_nombre;
    $datos_meses[$anio_mes_key] = 0; // Se inicializan todos los contadores en 0
}

// Se consultan las donaciones de los últimos 6 meses
$sql_por_mes = "SELECT DATE_FORMAT(fecha_validacion, '%Y-%m') AS anio_mes, COUNT(*) AS total
                FROM donaciones
                WHERE estatus_id = 3 AND fecha_validacion >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY anio_mes";
$resultado_por_mes = $conexion->query($sql_por_mes);
if ($resultado_por_mes) {
    while ($fila = $resultado_por_mes->fetch_assoc()) {
        // Se actualiza el contador para los meses que sí tuvieron donaciones
        if (isset($datos_meses[$fila['anio_mes']])) {
            $datos_meses[$fila['anio_mes']] = (int) $fila['total'];
        }
    }
}
$estadisticas['por_mes']['cantidades'] = array_values($datos_meses);


// --- FIN: DEVOLVER RESULTADO COMO JSON ---
echo json_encode($estadisticas);
$conexion->close();
?>