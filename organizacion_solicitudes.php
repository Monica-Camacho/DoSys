<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 1. AUTENTICACIÓN Y AUTORIZACIÓN DE LA ORGANIZACIÓN
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

// 2. FUNCIÓN PARA OBTENER LOS AVISOS (SOLICITUDES) POR ESTATUS
function obtener_avisos_por_estatus($conexion, $organizacion_id, $estatus_ids) {
    $avisos = [];
    if (!is_array($estatus_ids)) { $estatus_ids = [$estatus_ids]; }
    if (empty($estatus_ids)) { return []; }
    
    $placeholders = implode(',', array_fill(0, count($estatus_ids), '?'));
    
    $sql = "SELECT
                a.id AS aviso_id, a.titulo, a.descripcion, a.fecha_creacion, a.estatus_id, a.categoria_id, a.urgencia_id,
                cat.nombre as categoria_nombre,
                un.nombre AS urgencia_nombre,
                CONCAT(dnt.nombre, ' ', dnt.apellido_paterno) AS nombre_solicitante,
                COALESCE(ss.unidades_requeridas, sm.cantidad_requerida, sd.cantidad_requerida) AS cantidad_requerida,
                (SELECT COALESCE(SUM(cantidad), 0) FROM donaciones WHERE aviso_id = a.id AND estatus_id = 3) AS cantidad_recolectada,
                ts.tipo AS tipo_sangre,
                sm.nombre_medicamento, sm.dosis, sm.presentacion,
                sd.nombre_dispositivo, sd.especificaciones
            FROM avisos a
            LEFT JOIN donatarios dnt ON a.donatario_id = dnt.id
            LEFT JOIN categorias_donacion cat ON a.categoria_id = cat.id
            LEFT JOIN urgencia_niveles un ON a.urgencia_id = un.id
            LEFT JOIN solicitudes_sangre ss ON a.id = ss.aviso_id
            LEFT JOIN tipos_sangre ts ON ss.tipo_sangre_id = ts.id
            LEFT JOIN solicitudes_medicamentos sm ON a.id = sm.aviso_id
            LEFT JOIN solicitudes_dispositivos sd ON a.id = sd.aviso_id
            WHERE a.organizacion_id = ? AND a.estatus_id IN ($placeholders)
            ORDER BY a.fecha_creacion DESC";

    $types = 'i' . str_repeat('i', count($estatus_ids));
    $params = array_merge([$organizacion_id], $estatus_ids);

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $resultado = $stmt->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        $avisos[] = $fila;
    }
    $stmt->close();
    return $avisos;
}

$solicitudes_pendientes = obtener_avisos_por_estatus($conexion, $organizacion_id, 1);
$solicitudes_activas = obtener_avisos_por_estatus($conexion, $organizacion_id, 2);
$solicitudes_historial = obtener_avisos_por_estatus($conexion, $organizacion_id, [3, 4]);
$urgencias = $conexion->query("SELECT id, nombre FROM urgencia_niveles ORDER BY id")->fetch_all(MYSQLI_ASSOC);

$mapa_categorias = [
    1 => ['icono' => 'fa-tint', 'color' => 'text-danger'],
    2 => ['icono' => 'fa-pills', 'color' => 'text-primary'],
    3 => ['icono' => 'fa-wheelchair', 'color' => 'text-warning'],
];

$mapa_colores_urgencia_badge = [
    'Bajo' => 'bg-secondary',
    'Medio' => 'bg-info text-dark',
    'Alto' => 'bg-warning text-dark',
    'Crítico' => 'bg-danger'
];

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="utf-8">
        <title>DoSys - Gestionar Solicitudes</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

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
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
    </div>
    
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Gestionar Solicitudes</h1>
                <p class="fs-5 text-muted mb-0">Valida, aprueba y da seguimiento a las solicitudes de ayuda.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <ul class="nav nav-pills nav-fill mb-4" id="requestsTabs" role="tablist">
                <li class="nav-item" role="presentation"><button class="nav-link active position-relative" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">Por Validar <?php if (count($solicitudes_pendientes) > 0): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo count($solicitudes_pendientes); ?></span><?php endif; ?></button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button">Activas</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">Historial</button></li>
            </ul>

            <div class="tab-content" id="requestsTabsContent">
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    <div class="card border-0 shadow-sm"><div class="card-body p-4">
                        <h5 class="card-title mb-4">Solicitudes Pendientes de Validación</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light"><tr><th>Tipo</th><th>Solicitante</th><th>Fecha</th><th class="text-center">Acciones</th></tr></thead>
                                <tbody>
                                    <?php if (empty($solicitudes_pendientes)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">No hay solicitudes pendientes.</td></tr>
                                    <?php else: foreach ($solicitudes_pendientes as $solicitud): ?>
                                    <tr data-bs-toggle="modal" data-bs-target="#detalleSolicitudModal" style="cursor: pointer;"
                                        data-titulo="<?php echo htmlspecialchars($solicitud['titulo']); ?>"
                                        data-solicitante="<?php echo htmlspecialchars($solicitud['nombre_solicitante'] ?? 'No disponible'); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($solicitud['descripcion']); ?>"
                                        data-categoria-nombre="<?php echo htmlspecialchars($solicitud['categoria_nombre'] ?? 'No disponible'); ?>"
                                        data-urgencia-nombre="<?php echo htmlspecialchars($solicitud['urgencia_nombre'] ?? 'No especificada'); ?>"
                                        data-urgencia-clase="<?php echo $mapa_colores_urgencia_badge[$solicitud['urgencia_nombre']] ?? 'bg-secondary'; ?>"
                                        data-tipo-sangre="<?php echo htmlspecialchars($solicitud['tipo_sangre'] ?? 'N/A'); ?>"
                                        data-medicamento="<?php echo htmlspecialchars($solicitud['nombre_medicamento'] ?? 'N/A'); ?>"
                                        data-dosis="<?php echo htmlspecialchars($solicitud['dosis'] ?? 'N/A'); ?>"
                                        data-presentacion="<?php echo htmlspecialchars($solicitud['presentacion'] ?? 'N/A'); ?>"
                                        data-dispositivo="<?php echo htmlspecialchars($solicitud['nombre_dispositivo'] ?? 'N/A'); ?>"
                                        data-especificaciones="<?php echo htmlspecialchars($solicitud['especificaciones'] ?? 'N/A'); ?>"
                                        data-cantidad="<?php echo htmlspecialchars($solicitud['cantidad_requerida'] ?? 'N/A'); ?>">
                                        <td><i class="fas <?php echo $mapa_categorias[$solicitud['categoria_id']]['icono'] ?? 'fa-heart'; ?> <?php echo $mapa_categorias[$solicitud['categoria_id']]['color'] ?? 'text-muted'; ?> me-2"></i><?php echo htmlspecialchars($solicitud['categoria_nombre'] ?? 'No disponible'); ?></td>
                                        <td><?php echo htmlspecialchars($solicitud['nombre_solicitante'] ?? 'No disponible'); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($solicitud['fecha_creacion'])); ?></td>
                                        <td class="text-center" onclick="event.stopPropagation();">
                                            <form action="auth/gestionar_aviso.php" method="POST" class="d-inline">
                                                <input type="hidden" name="aviso_id" value="<?php echo $solicitud['aviso_id']; ?>">
                                                <button type="button" class="btn btn-sm btn-outline-primary me-2" title="Editar Urgencia" data-bs-toggle="modal" data-bs-target="#editarUrgenciaModal" data-aviso-id="<?php echo $solicitud['aviso_id']; ?>" data-urgencia-actual="<?php echo $solicitud['urgencia_id']; ?>"><i class="fas fa-pencil-alt"></i> Editar</button>
                                                <button type="submit" name="accion" value="aprobar" class="btn btn-sm btn-outline-success me-2" title="Aprobar"><i class="fas fa-check"></i> Aprobar</button>
                                                <button type="submit" name="accion" value="rechazar" class="btn btn-sm btn-outline-danger" title="Rechazar"><i class="fas fa-times"></i> Rechazar</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div></div>
                </div>
                
                <div class="tab-pane fade" id="active" role="tabpanel">
                     <div class="card border-0 shadow-sm"><div class="card-body p-4">
                        <h5 class="card-title mb-4">Solicitudes Activas</h5>
                         <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light"><tr><th>Tipo</th><th>Solicitante</th><th>Progreso</th><th class="text-center">Acciones</th></tr></thead>
                                <tbody>
                                    <?php if (empty($solicitudes_activas)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">No hay solicitudes activas.</td></tr>
                                    <?php else: foreach($solicitudes_activas as $solicitud): ?>
                                        <?php $porcentaje = ($solicitud['cantidad_requerida'] > 0) ? round(($solicitud['cantidad_recolectada'] / $solicitud['cantidad_requerida']) * 100) : 0; ?>
                                        <tr data-bs-toggle="modal" data-bs-target="#detalleSolicitudModal" style="cursor: pointer;"
                                            data-titulo="<?php echo htmlspecialchars($solicitud['titulo']); ?>"
                                            data-solicitante="<?php echo htmlspecialchars($solicitud['nombre_solicitante'] ?? 'No disponible'); ?>"
                                            data-descripcion="<?php echo htmlspecialchars($solicitud['descripcion']); ?>"
                                            data-categoria-nombre="<?php echo htmlspecialchars($solicitud['categoria_nombre'] ?? 'No disponible'); ?>"
                                            data-urgencia-nombre="<?php echo htmlspecialchars($solicitud['urgencia_nombre'] ?? 'No especificada'); ?>"
                                            data-urgencia-clase="<?php echo $mapa_colores_urgencia_badge[$solicitud['urgencia_nombre']] ?? 'bg-secondary'; ?>"
                                            data-tipo-sangre="<?php echo htmlspecialchars($solicitud['tipo_sangre'] ?? 'N/A'); ?>"
                                            data-medicamento="<?php echo htmlspecialchars($solicitud['nombre_medicamento'] ?? 'N/A'); ?>"
                                            data-dosis="<?php echo htmlspecialchars($solicitud['dosis'] ?? 'N/A'); ?>"
                                            data-presentacion="<?php echo htmlspecialchars($solicitud['presentacion'] ?? 'N/A'); ?>"
                                            data-dispositivo="<?php echo htmlspecialchars($solicitud['nombre_dispositivo'] ?? 'N/A'); ?>"
                                            data-especificaciones="<?php echo htmlspecialchars($solicitud['especificaciones'] ?? 'N/A'); ?>"
                                            data-cantidad="<?php echo htmlspecialchars($solicitud['cantidad_requerida'] ?? 'N/A'); ?>">
                                            <td><i class="fas <?php echo $mapa_categorias[$solicitud['categoria_id']]['icono'] ?? 'fa-heart'; ?> <?php echo $mapa_categorias[$solicitud['categoria_id']]['color'] ?? 'text-muted'; ?> me-2"></i><?php echo htmlspecialchars($solicitud['categoria_nombre'] ?? 'No disponible'); ?></td>
                                            <td><?php echo htmlspecialchars($solicitud['nombre_solicitante'] ?? 'No disponible'); ?></td>
                                            <td>
                                                <div class="progress" style="height: 20px;"><div class="progress-bar" role="progressbar" style="width: <?php echo $porcentaje; ?>%;" aria-valuenow="<?php echo $porcentaje; ?>"><?php echo $porcentaje; ?>%</div></div>
                                                <small class="text-muted"><?php echo number_format($solicitud['cantidad_recolectada']); ?> / <?php echo number_format($solicitud['cantidad_requerida']); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <a href="organizacion_donantes.php?aviso_id=<?php echo $solicitud['aviso_id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Donantes" onclick="event.stopPropagation();"><i class="fas fa-users"></i> Ver Donantes</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div></div>
                </div>

                <div class="tab-pane fade" id="history" role="tabpanel">
                     <div class="card border-0 shadow-sm"><div class="card-body p-4">
                        <h5 class="card-title mb-4">Historial de Solicitudes</h5>
                         <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light"><tr><th>Tipo</th><th>Solicitante</th><th>Fecha de Cierre</th><th class="text-center">Estado Final</th></tr></thead>
                                <tbody>
                                    <?php if (empty($solicitudes_historial)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">No hay solicitudes en el historial.</td></tr>
                                    <?php else: foreach($solicitudes_historial as $solicitud): ?>
                                    <tr data-bs-toggle="modal" data-bs-target="#detalleSolicitudModal" style="cursor: pointer;"
                                        data-titulo="<?php echo htmlspecialchars($solicitud['titulo']); ?>"
                                        data-solicitante="<?php echo htmlspecialchars($solicitud['nombre_solicitante'] ?? 'No disponible'); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($solicitud['descripcion']); ?>"
                                        data-categoria-nombre="<?php echo htmlspecialchars($solicitud['categoria_nombre'] ?? 'No disponible'); ?>"
                                        data-urgencia-nombre="<?php echo htmlspecialchars($solicitud['urgencia_nombre'] ?? 'No especificada'); ?>"
                                        data-urgencia-clase="<?php echo $mapa_colores_urgencia_badge[$solicitud['urgencia_nombre']] ?? 'bg-secondary'; ?>"
                                        data-tipo-sangre="<?php echo htmlspecialchars($solicitud['tipo_sangre'] ?? 'N/A'); ?>"
                                        data-medicamento="<?php echo htmlspecialchars($solicitud['nombre_medicamento'] ?? 'N/A'); ?>"
                                        data-dosis="<?php echo htmlspecialchars($solicitud['dosis'] ?? 'N/A'); ?>"
                                        data-presentacion="<?php echo htmlspecialchars($solicitud['presentacion'] ?? 'N/A'); ?>"
                                        data-dispositivo="<?php echo htmlspecialchars($solicitud['nombre_dispositivo'] ?? 'N/A'); ?>"
                                        data-especificaciones="<?php echo htmlspecialchars($solicitud['especificaciones'] ?? 'N/A'); ?>"
                                        data-cantidad="<?php echo htmlspecialchars($solicitud['cantidad_requerida'] ?? 'N/A'); ?>">
                                        <td><i class="fas <?php echo $mapa_categorias[$solicitud['categoria_id']]['icono'] ?? 'fa-heart'; ?> <?php echo $mapa_categorias[$solicitud['categoria_id']]['color'] ?? 'text-muted'; ?> me-2"></i><?php echo htmlspecialchars($solicitud['categoria_nombre'] ?? 'No disponible'); ?></td>
                                        <td><?php echo htmlspecialchars($solicitud['nombre_solicitante'] ?? 'No disponible'); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($solicitud['fecha_creacion'])); ?></td>
                                        <td class="text-center">
                                            <?php if($solicitud['estatus_id'] == 3): ?><span class="badge bg-success">Completada</span>
                                            <?php elseif($solicitud['estatus_id'] == 4): ?><span class="badge bg-danger">Rechazada</span>
                                            <?php endif; ?>
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
    
    <div class="modal fade" id="detalleSolicitudModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title" id="detalle-titulo"></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p><strong>Solicitante:</strong> <span id="detalle-solicitante"></span></p>
                    <p><strong>Nivel de Urgencia:</strong> <span id="detalle-urgencia"></span></p>
                    <p><strong>Descripción:</strong></p>
                    <p class="text-muted" id="detalle-descripcion"></p>
                    <hr>
                    <div id="detalle-especifico"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editarUrgenciaModal" tabindex="-1" aria-labelledby="editarUrgenciaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarUrgenciaModalLabel">Editar Nivel de Urgencia</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="auth/gestionar_aviso.php" method="POST">
            <div class="modal-body">
                <input type="hidden" name="accion" value="editar_urgencia">
                <input type="hidden" name="aviso_id" id="edit_aviso_id">
                
                <div class="mb-3">
                    <label for="edit_urgencia_id" class="form-label">Nivel de Urgencia</label>
                    <select id="edit_urgencia_id" name="urgencia_id" class="form-select" required>
                        <option value="" disabled>Selecciona un nivel...</option>
                        <?php foreach ($urgencias as $urgencia): ?>
                            <option value="<?php echo $urgencia['id']; ?>"><?php echo htmlspecialchars($urgencia['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
        
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    <?php require_once 'templates/scripts.php'; ?>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const detalleModal = document.getElementById('detalleSolicitudModal');
        detalleModal.addEventListener('show.bs.modal', function (event) {
            const row = event.relatedTarget.closest('tr');
            if (!row) { return; }
            
            const urgenciaNombre = row.dataset.urgenciaNombre;
            const urgenciaClase = row.dataset.urgenciaClase;

            document.getElementById('detalle-titulo').textContent = row.dataset.titulo;
            document.getElementById('detalle-solicitante').textContent = row.dataset.solicitante;
            document.getElementById('detalle-descripcion').textContent = row.dataset.descripcion;
            
            const urgenciaSpan = document.getElementById('detalle-urgencia');
            urgenciaSpan.innerHTML = `<span class="badge ${urgenciaClase}">${urgenciaNombre}</span>`;

            let detallesHtml = '<h5>Detalles Específicos</h5>';
            const categoria = row.dataset.categoriaNombre;

            if (categoria === 'Sangre') {
                detallesHtml += `<p><strong>Tipo de Sangre Requerido:</strong> ${row.dataset.tipoSangre}</p>`;
                detallesHtml += `<p><strong>Unidades Requeridas:</strong> ${row.dataset.cantidad}</p>`;
            } else if (categoria === 'Medicamentos') {
                detallesHtml += `<p><strong>Medicamento:</strong> ${row.dataset.medicamento}</p>`;
                detallesHtml += `<p><strong>Dosis:</strong> ${row.dataset.dosis}</p>`;
                detallesHtml += `<p><strong>Presentación:</strong> ${row.dataset.presentacion}</p>`;
                detallesHtml += `<p><strong>Cantidad Requerida:</strong> ${row.dataset.cantidad}</p>`;
            } else if (categoria === 'Dispositivos Medicos') {
                detallesHtml += `<p><strong>Dispositivo:</strong> ${row.dataset.dispositivo}</p>`;
                detallesHtml += `<p><strong>Especificaciones:</strong> ${row.dataset.especificaciones}</p>`;
                detallesHtml += `<p><strong>Cantidad Requerida:</strong> ${row.dataset.cantidad}</p>`;
            }
            document.getElementById('detalle-especifico').innerHTML = detallesHtml;
        });

        const editarUrgenciaModal = document.getElementById('editarUrgenciaModal');
        if (editarUrgenciaModal) {
            editarUrgenciaModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const avisoId = button.dataset.avisoId;
                const urgenciaActualId = button.dataset.urgenciaActual;

                const modal = event.target;
                modal.querySelector('#edit_aviso_id').value = avisoId;
                modal.querySelector('#edit_urgencia_id').value = urgenciaActualId;
            });
        }
    });
    </script>
</body>
</html>