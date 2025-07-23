<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 1. AUTENTICACIÓN Y AUTORIZACIÓN
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['id'];

$sql_org = "SELECT organizacion_id FROM usuarios_x_organizaciones WHERE usuario_id = ?";
$stmt_org = $conexion->prepare($sql_org);
$stmt_org->bind_param("i", $usuario_id);
$stmt_org->execute();
$resultado_org = $stmt_org->get_result();
if ($resultado_org->num_rows === 0) {
    header('Location: persona_dashboard.php');
    exit();
}
$organizacion_id = $resultado_org->fetch_assoc()['organizacion_id'];
$stmt_org->close();

// 2. OBTENER LAS LISTAS DE DONACIONES CON DESCRIPCIÓN MEJORADA
function obtener_donaciones_por_estatus($conexion, $organizacion_id, $estatus_ids) {
    $donaciones = [];
    if (!is_array($estatus_ids)) { $estatus_ids = [$estatus_ids]; }
    if (empty($estatus_ids)) { return []; }
    
    $placeholders = implode(',', array_fill(0, count($estatus_ids), '?'));
    
    $sql = "SELECT 
                d.id AS donacion_id, d.estatus_id,
                d.fecha_compromiso, d.fecha_validacion,
                d.cantidad, d.item_nombre, d.item_detalle, d.fecha_caducidad, d.ruta_foto,
                CONCAT(pp.nombre, ' ', pp.apellido_paterno) AS nombre_donante,
                ts.tipo AS tipo_sangre_donante,
                CASE
                    WHEN d.item_nombre IS NOT NULL AND d.item_nombre != '' THEN d.item_nombre
                    WHEN a.categoria_id = 1 THEN 'Donación de Sangre'
                    WHEN d.aviso_id IS NULL AND d.item_nombre IS NULL THEN 'Donación de Sangre (General)'
                    ELSE CONCAT('Apoyo para: ', a.titulo)
                END AS donacion_descripcion
            FROM donaciones d
            JOIN personas_perfil pp ON d.donante_id = pp.usuario_id
            LEFT JOIN avisos a ON d.aviso_id = a.id
            LEFT JOIN tipos_sangre ts ON pp.tipo_sangre_id = ts.id
            WHERE 
                (d.organizacion_id = ? OR a.organizacion_id = ?) 
                AND d.estatus_id IN ($placeholders)";
    
    $types = 'ii' . str_repeat('i', count($estatus_ids));
    $params = array_merge([$organizacion_id, $organizacion_id], $estatus_ids);
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $resultado = $stmt->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        $donaciones[] = $fila;
    }
    $stmt->close();
    return $donaciones;
}

$donaciones_pendientes = obtener_donaciones_por_estatus($conexion, $organizacion_id, 1);
$donaciones_aprobadas = obtener_donaciones_por_estatus($conexion, $organizacion_id, 2);
$donaciones_historial = obtener_donaciones_por_estatus($conexion, $organizacion_id, [3, 4]);

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Gestionar Donantes</title>
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
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Cargando...</span></div>
    </div>
    
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Gestionar Donaciones</h1>
                <p class="fs-5 text-muted mb-0">Aprueba y da seguimiento a los donantes comprometidos con tus causas.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <ul class="nav nav-pills nav-fill mb-4" id="donorsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active position-relative" id="pending-donors-tab" data-bs-toggle="tab" data-bs-target="#pending-donors" type="button" role="tab" aria-controls="pending-donors" aria-selected="true">
                        Pendientes de Aprobación
                        <?php if(count($donaciones_pendientes) > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo count($donaciones_pendientes); ?></span>
                        <?php endif; ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="in-progress-tab" data-bs-toggle="tab" data-bs-target="#in-progress" type="button" role="tab" aria-controls="in-progress" aria-selected="false">
                        Aprobados (En Proceso)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-donors-tab" data-bs-toggle="tab" data-bs-target="#history-donors" type="button" role="tab" aria-controls="history-donors" aria-selected="false">
                        Historial
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="donorsTabsContent">
                
                <div class="tab-pane fade show active" id="pending-donors" role="tabpanel" aria-labelledby="pending-donors-tab">
                    <div class="card border-0 shadow-sm"><div class="card-body p-4">
                        <h5 class="card-title mb-4">Donaciones Pendientes de Aprobación</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light"><tr><th>Donante</th><th>Donación</th><th>Fecha de Compromiso</th><th class="text-center">Acciones</th></tr></thead>
                                <tbody>
                                    <?php if(empty($donaciones_pendientes)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">No hay donaciones pendientes.</td></tr>
                                    <?php else: foreach($donaciones_pendientes as $donacion): ?>
                                        <tr style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#detalleDonacionModal"
                                            data-donante="<?php echo htmlspecialchars($donacion['nombre_donante']); ?>"
                                            data-descripcion="<?php echo htmlspecialchars($donacion['donacion_descripcion']); ?>"
                                            data-cantidad="<?php echo htmlspecialchars($donacion['cantidad']); ?>"
                                            data-item-nombre="<?php echo htmlspecialchars($donacion['item_nombre'] ?? 'N/A'); ?>"
                                            data-item-detalle="<?php echo htmlspecialchars($donacion['item_detalle'] ?? 'No se proporcionaron detalles.'); ?>"
                                            data-fecha-caducidad="<?php echo !empty($donacion['fecha_caducidad']) ? date('d/m/Y', strtotime($donacion['fecha_caducidad'])) : 'No aplica'; ?>"
                                            data-foto="<?php echo !empty($donacion['ruta_foto']) ? htmlspecialchars($donacion['ruta_foto']) : ''; ?>"
                                            data-tipo-sangre="<?php echo htmlspecialchars($donacion['tipo_sangre_donante'] ?? 'No especificado'); ?>"> 
                                            <td><?php echo htmlspecialchars($donacion['nombre_donante']); ?></td>
                                            <td><?php echo htmlspecialchars($donacion['donacion_descripcion']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($donacion['fecha_compromiso'])); ?></td>
                                            <td class="text-center">
                                                <form action="auth/gestionar_donacion.php" method="POST" class="d-inline" onclick="event.stopPropagation();">
                                                    <input type="hidden" name="donacion_id" value="<?php echo $donacion['donacion_id']; ?>">
                                                    <button type="submit" name="accion" value="aprobar" class="btn btn-sm btn-success me-1" title="Aprobar"><i class="fas fa-check"></i></button>
                                                    <button type="submit" name="accion" value="rechazar" class="btn btn-sm btn-danger" title="Rechazar"><i class="fas fa-times"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div></div>
                </div>

                <div class="tab-pane fade" id="in-progress" role="tabpanel" aria-labelledby="in-progress-tab">
                     <div class="card border-0 shadow-sm"><div class="card-body p-4">
                        <h5 class="card-title mb-4">Donaciones Aprobadas en Proceso de Entrega</h5>
                         <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light"><tr><th>Donante</th><th>Donación</th><th>Fecha de Aprobación</th><th class="text-center">Acciones</th></tr></thead>
                                <tbody>
                                    <?php if(empty($donaciones_aprobadas)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">No hay donaciones en proceso.</td></tr>
                                    <?php else: foreach($donaciones_aprobadas as $donacion): ?>
                                        <tr style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#detalleDonacionModal"
                                            data-donante="<?php echo htmlspecialchars($donacion['nombre_donante']); ?>"
                                            data-descripcion="<?php echo htmlspecialchars($donacion['donacion_descripcion']); ?>"
                                            data-cantidad="<?php echo htmlspecialchars($donacion['cantidad']); ?>"
                                            data-item-nombre="<?php echo htmlspecialchars($donacion['item_nombre'] ?? 'N/A'); ?>"
                                            data-item-detalle="<?php echo htmlspecialchars($donacion['item_detalle'] ?? 'No se proporcionaron detalles.'); ?>"
                                            data-fecha-caducidad="<?php echo !empty($donacion['fecha_caducidad']) ? date('d/m/Y', strtotime($donacion['fecha_caducidad'])) : 'No aplica'; ?>"
                                            data-foto="<?php echo !empty($donacion['ruta_foto']) ? htmlspecialchars($donacion['ruta_foto']) : ''; ?>"
                                            data-tipo-sangre="<?php echo htmlspecialchars($donacion['tipo_sangre_donante'] ?? 'No especificado'); ?>">
                                            <td><?php echo htmlspecialchars($donacion['nombre_donante']); ?></td>
                                            <td><?php echo htmlspecialchars($donacion['donacion_descripcion']); ?></td>
                                            <td><?php echo !empty($donacion['fecha_validacion']) ? date('d/m/Y', strtotime($donacion['fecha_validacion'])) : 'N/A'; ?></td>
                                            <td class="text-center">
                                                <form action="auth/gestionar_donacion.php" method="POST" class="d-inline" onclick="event.stopPropagation();">
                                                    <input type="hidden" name="donacion_id" value="<?php echo $donacion['donacion_id']; ?>">
                                                    
                                                    <button type="submit" name="accion" value="recibir" class="btn btn-sm btn-primary me-1" title="Marcar como Recibido"><i class="fas fa-gift"></i></button>
                                                    
                                                    <button type="submit" name="accion" value="no_concretado" class="btn btn-sm btn-warning" title="Marcar como No Concretado"><i class="fas fa-ban"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div></div>
                </div>

                <div class="tab-pane fade" id="history-donors" role="tabpanel" aria-labelledby="history-donors-tab">
                     <div class="card border-0 shadow-sm"><div class="card-body p-4">
                        <h5 class="card-title mb-4">Historial de Donaciones</h5>
                         <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light"><tr><th>Donante</th><th>Donación</th><th>Fecha Finalización</th><th class="text-center">Resultado</th></tr></thead>
                                <tbody>
                                    <?php if(empty($donaciones_historial)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">Aún no hay donaciones en el historial.</td></tr>
                                    <?php else: foreach($donaciones_historial as $donacion): ?>
                                        <tr style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#detalleDonacionModal"
                                            data-donante="<?php echo htmlspecialchars($donacion['nombre_donante']); ?>"
                                            data-descripcion="<?php echo htmlspecialchars($donacion['donacion_descripcion']); ?>"
                                            data-cantidad="<?php echo htmlspecialchars($donacion['cantidad']); ?>"
                                            data-item-nombre="<?php echo htmlspecialchars($donacion['item_nombre'] ?? 'N/A'); ?>"
                                            data-item-detalle="<?php echo htmlspecialchars($donacion['item_detalle'] ?? 'No se proporcionaron detalles.'); ?>"
                                            data-fecha-caducidad="<?php echo !empty($donacion['fecha_caducidad']) ? date('d/m/Y', strtotime($donacion['fecha_caducidad'])) : 'No aplica'; ?>"
                                            data-foto="<?php echo !empty($donacion['ruta_foto']) ? htmlspecialchars($donacion['ruta_foto']) : ''; ?>"
                                            data-tipo-sangre="<?php echo htmlspecialchars($donacion['tipo_sangre_donante'] ?? 'No especificado'); ?>">
                                            <td><?php echo htmlspecialchars($donacion['nombre_donante']); ?></td>
                                            <td><?php echo htmlspecialchars($donacion['donacion_descripcion']); ?></td>
                                            <td><?php echo !empty($donacion['fecha_validacion']) ? date('d/m/Y', strtotime($donacion['fecha_validacion'])) : 'N/A'; ?></td>
                                            <td class="text-center">
                                                <?php if ($donacion['estatus_id'] == 3): ?><span class="badge bg-success">Recibido</span><?php elseif ($donacion['estatus_id'] == 4): ?><span class="badge bg-danger">No Concretado</span><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="detalleDonacionModal" tabindex="-1" aria-labelledby="detalleDonacionModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="detalleDonacionModalLabel">Detalles de la Donación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="detalle-foto-container" class="mb-3 text-center" style="display: none;">
                <img id="detalle-foto" src="" class="img-fluid rounded" alt="Foto del Ítem" style="max-height: 250px;">
            </div>
            <p><strong>Donante:</strong> <span id="detalle-donante"></span></p>
            <p><strong>Descripción General:</strong> <span id="detalle-descripcion"></span></p>
            <p><strong>Ítem Específico:</strong> <span id="detalle-item-nombre"></span></p>
            <p><strong>Cantidad:</strong> <span id="detalle-cantidad"></span> unidad(es)</p>
            <p><strong>Fecha de Caducidad:</strong> <span id="detalle-fecha-caducidad"></span></p>
            <p><strong>Detalles Adicionales:</strong></p>
            <p class="text-muted" id="detalle-item-detalle"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    
    <?php require_once 'templates/scripts.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const detalleModal = document.getElementById('detalleDonacionModal');
        if (detalleModal) {
            detalleModal.addEventListener('show.bs.modal', function (event) {
                const row = event.relatedTarget;

                const donante = row.getAttribute('data-donante');
                const descripcion = row.getAttribute('data-descripcion');
                const cantidad = row.getAttribute('data-cantidad');
                const itemNombre = row.getAttribute('data-item-nombre');
                const itemDetalle = row.getAttribute('data-item-detalle');
                const fechaCaducidad = row.getAttribute('data-fecha-caducidad');
                const fotoUrl = row.getAttribute('data-foto');
                const tipoSangre = row.getAttribute('data-tipo-sangre');

                const modal = event.target;
                modal.querySelector('#detalle-donante').textContent = donante;
                modal.querySelector('#detalle-descripcion').textContent = descripcion;
                modal.querySelector('#detalle-cantidad').textContent = cantidad;
                modal.querySelector('#detalle-item-detalle').textContent = itemDetalle;
                modal.querySelector('#detalle-fecha-caducidad').textContent = fechaCaducidad;
                
                let itemEspecificoFinal = itemNombre;
                if ((itemNombre === 'N/A' || itemNombre === '') && descripcion.toLowerCase().includes('sangre')) {
                    if (tipoSangre && tipoSangre !== 'No especificado' && tipoSangre !== 'null') {
                        itemEspecificoFinal = 'Tipo de Sangre del Donante: ' + tipoSangre;
                    } else {
                        itemEspecificoFinal = 'Tipo de Sangre del Donante: No especificado';
                    }
                }
                modal.querySelector('#detalle-item-nombre').textContent = itemEspecificoFinal;

                const fotoContainer = modal.querySelector('#detalle-foto-container');
                const fotoImg = modal.querySelector('#detalle-foto');
                
                if (fotoUrl) {
                    fotoImg.src = fotoUrl;
                    fotoContainer.style.display = 'block';
                } else {
                    fotoContainer.style.display = 'none';
                }
            });
        }
    });
    </script>
</body>
</html>