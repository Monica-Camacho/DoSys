<?php
// 1. INICIALIZACIÓN Y SEGURIDAD
require_once 'config.php';
require_once 'conexion_local.php'; // Asegúrate que tu variable de conexión es $conexion
session_start();

// Verificación de Seguridad
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

// Obtener el ID del usuario de la sesión
$usuario_id = $_SESSION['id'];
$organizacion = []; // Variable para los datos de la organización

// 4. --- CONSULTA ADAPTADA PARA ORGANIZACIONES ---
$sql = "SELECT
            -- Datos de la organización
            op.id AS organizacion_id,
            op.nombre_organizacion,
            op.cluni, 
            op.mision, 
            
            -- Dato clave para permisos
            r.nombre AS rol_usuario,
            
            -- Dirección (Info vital)
            d.calle, d.numero_exterior, d.numero_interior, d.colonia, 
            d.codigo_postal, d.municipio, d.estado,
            d.latitud, d.longitud,
            
            -- Datos del representante legal (se mantiene)
            rep.nombre AS representante_nombre, rep.apellido_paterno AS representante_ap,
            rep.apellido_materno AS representante_am, rep.email AS representante_email,
            rep.telefono AS representante_telefono,
            
            -- Rutas a los documentos
            (SELECT ruta_archivo FROM documentos WHERE id = op.logo_documento_id) AS logo_url,
            (SELECT ruta_archivo FROM documentos WHERE propietario_id = u.id AND tipo_documento_id = 5 ORDER BY fecha_carga DESC LIMIT 1) AS documento_url 

        FROM usuarios u
        JOIN usuarios_x_organizaciones uxo ON u.id = uxo.usuario_id 
        JOIN organizaciones_perfil op ON uxo.organizacion_id = op.id 
        JOIN roles r ON u.rol_id = r.id
        LEFT JOIN direcciones d ON op.direccion_id = d.id 
        LEFT JOIN representantes rep ON op.representante_id = rep.id
        
        WHERE u.id = ?";

if ($stmt = $conexion->prepare($sql)) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $organizacion = $resultado->fetch_assoc();
    } else {
        die("Error: No se encontró un perfil de organización para el usuario con ID: " . htmlspecialchars($usuario_id));
    }
    $stmt->close();
} else {
    die("Error al preparar la consulta: " . $conexion->error);
}

// --- INICIO CÓDIGO AÑADIDO PARA OBTENER CATEGORÍAS ---
// Obtener todas las categorías disponibles para mostrarlas como opciones
$todas_las_categorias = $conexion->query("SELECT id, nombre FROM categorias_donacion ORDER BY id")->fetch_all(MYSQLI_ASSOC);

// Obtener los IDs de las categorías que la organización YA tiene seleccionadas
$categorias_actuales_ids = [];
if (isset($organizacion['organizacion_id'])) {
    $stmt_cats = $conexion->prepare("SELECT categoria_id FROM organizaciones_x_categorias WHERE organizacion_id = ?");
    $stmt_cats->bind_param("i", $organizacion['organizacion_id']);
    $stmt_cats->execute();
    $resultado_cats = $stmt_cats->get_result();
    while ($fila = $resultado_cats->fetch_assoc()) {
        $categorias_actuales_ids[] = $fila['categoria_id'];
    }
    $stmt_cats->close();
}
// --- FIN CÓDIGO AÑADIDO PARA OBTENER CATEGORÍAS ---

// 5. LÓGICA DE PERMISOS
$rol_usuario = $organizacion['rol_usuario'] ?? '';
$puede_editar = ($rol_usuario === 'Administrador');

// 6. Cerramos la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>DoSys - Perfil de Organizacion</title>
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
                <h1 class='display-5 mb-0'>Perfil de la Organización</h1>
                <p class="fs-5 text-muted mb-0">Administra la información pública y de contacto de tu organización.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Profile Content Start -->
    <div class="container-fluid py-5">
        <div class="container">

        <div class="row g-5">
            <div class="col-lg-4">
                <div class="d-flex flex-column text-center align-items-center bg-white p-4 rounded shadow-sm">
                    
                    <img id="profileImage" class="img-fluid rounded-circle mb-3" 
                        src="<?php echo !empty($organizacion['logo_url']) ? BASE_URL . htmlspecialchars($organizacion['logo_url']) : BASE_URL . 'img/elements/sin_logo.jpg'; ?>" 
                        alt="Logo de la Organización" style="width: 150px; height: 150px; object-fit: cover;">
                    
                    <h4 class="mb-1"><?php echo htmlspecialchars($organizacion['nombre_organizacion'] ?? 'Nombre de la Organización'); ?></h4>
                    
                    <button type="button" id="changeLogoBtn" class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#changeLogoModal" style="display: none;">
                        <i class="fas fa-camera me-2"></i>Cambiar Logo
                    </button>
                </div>
            </div>


            <div class="col-lg-8">
                <form id="profileForm" action="auth/update_organizacion_process.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="organizacion_id" value="<?php echo htmlspecialchars($organizacion['organizacion_id']); ?>">
                    
                    <ul class="nav nav-tabs nav-pills nav-fill mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="org-tab" data-bs-toggle="tab" data-bs-target="#org" type="button" role="tab" aria-controls="org" aria-selected="true">
                                <i class="fas fa-sitemap me-2"></i>Datos de la Organización
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="direccion-tab" data-bs-toggle="tab" data-bs-target="#direccion" type="button" role="tab" aria-controls="direccion" aria-selected="false">
                                <i class="fas fa-map-marked-alt me-2"></i>Ubicación
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="representative-tab" data-bs-toggle="tab" data-bs-target="#representative" type="button" role="tab" aria-controls="representative" aria-selected="false">
                                <i class="fas fa-user-tie me-2"></i>Representante
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabsContent">
                        <!-- Pestaña: Datos de la organización -->
                        <div class="tab-pane fade show active" id="org" role="tabpanel" aria-labelledby="org-tab">
                            <div class="card border-0 shadow-sm"><div class="card-body p-4">
                                <h5 class="card-title mb-4">Datos de la Organización</h5>
                                <div class="row g-3">
                                    <!-- CAMBIO: Campo adaptado para 'nombre_organizacion' -->
                                    <div class="col-md-6"><label class="form-label">Nombre de la Organización</label><input type="text" name="nombre_organizacion" class="form-control" value="<?php echo htmlspecialchars($organizacion['nombre_organizacion'] ?? ''); ?>" disabled></div>
                                    <!-- CAMBIO: Campo adaptado para 'cluni' -->
                                    <div class="col-md-6"><label class="form-label">CLUNI</label><input type="text" name="cluni" class="form-control" value="<?php echo htmlspecialchars($organizacion['cluni'] ?? ''); ?>" disabled></div>
                                    <!-- CAMBIO: Campo adaptado para 'mision' -->
                                    <div class="col-12"><label class="form-label">Misión de la Organización</label><textarea name="mision" class="form-control" rows="4" disabled><?php echo htmlspecialchars($organizacion['mision'] ?? ''); ?></textarea></div>
                                </div>
                                
                                <hr class="my-4">
                                <h6 class="mb-3">Documento de Validación</h6>
                                <div class="d-flex justify-content-between align-items-center" id="document-section">
                                    <div>
                                        <i class="fas fa-file-alt fa-2x text-muted me-2"></i>
                                        <!-- CAMBIO: Texto del documento -->
                                        <span class="fw-bold">Documento Constitutivo (CLUNI, etc.)</span>
                                    </div>
                                    <div>
                                        <?php if (!empty($organizacion['documento_url'])): ?>
                                            <a href="<?php echo BASE_URL . htmlspecialchars($organizacion['documento_url']); ?>" target="_blank" class="btn btn-success btn-sm">Ver Documento</a>
                                            <button type="button" id="replaceDocBtn" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal" style="display: none;">Reemplazar</button>
                                        <?php else: ?>
                                            <button type="button" id="uploadDocBtn" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal" style="display: none;">Subir Documento</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                    <hr class="my-4">
                                    <h6 class="mb-3">Categorías de Donación Atendidas</h6>
                                    <div class="row">
                                        <?php foreach ($todas_las_categorias as $categoria): ?>
                                            <?php
                                                $is_checked = in_array($categoria['id'], $categorias_actuales_ids);
                                            ?>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="categorias[]" 
                                                           value="<?php echo $categoria['id']; ?>" 
                                                           id="cat<?php echo $categoria['id']; ?>" 
                                                           <?php if ($is_checked) echo 'checked'; ?> 
                                                           disabled>
                                                    <label class="form-check-label" for="cat<?php echo $categoria['id']; ?>">
                                                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                            </div></div>
                        </div>

                        <!-- Pestaña: Dirección (Solo cambia la variable a $organizacion) -->
                        <div class="tab-pane fade" id="direccion" role="tabpanel" aria-labelledby="direccion-tab">
                            <div class="card border-0 shadow-sm"><div class="card-body p-4">
                                <h5 class="card-title mb-4">Dirección Registrada</h5>
                                <div class="row g-3">
                                    <div class="col-12"><label class="form-label">Calle</label><input type="text" name="calle" class="form-control" value="<?php echo htmlspecialchars($organizacion['calle'] ?? ''); ?>" disabled></div>
                                    <div class="col-md-6"><label class="form-label">Número Exterior</label><input type="text" name="numero_exterior" class="form-control" value="<?php echo htmlspecialchars($organizacion['numero_exterior'] ?? ''); ?>" disabled></div>
                                    <div class="col-md-6"><label class="form-label">Número Interior</label><input type="text" name="numero_interior" class="form-control" value="<?php echo htmlspecialchars($organizacion['numero_interior'] ?? ''); ?>" disabled></div>
                                    <div class="col-md-6"><label class="form-label">Colonia</label><input type="text" name="colonia" class="form-control" value="<?php echo htmlspecialchars($organizacion['colonia'] ?? ''); ?>" disabled></div>
                                    <div class="col-md-6"><label class="form-label">Código Postal</label><input type="text" name="codigo_postal" class="form-control" value="<?php echo htmlspecialchars($organizacion['codigo_postal'] ?? ''); ?>" disabled></div>
                                    <div class="col-md-6"><label class="form-label">Municipio</label><input type="text" name="municipio" class="form-control" value="<?php echo htmlspecialchars($organizacion['municipio'] ?? ''); ?>" disabled></div>
                                    <div class="col-md-6"><label class="form-label">Estado</label><input type="text" name="estado" class="form-control" value="<?php echo htmlspecialchars($organizacion['estado'] ?? ''); ?>" disabled></div>
                                </div>
                                <hr class="my-4">
                                <h6 class="mb-3">Ubicación en el Mapa</h6>
                                <div id="map-canvas" style="height: 300px; width: 100%; border-radius: 0.25rem;"></div>
                                <input type="hidden" id="lat-input" name="latitud" value="<?php echo htmlspecialchars($organizacion['latitud'] ?? '17.9869'); ?>">
                                <input type="hidden" id="lng-input" name="longitud" value="<?php echo htmlspecialchars($organizacion['longitud'] ?? '-92.9303'); ?>">
                            </div></div>
                        </div>

                        <!-- Pestaña: Representante (Solo cambia la variable a $organizacion) -->
                        <div class="tab-pane fade" id="representative" role="tabpanel" aria-labelledby="representative-tab">
                            <div class="card border-0 shadow-sm"><div class="card-body p-4">
                                <h5 class="card-title mb-4">Datos del Representante</h5>
                                <?php if (!empty($organizacion['representante_nombre'])): ?>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4"><label class="form-label">Nombre(s)</label><input type="text" name="rep_nombre" class="form-control" value="<?php echo htmlspecialchars($organizacion['representante_nombre']); ?>" disabled></div>
                                        <div class="col-md-4"><label class="form-label">Apellido Paterno</label><input type="text" name="rep_apellido_p" class="form-control" value="<?php echo htmlspecialchars($organizacion['representante_ap']); ?>" disabled></div>
                                        <div class="col-md-4"><label class="form-label">Apellido Materno</label><input type="text" name="rep_apellido_m" class="form-control" value="<?php echo htmlspecialchars($organizacion['representante_am']); ?>" disabled></div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6"><label class="form-label">Correo</label><input type="email" name="rep_email" class="form-control" value="<?php echo htmlspecialchars($organizacion['representante_email']); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Teléfono</label><input type="tel" name="rep_telefono" class="form-control" value="<?php echo htmlspecialchars($organizacion['representante_telefono']); ?>" disabled></div>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info" role="alert">No hay un representante asignado.</div>
                                <?php endif; ?>
                            </div></div>
                        </div>
                    </div> 
                    
                    <!-- Botones de Acción (Sin cambios en su lógica) -->
                    <div id="action-buttons" class="mt-4 text-end">
                        <?php if ($puede_editar): ?>
                            <button type="button" id="editBtn" class="btn btn-secondary">Editar Información</button>
                        <?php endif; ?>
                        <button type="submit" id="saveBtn" class="btn btn-primary" style="display:none;">Guardar Cambios</button>
                        <button type="button" id="cancelBtn" class="btn btn-light" style="display:none;">Cancelar</button>
                    </div>
                </form> 
            </div>
        </div>

        </div>
    </div>
     <!-- Profile Content End -->
 
    <!-- Ventana Modal para Subir el logo -->
    <div class="modal fade" id="changeLogoModal" tabindex="-1" aria-labelledby="changeLogoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeLogoModalLabel">Cambiar Logo de la Organización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadLogoForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="logoFile" class="form-label">Selecciona una imagen (JPG, PNG, GIF - Máx 5MB)</label>
                            <input class="form-control" type="file" id="logoFile" name="logoFile" accept="image/jpeg, image/png, image/gif">
                        </div>
                        <div id="uploadLogoMessage" class="mt-3"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="submitLogoButton">Subir Logo</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventana Modal para Subir la constancia de situacion fiscal -->
    <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadDocumentModalLabel">Documento de Validación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Sube el Documento Constitutivo o el CLUNI de tu organización (PDF, JPG, PNG - Máx 5MB).</p>
                    
                    <form id="uploadOrgDocumentForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input class="form-control" type="file" id="org_document_file" name="org_document_file" accept=".pdf,image/jpeg,image/png">
                        </div>
                        <div id="uploadDocumentMessage" class="mt-3"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="submitOrgDocumentButton">Subir Documento</button>
                </div>
            </div>
        </div>
    </div>

        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>

        <script>
            // =================================================================
            //  VARIABLES GLOBALES PARA EL MAPA
            // =================================================================
            let map;
            let marker;

            // =================================================================
            //  LÓGICA DE GOOGLE MAPS (No requiere cambios)
            // =================================================================
            function initMap() {
                const latInput = document.getElementById('lat-input');
                const lngInput = document.getElementById('lng-input');
                const initialLat = parseFloat(latInput.value) || 17.9869;
                const initialLng = parseFloat(lngInput.value) || -92.9303;
                const initialPosition = { lat: initialLat, lng: initialLng };

                map = new google.maps.Map(document.getElementById('map-canvas'), {
                    center: initialPosition,
                    zoom: 16
                });

                marker = new google.maps.Marker({
                    position: initialPosition,
                    map: map,
                    draggable: false // No se puede arrastrar por defecto
                });

                marker.addListener('dragend', () => {
                    const newPosition = marker.getPosition();
                    document.getElementById('lat-input').value = newPosition.lat();
                    document.getElementById('lng-input').value = newPosition.lng();
                });
            }

            // =================================================================
            //  LÓGICA DE LA PÁGINA (jQuery)
            // =================================================================
            $(document).ready(function() {

                // --- LÓGICA DEL BOTÓN DE EDICIÓN (No requiere cambios) ---
                $('#editBtn').on('click', function() {
                    // 1. Habilitamos campos
                    $('#profileForm').find('input, textarea').prop('disabled', false);

                    // 2. Habilitamos mapa
                    if (marker) {
                        marker.setDraggable(true);
                    }

                    // 3. Mostramos/Ocultamos botones de acción
                    $('#editBtn').hide();
                    $('#saveBtn, #cancelBtn').show();
                    
                    // 4. Mostramos botones de carga de archivos
                    $('#changeLogoBtn').show();
                    $('#replaceDocBtn, #uploadDocBtn').show();
                });

                // --- LÓGICA DEL BOTÓN CANCELAR (No requiere cambios) ---
                $('#cancelBtn').on('click', function() {
                    window.location.reload();
                });


                // --- LÓGICA PARA SUBIR LOGO (Adaptada) ---
                $('#submitLogoButton').on('click', function() {
                    var formData = new FormData($('#uploadLogoForm')[0]);
                    var messageDiv = $('#uploadLogoMessage');
                    messageDiv.html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');

                    $.ajax({
                        // CAMBIO: URL apunta al script de la organización
                        url: '<?php echo BASE_URL; ?>auth/upload_org_logo_process.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                messageDiv.html('<div class="alert alert-success">' + response.message + '</div>');
                                $('#profileImage').attr('src', response.new_logo_url + '?t=' + new Date().getTime());
                                setTimeout(() => {
                                    $('#changeLogoModal').modal('hide');
                                    messageDiv.html('');
                                }, 2000);
                            } else {
                                messageDiv.html('<div class="alert alert-danger">' + response.message + '</div>');
                            }
                        },
                        error: function() {
                            messageDiv.html('<div class="alert alert-danger">Error de conexión.</div>');
                        }
                    });
                });

                // --- LÓGICA PARA SUBIR DOCUMENTO (Adaptada) ---
                // CAMBIO: El selector del botón ahora es el del modal de organización
                $('#submitOrgDocumentButton').on('click', function() {
                    // CAMBIO: El selector del formulario ahora es el del modal de organización
                    var formData = new FormData($('#uploadOrgDocumentForm')[0]);
                    var messageDiv = $('#uploadDocumentMessage'); // El ID del div de mensajes es el mismo
                    messageDiv.html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');

                    $.ajax({
                        // CAMBIO: URL apunta al script de la organización
                        url: '<?php echo BASE_URL; ?>auth/upload_org_document_process.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                messageDiv.html('<div class="alert alert-success">' + response.message + '</div>');
                                setTimeout(() => {
                                    // CAMBIO: Recargamos la sección correcta, con el ID 'document-section'
                                    $('#document-section').load(location.href + ' #document-section > *');
                                    $('#uploadDocumentModal').modal('hide');
                                    messageDiv.html('');
                                }, 2000);
                            } else {
                                messageDiv.html('<div class="alert alert-danger">' + response.message + '</div>');
                            }
                        },
                        error: function() {
                            messageDiv.html('<div class="alert alert-danger">Error de conexión.</div>');
                        }
                    });
                });
            });
        </script>
          
</body>

</html>
