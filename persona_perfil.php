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

// 4. Consulta a la Base de Datos para obtener los datos del perfil
// 4. Consulta a la Base de Datos
$sql = "SELECT 
            p.nombre, p.apellido_paterno, p.apellido_materno, p.fecha_nacimiento, p.sexo, p.telefono, 
            p.tipo_sangre_id, u.email,
            (SELECT ruta_archivo FROM documentos WHERE propietario_id = u.id AND tipo_documento_id = 1 ORDER BY fecha_carga DESC LIMIT 1) AS foto_url,
            (SELECT ruta_archivo FROM documentos WHERE propietario_id = u.id AND tipo_documento_id = 8 ORDER BY fecha_carga DESC LIMIT 1) AS ine_url,
            d.calle, d.numero_exterior, d.numero_interior, d.colonia, d.codigo_postal, d.municipio, d.estado,
            d.latitud, d.longitud
        FROM personas_perfil p
        JOIN usuarios u ON p.usuario_id = u.id
        LEFT JOIN direcciones d ON p.direccion_id = d.id
        WHERE p.usuario_id = ?";

$perfil = []; 

if ($stmt = $conexion->prepare($sql)) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $perfil = $resultado->fetch_assoc();
    } else {
        die("Error: No se pudo encontrar el perfil del usuario.");
    }
    $stmt->close();
}

// Consulta para obtener todos los tipos de sangre
$tipos_sangre = [];
$resultado_sangre = $conexion->query("SELECT id, tipo FROM tipos_sangre ORDER BY tipo");
if ($resultado_sangre) {
    $tipos_sangre = $resultado_sangre->fetch_all(MYSQLI_ASSOC);
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Tu head original se mantiene intacto -->
    <meta charset="utf-8">
    <title>Mi Perfil - DoSys</title>
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
        <style>
        #map-canvas {
            height: 400px;
            width: 100%;
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
        }
    </style>

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
        <div class="container-fluid topbar px-0 px-lg-4 bg-light py-2 d-none d-lg-block">
            <div class="container">
                <div class="row gx-0 align-items-center">
                    <div class="col-lg-8 text-center text-lg-start mb-lg-0">
                        <div class="d-flex flex-wrap">
                            <div class="border-end border-primary pe-3">
                                <a href="mapa.html" class="text-muted small"><i class="fas fa-map-marker-alt text-primary me-2"></i>Lugares de donación</a>
                            </div>
                            <div class="ps-3">
                                <a href="mailto:contacto@dosys.mx" class="text-muted small"><i class="fas fa-envelope text-primary me-2"></i>contacto@dosys.mx</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <div class="d-flex justify-content-end">
                            <div class="d-flex border-end border-primary pe-3">
                                <a class="btn p-0 text-primary me-3" href="https://www.instagram.com/dosys_official/" target="_blank"><i class="fab fa-instagram"></i></a>
                                <a class="btn p-0 text-primary me-0" href="https://www.tiktok.com/@dosys.official" target="_blank"><i class="fab fa-tiktok"></i></a>
                            </div>
                            <div class="dropdown ms-3">
                                <a href="#" class="dropdown-toggle text-dark" data-bs-toggle="dropdown"><small><i class="fas fa-globe-europe text-primary me-2"></i> Idioma</small></a>
                                <div class="dropdown-menu rounded">
                                    <a href="#" class="dropdown-item">Español</a>
                                    <a href="mantenimiento.html" class="dropdown-item">Inglés</a>
                                    <a href="mantenimiento.html" class="dropdown-item">Nahuatl</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->
         
        <!-- Navbar Start -->
        <?php require_once 'templates/navbar.php'; ?>
        <!-- Navbar End -->

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Mi Perfil</h1>
                <p class="fs-5 text-muted mb-0">Administra tu información personal, médica y de seguridad.</p>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Columna de la Foto -->
                <div class="col-lg-4">
                     <div class="d-flex flex-column text-center align-items-center bg-white p-4 rounded shadow-sm">
                        <!-- La imagen ahora muestra la foto del usuario o la de por defecto -->
                        <img class="img-fluid rounded-circle mb-3" 
                             src="<?php echo !empty($perfil['foto_url']) ? BASE_URL . htmlspecialchars($perfil['foto_url']) : BASE_URL . 'img/user.jpg'; ?>" 
                             alt="Foto de Perfil" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                        
                        <h4 class="mb-1"><?php echo htmlspecialchars($perfil['nombre'] ?? 'Usuario'); ?></h4>
                        
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                            Subir Foto
                        </button>
                    </div>
                </div>

                <div class="col-lg-8">
                    <ul class="nav nav-tabs nav-pills nav-fill mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                                <i class="fas fa-user-edit me-2"></i>Información Personal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="direccion-tab" data-bs-toggle="tab" data-bs-target="#direccion" type="button" role="tab" aria-controls="direccion" aria-selected="false">
                                <i class="fas fa-map-marker-alt me-2"></i>Dirección
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab" aria-controls="medical" aria-selected="false">
                                <i class="fas fa-briefcase-medical me-2"></i>Información Médica
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                                <i class="fas fa-shield-alt me-2"></i>Seguridad
                            </button>
                        </li>
                    </ul>

                    <form action="<?php echo BASE_URL; ?>auth/update_profile_process.php" method="POST">
                    <div class="tab-content" id="profileTabsContent">
                        
                            <!-- Tab de Información Personal -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-4">Datos Personales</h5>
                                        <div class="row g-3">
                                            <div class="col-md-4"><label for="nombre" class="form-label">Nombre(s)</label><input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo htmlspecialchars($perfil['nombre'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-4"><label for="apellido_paterno" class="form-label">Apellido Paterno</label><input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" value="<?php echo htmlspecialchars($perfil['apellido_paterno'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-4"><label for="apellido_materno" class="form-label">Apellido Materno</label><input type="text" class="form-control" name="apellido_materno" id="apellido_materno" value="<?php echo htmlspecialchars($perfil['apellido_materno'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-6"><label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label><input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo htmlspecialchars($perfil['fecha_nacimiento'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-6"><label for="sexo" class="form-label">Sexo al Nacer</label><select id="sexo" name="sexo" class="form-select" disabled><option <?php if(empty($perfil['sexo'])) echo 'selected'; ?>>No especificado</option><option <?php if(($perfil['sexo'] ?? '') == 'Masculino') echo 'selected'; ?>>Masculino</option><option <?php if(($perfil['sexo'] ?? '') == 'Femenino') echo 'selected'; ?>>Femenino</option></select></div>
                                        </div>
                                        <hr class="my-4">
                                        <h6 class="mb-3">Documento de Identidad</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-id-card fa-2x text-muted me-2"></i>
                                                <span class="fw-bold">Credencial del Lector (INE)</span>
                                            </div>
                                            <div>
                                                <?php if (!empty($perfil['ine_url'])): ?>
                                                    <a href="<?php echo BASE_URL . htmlspecialchars($perfil['ine_url']); ?>" target="_blank" class="btn btn-success btn-sm">Ver Documento</a>
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadIneModal">Reemplazar</button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadIneModal">Subir Documento</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab de Dirección -->
                            <!-- Tab de Dirección -->
                            <div class="tab-pane fade" id="direccion" role="tabpanel" aria-labelledby="direccion-tab">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-4">Tu Dirección Registrada</h5>
                                        <div class="row g-3">
                                            <div class="col-12"><label class="form-label">Calle</label><input type="text" class="form-control" name="dir_calle" value="<?php echo htmlspecialchars($perfil['calle'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-6"><label class="form-label">Número Exterior</label><input type="text" class="form-control" name="dir_num_ext" value="<?php echo htmlspecialchars($perfil['numero_exterior'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-6"><label class="form-label">Número Interior</label><input type="text" class="form-control" name="dir_num_int" value="<?php echo htmlspecialchars($perfil['numero_interior'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-6"><label class="form-label">Colonia</label><input type="text" class="form-control" name="dir_colonia" value="<?php echo htmlspecialchars($perfil['colonia'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-6"><label class="form-label">Código Postal</label><input type="text" class="form-control" name="dir_cp" value="<?php echo htmlspecialchars($perfil['codigo_postal'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-6"><label class="form-label">Municipio</label><input type="text" class="form-control" name="dir_municipio" value="<?php echo htmlspecialchars($perfil['municipio'] ?? ''); ?>" disabled></div>
                                            <div class="col-md-6"><label class="form-label">Estado</label><input type="text" class="form-control" name="dir_estado" value="<?php echo htmlspecialchars($perfil['estado'] ?? ''); ?>" disabled></div>
                                        </div>
                                        
                                        <hr class="my-4">
                                        <h6 class="mb-3">Ubicación en el Mapa</h6>
                                        
                                        <!-- Contenedor del Mapa -->
                                        <div id="map-canvas" class="mb-3"></div>
                                        
                                        <!-- Campos ocultos para guardar las coordenadas -->
                                        <input type="hidden" id="lat-input" name="latitud" value="<?php echo htmlspecialchars($perfil['latitud'] ?? '17.9869'); ?>">
                                        <input type="hidden" id="lng-input" name="longitud" value="<?php echo htmlspecialchars($perfil['longitud'] ?? '-92.9303'); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Tab de Información Médica -->
                            <div class="tab-pane fade" id="medical" role="tabpanel" aria-labelledby="medical-tab">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <form>
                                            <div class="row g-3">
                                                <div class="col-md-6"><label class="form-label">Tipo de Sangre</label><select class="form-select"><?php foreach($tipos_sangre as $tipo): ?><option value="<?php echo $tipo['id']; ?>" <?php if(($perfil['tipo_sangre_id'] ?? '') == $tipo['id']) echo 'selected'; ?>><?php echo htmlspecialchars($tipo['tipo']); ?></option><?php endforeach; ?></select></div>
                                                <div class="col-12"><label class="form-label">Enfermedades Crónicas</label><textarea class="form-control" rows="2"></textarea></div>
                                                <div class="col-12"><label class="form-label">Alergias</label><textarea class="form-control" rows="2"></textarea></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab de Seguridad -->
                            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <form>
                                            <div class="row g-3">
                                                <div class="col-12"><label class="form-label">Correo Electrónico</label><input type="email" class="form-control" value="<?php echo htmlspecialchars($perfil['email'] ?? ''); ?>" readonly></div>
                                                <hr>
                                                <div class="col-md-6"><label class="form-label">Contraseña Actual</label><input type="password" class="form-control"></div>
                                                <div class="col-md-6"><label class="form-label">Nueva Contraseña</label><input type="password" class="form-control"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <!-- Botones de Acción -->
                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-secondary">Editar Perfil</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventana Modal para Subir la Foto (se mantiene igual) -->
    <div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="uploadPhotoModalLabel">Subir Nueva Foto de Perfil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="<?php echo BASE_URL; ?>auth/upload_photo_process.php" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="profile_photo" class="form-label">Selecciona una imagen (JPG, PNG)</label>
                <input class="form-control" type="file" id="profile_photo" name="profile_photo" accept="image/jpeg, image/png" required>
              </div>
              <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Subir Foto</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Ventana Modal para Subir la Credencial del Lector (INE) -->
    <div class="modal fade" id="uploadIneModal" tabindex="-1" aria-labelledby="uploadIneModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="uploadIneModalLabel">Subir Credencial del Lector (INE)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="<?php echo BASE_URL; ?>auth/upload_document_process.php" method="POST" enctype="multipart/form-data">
              <!-- Campo oculto para decirle al script qué tipo de documento es -->
              <input type="hidden" name="document_type_code" value="DOC_INE_PERFIL">
              <div class="mb-3">
                <label for="ine_document" class="form-label">Selecciona un archivo (PDF, JPG, PNG)</label>
                <input class="form-control" type="file" id="ine_document" name="document_file" accept=".pdf, image/jpeg, image/png" required>
              </div>
              <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Subir Documento</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 wow fadeIn" data-wow-delay="0.2s">
        <div class="container py-5">
            <div class="row g-5">
                <!-- Legal Information -->
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Información Legal</h4>
                        <a class="btn-link" href="#"><i class="fas fa-angle-right me-2"></i> Términos y Condiciones</a>
                        <a class="btn-link" href="#"><i class="fas fa-angle-right me-2"></i> Política de Privacidad</a>
                        <a class="btn-link" href="#"><i class="fas fa-angle-right me-2"></i> Aviso Legal</a>
                    </div>
                </div>
                <!-- Quick Links -->
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Enlaces Rápidos</h4>
                        <a class="btn-link" href="index.html"><i class="fas fa-angle-right me-2"></i> Inicio</a>
                        <a class="btn-link" href="avisos.html"><i class="fas fa-angle-right me-2"></i> Avisos de Donación</a>
                        <a class="btn-link" href="mapa.php"><i class="fas fa-angle-right me-2"></i> Mapa</a>
                        <a class="btn-link" href="C-Sobre_Nosotros.html"><i class="fas fa-angle-right me-2"></i> Sobre Nosotros</a>
                        <a class="btn-link" href="C-Nuestro_Equipo.html"><i class="fas fa-angle-right me-2"></i> Nuestro Equipo</a>
                    </div>
                </div>
                <!-- Contact -->
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Contáctanos</h4>
                        <p class="mb-3">¿Tienes alguna duda o necesitas ayuda? No dudes en contactarnos a través de los siguientes medios:</p>
                        <p class="mb-2"><i class="fas fa-envelope me-2 text-white"></i> <a href="mailto:contacto@dosys.mx" class="text-white">contacto@dosys.mx</a></p>
                        <p class="mb-2"><i class="fab fa-whatsapp me-2 text-white"></i> Asesor: 99-33-59-09-31</p>
                        <p class="mb-2"><i class="fab fa-whatsapp me-2 text-white"></i> Líder de Equipo: 99-31-54-67-94</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright Section -->
         <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center text-white" style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding: 25px 0;">
                        <p class="mb-2">Copyright © 2024 DoSys</p>
                        <p class="small mb-0">El presente sitio web es operado por DoSys S. de R.L. de C.V. Todos los derechos reservados. El uso de esta plataforma está sujeto a nuestros Términos y Condiciones y Política de Privacidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>

    <!-- IMPORTANTE: Reemplaza 'TU_API_KEY' con tu clave de API de Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAWXq_cevVYbU88p2xYuVUMOWpHctcDlE8&libraries=places&callback=initMap" async defer></script>

    <script>
        let map;
        let marker;

        // Función para inicializar el mapa y el autocompletado
        function initMap() {
            // Lee las coordenadas iniciales desde los campos ocultos
            const initialLat = parseFloat(document.getElementById('lat-input').value) || 17.9869;
            const initialLng = parseFloat(document.getElementById('lng-input').value) || -92.9303;
            const initialPosition = { lat: initialLat, lng: initialLng };

            // Crea el mapa centrado en la posición inicial
            map = new google.maps.Map(document.getElementById('map-canvas'), {
                center: initialPosition,
                zoom: 16
            });

            // Crea el marcador en la posición inicial
            marker = new google.maps.Marker({
                position: initialPosition,
                map: map,
                draggable: false // Inicialmente no se puede arrastrar
            });

            // --- Lógica de Autocompletado ---
            const addressInput = document.getElementById('address-input');
            const autocomplete = new google.maps.places.Autocomplete(addressInput, {
                fields: ["address_components", "geometry", "name", "formatted_address"],
                types: ["address"],
            });
            autocomplete.bindTo('bounds', map);

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
                if (!place.geometry || !place.geometry.location) {
                    return;
                }

                // Mueve el mapa y el marcador a la nueva dirección
                map.setCenter(place.geometry.location);
                map.setZoom(18);
                marker.setPosition(place.geometry.location);
                
                // Rellena los campos del formulario con los datos de la dirección
                fillInAddress(place);
            });

            // --- Lógica de arrastrar el marcador ---
            marker.addListener('dragend', () => {
                const newPosition = marker.getPosition();
                updateCoordinates(newPosition.lat(), newPosition.lng());
                
                // Realiza geocodificación inversa para obtener la dirección del punto arrastrado
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'location': newPosition }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        addressInput.value = results[0].formatted_address;
                        fillInAddress(results[0]);
                    }
                });
            });
        }

        // Función para rellenar los campos del formulario de forma robusta
        function fillInAddress(place) {
            const components = place.address_components;
            if (!components) return;

            const get = (type) => components.find(c => c.types.includes(type))?.long_name || '';
            
            let streetName = get('route');
            let streetNumber = get('street_number');

            document.getElementById('dir_calle').value = streetName;
            document.getElementById('dir_num_ext').value = streetNumber;
            document.getElementById('dir_colonia').value = get('sublocality_level_1') || get('locality');
            document.getElementById('dir_municipio').value = get('administrative_area_level_2') || get('locality');
            document.getElementById('dir_estado').value = get('administrative_area_level_1');
            document.getElementById('dir_cp').value = get('postal_code');
            
            // Actualiza los campos ocultos de latitud y longitud
            if (place.geometry) {
                updateCoordinates(place.geometry.location.lat(), place.geometry.location.lng());
            }
        }

        // Función para actualizar los campos ocultos de latitud y longitud
        function updateCoordinates(lat, lng) {
            document.getElementById('lat-input').value = lat;
            document.getElementById('lng-input').value = lng;
        }

        // Script para habilitar/deshabilitar la edición de campos
        document.getElementById('edit-button').addEventListener('click', function() {
            const form = this.closest('form');
            const inputs = form.querySelectorAll('input:not([readonly]), select, textarea');
            inputs.forEach(input => {
                input.removeAttribute('disabled');
            });
            
            if(marker) {
                marker.setDraggable(true); // Hacer el marcador arrastrable
            }
            
            this.style.display = 'none';
            document.getElementById('save-button').style.display = 'inline-block';
        });
    </script>

</body>
</html>