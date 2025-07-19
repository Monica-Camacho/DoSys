<?php
require_once 'config.php'; // Incluye la configuración y la URL base.
require_once 'conexion_local.php'; 
// Inicia la sesión.
session_start();

// Muestra una alerta si hay un error en el inicio de sesión.
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "<script>alert('Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.');</script>";
}

// 1. Obtener la lista de organizaciones para que el usuario elija quién gestionará su aviso
$organizaciones = [];
$sql_orgs = "SELECT id, nombre_organizacion FROM organizaciones_perfil ORDER BY nombre_organizacion ASC";
$resultado_orgs = $conexion->query($sql_orgs);
if ($resultado_orgs) {
    while ($fila = $resultado_orgs->fetch_assoc()) {
        $organizaciones[] = $fila;
    }
}

// 2. Obtener los tipos de sangre
$tipos_sangre = [];
$sql_sangre = "SELECT id, nombre FROM tipos_sangre ORDER BY nombre ASC";
$resultado_sangre = $conexion->query($sql_sangre);
if ($resultado_sangre) {
    while ($fila = $resultado_sangre->fetch_assoc()) {
        $tipos_sangre[] = $fila;
    }
}
$conexion->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>DoSys - Crear Solicitud de Donación</title>
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
                <h1 class='display-5 mb-0'>Crear Solicitud</h1>
                <p class="fs-5 text-muted mb-0">Completa la siguiente información para validar tu caso.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Request Form Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
<form class="row g-5" action="auth/crear_aviso_process.php" method="POST" enctype="multipart/form-data">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">1. ¿Qué se necesita?</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="titulo" class="form-label">Título del Aviso</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ej: Donadores de Sangre O+ para cirugía" required>
                    </div>
                    <div class="col-md-6">
                        <label for="urgencia" class="form-label">Nivel de Urgencia</label>
                        <select id="urgencia" name="urgencia_id" class="form-select" required>
                            <option value="1">Baja</option>
                            <option value="2">Media</option>
                            <option value="3">Alta</option>
                            <option value="4">Crítica</option>
                        </select>
                    </div>
                    
                    <div id="campos-sangre" class="row g-3">
                        <div class="col-md-6">
                            <label for="tipoSangre" class="form-label">Tipo de Sangre</label>
                            <select id="tipoSangre" name="tipo_sangre_id" class="form-select" required>
                                <option value="">Selecciona un tipo...</option>
                                <?php foreach ($tipos_sangre as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="unidades" class="form-label">Unidades Requeridas</label>
                            <input type="number" class="form-control" id="unidades" name="unidades_requeridas" placeholder="Ej: 4" min="1" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">2. ¿Para quién es la ayuda?</h5>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="soy_donatario">
                    <label class="form-check-label" for="soy_donatario">
                        La ayuda es para mí (el donatario soy yo).
                    </label>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="donatario_nombre" class="form-label">Nombre(s) del Donatario</label>
                        <input type="text" class="form-control" id="donatario_nombre" name="donatario_nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label for="donatario_apellidos" class="form-label">Apellidos del Donatario</label>
                        <input type="text" class="form-control" id="donatario_apellidos" name="donatario_apellidos" required>
                    </div>
                </div>
            </div>
        </div>

         <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">3. Justificación</h5>
                 <div class="col-12">
                    <label for="descripcion" class="form-label">Descripción Detallada de la Situación</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Explica brevemente por qué necesitas la ayuda..." required></textarea>
                </div>
                <div class="col-12 mt-3">
                    <label for="documento" class="form-label">Documento Médico de Soporte (Indispensable)</label>
                    <input type="file" class="form-control" id="documento" name="documento" required accept="application/pdf,image/jpeg,image/png">
                    <small class="form-text text-muted">Sube la solicitud del hospital o la receta médica.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm" style="position: sticky; top: 20px;">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">4. Organización Gestora</h5>
                <p class="text-muted">Selecciona la organización de confianza que se encargará de validar y gestionar tu solicitud.</p>
                <div class="mb-3">
                    <label for="organizacion" class="form-label">Organización Responsable</label>
                    <select id="organizacion" name="organizacion_id" class="form-select" required>
                        <option value="">Selecciona una organización...</option>
                        <?php foreach ($organizaciones as $org): ?>
                            <option value="<?php echo $org['id']; ?>"><?php echo htmlspecialchars($org['nombre_organizacion']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <hr>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="terminos" required>
                    <label class="form-check-label" for="terminos">
                        Acepto los <a href="#">términos y condiciones</a> y confirmo que la información es verídica.
                    </label>
                </div>
                <button type="submit" class="btn btn-primary w-100 p-3">Enviar Solicitud para Validación</button>
            </div>
        </div>
    </div>
</form>
        </div>
    </div>
    <!-- Request Form Content End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>

    <!-- Dynamic Form Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Obtener el parámetro 'categoria' de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const categoria = urlParams.get('categoria');

            // 2. Seleccionar los contenedores de los campos
            const camposSangre = document.getElementById('campos-sangre');
            const camposMedicamentos = document.getElementById('campos-medicamentos');
            const camposDispositivos = document.getElementById('campos-dispositivos');
            const tituloPagina = document.querySelector('.display-5'); // El H1 del encabezado

            // 3. Mostrar solo el grupo que corresponde a la categoría
            if (categoria === 'sangre') {
                camposSangre.classList.remove('d-none');
                tituloPagina.textContent = 'Solicitud de Sangre';
            } else if (categoria === 'medicamentos') {
                camposMedicamentos.classList.remove('d-none');
                tituloPagina.textContent = 'Solicitud de Medicamentos';
            } else if (categoria === 'dispositivos') {
                camposDispositivos.classList.remove('d-none');
                tituloPagina.textContent = 'Solicitud de Dispositivos';
            } else {
                // Opcional: si no hay categoría, mostrar un mensaje o redirigir a segmentos.php
                tituloPagina.textContent = 'Crear Solicitud';
                // Podrías mostrar un mensaje pidiendo que seleccionen una categoría primero.
            }
        });
    </script>
    
</body>

</html>
