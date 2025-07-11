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
    <title>DoSys - Sobre Nosotros</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">

    <!-- Google Web Fonts, Iconos y Estilos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Librerías de Estilos -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Estilos de Bootstrap y de la Plantilla -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
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

    <!-- Header Start (MODIFICADO) -->
    <div class="container-fluid bg-light py-5">
        <div class="container text-center">
            <div>
                <h1 class='display-5 mb-0'>Sobre Nosotros</h1>
                <p class="fs-5 text-muted mb-0">Conoce la historia, el propósito y los valores que impulsan a DoSys.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- About Section Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <img src="img/about-img.jpg" class="img-fluid rounded" alt="Imagen del equipo de DoSys">
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h2 class="display-6">Nuestra Historia</h2>
                    <p class="mb-4">DoSys nació de una idea simple pero poderosa: en un mundo conectado, nadie debería enfrentar una emergencia médica en soledad. Inspirados por historias de lucha y solidaridad en nuestra comunidad de Villahermosa, un grupo de estudiantes decidimos crear un puente digital para que la ayuda llegue a tiempo.</p>
                    <p class="mb-4">Lo que comenzó como un proyecto de residencia profesional, se ha convertido en una misión de vida. Buscamos centralizar las donaciones de sangre, medicamentos y dispositivos para crear una red de apoyo sólida, transparente y eficiente para todo México.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- About Section End -->

    <!-- Mission and Vision Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                <h2 class="display-6">Nuestro Propósito</h2>
                <p>Creemos en un futuro donde la tecnología y la empatía se unen para crear comunidades más fuertes y resilientes.</p>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="accordionMissionVision">
                        <!-- Misión -->
                        <div class="accordion-item wow fadeIn" data-wow-delay="0.3s">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fas fa-bullseye me-3 text-primary"></i> Nuestra Misión
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionMissionVision">
                                <div class="accordion-body">
                                    Facilitar y promover una cultura de donación altruista en México, conectando a donantes, organizaciones y beneficiarios a través de una plataforma tecnológica accesible, segura y confiable, para salvar vidas y mejorar la calidad de vida de quienes más lo necesitan.
                                </div>
                            </div>
                        </div>
                        <!-- Visión -->
                        <div class="accordion-item wow fadeIn" data-wow-delay="0.5s">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="fas fa-eye me-3 text-primary"></i> Nuestra Visión
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionMissionVision">
                                <div class="accordion-body">
                                    Ser la plataforma líder y el referente nacional en la gestión de donaciones de salud, reconocida por nuestro impacto social, transparencia e innovación, construyendo una comunidad donde la solidaridad sea un pilar fundamental del bienestar social en México.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mission and Vision End -->

    <!-- Values Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                <h2 class="display-6">Nuestros Valores</h2>
                <p>Son los pilares que guían cada una de nuestras acciones y decisiones.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="bg-light p-4 text-center rounded-3 h-100">
                        <div class="bg-primary d-inline-block p-3 rounded-pill mb-3">
                            <i class="fas fa-hands-helping fa-4x text-white"></i>
                        </div>
                        <h4 class="mb-3">Solidaridad</h4>
                        <p class="text-muted mb-0">Fomentamos la ayuda mutua y el apoyo incondicional como pilares de nuestra comunidad.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeIn" data-wow-delay="0.2s">
                    <div class="bg-light p-4 text-center rounded-3 h-100">
                        <div class="bg-primary d-inline-block p-3 rounded-pill mb-3">
                            <i class="fas fa-heartbeat fa-4x text-white"></i>
                        </div>
                        <h4 class="mb-3">Empatía</h4>
                        <p class="text-muted mb-0">Entendemos las necesidades de los demás y actuamos con compasión para marcar una diferencia real.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeIn" data-wow-delay="0.3s">
                    <div class="bg-light p-4 text-center rounded-3 h-100">
                        <div class="bg-primary d-inline-block p-3 rounded-pill mb-3">
                            <i class="fas fa-check-circle fa-4x text-white"></i>
                        </div>
                        <h4 class="mb-3">Confianza</h4>
                        <p class="text-muted mb-0">Garantizamos la seguridad y transparencia en cada donación para construir un entorno fiable.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeIn" data-wow-delay="0.4s">
                    <div class="bg-light p-4 text-center rounded-3 h-100">
                        <div class="bg-primary d-inline-block p-3 rounded-pill mb-3">
                            <i class="fas fa-handshake fa-4x text-white"></i>
                        </div>
                        <h4 class="mb-3">Compromiso</h4>
                        <p class="text-muted mb-0">Estamos dedicados a nuestra misión, trabajando con perseverancia para superar cualquier obstáculo.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeIn" data-wow-delay="0.5s">
                    <div class="bg-light p-4 text-center rounded-3 h-100">
                        <div class="bg-primary d-inline-block p-3 rounded-pill mb-3">
                            <i class="fas fa-lightbulb fa-4x text-white"></i>
                        </div>
                        <h4 class="mb-3">Innovación</h4>
                        <p class="text-muted mb-0">Buscamos constantemente nuevas y mejores formas de usar la tecnología para salvar vidas.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeIn" data-wow-delay="0.6s">
                    <div class="bg-light p-4 text-center rounded-3 h-100">
                        <div class="bg-primary d-inline-block p-3 rounded-pill mb-3">
                            <i class="fas fa-glasses fa-4x text-white"></i>
                        </div>
                        <h4 class="mb-3">Transparencia</h4>
                        <p class="text-muted mb-0">Operamos con claridad, asegurando que cada acción y donación sea visible y rastreable.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Values End -->

    <!-- Call to Action Start -->
    <div class="container-fluid bg-primary my-5 p-5">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 text-white mb-3 wow fadeIn" data-wow-delay="0.2s">Una Dosys de esperanza, puede cambiar vidas</h2>
                    <p class="text-white mb-4 wow fadeIn" data-wow-delay="0.4s">Tu donación es más que un acto de generosidad; es un mensaje de que nadie está solo. Únete a nuestra comunidad y sé parte del cambio.</p>
                    <a href="r_seleccionar_tipo.html" class="btn btn-light rounded-pill py-3 px-5 wow fadeIn" data-wow-delay="0.6s">Conviértete en Donante</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Call to Action End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
          
</body>
</html>
