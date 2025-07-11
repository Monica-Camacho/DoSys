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
    <title>DoSys - Nuestro Equipo</title>
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

    <!-- Estilos para el efecto hover y uniformidad de imágenes -->
    <style>
        .team-item .team-img {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem 0.5rem 0 0;
            height: 300px; /* Altura fija para el contenedor de la imagen */
        }
        .team-item .team-img img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* La imagen cubre el contenedor, recortándose si es necesario */
            object-position: center top; /* Centra la imagen, priorizando la parte superior */
            transition: transform 0.5s ease;
        }
        .team-item:hover .team-img img {
            transform: scale(1.1); /* Efecto de zoom sutil al pasar el mouse */
        }
        .team-item .team-img::after {
            position: absolute;
            content: "";
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(6, 163, 218, 0.7);
            opacity: 0;
            transition: 0.5s;
        }
        .team-item:hover .team-img::after {
            opacity: 1;
        }
        .team-item .team-social {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: 0.5s;
            z-index: 1;
        }
        .team-item:hover .team-social {
            opacity: 1;
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
                            <a href="mapa.php" class="text-muted small"><i class="fas fa-map-marker-alt text-primary me-2"></i>Lugares de donación</a>
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

    <!-- Header Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container text-center">
            <div>
                <h1 class='display-5 mb-0'>Las Mentes y Corazones detrás de DoSys</h1>
                <p class="fs-5 text-muted mb-0">Un equipo apasionado por la tecnología y el impacto social.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Miembros Activos Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                <h2 class="display-6">Miembros Activos</h2>
                <p>El núcleo de DoSys, impulsando la visión desde el primer día.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <!-- Mónica Camacho -->
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="team-item bg-light rounded-3 h-100 shadow-sm border">
                        <div class="team-img">
                            <img src="img/Conocenos/Integrantes/Monica.jpg" class="img-fluid" alt="Mónica Camacho">
                            <div class="team-social">
                                <a class="btn btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h5 class="mb-1">Mónica Camacho</h5>
                            <p class="text-muted m-0">CEO & Líder de Proyecto</p>
                        </div>
                    </div>
                </div>
                <!-- Gabriel Zárate -->
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.2s">
                    <div class="team-item bg-light rounded-3 h-100 shadow-sm border">
                        <div class="team-img">
                            <img src="img/Conocenos/Integrantes/Zarate.jpg" class="img-fluid" alt="Gabriel Zárate">
                            <div class="team-social">
                                <a class="btn btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h5 class="mb-1">Gabriel Zárate</h5>
                            <p class="text-muted m-0">Director de Desarrollo de Aplicaciones</p>
                        </div>
                    </div>
                </div>
                <!-- Freddy Vela -->
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                    <div class="team-item bg-light rounded-3 h-100 shadow-sm border">
                        <div class="team-img">
                            <img src="img/Conocenos/Integrantes/Freddy.jpg" class="img-fluid" alt="Freddy Vela">
                            <div class="team-social">
                                <a class="btn btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h5 class="mb-1">Freddy Vela</h5>
                            <p class="text-muted m-0">Director de Relaciones con Asociaciones</p>
                        </div>
                    </div>
                </div>
                <!-- Sergio Jimenez -->
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.4s">
                    <div class="team-item bg-light rounded-3 h-100 shadow-sm border">
                        <div class="team-img">
                            <img src="img/Conocenos/Integrantes/Sergio.jpg" class="img-fluid" alt="Sergio Jimenez">
                            <div class="team-social">
                                <a class="btn btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h5 class="mb-1">Sergio Jimenez</h5>
                            <p class="text-muted m-0">Director de Recursos Humanos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Miembros Activos End -->

    <!-- Asesoras Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                <h2 class="display-6">Asesoras</h2>
                <p>La experiencia y guía que nos ayudan a crecer y tomar las mejores decisiones.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <!-- M.A. Arcely Aquino Ruíz -->
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="team-item bg-white rounded-3 h-100 shadow-sm border">
                        <div class="team-img">
                            <img src="img/Conocenos/Integrantes/Arcely.jpg" class="img-fluid" alt="M.A. Arcely Aquino Ruíz">
                            <div class="team-social">
                                <a class="btn btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h5 class="mb-1">M.A. Arcely Aquino Ruíz</h5>
                            <p class="text-muted m-0">Asesora Principal</p>
                        </div>
                    </div>
                </div>
                <!-- M.I.S. Dulce María León de la O. -->
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.2s">
                    <div class="team-item bg-white rounded-3 h-100 shadow-sm border">
                        <div class="team-img">
                            <img src="img/Conocenos/Integrantes/Dulce.jpg" class="img-fluid" alt="M.I.S. Dulce María León de la O.">
                            <div class="team-social">
                                <a class="btn btn-primary btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h5 class="mb-1">M.I.S. Dulce María León de la O.</h5>
                            <p class="text-muted m-0">Asesora</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Asesoras End -->

    <!-- Call to Action Start -->
    <div class="container-fluid bg-primary my-5 p-5">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 text-white mb-3 wow fadeIn" data-wow-delay="0.2s">¿Quieres ser parte del cambio?</h2>
                    <p class="text-white mb-4 wow fadeIn" data-wow-delay="0.4s">Siempre estamos buscando personas talentosas y apasionadas que quieran unirse a nuestra misión. Si crees que puedes aportar, nos encantaría saber de ti.</p>
                    <a href="mailto:contacto@dosys.mx" class="btn btn-light rounded-pill py-3 px-5 wow fadeIn" data-wow-delay="0.6s">Contáctanos</a>
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
          
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

</body>
</html>
