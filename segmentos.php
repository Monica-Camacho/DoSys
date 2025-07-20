<?php
require_once 'config.php'; // Incluye la configuración y la URL base.
// Inicia la sesión.
session_start();

// Muestra una alerta si hay un error en el inicio de sesión.
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "<script>alert('Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.');</script>";
}
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
        <meta charset="utf-8">
        <title>DoSys - Seleccionar Tipo de Solicitud</title>
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
        <link href="css/Donaciones.css" rel="stylesheet">

        <style>
            .carousel-caption {
                top: 0;
                bottom: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
            }
            
            /* Responsive adjustments ONLY for mobile devices */
            @media (max-width: 768px) {
                .carousel-item {
                    height: 85vh; /* Give a consistent, good-looking height on mobile */
                }
                .carousel-item .carousel-image-container,
                .carousel-item .carousel-image {
                    height: 100%;
                    object-fit: cover; /* Ensure image covers the slide area without distortion */
                }

                .carousel-caption h1 {
                    font-size: 2.5rem; /* Smaller title on mobile */
                }
                .carousel-caption p {
                    font-size: 1rem; /* Smaller paragraph on mobile */
                    margin-bottom: 1.5rem !important;
                }
                .carousel-caption .d-flex.gap-4 {
                    flex-direction: column; /* Stack buttons vertically on mobile */
                    gap: 0.75rem !important; /* Reduce gap between stacked buttons */
                    align-items: center;
                }
                 .carousel-caption .btn {
                    font-size: 0.9rem;
                    padding: 0.7rem 1.5rem;
                    width: 80%;
                    max-width: 280px;
                }
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
                                <a href="crear_donacion_persona.php?categoria=sangre" class="btn btn-primary btn-lg rounded-pill px-4">Ser Donante</a>
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
                                <a href="crear_donacion_persona.php?categoria=medicamentos" class="btn btn-primary btn-lg rounded-pill px-4">Donar Medicamentos</a>
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
                                <a href="crear_donacion_persona.php?categoria=dispositivos" class="btn btn-primary btn-lg rounded-pill px-4">Donar Dispositivo</a>
                                <a href="avisos_crear_dispositivos.php" class="btn btn-success btn-lg rounded-pill px-4">Solicitar Dispositivo</a>
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
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
          
</body>

</html>
