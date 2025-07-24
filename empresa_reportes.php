<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 1. AUTENTICACIÓN Y PERMISOS
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$admin_id = $_SESSION['id'];

// Obtener la empresa_id y el rol del usuario logueado
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
    header('Location: index.php'); // Redirigir a una página segura si no tiene permisos
    exit();
}

$permisos = $resultado_permisos->fetch_assoc();
$empresa_id = $permisos['empresa_id'];
$rol_admin = $permisos['rol_id'];
$stmt_permisos->close();

// Solo los administradores (rol_id = 1) de la empresa pueden ver los reportes.
// Los visualizadores (rol_id = 2) también pueden ver los reportes.
if ($rol_admin != 1 && $rol_admin != 2) {
    $_SESSION['error_message'] = "No tienes permiso para ver los reportes de empresa.";
    header('Location: empresa_dashboard.php'); // Redirigir al dashboard de empresa si no es admin
    exit();
}

// --- Lógica de Filtrado ---
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$benefit_filter_id = filter_input(INPUT_GET, 'benefit_filter', FILTER_VALIDATE_INT);

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
if ($benefit_filter_id && $benefit_filter_id != 'all') { // 'all' es para "Todos los beneficios"
    $where_clauses[] = "ea.id = ?";
    $params_types .= "i";
    $params_values[] = $benefit_filter_id;
}

$where_sql = "WHERE " . implode(" AND ", $where_clauses);

// --- Obtener datos para KPIs de Reportes ---

// KPI: Total de Canjes
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

// KPI: Beneficio Más Popular
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
    $kpi_beneficio_popular_nombre = htmlspecialchars($row['titulo']);
    $kpi_beneficio_popular_canjes = $row['total_canjes'];
}
$stmt_beneficio_popular->close();

// KPI: Donante Más Activo
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
    $kpi_donante_activo_nombre = htmlspecialchars($row['nombre'] . ' ' . $row['apellido_paterno']);
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

// Obtener la lista de beneficios para el filtro dropdown
$beneficios_dropdown = [];
$sql_beneficios_dropdown = "SELECT id, titulo FROM empresas_apoyos WHERE empresa_id = ? AND activo = 1 ORDER BY titulo ASC";
$stmt_beneficios_dropdown = $conexion->prepare($sql_beneficios_dropdown);
$stmt_beneficios_dropdown->bind_param("i", $empresa_id);
$stmt_beneficios_dropdown->execute();
$result_beneficios_dropdown = $stmt_beneficios_dropdown->get_result();
while ($row = $result_beneficios_dropdown->fetch_assoc()) {
    $beneficios_dropdown[] = $row;
}
$stmt_beneficios_dropdown->close();

$conexion->close(); // Cerrar la conexión
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Reportes de Beneficios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link rel="stylesheet" href="lib/animate/animate.min.css"/>
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Flatpickr CSS for Date Range Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <!-- Spinner End -->

        <!-- Topbar Start -->
        <?php require_once 'templates/topbar.php'; ?>
        <!-- Topbar End -->

        <!-- Navbar Start -->
        <?php require_once 'templates/navbar.php'; ?>
        <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Reportes de Impacto</h1>
                <p class="fs-5 text-muted mb-0">Analiza el rendimiento y el alcance de tus beneficios.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Reports Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- Filters -->
                    <div class="bg-light p-3 rounded mb-4">
                        <form class="row g-3 align-items-end" method="GET" action="empresa_reportes.php">
                            <div class="col-md-4">
                                <label for="reportRange" class="form-label">Rango de Fechas</label>
                                <input type="text" class="form-control" id="reportRange" name="date_range" placeholder="Selecciona un rango de fechas" value="<?php echo htmlspecialchars($start_date . ' to ' . $end_date); ?>">
                                <input type="hidden" name="start_date" id="start_date_hidden" value="<?php echo htmlspecialchars($start_date); ?>">
                                <input type="hidden" name="end_date" id="end_date_hidden" value="<?php echo htmlspecialchars($end_date); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="benefitFilter" class="form-label">Filtrar por Beneficio</label>
                                <select class="form-select" id="benefitFilter" name="benefit_filter">
                                    <option value="all" <?php echo ($benefit_filter_id === 'all' || is_null($benefit_filter_id)) ? 'selected' : ''; ?>>Todos los beneficios...</option>
                                    <?php foreach ($beneficios_dropdown as $beneficio): ?>
                                        <option value="<?php echo $beneficio['id']; ?>" <?php echo ($benefit_filter_id == $beneficio['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($beneficio['titulo']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Generar Reporte</button>
                            </div>
                            <div class="col-md-2">
                                <!-- Botón de Exportar PDF -->
                                <button type="button" class="btn btn-outline-success w-100" id="exportPdfBtn"><i class="fas fa-file-pdf me-2"></i>Exportar PDF</button>
                            </div>
                        </form>
                    </div>

                    <!-- KPIs -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card text-center bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total de Canjes</h5>
                                    <p class="display-4 fw-bold"><?php echo $kpi_total_canjes; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Beneficio Más Popular</h5>
                                    <p class="fs-5 fw-bold mb-0"><?php echo $kpi_beneficio_popular_nombre; ?></p>
                                    <?php if ($kpi_beneficio_popular_canjes > 0): ?>
                                        <small>(<?php echo $kpi_beneficio_popular_canjes; ?> canjes)</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Donante Más Activo</h5>
                                    <p class="fs-5 fw-bold mb-0"><?php echo $kpi_donante_activo_nombre; ?></p>
                                    <?php if ($kpi_donante_activo_canjes > 0): ?>
                                        <small>(<?php echo $kpi_donante_activo_canjes; ?> canjes)</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Beneficio Canjeado</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Donante</th>
                                    <th scope="col">Fecha de Canje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($report_data)): ?>
                                    <?php foreach ($report_data as $row): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($row['beneficio_titulo']); ?></strong></td>
                                            <td><span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['codigo_canje']); ?></span></td>
                                            <td><?php echo htmlspecialchars($row['donante_nombre'] . ' ' . $row['donante_apellido']); ?></td>
                                            <td><?php echo htmlspecialchars($row['fecha_canje_formatted']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No se encontraron canjes de beneficios para los filtros seleccionados.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Reports Content End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
    <?php require_once 'templates/scripts.php'; ?>
    <!-- Flatpickr JS for Date Range Picker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Flatpickr para el rango de fechas
            flatpickr("#reportRange", {
                mode: "range",
                dateFormat: "Y-m-d", // Formato de fecha para enviar al backend
                altInput: true, // Muestra una fecha legible en el input
                altFormat: "d/m/Y", // Formato legible
                onClose: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        document.getElementById('start_date_hidden').value = instance.formatDate(selectedDates[0], "Y-m-d");
                        document.getElementById('end_date_hidden').value = instance.formatDate(selectedDates[1], "Y-m-d");
                    } else {
                        document.getElementById('start_date_hidden').value = '';
                        document.getElementById('end_date_hidden').value = '';
                    }
                }
            });

            // Pre-llenar el campo de rango de fechas si ya hay valores en los hidden inputs
            const startDateHidden = document.getElementById('start_date_hidden').value;
            const endDateHidden = document.getElementById('end_date_hidden').value;
            if (startDateHidden && endDateHidden) {
                const fp = document.querySelector("#reportRange")._flatpickr;
                fp.setDate([startDateHidden, endDateHidden]);
            }

            // Event listener para el botón de exportar PDF
            document.getElementById('exportPdfBtn').addEventListener('click', function() {
                const startDate = document.getElementById('start_date_hidden').value;
                const endDate = document.getElementById('end_date_hidden').value;
                const benefitFilter = document.getElementById('benefitFilter').value;

                let exportUrl = 'auth/export_report_pdf.php?';
                if (startDate) {
                    exportUrl += 'start_date=' + encodeURIComponent(startDate) + '&';
                }
                if (endDate) {
                    exportUrl += 'end_date=' + encodeURIComponent(endDate) + '&';
                }
                if (benefitFilter) {
                    exportUrl += 'benefit_filter=' + encodeURIComponent(benefitFilter);
                }
                
                // Abre el PDF en una nueva pestaña/ventana
                window.open(exportUrl, '_blank');
            });
        });
    </script>
</body>

</html>
