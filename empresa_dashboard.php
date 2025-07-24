<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// Muestra una alerta si hay un error en el inicio de sesión.
// Se recomienda usar el modal de errores ya implementado en otras vistas.
// if (isset($_GET['error']) && $_GET['error'] == 1) {
//     echo "<script>alert('Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.');</script>";
// }

// 1. AUTENTICACIÓN Y PERMISOS
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$admin_id = $_SESSION['id'];

// Obtener la empresa_id y el rol del usuario logueado
$sql_permisos = "SELECT ue.empresa_id, u.rol_id, pp.nombre AS admin_nombre, pp.apellido_paterno AS admin_apellido
                 FROM usuarios_x_empresas ue
                 JOIN usuarios u ON ue.usuario_id = u.id
                 JOIN personas_perfil pp ON u.id = pp.usuario_id
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
$admin_nombre_completo = htmlspecialchars($permisos['admin_nombre'] . ' ' . $permisos['admin_apellido']);
$stmt_permisos->close();

// Solo los administradores (rol_id = 1) de la empresa pueden ver el dashboard completo.
// Los visualizadores (rol_id = 2) también pueden ver el dashboard.
if ($rol_admin != 1 && $rol_admin != 2) {
    $_SESSION['error_message'] = "No tienes permiso para ver el panel de empresa.";
    header('Location: index.php'); // Redirigir a una página segura
    exit();
}

// Obtener nombre comercial de la empresa
$nombre_empresa = "Tu Empresa"; // Valor por defecto
$sql_empresa_nombre = "SELECT nombre_comercial FROM empresas_perfil WHERE id = ?";
$stmt_empresa_nombre = $conexion->prepare($sql_empresa_nombre);
$stmt_empresa_nombre->bind_param("i", $empresa_id);
$stmt_empresa_nombre->execute();
$result_empresa_nombre = $stmt_empresa_nombre->get_result();
if ($row_empresa = $result_empresa_nombre->fetch_assoc()) {
    $nombre_empresa = htmlspecialchars($row_empresa['nombre_comercial']);
}
$stmt_empresa_nombre->close();


// --- Obtener datos para KPIs ---

// 1. Beneficios Activos
$kpi_beneficios_activos = 0;
$sql_beneficios_activos = "SELECT COUNT(*) AS total FROM empresas_apoyos WHERE empresa_id = ? AND activo = 1";
$stmt_beneficios_activos = $conexion->prepare($sql_beneficios_activos);
$stmt_beneficios_activos->bind_param("i", $empresa_id);
$stmt_beneficios_activos->execute();
$result_beneficios_activos = $stmt_beneficios_activos->get_result();
if ($row = $result_beneficios_activos->fetch_assoc()) {
    $kpi_beneficios_activos = $row['total'];
}
$stmt_beneficios_activos->close();

// 2. Beneficios Canjeados (Mes Actual)
$kpi_beneficios_canjeados_mes = 0;
$sql_beneficios_canjeados_mes = "SELECT COUNT(db.id) AS total
                                 FROM donantes_beneficios db
                                 JOIN empresas_apoyos ea ON db.apoyo_id = ea.id
                                 WHERE ea.empresa_id = ?
                                 AND db.estado = 'Canjeado'
                                 AND MONTH(db.fecha_canje) = MONTH(CURDATE())
                                 AND YEAR(db.fecha_canje) = YEAR(CURDATE())";
$stmt_beneficios_canjeados_mes = $conexion->prepare($sql_beneficios_canjeados_mes);
$stmt_beneficios_canjeados_mes->bind_param("i", $empresa_id);
$stmt_beneficios_canjeados_mes->execute();
$result_beneficios_canjeados_mes = $stmt_beneficios_canjeados_mes->get_result();
if ($row = $result_beneficios_canjeados_mes->fetch_assoc()) {
    $kpi_beneficios_canjeados_mes = $row['total'];
}
$stmt_beneficios_canjeados_mes->close();

// 3. Usuarios Registrados (en esta empresa)
$kpi_usuarios_registrados = 0;
$sql_usuarios_registrados = "SELECT COUNT(*) AS total FROM usuarios_x_empresas WHERE empresa_id = ?";
$stmt_usuarios_registrados = $conexion->prepare($sql_usuarios_registrados);
$stmt_usuarios_registrados->bind_param("i", $empresa_id);
$stmt_usuarios_registrados->execute();
$result_usuarios_registrados = $stmt_usuarios_registrados->get_result();
if ($row = $result_usuarios_registrados->fetch_assoc()) {
    $kpi_usuarios_registrados = $row['total'];
}
$stmt_usuarios_registrados->close();

// --- Obtener datos para el gráfico de Actividad de Beneficios (Últimos 6 meses) ---
$chart_data = [];
// Inicializar con 0 para los últimos 6 meses
for ($i = 5; $i >= 0; $i--) {
    $month_ts = strtotime("-$i months");
    $month_name = date('M', $month_ts); // Ej: Jan, Feb
    $chart_data[$month_name] = 0;
}

$sql_chart_data = "SELECT MONTH(db.fecha_canje) AS month_num,
                          YEAR(db.fecha_canje) AS year_num,
                          COUNT(db.id) AS total_canjes
                   FROM donantes_beneficios db
                   JOIN empresas_apoyos ea ON db.apoyo_id = ea.id
                   WHERE ea.empresa_id = ?
                   AND db.estado = 'Canjeado'
                   AND db.fecha_canje >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                   GROUP BY year_num, month_num
                   ORDER BY year_num ASC, month_num ASC";
$stmt_chart_data = $conexion->prepare($sql_chart_data);
$stmt_chart_data->bind_param("i", $empresa_id);
$stmt_chart_data->execute();
$result_chart_data = $stmt_chart_data->get_result();

while ($row = $result_chart_data->fetch_assoc()) {
    $month_name = date('M', mktime(0, 0, 0, $row['month_num'], 1, $row['year_num']));
    // Asegurarse de que el mes exista en nuestro array inicializado
    if (array_key_exists($month_name, $chart_data)) {
        $chart_data[$month_name] = $row['total_canjes'];
    }
}
$stmt_chart_data->close();

// Convertir los datos del gráfico a un formato adecuado para JavaScript (porcentajes para la barra)
$max_canjes = max($chart_data);
$chart_bars = [];
foreach ($chart_data as $month => $canjes) {
    $percentage = ($max_canjes > 0) ? round(($canjes / $max_canjes) * 100) : 0;
    $chart_bars[] = ['month' => $month, 'percentage' => $percentage, 'value' => $canjes];
}


// --- Obtener Usuarios Recientes ---
$usuarios_recientes = [];
$sql_usuarios_recientes = "SELECT pp.nombre, pp.apellido_paterno, r.nombre AS rol_nombre
                           FROM usuarios u
                           JOIN personas_perfil pp ON u.id = pp.usuario_id
                           JOIN usuarios_x_empresas uxe ON u.id = uxe.usuario_id
                           JOIN roles r ON u.rol_id = r.id
                           WHERE uxe.empresa_id = ?
                           ORDER BY u.fecha_registro DESC
                           LIMIT 3";
$stmt_usuarios_recientes = $conexion->prepare($sql_usuarios_recientes);
$stmt_usuarios_recientes->bind_param("i", $empresa_id);
$stmt_usuarios_recientes->execute();
$result_usuarios_recientes = $stmt_usuarios_recientes->get_result();
while ($row = $result_usuarios_recientes->fetch_assoc()) {
    $usuarios_recientes[] = $row;
}
$stmt_usuarios_recientes->close();

$conexion->close(); // Cerrar la conexión
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Panel de Empresa</title>
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
                <h1 class='display-5 mb-0'>Panel de <?php echo $nombre_empresa; ?></h1>
                <p class="fs-5 text-muted mb-0">Bienvenido, <?php echo $admin_nombre_completo; ?>. Aquí tienes un resumen de la actividad de tu empresa.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Main Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <!-- KPIs -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-tags fa-3x text-primary mb-3"></i>
                            <h2 class="card-title"><?php echo $kpi_beneficios_activos; ?></h2>
                            <p class="card-text text-muted">Beneficios Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h2 class="card-title"><?php echo $kpi_beneficios_canjeados_mes; ?></h2>
                            <p class="card-text text-muted">Beneficios Canjeados (Mes)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-users fa-3x text-info mb-3"></i>
                            <h2 class="card-title"><?php echo $kpi_usuarios_registrados; ?></h2>
                            <p class="card-text text-muted">Usuarios Registrados</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5">
                <!-- Main Column -->
                <div class="col-lg-8">
                    <!-- Chart -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Actividad de Beneficios (Últimos 6 meses)</h5>
                            <!-- Simple Bar Chart Visualization (dynamic) -->
                            <div class="d-flex align-items-end justify-content-around" style="height: 250px; border: 1px solid #eee; padding: 20px 10px 0 10px; border-radius: .25rem;">
                                <?php foreach ($chart_bars as $bar): ?>
                                    <div class="text-center">
                                        <div class="progress" style="height: 120px; width: 30px; writing-mode: vertical-lr;">
                                            <div class="progress-bar" role="progressbar" style="height: <?php echo $bar['percentage']; ?>%; width: 100%;" aria-valuenow="<?php echo $bar['percentage']; ?>" aria-valuemin="0" aria-valuemax="100" title="<?php echo $bar['value']; ?> canjes"></div>
                                        </div>
                                        <small class="text-muted mt-2 d-block"><?php echo htmlspecialchars($bar['month']); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Column -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Acciones Rápidas</h5>
                            <a href="empresa_beneficios.php" class="btn btn-primary w-100 mb-2">Gestionar Beneficios</a>
                            <a href="empresa_usuarios.php" class="btn btn-secondary w-100">Gestionar Usuarios</a>
                        </div>
                    </div>
                    <!-- Recent Users -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Usuarios Recientes</h5>
                            <ul class="list-group list-group-flush">
                                <?php if (!empty($usuarios_recientes)): ?>
                                    <?php foreach ($usuarios_recientes as $user): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido_paterno']); ?>
                                            <span class="badge bg-<?php echo ($user['rol_nombre'] == 'Administrador') ? 'success' : 'info'; ?> rounded-pill"><?php echo htmlspecialchars($user['rol_nombre']); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="list-group-item text-center">No hay usuarios recientes.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
    
</body>

</html>
