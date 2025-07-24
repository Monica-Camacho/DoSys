<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 1. VERIFICAR QUE EL USUARIO HAYA INICIADO SESIÓN
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['id'];

// 2. OBTENER DATOS PARA EL PANEL

// -- Nombre del usuario para el saludo
$nombre_usuario = "Usuario"; // Valor por defecto
$sql_nombre = "SELECT nombre FROM personas_perfil WHERE usuario_id = ?";
$stmt_nombre = $conexion->prepare($sql_nombre);
$stmt_nombre->bind_param("i", $usuario_id);
if ($stmt_nombre->execute()) {
    $resultado = $stmt_nombre->get_result();
    if ($fila = $resultado->fetch_assoc()) {
        $nombre_usuario = $fila['nombre'];
    }
}
$stmt_nombre->close();

// -- Solicitudes creadas por el usuario (TODOS LOS ESTADOS)
$mis_solicitudes = [];
// --- INICIO DE LA MODIFICACIÓN EN LA CONSULTA ---
$sql_solicitudes = "SELECT
                        a.id AS aviso_id, a.titulo, a.categoria_id, a.fecha_creacion, a.estatus_id, -- Se añade estatus_id
                        COALESCE(ss.unidades_requeridas, sm.cantidad_requerida, sd.cantidad_requerida) AS cantidad_requerida,
                        COALESCE((SELECT SUM(cantidad) FROM donaciones WHERE aviso_id = a.id AND estatus_id = 3), 0) AS cantidad_recolectada
                    FROM avisos a
                    LEFT JOIN solicitudes_sangre ss ON a.id = ss.aviso_id
                    LEFT JOIN solicitudes_medicamentos sm ON a.id = sm.aviso_id
                    LEFT JOIN solicitudes_dispositivos sd ON a.id = sd.aviso_id
                    WHERE a.creador_id = ? -- Ya no filtramos por estatus, traemos todas
                    ORDER BY a.fecha_creacion DESC";
// --- FIN DE LA MODIFICACIÓN EN LA CONSULTA ---
$stmt_solicitudes = $conexion->prepare($sql_solicitudes);
$stmt_solicitudes->bind_param("i", $usuario_id);
if ($stmt_solicitudes->execute()) {
    $resultado = $stmt_solicitudes->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        $mis_solicitudes[] = $fila;
    }
}
$stmt_solicitudes->close();

// -- Donaciones realizadas por el usuario
$mis_donaciones = [];
$sql_donaciones = "SELECT
                        d.item_nombre, d.fecha_compromiso, d.estatus_id,
                        a.titulo AS aviso_titulo,
                        op.nombre_organizacion
                    FROM donaciones d
                    LEFT JOIN avisos a ON d.aviso_id = a.id
                    LEFT JOIN organizaciones_perfil op ON d.organizacion_id = op.id
                    WHERE d.donante_id = ?
                    ORDER BY d.fecha_compromiso DESC";
$stmt_donaciones = $conexion->prepare($sql_donaciones);
$stmt_donaciones->bind_param("i", $usuario_id);
if ($stmt_donaciones->execute()) {
    $resultado = $stmt_donaciones->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        $mis_donaciones[] = $fila;
    }
}
$stmt_donaciones->close();


// -- Estadísticas para la barra lateral "Tu Impacto"
$total_donaciones_completadas = 0;
$sql_stat_donaciones = "SELECT COUNT(*) AS total FROM donaciones WHERE donante_id = ? AND estatus_id = 3";
$stmt_stat_don = $conexion->prepare($sql_stat_donaciones);
$stmt_stat_don->bind_param("i", $usuario_id);
if($stmt_stat_don->execute()){
    $total_donaciones_completadas = $stmt_stat_don->get_result()->fetch_assoc()['total'];
}
$stmt_stat_don->close();

// Asumimos que el estatus de un aviso completado es 3
$total_solicitudes_completadas = 0;
$sql_stat_solicitudes = "SELECT COUNT(*) AS total FROM avisos WHERE creador_id = ? AND estatus_id = 3";
$stmt_stat_sol = $conexion->prepare($sql_stat_solicitudes);
$stmt_stat_sol->bind_param("i", $usuario_id);
if($stmt_stat_sol->execute()){
    $total_solicitudes_completadas = $stmt_stat_sol->get_result()->fetch_assoc()['total'];
}
$stmt_stat_sol->close();

$conexion->close();

// Mapas de datos para el HTML
$iconos_categoria = [
    1 => '<i class="fas fa-tint fa-2x text-danger"></i>',
    2 => '<i class="fas fa-pills fa-2x text-primary"></i>',
    3 => '<i class="fas fa-wheelchair fa-2x text-warning"></i>'
];
$estatus_donacion_mapa = [
    1 => ['texto' => 'Pendiente', 'color' => 'warning'],
    2 => ['texto' => 'Aprobada', 'color' => 'info'],
    3 => ['texto' => 'Completada', 'color' => 'success'],
    4 => ['texto' => 'Rechazada', 'color' => 'danger']
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Panel de Usuario</title>
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
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class='display-5 mb-0'>¡Hola, <?php echo htmlspecialchars($nombre_usuario); ?>!</h1>
                    <p class="fs-5 text-muted mb-0">Bienvenido a tu panel de control.</p>
                </div>
                <a href="segmentos.php" class="btn btn-primary rounded-pill py-2 px-4 d-none d-lg-block"><i class="fas fa-plus me-2"></i>Crear Nueva Solicitud</a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">Mis Solicitudes</h4>
                            <div id="lista-solicitudes">
                                <?php if (empty($mis_solicitudes)) : ?>
                                    <p class="text-muted">No has creado ninguna solicitud de donación.</p>
                                <?php else : ?>
                                    <?php foreach ($mis_solicitudes as $solicitud) : ?>
                                        <a href="avisos_detalles.php?id=<?php echo $solicitud['aviso_id']; ?>" class="text-decoration-none text-dark d-block">
                                            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                <div class="flex-shrink-0 text-center" style="width: 50px;">
                                                    <?php echo $iconos_categoria[$solicitud['categoria_id']] ?? ''; ?>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="mb-1"><?php echo htmlspecialchars($solicitud['titulo']); ?></h5>
                                                        <small class="text-muted"><?php echo date('d/m/Y', strtotime($solicitud['fecha_creacion'])); ?></small>
                                                    </div>
                                                    
                                                    <?php 
                                                    switch ($solicitud['estatus_id']):
                                                        case 1: // Pendiente ?>
                                                            <p class="mb-1 mt-2"><span class="badge bg-secondary">Pendiente de Aprobación</span></p>
                                                            <?php break;
                                                        
                                                        case 2: // Activa (con barra de progreso)
                                                            $porcentaje = ($solicitud['cantidad_requerida'] > 0) ? round(($solicitud['cantidad_recolectada'] / $solicitud['cantidad_requerida']) * 100) : 0;
                                                            ?>
                                                            <p class="mb-1 text-muted">Progreso: <?php echo $solicitud['cantidad_recolectada']; ?> de <?php echo $solicitud['cantidad_requerida']; ?> unidades.</p>
                                                            <div class="progress" style="height: 10px;">
                                                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $porcentaje; ?>%;" aria-valuenow="<?php echo $porcentaje; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <?php break;
                                                        
                                                        case 3: // Completada ?>
                                                            <p class="mb-1 mt-2"><span class="badge bg-success">Completada</span></p>
                                                            <?php break;
                                                            
                                                        case 4: // Rechazada ?>
                                                            <p class="mb-1 mt-2"><span class="badge bg-danger">Rechazada</span></p>
                                                            <?php break;

                                                    endswitch;
                                                    ?>
                                                    </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">Mis Donaciones Realizadas</h4>
                            <div id="lista-donaciones">
                                <?php if (empty($mis_donaciones)) : ?>
                                    <p class="text-muted">Aún no has realizado ninguna donación. ¡Anímate a ayudar!</p>
                                <?php else : ?>
                                    <?php foreach ($mis_donaciones as $donacion) :
                                        $estatus = $estatus_donacion_mapa[$donacion['estatus_id']] ?? ['texto' => 'Desconocido', 'color' => 'secondary'];
                                    ?>
                                    <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                        <div class="flex-shrink-0 text-center" style="width: 50px;">
                                            <i class="fas fa-hand-holding-heart fa-2x text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="mb-1">
                                                    Donación de <?php echo htmlspecialchars($donacion['item_nombre'] ?? 'Sangre'); ?>
                                                </h5>
                                                <span class="badge bg-<?php echo $estatus['color']; ?>"><?php echo $estatus['texto']; ?></span>
                                            </div>
                                            <p class="mb-0 text-muted">
                                                <?php if (!empty($donacion['aviso_titulo'])) : ?>
                                                    <i class="fas fa-bullhorn me-2"></i>Para la solicitud: "<?php echo htmlspecialchars($donacion['aviso_titulo']); ?>"<br>
                                                <?php elseif (!empty($donacion['nombre_organizacion'])) : ?>
                                                    <i class="fas fa-building me-2"></i>Entregado a: <?php echo htmlspecialchars($donacion['nombre_organizacion']); ?><br>
                                                <?php endif; ?>
                                                <i class="fas fa-calendar-alt me-2"></i>Fecha de compromiso: <?php echo date('d/m/Y', strtotime($donacion['fecha_compromiso'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4 position-sticky" style="top: 20px;">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">Tu Impacto</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Donaciones Completadas
                                    <span class="badge bg-primary rounded-pill"><?php echo $total_donaciones_completadas; ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Solicitudes Completadas
                                    <span class="badge bg-success rounded-pill"><?php echo $total_solicitudes_completadas; ?></span>
                                </li>
                            </ul>
                            <h4 class="card-title mt-4 mb-4">Beneficios para Donantes</h4>
                            <p class="text-muted">Gracias a tu generosidad, tienes acceso a promociones de nuestras empresas aliadas.</p>
                            <a href="persona_beneficios.php" class="btn btn-outline-primary w-100">Ver Beneficios</a>
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