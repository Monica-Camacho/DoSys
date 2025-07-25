<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['id'];

// --- CONSULTAS SEPARADAS POR ESTADO ---
$beneficios_disponibles = [];
$beneficios_historial = [];

// Consulta para beneficios DISPONIBLES
$sql_disponibles = "SELECT db.id, ea.titulo, ea.descripcion, ea.fecha_expiracion, db.codigo_canje,
                           ep.nombre_comercial AS empresa_nombre, d.ruta_archivo AS empresa_logo,
                           img_benefit.ruta_archivo AS imagen_beneficio_ruta
                    FROM donantes_beneficios db
                    JOIN empresas_apoyos ea ON db.apoyo_id = ea.id
                    JOIN empresas_perfil ep ON ea.empresa_id = ep.id
                    LEFT JOIN documentos d ON ep.logo_documento_id = d.id
                    LEFT JOIN documentos img_benefit ON ea.imagen_documento_id = img_benefit.id
                    WHERE db.usuario_id = ? AND db.estado = 'Disponible'
                    ORDER BY db.fecha_otorgado DESC";
$stmt_disponibles = $conexion->prepare($sql_disponibles);
$stmt_disponibles->bind_param("i", $usuario_id);
$stmt_disponibles->execute();
$resultado_disponibles = $stmt_disponibles->get_result();
while ($row = $resultado_disponibles->fetch_assoc()) {
    $beneficios_disponibles[] = $row;
}
$stmt_disponibles->close();

// Consulta para HISTORIAL (canjeados o expirados)
$sql_historial = "SELECT db.id, ea.titulo, ea.descripcion, db.estado, db.fecha_canje,
                         ep.nombre_comercial AS empresa_nombre, d.ruta_archivo AS empresa_logo
                  FROM donantes_beneficios db
                  JOIN empresas_apoyos ea ON db.apoyo_id = ea.id
                  JOIN empresas_perfil ep ON ea.empresa_id = ep.id
                  LEFT JOIN documentos d ON ep.logo_documento_id = d.id
                  WHERE db.usuario_id = ? AND db.estado IN ('Canjeado', 'Expirado')
                  ORDER BY db.fecha_canje DESC, db.fecha_otorgado DESC";
$stmt_historial = $conexion->prepare($sql_historial);
$stmt_historial->bind_param("i", $usuario_id);
$stmt_historial->execute();
$resultado_historial = $stmt_historial->get_result();
while ($row = $resultado_historial->fetch_assoc()) {
    $beneficios_historial[] = $row;
}
$stmt_historial->close();
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Mis Beneficios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .benefit-card { background-color: #fff; border: 1px solid #e9ecef; border-radius: 0.75rem; transition: all 0.3s ease; display: flex; flex-direction: column; overflow: hidden; }
        .benefit-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .benefit-image-container { width: 100%; height: 180px; overflow: hidden; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; }
        .benefit-image-container img { width: 100%; height: 100%; object-fit: cover; }
        .benefit-logo-container { height: 80px; padding: 0.5rem; display: flex; align-items: center; justify-content: center; border-bottom: 1px dashed #ccc; background-color: #fff; }
        .benefit-logo-container img { max-width: 100px; max-height: 60px; object-fit: contain; }
        .benefit-card .card-body { flex-grow: 1; display: flex; flex-direction: column; }
        #benefitModal .coupon-code { background-color: #e9ecef; border: 2px dashed #adb5bd; padding: 1rem; font-size: 1.5rem; font-weight: bold; letter-spacing: 2px; color: #343a40; word-break: break-all; }
    </style>
</head>
<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div></div>
    
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Mis Beneficios</h1>
                <p class="fs-5 text-muted mb-0">Gracias por tu generosidad. Aquí tienes las recompensas de nuestras empresas aliadas.</p>
            </div>
        </div>
    </div>

    <main class="container py-5">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <ul class="nav nav-pills nav-fill mb-4" id="benefitsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="disponibles-tab" data-bs-toggle="tab" data-bs-target="#disponibles" type="button">Disponibles</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button">Historial</button>
            </li>
        </ul>

        <div class="tab-content" id="benefitsTabsContent">
            <div class="tab-pane fade show active" id="disponibles" role="tabpanel">
                <div class="row g-4">
                    <?php if (!empty($beneficios_disponibles)): ?>
                        <?php foreach ($beneficios_disponibles as $beneficio): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card benefit-card h-100">
                                    <div class="benefit-image-container"><img src="<?php echo !empty($beneficio['imagen_beneficio_ruta']) ? BASE_URL . htmlspecialchars($beneficio['imagen_beneficio_ruta']) : 'https://placehold.co/400x180/E9ECEF/6C757D?text=Beneficio'; ?>" alt="Imagen de Beneficio"></div>
                                    <div class="benefit-logo-container"><img src="<?php echo !empty($beneficio['empresa_logo']) ? BASE_URL . htmlspecialchars($beneficio['empresa_logo']) : 'https://placehold.co/100x60/E9ECEF/6C757D?text=Logo'; ?>" alt="Logo de Empresa"></div>
                                    <div class="card-body p-4">
                                        <h5 class="card-title"><?php echo htmlspecialchars($beneficio['titulo']); ?></h5>
                                        <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars($beneficio['descripcion']); ?></p>
                                        <p class="card-text small mb-3"><i class="far fa-calendar-alt me-2"></i><strong>Expira:</strong> <?php echo $beneficio['fecha_expiracion'] ? date("d/m/Y", strtotime($beneficio['fecha_expiracion'])) : 'N/A'; ?></p>
                                        
                                        <?php if (empty($beneficio['codigo_canje'])): ?>
                                            <a href="auth/reclamar_beneficio.php?id=<?php echo $beneficio['id']; ?>" class="btn btn-success w-100" onclick="return confirm('¿Estás seguro de que quieres reclamar este código? Una vez generado, deberás usarlo.');">
                                                <i class="fas fa-ticket-alt me-2"></i>Reclamar Código
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#benefitModal"
                                                    data-titulo="<?php echo htmlspecialchars($beneficio['titulo']); ?>"
                                                    data-empresa="<?php echo htmlspecialchars($beneficio['empresa_nombre']); ?>"
                                                    data-codigo="<?php echo htmlspecialchars($beneficio['codigo_canje']); ?>"
                                                    data-logo="<?php echo !empty($beneficio['empresa_logo']) ? BASE_URL . htmlspecialchars($beneficio['empresa_logo']) : ''; ?>"
                                                    data-imagen-beneficio="<?php echo !empty($beneficio['imagen_beneficio_ruta']) ? BASE_URL . htmlspecialchars($beneficio['imagen_beneficio_ruta']) : ''; ?>">
                                                <i class="fas fa-cut me-2"></i>Ver Código
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12"><div class="alert alert-info text-center"><h4 class="alert-heading">¡Aún no tienes beneficios!</h4><p>Realiza una donación para empezar a recibir recompensas.</p><hr><a href="avisos.php" class="btn btn-primary">Ver Oportunidades de Donación</a></div></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="historial" role="tabpanel">
                 <div class="row g-4">
                    <?php if (!empty($beneficios_historial)): ?>
                        <?php foreach ($beneficios_historial as $beneficio): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card benefit-card h-100 opacity-75">
                                    <div class="benefit-logo-container"><img src="<?php echo !empty($beneficio['empresa_logo']) ? BASE_URL . htmlspecialchars($beneficio['empresa_logo']) : 'https://placehold.co/100x60/E9ECEF/6C757D?text=Logo'; ?>" alt="Logo de Empresa"></div>
                                    <div class="card-body p-4">
                                        <span class="badge bg-<?php echo $beneficio['estado'] == 'Canjeado' ? 'success' : 'secondary'; ?> mb-2"><?php echo htmlspecialchars($beneficio['estado']); ?></span>
                                        <h5 class="card-title"><?php echo htmlspecialchars($beneficio['titulo']); ?></h5>
                                        <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars($beneficio['descripcion']); ?></p>
                                        <p class="card-text small mb-0"><i class="far fa-calendar-check me-2"></i><strong>Canjeado:</strong> <?php echo $beneficio['fecha_canje'] ? date("d/m/Y", strtotime($beneficio['fecha_canje'])) : 'Expirado'; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                         <div class="col-12"><div class="alert alert-light text-center"><p class="mb-0">Tu historial de beneficios canjeados o expirados aparecerá aquí.</p></div></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="benefitModal" tabindex="-1" aria-labelledby="benefitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="benefitModalLabel">Tu Beneficio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalEmpresaLogo" src="" alt="Logo de la Empresa" class="mb-3" style="max-height: 60px;">
                    <img id="modalImagenBeneficio" src="" alt="Imagen del Beneficio" class="mb-3" style="max-width: 100%; height: auto; border-radius: 5px; display: none;">
                    <h4 id="modalTituloBeneficio" class="mb-3"></h4>
                    <p>Presenta este código en la sucursal para hacerlo válido:</p>
                    <div class="coupon-code text-center my-3">
                        <span id="modalCodigo"></span>
                    </div>
                    <img id="modalQrCode" src="" alt="Código QR" style="width: 200px; height: 200px;">
                    <p class="mt-3 text-muted small">Este código es único e intransferible. Válido una sola vez.</p>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    
    <?php require_once 'templates/scripts.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var benefitModal = document.getElementById('benefitModal');
            benefitModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var titulo = button.getAttribute('data-titulo');
                var empresa = button.getAttribute('data-empresa');
                var codigo = button.getAttribute('data-codigo');
                var logo = button.getAttribute('data-logo');
                var imagenBeneficio = button.getAttribute('data-imagen-beneficio');
                var qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(codigo)}`;

                var modalTitle = benefitModal.querySelector('.modal-title');
                var modalLogo = benefitModal.querySelector('#modalEmpresaLogo');
                var modalImagenBeneficio = benefitModal.querySelector('#modalImagenBeneficio');
                var modalTituloBeneficio = benefitModal.querySelector('#modalTituloBeneficio');
                var modalCodigo = benefitModal.querySelector('#modalCodigo');
                var modalQrCode = benefitModal.querySelector('#modalQrCode');

                modalTitle.textContent = 'Beneficio de ' + empresa;
                modalLogo.src = logo;
                modalTituloBeneficio.textContent = titulo;
                modalCodigo.textContent = codigo;
                modalQrCode.src = qrUrl;

                if (imagenBeneficio && imagenBeneficio !== '') {
                    modalImagenBeneficio.src = imagenBeneficio;
                    modalImagenBeneficio.style.display = 'block';
                } else {
                    modalImagenBeneficio.style.display = 'none';
                    modalImagenBeneficio.src = '';
                }
            });
        });
    </script>

</body>
</html>