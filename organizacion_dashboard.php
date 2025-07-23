<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 1. AUTENTICACIÓN Y AUTORIZACIÓN
// =================================================================
// 1.1: Verificar que el usuario haya iniciado sesión.
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['id'];

// 1.2: Verificar que el usuario pertenece a una organización y obtener el ID de la misma.
$sql_org = "SELECT organizacion_id FROM usuarios_x_organizaciones WHERE usuario_id = ?";
$stmt_org = $conexion->prepare($sql_org);
$stmt_org->bind_param("i", $usuario_id);
$stmt_org->execute();
$resultado_org = $stmt_org->get_result();

if ($resultado_org->num_rows === 0) {
    // Si el usuario no está en la tabla, no es miembro de una organización. Redirigir.
    header('Location: persona_dashboard.php'); // O a donde consideres apropiado
    exit();
}
$organizacion_id = $resultado_org->fetch_assoc()['organizacion_id'];
$stmt_org->close();


// 2. OBTENER DATOS PARA EL PANEL
// =================================================================
// 2.1: Obtener nombre del usuario para el saludo
$nombre_usuario = "Admin";
$sql_nombre = "SELECT nombre FROM personas_perfil WHERE usuario_id = ?";
$stmt_nombre = $conexion->prepare($sql_nombre);
$stmt_nombre->bind_param("i", $usuario_id);
if ($stmt_nombre->execute()) {
    $resultado_nombre = $stmt_nombre->get_result();
    if ($fila = $resultado_nombre->fetch_assoc()) {
        $nombre_usuario = $fila['nombre'];
    }
}
$stmt_nombre->close();

// 2.2: Calcular los KPIs para la organización actual
function contar_registros($conexion, $sql, $params = [], $types = "") {
    $stmt = $conexion->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return (int) $stmt->get_result()->fetch_assoc()['total'];
}

// KPI 1: Solicitudes por Validar (estatus_aviso = 1)
$kpi_solicitudes_validar = contar_registros(
    $conexion,
    "SELECT COUNT(*) AS total FROM avisos WHERE organizacion_id = ? AND estatus_id = 1",
    [$organizacion_id],
    "i"
);

// KPI 2: Donantes por Aprobar (estatus_donacion = 1)
// Contamos donaciones generales (a la org) y específicas (a un aviso de la org)
$kpi_donantes_aprobar = contar_registros(
    $conexion,
    "SELECT COUNT(*) AS total FROM donaciones WHERE (organizacion_id = ? OR aviso_id IN (SELECT id FROM avisos WHERE organizacion_id = ?)) AND estatus_id = 1",
    [$organizacion_id, $organizacion_id],
    "ii"
);

// KPI 3: Solicitudes Activas (estatus_aviso = 2)
$kpi_solicitudes_activas = contar_registros(
    $conexion,
    "SELECT COUNT(*) AS total FROM avisos WHERE organizacion_id = ? AND estatus_id = 2",
    [$organizacion_id],
    "i"
);

// KPI 4: Donaciones Recibidas (estatus_donacion = 3)
$kpi_donaciones_recibidas = contar_registros(
    $conexion,
    "SELECT COUNT(*) AS total FROM donaciones WHERE (organizacion_id = ? OR aviso_id IN (SELECT id FROM avisos WHERE organizacion_id = ?)) AND estatus_id = 3",
    [$organizacion_id, $organizacion_id],
    "ii"
);

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Panel de Organización</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Cargando...</span></div></div>
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Panel de Organización</h1>
                <p class="fs-5 text-muted mb-0">Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?>. Gestiona el impacto de tu organización.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-3">
                    <div class="card border-warning border-2 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-user-check fa-3x text-warning mb-3"></i>
                            <h2 class="card-title"><?php echo $kpi_solicitudes_validar; ?></h2>
                            <p class="card-text text-muted">Solicitudes por Validar</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-info border-2 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-hand-holding-heart fa-3x text-info mb-3"></i>
                            <h2 class="card-title"><?php echo $kpi_donantes_aprobar; ?></h2>
                            <p class="card-text text-muted">Donantes por Aprobar</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-bullhorn fa-3x text-primary mb-3"></i>
                            <h2 class="card-title"><?php echo $kpi_solicitudes_activas; ?></h2>
                            <p class="card-text text-muted">Solicitudes Activas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-gift fa-3x text-success mb-3"></i>
                            <h2 class="card-title"><?php echo $kpi_donaciones_recibidas; ?></h2>
                            <p class="card-text text-muted">Donaciones Recibidas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <h5 class="card-title mb-4">Acciones Principales</h5>

                            <a href="organizacion_solicitudes.php" class="btn btn-warning w-100 mb-2 p-3 position-relative">
                                <i class="fas fa-user-check me-2"></i>Validar Solicitudes
                                <?php if($kpi_solicitudes_validar > 0): ?>
                                    <span class="badge bg-dark rounded-pill position-absolute top-50 end-0 translate-middle-y me-3"><?php echo $kpi_solicitudes_validar; ?></span>
                                <?php endif; ?>
                            </a>

                            <a href="organizacion_donantes.php" class="btn btn-info text-white w-100 mb-2 p-3 position-relative">
                                <i class="fas fa-hand-holding-heart me-2"></i>Gestionar Donantes
                                <?php if($kpi_donantes_aprobar > 0): ?>
                                    <span class="badge bg-dark rounded-pill position-absolute top-50 end-0 translate-middle-y me-3"><?php echo $kpi_donantes_aprobar; ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="organizacion_usuarios.php" class="btn btn-secondary w-100 p-3">Gestionar Voluntarios</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    <?php require_once 'templates/scripts.php'; ?>
</body>
</html>