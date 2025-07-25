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
    header('Location: index.php');
    exit();
}

$permisos = $resultado_permisos->fetch_assoc();
$empresa_id = $permisos['empresa_id'];
$rol_admin = $permisos['rol_id'];
$stmt_permisos->close();

// Solo los administradores (rol_id = 1) de la empresa pueden gestionar beneficios
if ($rol_admin != 1) {
    $_SESSION['error_message'] = "No tienes permiso para gestionar beneficios de la empresa.";
    header('Location: empresa_dashboard.php');
    exit();
}

// Lógica para obtener los beneficios de la empresa
$beneficios_empresa = [];
$sql_beneficios = "SELECT ea.id, ea.titulo, ea.descripcion, ea.fecha_expiracion, ea.activo,
                          td.nombre AS tipo_apoyo_nombre,
                          d.ruta_archivo AS imagen_beneficio_ruta,
                          ea.tipo_apoyo_id
                   FROM empresas_apoyos ea
                   JOIN tipos_apoyo td ON ea.tipo_apoyo_id = td.id
                   LEFT JOIN documentos d ON ea.imagen_documento_id = d.id
                   WHERE ea.empresa_id = ?
                   ORDER BY ea.activo DESC, ea.id DESC";
$stmt_beneficios = $conexion->prepare($sql_beneficios);
$stmt_beneficios->bind_param("i", $empresa_id);
$stmt_beneficios->execute();
$resultado_beneficios = $stmt_beneficios->get_result();
while ($row = $resultado_beneficios->fetch_assoc()) {
    $beneficios_empresa[] = $row;
}
$stmt_beneficios->close();

// Obtener tipos de apoyo para el formulario de creación/edición
$tipos_apoyo = $conexion->query("SELECT id, nombre FROM tipos_apoyo ORDER BY nombre ASC")->fetch_all(MYSQLI_ASSOC);

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Gestión de Beneficios</title>
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
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div></div>
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Gestión de Beneficios</h1>
                <p class="fs-5 text-muted mb-0">Administra los beneficios que tu empresa ofrece a los donantes.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>

            <div class="d-flex justify-content-end mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBenefitModal"><i class="fas fa-plus-circle me-2"></i>Añadir Nuevo Beneficio</button>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Título</th>
                                    <th scope="col">Tipo de Apoyo</th>
                                    <th scope="col">Expira</th>
                                    <th scope="col">Activo</th>
                                    <th scope="col">Imagen</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($beneficios_empresa)): ?>
                                    <?php foreach ($beneficios_empresa as $beneficio): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($beneficio['titulo']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($beneficio['tipo_apoyo_nombre']); ?></td>
                                            <td><?php echo $beneficio['fecha_expiracion'] ? date("d/m/Y", strtotime($beneficio['fecha_expiracion'])) : 'N/A'; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $beneficio['activo'] ? 'success' : 'secondary'; ?>">
                                                    <?php echo $beneficio['activo'] ? 'Sí' : 'No'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($beneficio['imagen_beneficio_ruta'])): ?>
                                                    <img src="<?php echo BASE_URL . htmlspecialchars($beneficio['imagen_beneficio_ruta']); ?>" alt="Imagen de Beneficio" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-2 edit-benefit-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editBenefitModal"
                                                        data-id="<?php echo $beneficio['id']; ?>"
                                                        data-titulo="<?php echo htmlspecialchars($beneficio['titulo']); ?>"
                                                        data-descripcion="<?php echo htmlspecialchars($beneficio['descripcion']); ?>"
                                                        data-fecha-expiracion="<?php echo htmlspecialchars($beneficio['fecha_expiracion']); ?>"
                                                        data-activo="<?php echo htmlspecialchars($beneficio['activo']); ?>"
                                                        data-tipo-apoyo-id="<?php echo htmlspecialchars($beneficio['tipo_apoyo_id']); ?>"
                                                        data-imagen-ruta="<?php echo !empty($beneficio['imagen_beneficio_ruta']) ? BASE_URL . htmlspecialchars($beneficio['imagen_beneficio_ruta']) : ''; ?>">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-benefit-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#confirmDeleteModal" 
                                                        data-id="<?php echo $beneficio['id']; ?>" 
                                                        data-titulo="<?php echo htmlspecialchars($beneficio['titulo']); ?>">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay beneficios registrados para tu empresa.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    <?php require_once 'templates/scripts.php'; ?>

    <div class="modal fade" id="addBenefitModal" tabindex="-1" aria-labelledby="addBenefitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBenefitModalLabel">Añadir Nuevo Beneficio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="auth/gestionar_beneficios_empresa.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body row g-3">
                        <input type="hidden" name="action" value="add">
                        <div class="col-12"><label for="addTitulo" class="form-label">Título del Beneficio</label><input type="text" class="form-control" id="addTitulo" name="titulo" required></div>
                        <div class="col-12"><label for="addDescripcion" class="form-label">Descripción</label><textarea class="form-control" id="addDescripcion" name="descripcion" rows="3" required></textarea></div>
                        <div class="col-md-6"><label for="addTipoApoyo" class="form-label">Tipo de Apoyo</label><select class="form-select" id="addTipoApoyo" name="tipo_apoyo_id" required><option value="" selected disabled>Selecciona un tipo...</option><?php foreach ($tipos_apoyo as $tipo): ?><option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option><?php endforeach; ?></select></div>
                        <div class="col-md-6"><label for="addFechaExpiracion" class="form-label">Fecha de Expiración (opcional)</label><input type="date" class="form-control" id="addFechaExpiracion" name="fecha_expiracion"></div>
                        <div class="col-12"><label for="addImagenBeneficio" class="form-label">Imagen de Referencia (JPG, PNG)</label><input type="file" class="form-control" id="addImagenBeneficio" name="imagen_beneficio" accept="image/jpeg, image/png"><small class="text-muted">Max. 2MB.</small></div>
                        <div class="col-12 form-check"><input class="form-check-input" type="checkbox" id="addActivo" name="activo" value="1" checked><label class="form-check-label" for="addActivo">Beneficio Activo</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar Beneficio</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editBenefitModal" tabindex="-1" aria-labelledby="editBenefitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBenefitModalLabel">Editar Beneficio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="auth/gestionar_beneficios_empresa.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body row g-3">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="benefit_id" id="editBenefitId">
                        <div class="col-12"><label for="editTitulo" class="form-label">Título del Beneficio</label><input type="text" class="form-control" id="editTitulo" name="titulo" required></div>
                        <div class="col-12"><label for="editDescripcion" class="form-label">Descripción</label><textarea class="form-control" id="editDescripcion" name="descripcion" rows="3" required></textarea></div>
                        <div class="col-md-6"><label for="editTipoApoyo" class="form-label">Tipo de Apoyo</label><select class="form-select" id="editTipoApoyo" name="tipo_apoyo_id" required><option value="" disabled>Selecciona un tipo...</option><?php foreach ($tipos_apoyo as $tipo): ?><option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option><?php endforeach; ?></select></div>
                        <div class="col-md-6"><label for="editFechaExpiracion" class="form-label">Fecha de Expiración (opcional)</label><input type="date" class="form-control" id="editFechaExpiracion" name="fecha_expiracion"></div>
                        <div class="col-12"><label for="editImagenBeneficio" class="form-label">Cambiar Imagen (JPG, PNG)</label><input type="file" class="form-control" id="editImagenBeneficio" name="imagen_beneficio" accept="image/jpeg, image/png"><small class="text-muted">Dejar en blanco para mantener la imagen actual. Max. 2MB.</small><div id="currentImagePreview" class="mt-2" style="display: none;"><p class="mb-1">Imagen actual:</p><img src="" alt="Imagen Actual" style="max-width: 150px; height: auto; border-radius: 5px;"></div></div>
                        <div class="col-12 form-check"><input class="form-check-input" type="checkbox" id="editActivo" name="activo" value="1"><label class="form-check-label" for="editActivo">Beneficio Activo</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar Cambios</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">¿Estás seguro de que deseas eliminar el beneficio "<strong id="benefitTituloToDelete"></strong>"? Esta acción es irreversible.</div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><form id="deleteBenefitForm" action="auth/gestionar_beneficios_empresa.php" method="POST" style="display: inline;"><input type="hidden" name="action" value="delete"><input type="hidden" name="benefit_id" id="benefitIdToDelete"><button type="submit" class="btn btn-danger">Eliminar</button></form></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editBenefitModal = document.getElementById('editBenefitModal');
            if (editBenefitModal) {
                editBenefitModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-id');
                    var titulo = button.getAttribute('data-titulo');
                    var descripcion = button.getAttribute('data-descripcion');
                    var fechaExpiracion = button.getAttribute('data-fecha-expiracion');
                    var activo = button.getAttribute('data-activo');
                    var tipoApoyoId = button.getAttribute('data-tipo-apoyo-id');
                    var imagenRuta = button.getAttribute('data-imagen-ruta');

                    var modalIdInput = editBenefitModal.querySelector('#editBenefitId');
                    var modalTituloInput = editBenefitModal.querySelector('#editTitulo');
                    var modalDescripcionInput = editBenefitModal.querySelector('#editDescripcion');
                    var modalFechaExpiracionInput = editBenefitModal.querySelector('#editFechaExpiracion');
                    var modalActivoCheckbox = editBenefitModal.querySelector('#editActivo');
                    var modalTipoApoyoSelect = editBenefitModal.querySelector('#editTipoApoyo');
                    var currentImagePreviewDiv = editBenefitModal.querySelector('#currentImagePreview');
                    var currentImageElement = editBenefitModal.querySelector('#currentImagePreview img');

                    modalIdInput.value = id;
                    modalTituloInput.value = titulo;
                    modalDescripcionInput.value = descripcion;
                    modalFechaExpiracionInput.value = fechaExpiracion;
                    modalActivoCheckbox.checked = (activo == 1);
                    modalTipoApoyoSelect.value = tipoApoyoId;

                    if (imagenRuta) {
                        currentImageElement.src = imagenRuta;
                        currentImagePreviewDiv.style.display = 'block';
                    } else {
                        currentImagePreviewDiv.style.display = 'none';
                        currentImageElement.src = '';
                    }
                });
            }

            var confirmDeleteModal = document.getElementById('confirmDeleteModal');
            if (confirmDeleteModal) {
                confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-id');
                    var titulo = button.getAttribute('data-titulo');

                    var modalBenefitIdInput = confirmDeleteModal.querySelector('#benefitIdToDelete');
                    var modalBenefitTituloSpan = confirmDeleteModal.querySelector('#benefitTituloToDelete');

                    modalBenefitIdInput.value = id;
                    modalBenefitTituloSpan.textContent = titulo;
                });
            }
        });
    </script>
</body>
</html>