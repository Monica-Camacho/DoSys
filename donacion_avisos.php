<?php
require_once 'config.php';
require_once 'conexion_local.php';
session_start();

// =================================================================
// PASO 1: VERIFICACIÓN Y OBTENCIÓN DE DATOS
// =================================================================

// Verificamos que el usuario haya iniciado sesión
if (!isset($_SESSION['id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php?error=2');
    exit();
}

// Obtenemos y validamos el ID del aviso desde la URL
if (!isset($_GET['aviso_id']) || !is_numeric($_GET['aviso_id'])) {
    header('Location: avisos.php');
    exit();
}
$aviso_id = intval($_GET['aviso_id']);

// Consulta para obtener los datos del aviso y la organización
$sql = "SELECT
            a.id AS aviso_id, a.titulo, a.categoria_id,
            op.id AS organizacion_id, op.nombre_organizacion,
            d.calle, d.numero_exterior, d.colonia, d.municipio, d.estado,
            d.latitud, d.longitud
        FROM
            avisos a
        JOIN
            organizaciones_perfil op ON a.organizacion_id = op.id
        JOIN
            direcciones d ON op.direccion_id = d.id
        WHERE
            a.id = ? AND a.estatus_id = 2";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $aviso_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header('Location: avisos.php');
    exit();
}
$aviso = $resultado->fetch_assoc();


// --- INICIO DE LA CORRECCIÓN DEFINITIVA ---
$tipo_sangre_donante = null;
if ($aviso['categoria_id'] == 1) {
    $usuario_id = $_SESSION['id'];
    // Usamos el nombre de tabla correcto: 'personas_perfil'
    $sql_sangre = "SELECT ts.tipo
                   FROM personas_perfil pp
                   JOIN tipos_sangre ts ON pp.tipo_sangre_id = ts.id
                   WHERE pp.usuario_id = ?";
    
    $stmt_sangre = $conexion->prepare($sql_sangre);
    $stmt_sangre->bind_param("i", $usuario_id);
    $stmt_sangre->execute();
    $res_sangre = $stmt_sangre->get_result();
    if ($fila_sangre = $res_sangre->fetch_assoc()) {
        $tipo_sangre_donante = $fila_sangre['tipo'];
    }
    $stmt_sangre->close();
}
// --- FIN DE LA CORRECCIÓN DEFINITIVA ---


$stmt->close();
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Registrar Donación</title>
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
        #map { height: 300px; width: 100%; border-radius: .25rem; }
    </style>
</head>
<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"><span class="sr-only">Cargando...</span></div></div>
    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Confirmar Donación</h1>
                <p class="fs-5 text-muted mb-0">Estás donando para la solicitud: "<?php echo htmlspecialchars($aviso['titulo']); ?>"</p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <form class="row g-5" action="auth/donacion_procesar.php" method="POST" enctype="multipart/form-data">
                
                <input type="hidden" name="aviso_id" value="<?php echo $aviso['aviso_id']; ?>">
                <input type="hidden" name="organizacion_id" value="<?php echo $aviso['organizacion_id']; ?>">

                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">1. Detalles de tu Donación</h5>
                            
                            <div id="campos-sangre" class="<?php echo ($aviso['categoria_id'] == 1) ? '' : 'd-none'; ?>">
                                <p>Estás a punto de registrar tu intención de donar sangre. La organización se pondrá en contacto contigo para agendar una cita.</p>
                                <?php if ($tipo_sangre_donante): ?>
                                <div class="alert alert-info">
                                    <strong>Tu tipo de sangre (según tu perfil):</strong> <?php echo htmlspecialchars($tipo_sangre_donante); ?>.
                                </div>
                                <?php endif; ?>
                                <label for="cantidad_sangre" class="form-label">Unidades a Donar</label>
                                <input type="number" name="cantidad" class="form-control" id="cantidad_sangre" value="1" min="1" required>
                            </div>
                            
                            <div id="campos-medicamentos" class="<?php echo ($aviso['categoria_id'] == 2) ? '' : 'd-none'; ?>">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="nombre_medicamento" class="form-label">Nombre del Medicamento</label>
                                        <input type="text" name="nombre_medicamento" class="form-control" id="nombre_medicamento" placeholder="Ej: Paracetamol 500mg" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cantidad_medicamento" class="form-label">Cantidad (cajas, frascos)</label>
                                        <input type="number" name="cantidad" class="form-control" id="cantidad_medicamento" value="1" min="1" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="caducidad" class="form-label">Fecha de Caducidad</label>
                                        <input type="date" name="caducidad" class="form-control" id="caducidad" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="foto_medicamento" class="form-label">Foto del Medicamento (Opcional)</label>
                                        <input type="file" name="foto" class="form-control" id="foto_medicamento" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            
                            <div id="campos-dispositivos" class="<?php echo ($aviso['categoria_id'] == 3) ? '' : 'd-none'; ?>">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nombre_dispositivo" class="form-label">Tipo de Dispositivo</label>
                                        <input type="text" name="nombre_dispositivo" class="form-control" id="nombre_dispositivo" placeholder="Ej: Silla de ruedas" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="estado_dispositivo" class="form-label">Estado del Dispositivo</label>
                                        <select id="estado_dispositivo" name="estado_dispositivo" class="form-select" required>
                                            <option value="Nuevo">Nuevo</option>
                                            <option value="Usado - Buen estado">Usado - Buen estado</option>
                                            <option value="Usado - Regular">Usado - Regular</option>
                                        </select>
                                    </div>
                                     <div class="col-md-6">
                                        <label for="cantidad_dispositivo" class="form-label">Cantidad</label>
                                        <input type="number" name="cantidad" class="form-control" id="cantidad_dispositivo" value="1" min="1" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="foto_dispositivo" class="form-label">Foto del Dispositivo (Opcional)</label>
                                        <input type="file" name="foto" class="form-control" id="foto_dispositivo" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">2. Punto de Entrega Fijo</h5>
                            <p class="text-muted">La donación será entregada en el centro que realizó la solicitud.</p>
                            
                            <div id="map"></div>
                            
                            <div class="mt-3 mb-3">
                                <label for="centro_donacion" class="form-label">Centro de Donación</label>
                                <input type="text" class="form-control" id="centro_donacion" value="<?php echo htmlspecialchars($aviso['nombre_organizacion']); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="direccion_centro" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion_centro" rows="2" disabled><?php echo htmlspecialchars($aviso['calle'] . ' ' . $aviso['numero_exterior'] . ', ' . $aviso['colonia']); ?></textarea>
                            </div>
                            <hr>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" id="terminos" required>
                                <label class="form-check-label" for="terminos">
                                    Confirmo que la información es correcta y acepto los <a href="#">términos y condiciones</a> de donación.
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 p-3">Confirmar mi Compromiso</button>
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
        function initMap() {
            const locationCoords = {
                lat: <?php echo $aviso['latitud'] ?? '17.9869'; ?>,
                lng: <?php echo $aviso['longitud'] ?? '-92.9303'; ?>
            };
            
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: locationCoords,
                mapTypeControl: false,
                streetViewControl: false,
                draggable: false,
                zoomControl: false,
                scrollwheel: false,
                disableDoubleClickZoom: true
            });

            new google.maps.Marker({
                position: locationCoords,
                map: map,
                title: "<?php echo htmlspecialchars($aviso['nombre_organizacion']); ?>"
            });
        }
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionamos todos los contenedores de campos que están ocultos
            const divsOcultos = document.querySelectorAll('.card-body .d-none');
            
            divsOcultos.forEach(function(div) {
                // Encontramos todos los inputs, selects y textareas dentro de cada div oculto
                const campos = div.querySelectorAll('input, select, textarea');
                
                // Desactivamos cada campo encontrado
                campos.forEach(function(campo) {
                    campo.disabled = true;
                });
            });
        });
    </script>
    
</body>
</html>