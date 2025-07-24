<?php
// Deshabilitar temporalmente la visualización de errores para evitar "headers already sent" por warnings/notices.
// Esto es una medida de seguridad para la generación del PDF. Los errores deben ser gestionados en logs.
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', 0);

require_once '../config.php';
require_once '../conexion_local.php';
session_start();

// Incluir el autoloader de Composer para cargar FPDF
require_once '../vendor/autoload.php';

// La clase FPDF no usa namespaces en la librería tradicional, por lo que no se usa 'use Fpdf\Fpdf;'.

// Función auxiliar para convertir UTF-8 a ISO-8859-1 (requerido por FPDF para caracteres especiales)
function convert_to_latin1($string) {
    return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
}

// 1. AUTENTICACIÓN Y PERMISOS (similar a empresa_reportes.php)
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit();
}

$admin_id = $_SESSION['id'];

$sql_permisos = "SELECT ue.empresa_id, u.rol_id
                 FROM usuarios_x_empresas ue
                 JOIN usuarios u ON ue.usuario_id = u.id
                 WHERE ue.usuario_id = ?";
$stmt_permisos = $conexion->prepare($sql_permisos);
$stmt_permisos->bind_param("i", $admin_id);
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();

if ($resultado_permisos->num_rows === 0) {
    $_SESSION['error_message'] = "No tienes permiso para acceder a esta página o no estás asociado a una empresa.";
    header('Location: ../index.php');
    exit();
}

$permisos = $resultado_permisos->fetch_assoc();
$empresa_id = $permisos['empresa_id'];
$rol_admin = $permisos['rol_id'];
$stmt_permisos->close();

if ($rol_admin != 1 && $rol_admin != 2) {
    $_SESSION['error_message'] = "No tienes permiso para exportar reportes.";
    header('Location: ../empresa_dashboard.php');
    exit();
}

// --- Lógica de Filtrado (igual que en empresa_reportes.php) ---
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$benefit_filter_id = filter_input(INPUT_GET, 'benefit_filter', FILTER_VALIDATE_INT);

// Construye las cláusulas WHERE para la consulta SQL.
$where_clauses = ["ea.empresa_id = ? AND db.estado = 'Canjeado'"];
$params_types = "i";
$params_values = [$empresa_id];

if (!empty($start_date)) {
    $where_clauses[] = "DATE(db.fecha_canje) >= ?";
    $params_types .= "s";
    $params_values[] = $start_date;
}
if (!empty($end_date)) {
    $where_clauses[] = "DATE(db.fecha_canje) <= ?";
    $params_types .= "s";
    $params_values[] = $end_date;
}
if ($benefit_filter_id && $benefit_filter_id != 'all') {
    $where_clauses[] = "ea.id = ?";
    $params_types .= "i";
    $params_values[] = $benefit_filter_id;
}

$where_sql = "WHERE " . implode(" AND ", $where_clauses);

// --- Obtener datos para KPIs ---
$kpi_total_canjes = 0;
$sql_total_canjes = "SELECT COUNT(db.id) AS total FROM donantes_beneficios db JOIN empresas_apoyos ea ON db.apoyo_id = ea.id " . $where_sql;
$stmt_total_canjes = $conexion->prepare($sql_total_canjes);
$stmt_total_canjes->bind_param($params_types, ...$params_values);
$stmt_total_canjes->execute();
$result_total_canjes = $stmt_total_canjes->get_result();
if ($row = $result_total_canjes->fetch_assoc()) {
    $kpi_total_canjes = $row['total'];
}
$stmt_total_canjes->close();

$kpi_beneficio_popular_nombre = "N/A";
$kpi_beneficio_popular_canjes = 0;
$sql_beneficio_popular = "SELECT ea.titulo, COUNT(db.id) AS total_canjes
                          FROM donantes_beneficios db
                          JOIN empresas_apoyos ea ON db.apoyo_id = ea.id
                          " . $where_sql . "
                          GROUP BY ea.titulo
                          ORDER BY total_canjes DESC
                          LIMIT 1";
$stmt_beneficio_popular = $conexion->prepare($sql_beneficio_popular);
$stmt_beneficio_popular->bind_param($params_types, ...$params_values);
$stmt_beneficio_popular->execute();
$result_beneficio_popular = $stmt_beneficio_popular->get_result();
if ($row = $result_beneficio_popular->fetch_assoc()) {
    $kpi_beneficio_popular_nombre = $row['titulo'];
    $kpi_beneficio_popular_canjes = $row['total_canjes'];
}
$stmt_beneficio_popular->close();

$kpi_donante_activo_nombre = "N/A";
$kpi_donante_activo_canjes = 0;
$sql_donante_activo = "SELECT pp.nombre, pp.apellido_paterno, COUNT(db.id) AS total_canjes
                       FROM donantes_beneficios db
                       JOIN empresas_apoyos ea ON db.apoyo_id = ea.id
                       JOIN usuarios u ON db.usuario_id = u.id
                       JOIN personas_perfil pp ON u.id = pp.usuario_id
                       " . $where_sql . "
                       GROUP BY pp.nombre, pp.apellido_paterno
                       ORDER BY total_canjes DESC
                       LIMIT 1";
$stmt_donante_activo = $conexion->prepare($sql_donante_activo);
$stmt_donante_activo->bind_param($params_types, ...$params_values);
$stmt_donante_activo->execute();
$result_donante_activo = $stmt_donante_activo->get_result();
if ($row = $result_donante_activo->fetch_assoc()) {
    $kpi_donante_activo_nombre = $row['nombre'] . ' ' . $row['apellido_paterno'];
    $kpi_donante_activo_canjes = $row['total_canjes'];
}
$stmt_donante_activo->close();

// --- Obtener datos para la tabla de Reportes ---
$report_data = [];
$sql_report_data = "SELECT ea.titulo AS beneficio_titulo, db.codigo_canje,
                           pp.nombre AS donante_nombre, pp.apellido_paterno AS donante_apellido,
                           DATE_FORMAT(db.fecha_canje, '%d/%m/%Y') AS fecha_canje_formatted
                    FROM donantes_beneficios db
                    JOIN empresas_apoyos ea ON db.apoyo_id = ea.id
                    JOIN usuarios u ON db.usuario_id = u.id
                    JOIN personas_perfil pp ON u.id = pp.usuario_id
                    " . $where_sql . "
                    ORDER BY db.fecha_canje DESC";
$stmt_report_data = $conexion->prepare($sql_report_data);
$stmt_report_data->bind_param($params_types, ...$params_values);
$stmt_report_data->execute();
$result_report_data = $stmt_report_data->get_result();
while ($row = $result_report_data->fetch_assoc()) {
    $report_data[] = $row;
}
$stmt_report_data->close();
$conexion->close();

// --- Generar PDF con FPDF ---
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(33, 37, 41);

// Título del Reporte
$pdf->Cell(0, 10, convert_to_latin1('Reporte de Canjes de Beneficios - Empresa'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, convert_to_latin1('Generado el: ' . date('d/m/Y H:i')), 0, 1, 'C');
$pdf->Ln(10);

// Información de Filtros
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, convert_to_latin1('Filtros Aplicados:'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, convert_to_latin1('Rango de Fechas: ' . (empty($start_date) && empty($end_date) ? 'Todos' : $start_date . ' a ' . $end_date)), 0, 1, 'L');
$pdf->Cell(0, 6, convert_to_latin1('Beneficio: ' . ($benefit_filter_id == 'all' || is_null($benefit_filter_id) ? 'Todos' : $kpi_beneficio_popular_nombre)), 0, 1, 'L');
$pdf->Ln(5);

// KPIs
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, convert_to_latin1('Métricas Clave:'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);

// Definir el ancho de cada KPI para que quepan en la página (210mm de ancho total, 10mm de margen a cada lado = 190mm usable)
$kpi_width = 60;
$kpi_height = 20;
$kpi_margin = 5; // Margen entre KPIs

$current_x = 10; // Posición X inicial

// KPI 1: Total de Canjes
$pdf->SetX($current_x);
$pdf->SetFillColor(0, 123, 255); // Primary color
$pdf->SetTextColor(255, 255, 255); // White text
$pdf->Cell($kpi_width, $kpi_height, '', 1, 0, 'C', true); // Dibuja el fondo
$pdf->SetXY($current_x, $pdf->GetY() + 5); // Posiciona el texto dentro del cuadro
$pdf->Cell($kpi_width, 5, convert_to_latin1('Total de Canjes'), 0, 1, 'C');
$pdf->SetX($current_x); // Vuelve a la posición X para la siguiente línea de texto
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($kpi_width, 8, $kpi_total_canjes, 0, 0, 'C');
$current_x += $kpi_width + $kpi_margin; // Mueve la posición X para el siguiente KPI

// KPI 2: Beneficio Más Popular
$pdf->SetXY($current_x, $pdf->GetY() - 13); // Restablece Y para la parte superior del KPI
$pdf->SetFillColor(40, 167, 69); // Success color
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell($kpi_width, $kpi_height, '', 1, 0, 'C', true); // Dibuja el fondo
$pdf->SetXY($current_x, $pdf->GetY() + 5); // Posiciona el texto dentro del cuadro
$pdf->Cell($kpi_width, 5, convert_to_latin1('Beneficio Más Popular'), 0, 1, 'C');
$pdf->SetX($current_x); // Vuelve a la posición X
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell($kpi_width, 5, convert_to_latin1($kpi_beneficio_popular_nombre), 0, 1, 'C');
$pdf->SetX($current_x); // Vuelve a la posición X
$pdf->SetFont('Arial', '', 8);
$pdf->Cell($kpi_width, 4, convert_to_latin1('(' . $kpi_beneficio_popular_canjes . ' canjes)'), 0, 0, 'C');
$current_x += $kpi_width + $kpi_margin; // Mueve la posición X para el siguiente KPI

// KPI 3: Donante Más Activo
$pdf->SetXY($current_x, $pdf->GetY() - 14); // Restablece Y para la parte superior del KPI
$pdf->SetFillColor(23, 162, 184); // Info color
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell($kpi_width, $kpi_height, '', 1, 0, 'C', true); // Dibuja el fondo
$pdf->SetXY($current_x, $pdf->GetY() + 5); // Posiciona el texto dentro del cuadro
$pdf->Cell($kpi_width, 5, convert_to_latin1('Donante Más Activo'), 0, 1, 'C');
$pdf->SetX($current_x); // Vuelve a la posición X
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell($kpi_width, 5, convert_to_latin1($kpi_donante_activo_nombre), 0, 1, 'C');
$pdf->SetX($current_x); // Vuelve a la posición X
$pdf->SetFont('Arial', '', 8);
$pdf->Cell($kpi_width, 4, convert_to_latin1('(' . $kpi_donante_activo_canjes . ' canjes)'), 0, 0, 'C');

$pdf->Ln(15); // Espacio después de los KPIs.

// Tabla de Reporte
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(33, 37, 41);
$pdf->Cell(0, 8, convert_to_latin1('Detalle de Canjes:'), 0, 1, 'L');
$pdf->Ln(2);

// Anchos de las columnas de la tabla (ajustados para mejor distribución)
$col_width_beneficio = 70;
$col_width_codigo = 35;
$col_width_donante = 50;
$col_width_fecha = 35;
$total_width = $col_width_beneficio + $col_width_codigo + $col_width_donante + $col_width_fecha; // Debería ser 190mm (ancho de página - 2*margen)

// Encabezados de la tabla
$pdf->SetFillColor(233, 236, 239);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($col_width_beneficio, 7, convert_to_latin1('Beneficio Canjeado'), 1, 0, 'C', true);
$pdf->Cell($col_width_codigo, 7, convert_to_latin1('Código'), 1, 0, 'C', true);
$pdf->Cell($col_width_donante, 7, convert_to_latin1('Donante'), 1, 0, 'C', true);
$pdf->Cell($col_width_fecha, 7, convert_to_latin1('Fecha de Canje'), 1, 1, 'C', true); // 1 al final para salto de línea

// Datos de la tabla
$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);

if (!empty($report_data)) {
    foreach ($report_data as $row) {
        $pdf->Cell($col_width_beneficio, 7, convert_to_latin1($row['beneficio_titulo']), 1, 0, 'L');
        $pdf->Cell($col_width_codigo, 7, convert_to_latin1($row['codigo_canje']), 1, 0, 'C');
        // Usar MultiCell para el nombre del donante si es muy largo
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell($col_width_donante, 7, convert_to_latin1($row['donante_nombre'] . ' ' . $row['donante_apellido']), 1, 'L');
        // Volver a la posición para la siguiente celda en la misma fila
        $pdf->SetXY($x + $col_width_donante, $y);
        $pdf->Cell($col_width_fecha, 7, convert_to_latin1($row['fecha_canje_formatted']), 1, 1, 'C');
    }
} else {
    $pdf->Cell($total_width, 10, convert_to_latin1('No se encontraron canjes de beneficios para los filtros seleccionados.'), 1, 1, 'C');
}

// Limpiar el búfer de salida antes de enviar el PDF
ob_clean();

// Salida del PDF: 'D' para forzar la descarga.
$pdf->Output('D', 'reporte_beneficios_empresa_' . date('Ymd_His') . '.pdf');
exit();
?>
