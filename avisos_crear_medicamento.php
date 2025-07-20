<?php
// =================================================================
// 1. VERIFICACIÓN DE SESIÓN Y DATOS INICIALES
// =================================================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/conexion_local.php';
require_once __DIR__ . '/auth/ubicaciones_handler.php'; 

session_start();

// --- INICIO DE LA VERIFICACIÓN DE SEGURIDAD (PASO 1) ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}
// --- FIN DE LA VERIFICACIÓN ---

if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "<script>alert('Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.');</script>";
}

// Obtenemos TODAS las organizaciones con sus categorías
$todas_las_organizaciones = obtener_organizaciones_con_categorias($conexion);

// Filtramos solo las de MEDICAMENTOS (categoría 2) para el menú y el mapa
$organizaciones_filtradas = [];
foreach ($todas_las_organizaciones as $org) {
    $categorias_de_la_org = explode(',', $org['categorias_ids']);
    if (in_array('2', $categorias_de_la_org)) {
        $organizaciones_filtradas[] = $org;
    }
}

// Preparamos la lista filtrada para que el mapa la pueda usar
$organizaciones_para_mapa_json = json_encode($organizaciones_filtradas);

// Obtenemos los tipos de sangre
$tipos_sangre = [];
$sql_sangre = "SELECT id, tipo FROM tipos_sangre ORDER BY id ASC";
$resultado_sangre = $conexion->query($sql_sangre);
if ($resultado_sangre) {
    while ($fila = $resultado_sangre->fetch_assoc()) {
        $tipos_sangre[] = ['id' => $fila['id'], 'nombre' => $fila['tipo']];
    }
}

// Obtenemos los niveles de urgencia
$urgencia_niveles = [];
$sql_urgencia = "SELECT id, nombre FROM urgencia_niveles ORDER BY id ASC";
$resultado_urgencia = $conexion->query($sql_urgencia);
if ($resultado_urgencia) {
    while ($fila = $resultado_urgencia->fetch_assoc()) {
        $urgencia_niveles[] = $fila;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>DoSys - Crear Solicitud de Donación</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon, Fonts, CSS Libraries y Estilos -->
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="lib/animate/animate.min.css"/>
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner y Navegación -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <!-- Encabezado de la Página -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Crear Solicitud de Medicamentos</h1>
                <p class="fs-5 text-muted mb-0">Completa la siguiente información para validar tu caso.</p>
            </div>
        </div>
    </div>

    <!-- Contenido del Formulario -->
    <div class="container-fluid py-5">
        <div class="container">
            <form class="row g-5" action="auth/crear_aviso_medicamento_process.php" method="POST" enctype="multipart/form-data">
                
                <!-- Columna Izquierda: Datos del Paciente y de la Solicitud -->
                <div class="col-lg-6">
                    
                    <!-- Sección 1: Datos del Donatario (Paciente) -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">1. Datos del Paciente (Donatario)</h5>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="soy_donatario" name="es_para_mi">
                                <label class="form-check-label" for="soy_donatario">La ayuda es para mí.</label>
                            </div>
                            <div id="datos-donatario-externo">
                                <div class="row g-3">
                                    <div class="col-md-4"><label for="donatario_nombre" class="form-label">Nombre(s)</label><input type="text" class="form-control" id="donatario_nombre" name="donatario_nombre" required></div>
                                    <div class="col-md-4">
                                        <label for="donatario_paterno" class="form-label">Apellido Paterno</label>
                                        <input type="text" class="form-control" id="donatario_paterno" name="donatario_paterno" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="donatario_materno" class="form-label">Apellido Materno</label>
                                        <input type="text" class="form-control" id="donatario_materno" name="donatario_materno">
                                    </div>
                                    <div class="col-md-6"><label for="donatario_nacimiento" class="form-label">Fecha de Nacimiento</label><input type="date" class="form-control" id="donatario_nacimiento" name="donatario_nacimiento" required></div>
                                    <div class="col-md-6"><label class="form-label">Sexo</label><div class="d-flex pt-2"><div class="form-check me-3"><input class="form-check-input" type="radio" name="donatario_sexo" id="masculino" value="Masculino" required><label class="form-check-label" for="masculino">Masculino</label></div><div class="form-check"><input class="form-check-input" type="radio" name="donatario_sexo" id="femenino" value="Femenino" required><label class="form-check-label" for="femenino">Femenino</label></div></div></div>
                                    <div class="col-md-6"><label for="donatario_tipoSangre" class="form-label">Tipo de Sangre del Paciente</label><select id="donatario_tipoSangre" name="donatario_tipo_sangre_id" class="form-select" required><option value="">Selecciona un tipo...</option><?php foreach ($tipos_sangre as $tipo): ?><option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option><?php endforeach; ?></select></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 2: Datos del Aviso -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">2. Datos de la Solicitud</h5>
                            <div class="row g-3">
                                <div class="col-12"><label for="titulo" class="form-label">Título del Aviso</label><input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ej: Solicitud de Insulina" required></div>
                                <div class="col-12"><label for="descripcion" class="form-label">Descripción Breve</label><textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Explica brevemente la situación y para qué se necesita la ayuda." required></textarea></div>
                                <div class="col-md-6"><label for="urgencia" class="form-label">Nivel de Urgencia</label><select id="urgencia" name="urgencia_id" class="form-select" required><option value="">Selecciona un nivel...</option><?php foreach ($urgencia_niveles as $nivel): ?><option value="<?php echo $nivel['id']; ?>"><?php echo htmlspecialchars($nivel['nombre']); ?></option><?php endforeach; ?></select></div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 3: Detalles de la Donación y Justificación -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">3. Detalles y Justificación</h5>
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label for="nombre_medicamento" class="form-label">Nombre del Medicamento</label>
                                    <input type="text" class="form-control" id="nombre_medicamento" name="nombre_medicamento" placeholder="Ej: Paracetamol" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="dosis" class="form-label">Dosis</label>
                                    <input type="text" class="form-control" id="dosis" name="dosis" placeholder="Ej: 500 mg" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="presentacion" class="form-label">Presentación</label>
                                    <input type="text" class="form-control" id="presentacion" name="presentacion" placeholder="Ej: Caja con 20 tabletas" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="cantidad_requerida" class="form-label">Cantidad Requerida</label>
                                    <input type="number" class="form-control" id="cantidad_requerida" name="cantidad_requerida" placeholder="Ej: 2" min="1" required>
                                </div>
                                
                                <div class="col-12 mt-3">
                                    <label for="documento" class="form-label">Receta Médica (Indispensable)</label>
                                    <input type="file" class="form-control" id="documento" name="documento" required accept="application/pdf,image/jpeg,image/png">
                                    <small class="form-text text-muted">Sube la receta que valide la solicitud.</small>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- Columna Derecha: Organización Gestora y Envío -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm" style="position: sticky; top: 20px;">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">4. Organización Gestora</h5>
                            <p class="text-muted">Selecciona una organización para que gestione tu solicitud. Puedes usar el menú o hacer clic en el mapa.</p>
                            <div class="mb-3"><label for="organizacion" class="form-label">Organización Responsable</label><select id="organizacion" name="organizacion_id" class="form-select" required><option value="">Selecciona una organización...</option><?php foreach ($organizaciones_filtradas as $org): ?><option value="<?php echo $org['id']; ?>" data-lat="<?php echo $org['latitud']; ?>" data-lng="<?php echo $org['longitud']; ?>"><?php echo htmlspecialchars($org['nombre_organizacion']); ?></option><?php endforeach; ?></select></div>
                            <div id="map-canvas-organizaciones" class="mb-3" style="height: 250px; width: 100%; border-radius: .25rem; background-color: #f8f9fa;"></div>
                            <hr>
                            <div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="terminos" required><label class="form-check-label" for="terminos">Acepto los <a href="#">términos y condiciones</a> y confirmo que la información es verídica.</label></div>
                            <button type="submit" class="btn btn-primary w-100 p-3">Enviar Solicitud para Validación</button>
                        </div>
                    </div>
                </div>     
            </form>
        </div>
    </div>
    
    <!-- Footer y Scripts -->
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    
    <!-- Script para la lógica del checkbox -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('soy_donatario');
            const datosExterno = document.getElementById('datos-donatario-externo');
            const inputsExterno = datosExterno.querySelectorAll('input, select');
            checkbox.addEventListener('change', function() {
                const esParaMi = this.checked;
                datosExterno.style.display = esParaMi ? 'none' : 'block';
                inputsExterno.forEach(input => { input.required = !esParaMi; });
            });
        });
    </script>

    <!-- Script para el mapa interactivo -->
    <script>
        let map;
        let infoWindow;
        const marcadores = [];
        let userLocationMarker;
        function initMap() {
            const initialPos = { lat: 17.9869, lng: -92.9303 };
            map = new google.maps.Map(document.getElementById("map-canvas-organizaciones"), { zoom: 12, center: initialPos, mapTypeControl: false, streetViewControl: false });
            infoWindow = new google.maps.InfoWindow();
            map.addListener("click", () => infoWindow.close());
            const organizaciones = <?php echo $organizaciones_para_mapa_json; ?>;
            const selectOrganizacion = document.getElementById('organizacion');
            organizaciones.forEach(org => {
                const marker = new google.maps.Marker({ position: { lat: parseFloat(org.latitud), lng: parseFloat(org.longitud) }, map: map, title: org.nombre_organizacion, org_id: org.id, org_calle: org.calle, org_num_ext: org.numero_exterior, org_colonia: org.colonia });
                marker.addListener("click", (e) => {
                    e.domEvent.stopPropagation(); 
                    selectOrganizacion.value = marker.org_id;
                    map.setCenter(marker.getPosition());
                    const contentString = createInfoWindowContent(marker);
                    infoWindow.setContent(contentString);
                    infoWindow.open(map, marker);
                });
                marcadores.push(marker);
            });
            selectOrganizacion.addEventListener('change', function() {
                const selectedId = this.value;
                const selectedOption = this.options[this.selectedIndex];
                if (!selectedId) { infoWindow.close(); return; }
                const lat = parseFloat(selectedOption.getAttribute('data-lat'));
                const lng = parseFloat(selectedOption.getAttribute('data-lng'));
                map.setCenter({ lat: lat, lng: lng });
                map.setZoom(15);
                const marcadorCorrespondiente = marcadores.find(m => m.org_id == selectedId);
                if (marcadorCorrespondiente) {
                    const contentString = createInfoWindowContent(marcadorCorrespondiente);
                    infoWindow.setContent(contentString);
                    infoWindow.open(map, marcadorCorrespondiente);
                }
            });
            geolocalizarUsuario();
        }
        function createInfoWindowContent(marker) {
            const nombre = marker.title;
            const calle = marker.org_calle || '';
            const num_ext = marker.org_num_ext || '';
            const colonia = marker.org_colonia || '';
            return `<div style="font-family: 'DM Sans', sans-serif; max-width: 280px; font-size: 14px;"><h6 style="margin: 0 0 8px; font-weight: bold; font-size: 16px; color: #16243D;">${nombre}</h6><div style="border-left: 3px solid #06A3DA; padding-left: 12px;"><p style="margin: 0 0 2px;"><strong>Dirección:</strong></p><p style="margin: 0; color: #6c757d;">${calle} ${num_ext}, ${colonia}</p></div><div style="margin-top: 12px; padding-top: 8px; border-top: 1px solid #eee; text-align: center;"><span class="badge bg-primary">Organización Seleccionada</span></div></div>`;
        }
        function geolocalizarUsuario() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const userLocation = { lat: position.coords.latitude, lng: position.coords.longitude };
                    map.setCenter(userLocation);
                    if (userLocationMarker) userLocationMarker.setMap(null);
                    const svgIcon = `<svg width="48" height="48" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><g><circle cx="24" cy="24" r="22" fill="#06A3DA" stroke="#FFFFFF" stroke-width="2"/><g fill="#FFFFFF"><circle cx="24" cy="18" r="7"/><path d="M14 38 C14 30, 34 30, 34 38 Z"/></g></g></svg>`;
                    userLocationMarker = new google.maps.Marker({ position: userLocation, map: map, title: "Tu ubicación actual", icon: { url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgIcon), scaledSize: new google.maps.Size(48, 48), anchor: new google.maps.Point(24, 24) }, });
                });
            }
        }
        window.addEventListener("click", () => { if (infoWindow) { infoWindow.close(); } });
    </script>

    <?php require_once 'templates/scripts.php'; ?>
</body>
</html>
