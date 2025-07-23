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

// Se busca a qué organización pertenece el usuario
$sql_org = "SELECT organizacion_id FROM usuarios_x_organizaciones WHERE usuario_id = ?";
$stmt_org = $conexion->prepare($sql_org);
$stmt_org->bind_param("i", $usuario_id);
$stmt_org->execute();
$resultado_org = $stmt_org->get_result();
if ($resultado_org->num_rows === 0) {
    header('Location: persona_dashboard.php'); // Si no pertenece a una org, no puede estar aquí
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
                a.id AS aviso_id, a.titulo, a.descripcion, a.fecha_creacion, a.estatus_id,
                a.categoria_id, cat.nombre as categoria_nombre,
                
                /* Se une con la tabla 'donatarios' para obtener el nombre */
                CONCAT(dnt.nombre, ' ', dnt.apellido_paterno) AS nombre_solicitante,
                
                COALESCE(ss.unidades_requeridas, sm.cantidad_requerida, sd.cantidad_requerida) AS cantidad_requerida,
                (SELECT COALESCE(SUM(cantidad), 0) FROM donaciones WHERE aviso_id = a.id AND estatus_id = 3) AS cantidad_recolectada,
                ts.tipo AS tipo_sangre,
                sm.nombre_medicamento, sm.dosis, sm.presentacion,
                sd.nombre_dispositivo, sd.especificaciones
            FROM avisos a

            /* ESTA ES LA LÍNEA CLAVE CORREGIDA */
            LEFT JOIN donatarios dnt ON a.donatario_id = dnt.id
            
            LEFT JOIN categorias_donacion cat ON a.categoria_id = cat.id
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

// Se obtienen las solicitudes para cada pestaña
$solicitudes_pendientes = obtener_avisos_por_estatus($conexion, $organizacion_id, 1);
$solicitudes_activas = obtener_avisos_por_estatus($conexion, $organizacion_id, 2);
$solicitudes_historial = obtener_avisos_por_estatus($conexion, $organizacion_id, [3, 4]);

$mapa_categorias = [
    1 => ['icono' => 'fa-tint', 'color' => 'text-danger'],
    2 => ['icono' => 'fa-pills', 'color' => 'text-primary'],
    3 => ['icono' => 'fa-wheelchair', 'color' => 'text-warning'],
];

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Gestionar Solicitudes</title>
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
                <h1 class='display-5 mb-0'>Gestionar Solicitudes</h1>
                <p class="fs-5 text-muted mb-0">Valida, aprueba y da seguimiento a las solicitudes de ayuda.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <ul class="nav nav-pills nav-fill mb-4" id="requestsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active position-relative" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                        Por Validar
                        <?php if (count($solicitudes_pendientes) > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo count($solicitudes_pendientes); ?></span>
                        <?php endif; ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">Activas</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Historial</button></li>
            </ul>

            <div class="tab-content" id="requestsTabsContent">
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    <div class="card border-0 shadow-sm"><div class="card-body p-4">
                        <h5 class="card-title mb-4">Solicitudes Pendientes de Validación</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light"><tr><th>Tipo</th><th>Solicitante</th><th>Fecha de Solicitud</th><th class="text-center">Acciones</th></tr></thead>
                                <tbody>
                                    <?php if (empty($solicitudes_pendientes)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">No hay solicitudes pendientes de validación.</td></tr>
                                    <?php else: foreach ($solicitudes_pendientes as $solicitud): ?>
                                    <tr data-bs-toggle="modal" data-bs-target="#detalleSolicitudModal" style="cursor: pointer;"
                                        data-titulo="<?php echo htmlspecialchars($solicitud['titulo']); ?>"
                                        data-solicitante="<?php echo htmlspecialchars($solicitud['nombre_solicitante']); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($solicitud['descripcion']); ?>"
                                        data-categoria-nombre="<?php echo htmlspecialchars($solicitud['categoria_nombre']); ?>"
                                        data-tipo-sangre="<?php echo htmlspecialchars($solicitud['tipo_sangre'] ?? 'N/A'); ?>"
                                        data-medicamento="<?php echo htmlspecialchars($solicitud['nombre_medicamento'] ?? 'N/A'); ?>"
                                        data-dosis="<?php echo htmlspecialchars($solicitud['dosis'] ?? 'N/A'); ?>"
                                        data-presentacion="<?php echo htmlspecialchars($solicitud['presentacion'] ?? 'N/A'); ?>"
                                        data-dispositivo="<?php echo htmlspecialchars($solicitud['nombre_dispositivo'] ?? 'N/A'); ?>"
                                        data-especificaciones="<?php echo htmlspecialchars($solicitud['especificaciones'] ?? 'N/A'); ?>"
                                        data-cantidad="<?php echo htmlspecialchars($solicitud['cantidad_requerida'] ?? 'N/A'); ?>">
                                        <td><i class="fas <?php echo $mapa_categorias[$solicitud['categoria_id']]['icono']; ?> <?php echo $mapa_categorias[$solicitud['categoria_id']]['color']; ?> me-2"></i><?php echo htmlspecialchars($solicitud['categoria_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($solicitud['nombre_solicitante']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($solicitud['fecha_creacion'])); ?></td>
                                        <td class="text-center" onclick="event.stopPropagation();">
                                            <form action="auth/gestionar_aviso.php" method="POST" class="d-inline">
                                                <input type="hidden" name="aviso_id" value="<?php echo $solicitud['aviso_id']; ?>">
                                                <button type="button" class="btn btn-sm btn-info me-1" title="Ver Detalles" data-bs-toggle="modal" data-bs-target="#detalleSolicitudModal" ><i class="fas fa-eye"></i></button>
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
                                        <tr>
                                            <td><i class="fas <?php echo $mapa_categorias[$solicitud['categoria_id']]['icono']; ?> <?php echo $mapa_categorias[$solicitud['categoria_id']]['color']; ?> me-2"></i><?php echo htmlspecialchars($solicitud['categoria_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($solicitud['nombre_solicitante']); ?></td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentaje; ?>%;" aria-valuenow="<?php echo $porcentaje; ?>"><?php echo $porcentaje; ?>%</div>
                                                </div>
                                                <small class="text-muted"><?php echo number_format($solicitud['cantidad_recolectada']); ?> / <?php echo number_format($solicitud['cantidad_requerida']); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <a href="organizacion_donantes.php?aviso_id=<?php echo $solicitud['aviso_id']; ?>" class="btn btn-sm btn-primary" title="Ver Donantes"><i class="fas fa-users"></i></a>
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
                                    <tr>
                                        <td><i class="fas <?php echo $mapa_categorias[$solicitud['categoria_id']]['icono']; ?> <?php echo $mapa_categorias[$solicitud['categoria_id']]['color']; ?> me-2"></i><?php echo htmlspecialchars($solicitud['categoria_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($solicitud['nombre_solicitante']); ?></td>
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
                    <p><strong>Descripción:</strong></p>
                    <p class="text-muted" id="detalle-descripcion"></p>
                    <hr>
                    <div id="detalle-especifico"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></div>
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
            document.getElementById('detalle-titulo').textContent = row.dataset.titulo;
            document.getElementById('detalle-solicitante').textContent = row.dataset.solicitante;
            document.getElementById('detalle-descripcion').textContent = row.dataset.descripcion;

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
            } else if (categoria === 'Dispositivos Medicos') { // Ajusta este nombre si es diferente en tu BD
                detallesHtml += `<p><strong>Dispositivo:</strong> ${row.dataset.dispositivo}</p>`;
                detallesHtml += `<p><strong>Especificaciones:</strong> ${row.dataset.especificaciones}</p>`;
                detallesHtml += `<p><strong>Cantidad Requerida:</strong> ${row.dataset.cantidad}</p>`;
            }
            document.getElementById('detalle-especifico').innerHTML = detallesHtml;
        });
    });
    </script>
</body>
</html>