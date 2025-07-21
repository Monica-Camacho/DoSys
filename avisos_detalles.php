<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 1. OBTENER EL ID DEL AVISO
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: avisos.php');
    exit();
}
$aviso_id = intval($_GET['id']);

// 2. CONSULTA FINAL Y DEFINITIVA
$sql = "SELECT
            a.id AS aviso_id, a.titulo, a.descripcion,
            a.categoria_id, cd.nombre AS categoria_nombre,
            op.nombre_organizacion,
            d.calle, d.numero_exterior, d.colonia, d.municipio, d.estado, d.latitud, d.longitud,
            COALESCE(ss.unidades_requeridas, sm.cantidad_requerida, sd.cantidad_requerida) AS cantidad_requerida,
            0 AS cantidad_recolectada,
            
            -- AJUSTE FINAL: Seleccionamos solo la columna 'tipo' de la tabla 'tipos_sangre'
            ts.tipo AS tipo_sangre

        FROM
            avisos a
        JOIN
            organizaciones_perfil op ON a.organizacion_id = op.id
        JOIN
            direcciones d ON op.direccion_id = d.id
        JOIN
            categorias_donacion cd ON a.categoria_id = cd.id
        LEFT JOIN
            solicitudes_sangre ss ON a.id = ss.aviso_id AND a.categoria_id = 1
        LEFT JOIN
            tipos_sangre ts ON ss.tipo_sangre_id = ts.id
        LEFT JOIN
            solicitudes_medicamentos sm ON a.id = sm.aviso_id AND a.categoria_id = 2
        LEFT JOIN
            solicitudes_dispositivos sd ON a.id = sd.aviso_id AND a.categoria_id = 3
        WHERE
            a.id = ?";

$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    die("Error al preparar la consulta: " . htmlspecialchars($conexion->error));
}

$stmt->bind_param("i", $aviso_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header('Location: avisos.php');
    exit();
}

$aviso = $resultado->fetch_assoc();

// 3. CALCULAR EL PROGRESO
$requerido = $aviso['cantidad_requerida'] ?? 0;
$recolectado = $aviso['cantidad_recolectada'] ?? 0;
$porcentaje = ($requerido > 0) ? ($recolectado / $requerido) * 100 : 0;

// Mapa de íconos y colores
$mapa_categorias = [
    1 => ['icono' => 'fa-tint', 'color' => 'bg-danger'],
    2 => ['icono' => 'fa-pills', 'color' => 'bg-primary'],
    3 => ['icono' => 'fa-wheelchair', 'color' => 'bg-warning'],
];
$categoria_info = $mapa_categorias[$aviso['categoria_id']] ?? ['icono' => 'fa-heart', 'color' => 'bg-secondary'];

$stmt->close();
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - <?php echo htmlspecialchars($aviso['titulo']); ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="<?php echo htmlspecialchars(substr($aviso['descripcion'], 0, 155)); ?>" name="description">

    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="lib/animate/animate.min.css" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        #map { height: 400px; width: 100%; border-radius: .5rem; }
    </style>
</head>

<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h2 class="card-title mb-1"><?php echo htmlspecialchars($aviso['titulo']); ?></h2>
                                    <p class="card-text text-muted">
                                        <i class="fas fa-hospital me-2"></i><?php echo htmlspecialchars($aviso['nombre_organizacion']); ?>
                                        <br>
                                        <i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($aviso['municipio'] . ', ' . $aviso['estado']); ?>
                                    </p>
                                </div>
                                <span class="badge <?php echo $categoria_info['color']; ?> p-2 fs-6">
                                    <i class="fas <?php echo $categoria_info['icono']; ?> me-2"></i><?php echo htmlspecialchars($aviso['categoria_nombre']); ?>
                                </span>
                            </div>
                            <hr>
                            <h5 class="mb-3">Descripción de la Necesidad</h5>
                            <p><?php echo nl2br(htmlspecialchars($aviso['descripcion'])); ?></p>
                            
                            <?php if ($aviso['categoria_id'] == 1 && !empty($aviso['tipo_sangre'])): ?>
                            <div class="alert alert-danger mt-4">
                                <strong>Tipo de Sangre Requerido: </strong> <?php echo htmlspecialchars($aviso['tipo_sangre']); ?>
                            </div>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="mb-3">Progreso de la Donación</h5>
                            <p class="text-muted small mb-1"><?php echo number_format($recolectado); ?> de <?php echo number_format($requerido); ?> unidades recolectadas</p>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $porcentaje; ?>%;" aria-valuenow="<?php echo $porcentaje; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg">Donar Ahora</button>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-3">Ubicación de Referencia</h5>
                            <?php if (!empty($aviso['latitud']) && !empty($aviso['longitud'])): ?>
                                <div id="map"></div>
                            <?php else: ?>
                                <div class="alert alert-warning">No hay datos de ubicación para mostrar en el mapa.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>
    
    <?php require_once 'templates/scripts.php'; ?>

    <?php if (!empty($aviso['latitud']) && !empty($aviso['longitud'])): ?>
    <script>
        function initMap() {
            const locationCoords = {
                lat: <?php echo $aviso['latitud']; ?>,
                lng: <?php echo $aviso['longitud']; ?>
            };
            
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: locationCoords,
                mapTypeControl: false,
                streetViewControl: false
            });

            const marker = new google.maps.Marker({
                position: locationCoords,
                map: map,
                title: "<?php echo htmlspecialchars($aviso['nombre_organizacion']); ?>"
            });
        }
    </script>
    <?php endif; ?>
</body>

</html>