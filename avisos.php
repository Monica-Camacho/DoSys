<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// --- Lógica de filtros y clasificación ---
$keyword = $_GET['q'] ?? '';
$tipo_id = $_GET['tipo'] ?? '';
$ubicacion = $_GET['ubicacion'] ?? '';
$urgencia_id = filter_input(INPUT_GET, 'urgencia', FILTER_VALIDATE_INT);
$sort_by = $_GET['sort'] ?? 'recientes';

$avisos = [];

// --- Consulta SQL ---
$sql = "SELECT 
            a.id AS aviso_id, a.titulo, a.descripcion, a.categoria_id,
            a.urgencia_id, un.nombre AS urgencia_nombre,
            d.municipio, d.estado,
            COALESCE(ss.unidades_requeridas, sm.cantidad_requerida, sd.cantidad_requerida) AS cantidad_requerida,
            COALESCE((SELECT SUM(cantidad) FROM donaciones WHERE aviso_id = a.id AND estatus_id = 3), 0) AS cantidad_recolectada
        FROM 
            avisos a
        JOIN 
            organizaciones_perfil op ON a.organizacion_id = op.id
        LEFT JOIN 
            urgencia_niveles un ON a.urgencia_id = un.id
        LEFT JOIN 
            direcciones d ON op.direccion_id = d.id
        LEFT JOIN 
            solicitudes_sangre ss ON a.id = ss.aviso_id AND a.categoria_id = 1
        LEFT JOIN 
            solicitudes_medicamentos sm ON a.id = sm.aviso_id AND a.categoria_id = 2
        LEFT JOIN 
            solicitudes_dispositivos sd ON a.id = sd.aviso_id AND a.categoria_id = 3";

$conditions = ["a.estatus_id = 2", "op.estado = 'Activa'"];
$params = [];
$types = '';

// Lógica de filtros
if (!empty($keyword)) {
    $conditions[] = "(a.titulo LIKE ? OR a.descripcion LIKE ?)";
    $params[] = "%$keyword%"; $params[] = "%$keyword%";
    $types .= 'ss';
}
if (!empty($tipo_id)) {
    $conditions[] = "a.categoria_id = ?";
    $params[] = $tipo_id;
    $types .= 'i';
}
if (!empty($ubicacion)) {
    $conditions[] = "d.municipio = ?";
    $params[] = $ubicacion;
    $types .= 's';
}
if (!empty($urgencia_id)) {
    $conditions[] = "a.urgencia_id = ?";
    $params[] = $urgencia_id;
    $types .= 'i';
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Lógica de clasificación
$mapa_clasificacion = [
    'recientes' => 'a.fecha_creacion DESC',
    'urgencia' => 'a.urgencia_id DESC, a.fecha_creacion DESC'
];
$clausula_order_by = $mapa_clasificacion[$sort_by] ?? $mapa_clasificacion['recientes'];
$sql .= " ORDER BY " . $clausula_order_by;

$stmt = $conexion->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result();
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $avisos[] = $fila;
    }
}
$stmt->close();

$urgencias = $conexion->query("SELECT id, nombre FROM urgencia_niveles ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$conexion->close();

// Mapa de iconos y colores
$iconos_categoria = [
    1 => '<i class="fas fa-tint fa-2x text-danger"></i>',
    2 => '<i class="fas fa-pills fa-2x text-primary"></i>',
    3 => '<i class="fas fa-wheelchair fa-2x text-warning"></i>'
];
$colores_urgencia = [
    'Baja' => 'bg-info text-dark',
    'Medio' => 'bg-info text-dark',
    'Alto' => 'bg-warning text-dark',
    'Crítico' => 'bg-danger text-white'
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>DoSys - Avisos de Donación</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">

    <!-- Google Web Fonts, Iconos y Estilos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Librerías de Estilos -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Estilos de Bootstrap y de la Plantilla -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
    </div>
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="d-lg-flex justify-content-between align-items-center mb-5">
                <div class="text-center text-lg-start">
                    <h1 class="display-5">Avisos de Donación</h1>
                    <p class="fs-5 text-muted mb-0">Explora las solicitudes activas y encuentra una causa a la que puedas apoyar.</p>
                </div>
                <div class="text-center mt-4 mt-lg-0">
                    <a href="segmentos.php" class="btn btn-success rounded-pill py-2 px-4"><i class="fas fa-plus me-2"></i>Solicitar Donación</a>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <form class="row g-3 align-items-center bg-white p-3 rounded shadow-sm" method="GET" action="avisos.php">
                        <div class="col-lg-3 col-md-12">
                            <input type="text" name="q" class="form-control" placeholder="Buscar por palabra clave..." value="<?php echo htmlspecialchars($keyword); ?>">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select name="tipo" class="form-select">
                                <option value="">Tipo...</option>
                                <option value="1" <?php if ($tipo_id == '1') echo 'selected'; ?>>Sangre</option>
                                <option value="2" <?php if ($tipo_id == '2') echo 'selected'; ?>>Medicamentos</option>
                                <option value="3" <?php if ($tipo_id == '3') echo 'selected'; ?>>Dispositivos</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select name="ubicacion" class="form-select">
                                <option value="">Ubicación...</option>
                                <option value="Centro" <?php if ($ubicacion == 'Centro') echo 'selected'; ?>>Centro</option>
                                <option value="Cárdenas" <?php if ($ubicacion == 'Cárdenas') echo 'selected'; ?>>Cárdenas</option>
                                <option value="Comalcalco" <?php if ($ubicacion == 'Comalcalco') echo 'selected'; ?>>Comalcalco</option>
                                <option value="Paraíso" <?php if ($ubicacion == 'Paraíso') echo 'selected'; ?>>Paraíso</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select name="urgencia" class="form-select">
                                <option value="">Urgencia...</option>
                                <?php foreach ($urgencias as $urgencia): ?>
                                    <option value="<?php echo $urgencia['id']; ?>" <?php if ($urgencia_id == $urgencia['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($urgencia['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select name="sort" class="form-select">
                                <option value="recientes" <?php if ($sort_by == 'recientes') echo 'selected'; ?>>Clasificar por: Más Recientes</option>
                                <option value="urgencia" <?php if ($sort_by == 'urgencia') echo 'selected'; ?>>Clasificar por: Mayor Urgencia</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-12 text-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                <?php if (!empty($avisos)) : ?>
                    <?php foreach ($avisos as $aviso) : ?>
                        <?php
                        $requerido = $aviso['cantidad_requerida'];
                        $recolectado = $aviso['cantidad_recolectada'];
                        $porcentaje = ($requerido > 0) ? round(($recolectado / $requerido) * 100) : 0;
                        ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex flex-column p-4">

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <?php if ($aviso['urgencia_id'] > 1): // No mostrar badge para 'Bajo' (ID 1) ?>
                                                <span class="badge <?php echo $colores_urgencia[$aviso['urgencia_nombre']] ?? 'bg-secondary'; ?>">
                                                    <?php echo htmlspecialchars($aviso['urgencia_nombre']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span>&nbsp;</span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <?php echo $iconos_categoria[$aviso['categoria_id']] ?? '<i class="fas fa-heart fa-2x text-muted"></i>'; ?>
                                        </div>
                                    </div>

                                    <h5 class="card-title"><?php echo htmlspecialchars($aviso['titulo']); ?></h5>

                                    <p class="card-text text-muted small mb-3">
                                        <i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($aviso['municipio'] . ', ' . $aviso['estado']); ?>
                                    </p>

                                    <p class="card-text"><?php echo htmlspecialchars(substr($aviso['descripcion'], 0, 100)) . '...'; ?></p>
                                    
                                    <div class="mt-auto pt-3">
                                        <div class="progress mb-2" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $porcentaje; ?>%;" aria-valuenow="<?php echo $porcentaje; ?>"></div>
                                        </div>
                                        <p class="text-muted small"><?php echo number_format($recolectado); ?> de <?php echo number_format($requerido); ?> unidades recolectadas</p>
                                        <a href="avisos_detalles.php?id=<?php echo $aviso['aviso_id']; ?>" class="btn btn-primary rounded-pill w-100">Ver Detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center" role="alert">
                            <h4 class="alert-heading">¡No se encontraron resultados!</h4>
                            <p>Intenta ajustar tus filtros o revisa más tarde. Por el momento no hay solicitudes que coincidan con tu criterio.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>
    <?php require_once 'templates/scripts.php'; ?>
</body>
</html>