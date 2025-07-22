<?php
// Lógica corregida para incluir el manejador de ubicaciones
require_once 'config.php';
require_once 'conexion_local.php';
// --- INICIO DE LA CORRECCIÓN ---
// Se incluye el manejador de ubicaciones para obtener los datos de forma centralizada.
require_once 'auth/ubicaciones_handler.php'; 
// --- FIN DE LA CORRECCIÓN ---
session_start();

// 1. Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?error=2');
    exit();
}

// 2. Obtener todas las organizaciones activas usando la función del handler.
// Esto es más limpio y reutiliza el código que ya tienes.
$organizaciones = obtener_organizaciones_con_categorias($conexion);

// Convertimos los datos a JSON para que JavaScript los pueda usar en el mapa.
$organizaciones_json = json_encode($organizaciones);

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Donación General</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        #map { height: 250px; width: 100%; border-radius: .25rem; background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Cargando...</span></div></div>
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Donación General</h1>
                <p class="fs-5 text-muted mb-0">Ofrece tu ayuda donando artículos, medicamentos o sangre a una organización.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <form class="row g-5" action="auth/donacion_general_procesar.php" method="POST" enctype="multipart/form-data">
                
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">1. ¿Qué tipo de donación quieres hacer?</h5>
                            <div class="d-flex justify-content-around">
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="categoria_donacion" id="radioSangre" value="sangre" required><label class="form-check-label" for="radioSangre"><i class="fas fa-tint text-danger me-2"></i>Sangre</label></div>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="categoria_donacion" id="radioMedicamentos" value="medicamentos" required><label class="form-check-label" for="radioMedicamentos"><i class="fas fa-pills text-primary me-2"></i>Medicamentos</label></div>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="categoria_donacion" id="radioDispositivos" value="dispositivos" required><label class="form-check-label" for="radioDispositivos"><i class="fas fa-wheelchair text-warning me-2"></i>Dispositivos</label></div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="detalles-donacion" class="card border-0 shadow-sm mb-4 d-none">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">2. Completa los detalles</h5>
                            
                            <div id="campos-sangre" class="d-none"><label for="cantidad_sangre" class="form-label">Unidades a Donar</label><input type="number" name="cantidad_sangre" class="form-control" id="cantidad_sangre" value="1" min="1"></div>
                            <div id="campos-medicamentos" class="d-none"><div class="row g-3"><div class="col-12"><label for="nombre_medicamento" class="form-label">Nombre del Medicamento</label><input type="text" name="nombre_medicamento" class="form-control" id="nombre_medicamento" placeholder="Ej: Paracetamol 500mg"></div><div class="col-md-6"><label for="cantidad_medicamento" class="form-label">Cantidad (cajas)</label><input type="number" name="cantidad_medicamento" class="form-control" id="cantidad_medicamento" value="1" min="1"></div><div class="col-md-6"><label for="caducidad" class="form-label">Fecha de Caducidad</label><input type="date" name="caducidad" class="form-control" id="caducidad"></div><div class="col-12"><label for="foto_medicamento" class="form-label">Foto (Opcional)</label><input type="file" name="foto_medicamento" class="form-control" id="foto_medicamento" accept="image/*"></div></div></div>
                            <div id="campos-dispositivos" class="d-none"><div class="row g-3"><div class="col-md-6"><label for="nombre_dispositivo" class="form-label">Tipo de Dispositivo</label><input type="text" name="nombre_dispositivo" class="form-control" id="nombre_dispositivo" placeholder="Ej: Silla de ruedas"></div><div class="col-md-6"><label for="estado_dispositivo" class="form-label">Estado</label><select id="estado_dispositivo" name="estado_dispositivo" class="form-select"><option value="Nuevo">Nuevo</option><option value="Usado - Buen estado">Usado - Buen estado</option><option value="Usado - Regular">Usado - Regular</option></select></div><div class="col-md-6"><label for="cantidad_dispositivo" class="form-label">Cantidad</label><input type="number" name="cantidad_dispositivo" class="form-control" id="cantidad_dispositivo" value="1" min="1"></div><div class="col-md-6"><label for="foto_dispositivo" class="form-label">Foto (Opcional)</label><input type="file" name="foto_dispositivo" class="form-control" id="foto_dispositivo" accept="image/*"></div></div></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">3. ¿Dónde lo entregarás?</h5>
                            <p class="text-muted">Selecciona una organización del menú o haz clic en un punto del mapa.</p>
                            
                            <input type="hidden" id="organizacion_id_hidden" name="organizacion_id" value="">

                            <div class="mb-3">
                                <label for="organizacion_select" class="form-label">Centro de Donación</label>
                                <select id="organizacion_select" class="form-select" required>
                                    <option value="">Selecciona una organización...</option>
                                    <?php foreach ($organizaciones as $org): ?>
                                        <option value="<?php echo $org['id']; ?>" data-lat="<?php echo $org['latitud']; ?>" data-lng="<?php echo $org['longitud']; ?>">
                                            <?php echo htmlspecialchars($org['nombre_organizacion']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div id="map"></div>

                            <hr>
                            <div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="terminos" required><label class="form-check-label" for="terminos">Confirmo que la información es correcta y acepto los <a href="#">términos y condiciones</a>.</label></div>
                            <button type="submit" id="btn-confirmar" class="btn btn-primary w-100 p-3" disabled>Confirmar Donación</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <?php require_once 'templates/footer.php'; ?>
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    <?php require_once 'templates/scripts.php'; ?>
          
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Lógica para mostrar/ocultar campos ---
            const radios = document.querySelectorAll('input[name="categoria_donacion"]');
            const detallesDiv = document.getElementById('detalles-donacion');
            const campos = { sangre: document.getElementById('campos-sangre'), medicamentos: document.getElementById('campos-medicamentos'), dispositivos: document.getElementById('campos-dispositivos') };
            const inputs = { sangre: campos.sangre.querySelectorAll('input, select'), medicamentos: campos.medicamentos.querySelectorAll('input, select'), dispositivos: campos.dispositivos.querySelectorAll('input, select') };

            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    detallesDiv.classList.remove('d-none');
                    for (let key in campos) {
                        campos[key].classList.add('d-none');
                        inputs[key].forEach(input => { input.disabled = true; input.required = false; });
                    }
                    if (this.checked) {
                        campos[this.value].classList.remove('d-none');
                        inputs[this.value].forEach(input => {
                            input.disabled = false;
                            // Re-aplicar 'required' solo a los inputs que lo necesiten
                            if(input.name !== 'foto_medicamento' && input.name !== 'foto_dispositivo') {
                                input.required = true;
                            }
                        });
                    }
                });
            });

            // --- Script del mapa interactivo adaptado ---
            const organizaciones = <?php echo $organizaciones_json; ?>;
            let map;
            let infoWindow;
            const marcadores = [];
            const selectOrganizacion = document.getElementById('organizacion_select');
            const organizacionIdInput = document.getElementById('organizacion_id_hidden');
            const btnConfirmar = document.getElementById('btn-confirmar');

            window.initMap = function() {
                const initialPos = { lat: 17.9869, lng: -92.9303 };
                map = new google.maps.Map(document.getElementById("map"), { zoom: 12, center: initialPos, mapTypeControl: false });
                infoWindow = new google.maps.InfoWindow();

                organizaciones.forEach(org => {
                    const marker = new google.maps.Marker({
                        position: { lat: parseFloat(org.latitud), lng: parseFloat(org.longitud) },
                        map: map,
                        title: org.nombre_organizacion,
                        orgId: org.id
                    });

                    marker.addListener("click", () => {
                        selectOrganizacion.value = marker.orgId;
                        organizacionIdInput.value = marker.orgId;
                        btnConfirmar.disabled = false;
                        map.setCenter(marker.getPosition());
                        map.setZoom(15);
                        infoWindow.setContent(`<div class="p-2"><strong>${marker.title}</strong><br><span class="badge bg-primary mt-2">Organización Seleccionada</span></div>`);
                        infoWindow.open(map, marker);
                    });
                    marcadores.push(marker);
                });

                selectOrganizacion.addEventListener('change', function() {
                    const selectedId = this.value;
                    organizacionIdInput.value = selectedId;
                    infoWindow.close();
                    if (!selectedId) { btnConfirmar.disabled = true; return; }

                    btnConfirmar.disabled = false;
                    const marcadorCorrespondiente = marcadores.find(m => m.orgId == selectedId);
                    if (marcadorCorrespondiente) {
                        map.setCenter(marcadorCorrespondiente.getPosition());
                        map.setZoom(15);
                        infoWindow.setContent(`<div class="p-2"><strong>${marcadorCorrespondiente.title}</strong><br><span class="badge bg-primary mt-2">Organización Seleccionada</span></div>`);
                        infoWindow.open(map, marcadorCorrespondiente);
                    }
                });
            }
        });
    </script>
</body>
</html>