<?php
// 1. Incluimos los archivos necesarios y iniciamos la sesión
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// 2. Verificación de Seguridad
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

// 3. Obtener el ID del usuario de la sesión
$usuario_id = $_SESSION['id'];
$empresa = []; // Inicializamos el array

// 4. --- CONSULTA CORREGIDA (SOLO DIRECCIÓN COMERCIAL) ---
$sql = "SELECT
            -- Datos de la empresa
            ep.id AS empresa_id,
            ep.nombre_comercial,
            ep.razon_social,
            ep.rfc,
            ep.telefono_empresa,
            ep.descripcion,
            
            -- Datos del rol del usuario
            r.nombre AS rol_usuario,
            
            -- Dirección Comercial
            d_com.calle, d_com.numero_exterior, d_com.numero_interior, d_com.colonia, 
            d_com.codigo_postal, d_com.municipio, d_com.estado,
            d_com.latitud, d_com.longitud,
            
            -- Datos del representante legal
            rep.nombre AS representante_nombre, rep.apellido_paterno AS representante_ap,
            rep.apellido_materno AS representante_am, rep.email AS representante_email,
            rep.telefono AS representante_telefono,
            
            -- Rutas a los documentos
            (SELECT ruta_archivo FROM documentos WHERE id = ep.logo_documento_id) AS logo_url,
            (SELECT ruta_archivo FROM documentos WHERE propietario_id = u.id AND tipo_documento_id = 4 ORDER BY fecha_carga DESC LIMIT 1) AS documento_url

        FROM usuarios u
        JOIN usuarios_x_empresas uxe ON u.id = uxe.usuario_id
        JOIN empresas_perfil ep ON uxe.empresa_id = ep.id
        JOIN roles r ON u.rol_id = r.id
        LEFT JOIN direcciones d_com ON ep.direccion_comercial_id = d_com.id
        LEFT JOIN representantes rep ON ep.representante_id = rep.id
        
        WHERE u.id = ?";

if ($stmt = $conexion->prepare($sql)) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $empresa = $resultado->fetch_assoc();
    } else {
        die("Error: No se encontró un perfil para el usuario con ID: " . htmlspecialchars($usuario_id));
    }
    $stmt->close();
} else {
    die("Error al preparar la consulta: " . $conexion->error);
}

// 5. --- LÓGICA DE PERMISOS CORRECTA Y SIMPLE ---
// Se obtiene el rol del usuario de la consulta.
$rol_usuario = $empresa['rol_usuario'] ?? '';

// La única regla: puede editar si el rol es 'Administrador'.
$puede_editar = ($rol_usuario === 'Administrador');

// Se crea la variable para habilitar o deshabilitar los campos del formulario.
$disabled_attr = !$puede_editar ? 'disabled' : '';

// 6. Cerramos la conexión a la base de datos
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Perfil de Empresa</title>
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
                <h1 class='display-5 mb-0'>Perfil de la Empresa</h1>
                <p class="fs-5 text-muted mb-0">Administra la información pública y de contacto de tu empresa.</p>
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
                            src="<?php echo !empty($empresa['logo_url']) ? BASE_URL . htmlspecialchars($empresa['logo_url']) : BASE_URL . 'img/elements/sin_logo.jpg'; ?>" 
                            alt="Logo de la Empresa" style="width: 150px; height: 150px; object-fit: cover;">
                        
                        <h4 class="mb-1"><?php echo htmlspecialchars($empresa['nombre_comercial'] ?? 'Nombre de la Empresa'); ?></h4>
                        
                        <button type="button" id="changeLogoBtn" class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#changeLogoModal" style="display: none;">
                            <i class="fas fa-camera me-2"></i>Cambiar Logo
                        </button>
                    </div>
                </div>

                <div class="col-lg-8">
                    <form id="profileForm" action="auth/update_empresa_process.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="empresa_id" value="<?php echo htmlspecialchars($empresa['empresa_id']); ?>">
                        <ul class="nav nav-tabs nav-pills nav-fill mb-4" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab" aria-controls="company" aria-selected="true">
                                    <i class="fas fa-building me-2"></i>Datos de la Empresa
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
                            <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                                <div class="card border-0 shadow-sm"><div class="card-body p-4">
                                    <h5 class="card-title mb-4">Datos de la Empresa</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6"><label class="form-label">Nombre Comercial</label><input type="text" name="nombre_comercial" class="form-control" value="<?php echo htmlspecialchars($empresa['nombre_comercial'] ?? ''); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Razón Social</label><input type="text" name="razon_social" class="form-control" value="<?php echo htmlspecialchars($empresa['razon_social'] ?? ''); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">RFC</label><input type="text" name="rfc" class="form-control" value="<?php echo htmlspecialchars($empresa['rfc'] ?? ''); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Teléfono</label><input type="tel" name="telefono_empresa" class="form-control" value="<?php echo htmlspecialchars($empresa['telefono_empresa'] ?? ''); ?>" disabled></div>
                                        <div class="col-12"><label class="form-label">Descripción</label><textarea name="descripcion" class="form-control" rows="4" disabled><?php echo htmlspecialchars($empresa['descripcion'] ?? ''); ?></textarea></div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    <h6 class="mb-3">Documento de Validación de la Empresa</h6>
                                    <div class="d-flex justify-content-between align-items-center" id="company-document-section">
                                        <div>
                                            <i class="fas fa-file-contract fa-2x text-muted me-2"></i>
                                            <span class="fw-bold">Acta Constitutiva / Constancia Fiscal</span>
                                        </div>
                                        <div>
                                            <?php if (!empty($empresa['documento_url'])): ?>
                                                <a href="<?php echo BASE_URL . htmlspecialchars($empresa['documento_url']); ?>" target="_blank" class="btn btn-success btn-sm">Ver Documento</a>
                                                <button type="button" id="replaceDocBtn" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadCompanyDocumentModal" style="display: none;">Reemplazar</button>
                                            <?php else: ?>
                                                <button type="button" id="uploadDocBtn" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadCompanyDocumentModal" style="display: none;">Subir Documento</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div></div>
                            </div>

                            <div class="tab-pane fade" id="direccion" role="tabpanel" aria-labelledby="direccion-tab">
                                <div class="card border-0 shadow-sm"><div class="card-body p-4">
                                    <h5 class="card-title mb-4">Dirección Comercial</h5>
                                    <div class="row g-3">
                                        <div class="col-12"><label class="form-label">Calle</label><input type="text" name="calle" class="form-control" value="<?php echo htmlspecialchars($empresa['calle'] ?? ''); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Número Exterior</label><input type="text" name="numero_exterior" class="form-control" value="<?php echo htmlspecialchars($empresa['numero_exterior'] ?? ''); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Número Interior</label><input type="text" name="numero_interior" class="form-control" value="<?php echo htmlspecialchars($empresa['numero_interior'] ?? ''); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Colonia</label><input type="text" name="colonia" class="form-control" value="<?php echo htmlspecialchars($empresa['colonia'] ?? ''); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Código Postal</label><input type="text" name="codigo_postal" class="form-control" value="<?php echo htmlspecialchars($empresa['codigo_postal'] ?? ''); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Municipio</label><input type="text" name="municipio" class="form-control" value="<?php echo htmlspecialchars($empresa['municipio'] ?? ''); ?>" disabled></div>
                                        <div class="col-md-6"><label class="form-label">Estado</label><input type="text" name="estado" class="form-control" value="<?php echo htmlspecialchars($empresa['estado'] ?? ''); ?>" disabled></div>
                                    </div>
                                    <hr class="my-4">
                                    <h6 class="mb-3">Ubicación en el Mapa</h6>
                                    <div id="map-canvas" style="height: 300px; width: 100%; border-radius: 0.25rem;"></div>
                                    <input type="hidden" id="lat-input" name="latitud" value="<?php echo htmlspecialchars($empresa['latitud'] ?? '17.9869'); ?>">
                                    <input type="hidden" id="lng-input" name="longitud" value="<?php echo htmlspecialchars($empresa['longitud'] ?? '-92.9303'); ?>">
                                </div></div>
                            </div>

                            <div class="tab-pane fade" id="representative" role="tabpanel" aria-labelledby="representative-tab">
                                <div class="card border-0 shadow-sm"><div class="card-body p-4">
                                    <h5 class="card-title mb-4">Datos del Representante Legal</h5>
                                    <?php if (!empty($empresa['representante_nombre'])): ?>
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4"><label class="form-label">Nombre(s)</label><input type="text" name="rep_nombre" class="form-control" value="<?php echo htmlspecialchars($empresa['representante_nombre']); ?>" disabled></div>
                                            <div class="col-md-4"><label class="form-label">Apellido Paterno</label><input type="text" name="rep_apellido_p" class="form-control" value="<?php echo htmlspecialchars($empresa['representante_ap']); ?>" disabled></div>
                                            <div class="col-md-4"><label class="form-label">Apellido Materno</label><input type="text" name="rep_apellido_m" class="form-control" value="<?php echo htmlspecialchars($empresa['representante_am']); ?>" disabled></div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6"><label class="form-label">Correo</label><input type="email" name="rep_email" class="form-control" value="<?php echo htmlspecialchars($empresa['representante_email']); ?>" disabled></div>
                                            <div class="col-md-6"><label class="form-label">Teléfono</label><input type="tel" name="rep_telefono" class="form-control" value="<?php echo htmlspecialchars($empresa['representante_telefono']); ?>" disabled></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info" role="alert">No hay un representante legal asignado.</div>
                                    <?php endif; ?>
                                </div></div>
                            </div>
                        </div> <div id="action-buttons" class="mt-4 text-end">
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
    </div>
    <div class="modal fade" id="changeLogoModal" tabindex="-1" aria-labelledby="changeLogoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeLogoModalLabel">Cambiar Logo de la Empresa</h5>
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
    <div class="modal fade" id="uploadCompanyDocumentModal" tabindex="-1" aria-labelledby="uploadCompanyDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadCompanyDocumentModalLabel">Documento de Validación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Sube el Acta Constitutiva o la Constancia de Situación Fiscal de tu empresa (PDF, JPG, PNG - Máx 5MB).</p>
                <form id="uploadCompanyDocumentForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input class="form-control" type="file" id="company_document_file" name="company_document_file" accept=".pdf,image/jpeg,image/png">
                    </div>
                    <div id="uploadCompanyDocumentMessage" class="mt-3"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="submitCompanyDocumentButton">Subir Documento</button>
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
            //  LÓGICA DE GOOGLE MAPS (SIN CAMBIOS, SOLO ORGANIZADA)
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
                    draggable: false // Importante: No se puede arrastrar por defecto
                });

                marker.addListener('dragend', () => {
                    const newPosition = marker.getPosition();
                    updateCoordinates(newPosition.lat(), newPosition.lng());
                });
            }

            function updateCoordinates(lat, lng) {
                document.getElementById('lat-input').value = lat;
                document.getElementById('lng-input').value = lng;
            }


            // =================================================================
            //  LÓGICA DE LA PÁGINA (jQuery)
            // =================================================================
            $(document).ready(function() {

                // --- LÓGICA DEL BOTÓN DE EDICIÓN (SOLUCIÓN AL PROBLEMA) ---
                $('#editBtn').on('click', function() {
                    // 1. Habilitamos todos los campos del formulario principal
                    $('#profileForm').find('input, textarea').prop('disabled', false);

                    // 2. Habilitamos el marcador del mapa para que sea arrastrable
                    if (marker) {
                        marker.setDraggable(true);
                    }

                    // 3. Mostramos y ocultamos los botones de acción
                    $('#editBtn').hide();
                    $('#saveBtn, #cancelBtn').show();
                    
                    // 4. Mostramos los botones para cambiar archivos
                    $('#changeLogoBtn').show();
                    $('#replaceDocBtn, #uploadDocBtn').show(); // Muestra el que corresponda
                });

                // --- LÓGICA DEL BOTÓN CANCELAR ---
                $('#cancelBtn').on('click', function() {
                    // Simplemente recargamos la página para descartar cualquier cambio
                    window.location.reload();
                });


                // --- LÓGICA PARA SUBIR LOGO (Tu código original) ---
                $('#submitLogoButton').on('click', function() {
                    var formData = new FormData($('#uploadLogoForm')[0]);
                    var messageDiv = $('#uploadLogoMessage');
                    messageDiv.html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');

                    $.ajax({
                        url: '<?php echo BASE_URL; ?>auth/upload_logo_process.php',
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

                // --- LÓGICA PARA SUBIR ACTA CONSTITUTIVA (Tu código original) ---
                $('#submitCompanyDocumentButton').on('click', function() {
                    var formData = new FormData($('#uploadCompanyDocumentForm')[0]);
                    var messageDiv = $('#uploadCompanyDocumentMessage');
                    messageDiv.html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');

                    $.ajax({
                        url: '<?php echo BASE_URL; ?>auth/upload_company_document_process.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                messageDiv.html('<div class="alert alert-success">' + response.message + '</div>');
                                setTimeout(() => {
                                    // Recargamos solo la sección del documento para que se actualice
                                    $('#company-document-section').load(location.href + ' #company-document-section > *');
                                    $('#uploadCompanyDocumentModal').modal('hide');
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
