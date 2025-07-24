<?php
require_once 'config.php';
require_once 'conexion_local.php'; // Se necesita para las estadísticas
session_start();

if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "<script>alert('Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.');</script>";
}

// --- INICIO: BLOQUE PARA OBTENER ESTADÍSTICAS REALES ---
$estadisticas = [
    'total_donadores' => 0,
    'total_organizaciones' => 0,
    'total_ayudados' => 0,
    'total_medicamentos' => 0
];

// 1. Total de Donadores Altruistas (usuarios únicos que han donado)
$result_don = $conexion->query("SELECT COUNT(DISTINCT donante_id) AS total FROM donaciones WHERE estatus_id = 3");
if ($result_don) $estadisticas['total_donadores'] = $result_don->fetch_assoc()['total'] ?? 0;

// 2. Total de Asociaciones Vinculadas (organizaciones activas)
$result_org = $conexion->query("SELECT COUNT(id) AS total FROM organizaciones_perfil WHERE estado = 'Activa'");
if ($result_org) $estadisticas['total_organizaciones'] = $result_org->fetch_assoc()['total'] ?? 0;

// 3. Total de Personas Ayudadas (beneficiarios únicos de avisos completados)
$result_ayu = $conexion->query("SELECT COUNT(DISTINCT donatario_id) AS total FROM avisos WHERE estatus_id = 3");
if ($result_ayu) $estadisticas['total_ayudados'] = $result_ayu->fetch_assoc()['total'] ?? 0;

// 4. Total de Medicamentos Donados (suma de cantidades de donaciones de medicamentos completadas)
$result_med = $conexion->query("SELECT SUM(d.cantidad) AS total FROM donaciones d JOIN avisos a ON d.aviso_id = a.id WHERE a.categoria_id = 2 AND d.estatus_id = 3");
if ($result_med) $estadisticas['total_medicamentos'] = $result_med->fetch_assoc()['total'] ?? 0;

$conexion->close();
// --- FIN: BLOQUE PARA OBTENER ESTADÍSTICAS ---
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8">
        <title>DoSys (Donation System)</title>
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
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <link href="css/Carrusel.css" rel="stylesheet">

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

        <!-- Carousel Start -->
        <div id="donationCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Slide 1: Sangre -->
                <div class="carousel-item active">
                    <div class="carousel-image-container h-100">
                        <img src="img/fondos/sangre.png" class="d-block w-100 carousel-image img-fluid" alt="Donación de sangre">
                        <div class="carousel-caption">
                            <div class="px-3">
                                <h1 class="text-light display-3 mb-4">Sangre</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-2">Tu donación puede salvar vidas. Ayuda a quienes más lo necesitan.</p>
                                <div class="d-flex gap-4 justify-content-center">
                                    <a href="donacion_general.php" class="btn btn-primary btn-lg rounded-pill px-4">Ser Donante</a>
                                    <a href="avisos_crear_sangre.php" class="btn btn-success btn-lg rounded-pill px-4">Solicitar Donante</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: Medicamentos -->
                <div class="carousel-item">
                    <div class="carousel-image-container h-100">
                        <img src="img/fondos/medicinas.png" class="d-block w-100 carousel-image img-fluid" alt="Donación de medicamentos">
                        <div class="carousel-caption">
                            <div class="px-3">
                                <h1 class="text-light display-3 mb-4">Medicamentos</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-2">Dona medicamentos que ya no necesites y ayuda a un tratamiento.</p>
                                <div class="d-flex gap-4 justify-content-center">
                                    <a href="donacion_general.php" class="btn btn-primary btn-lg rounded-pill px-4">Donar Medicamentos</a>
                                    <a href="avisos_crear_medicamento.php" class="btn btn-success btn-lg rounded-pill px-4">Solicitar Medicamentos</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3: Dispositivos -->
                <div class="carousel-item">
                    <div class="carousel-image-container h-100">
                        <img src="img/fondos/dispositivos.png" class="d-block w-100 carousel-image img-fluid" alt="Donación de dispositivos">
                        <div class="carousel-caption">
                            <div class="px-3">
                                <h1 class="text-light display-3 mb-4">Dispositivos de Asistencia</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-2">Mejora la calidad de vida de alguien donando un dispositivo médico.</p>
                                <div class="d-flex gap-4 justify-content-center">
                                    <a href="donacion_general.php" class="btn btn-primary btn-lg rounded-pill px-4">Donar Dispositivo</a>
                                    <a href="avisos_crear_dispositivo.php" class="btn btn-success btn-lg rounded-pill px-4">Solicitar Dispositivo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controles del Carrusel -->
            <button class="carousel-control-prev" type="button" data-bs-target="#donationCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#donationCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
        <!-- Carousel End -->
        
        <!-- ACERCA DE DOSYS -->
        <div class="container-fluid bg-light about pb-5">
            <div class="container pb-5">
                <div class="row g-5">
                    <div class="col-xl-6 wow fadeInLeft" data-wow-delay="0.2s">
                        <div class="about-item-content bg-white rounded p-5 h-100">
                            <h4 class="text-primary">Acerca de DoSys</h4>
                            <h1 class="display-4 mb-4">La Herramienta Universal de donación por excelencia.</h1>
                            <p style="text-align: justify;">Logramos ser un método universal para las donaciones altruistas en México, conectando con las diferentes organizaciones altruistas, las ONG, hospitales y el público en general.</p>
                            <p class="text-dark" style="text-align: justify;"><i class="fa fa-check text-primary me-3"></i>Facilitamos el sistema de donación.</p>
                            <p class="text-dark" style="text-align: justify;"><i class="fa fa-check text-primary me-3"></i>Recompensamos a nuestros donadores.</p>
                            <p class="text-dark mb-4" style="text-align: justify;"><i class="fa fa-check text-primary me-3"></i>Mejoramos la calidad de vida de las personas.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-primary rounded-pill py-3 px-3" href="c-sobre_nosotros.php">Más Información</a>
                                <a class="btn btn-outline-primary rounded-pill py-3 px-3" href="estadisticas.php">Ver Estadísticas</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 wow fadeInRight" data-wow-delay="0.2s">
                        <div class="bg-white rounded p-5 h-100">
                            <div class="row g-4 justify-content-center">
                                <div class="col-12">
                                    <div class="rounded bg-light">
                                        <img src="img/elements/index.png" class="img-fluid rounded w-100" alt="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="counter-item bg-light rounded p-3 h-100">
                                        <div class="counter-counting">
                                            <span class="text-primary fs-2 fw-bold" data-toggle="counter-up"><?php echo $estadisticas['total_donadores']; ?></span>
                                            <span class="h1 fw-bold text-primary">+</span>
                                        </div>
                                        <h4 class="mb-0 text-dark">Donadores Altruistas</h4>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="counter-item bg-light rounded p-3 h-100">
                                        <div class="counter-counting">
                                            <span class="text-primary fs-2 fw-bold" data-toggle="counter-up"><?php echo $estadisticas['total_organizaciones']; ?></span>
                                            <span class="h1 fw-bold text-primary">+</span>
                                        </div>
                                        <h4 class="mb-0 text-dark">Asociaciones vinculadas</h4>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="counter-item bg-light rounded p-3 h-100">
                                        <div class="counter-counting">
                                            <span class="text-primary fs-2 fw-bold" data-toggle="counter-up"><?php echo $estadisticas['total_medicamentos']; ?></span>
                                            <span class="h1 fw-bold text-primary">+</span>
                                        </div>
                                        <h4 class="mb-0 text-dark">Medicamentos donados</h4>
                                    </div>
                                </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
          
    </body>

</html>
