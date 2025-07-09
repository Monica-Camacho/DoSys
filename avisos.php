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
        <title>DoSys - Avisos de Donación</title>
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
         
    <!-- MENÚ PRINCIPAL -->
    <!-- Navbar & Hero Start -->
    <div class="container-fluid nav-bar px-0 px-lg-4 py-lg-0">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light"> 
                <a href="index.html" class="navbar-brand p-0">
                    <img src="img/logos/DoSys_largo_fondoTransparente.png" alt="DoSys_Logo" class="img-fluid">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-0 mx-lg-auto">
                        <!-- Botones del menú -->
                        <a href="index.html" class="nav-item nav-link">Inicio</a>
                        <a href="avisos.html" class="nav-item nav-link active">Avisos de Donación</a>
                        <a href="mapa.php" class="nav-item nav-link">Mapa</a>
                        <a href="estadisticas.html" class="nav-item nav-link">Estadísticas</a>
                        <!-- Más botones del menú -->
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                <span class="dropdown-toggle">Conócenos</span>
                            </a>
                            <div class="dropdown-menu">
                                <a href="C-Sobre_Nosotros.html" class="dropdown-item">Sobre Nosotros</a>
                                <a href="C-Nuestro_Equipo.html" class="dropdown-item">Nuestro Equipo</a>
                                <a href="C-Nuestro_Equipo.html" class="dropdown-item">Logros</a>
                                <a href="index.html" class="dropdown-item">Empresas Aliadas</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center ms-lg-4">
                        <a href="login.php" class="btn btn-primary rounded-pill py-2 px-4 me-2 text-nowrap">Iniciar Sesión</a>
                        <a href="r_seleccionar_tipo.html" class="btn btn-outline-primary rounded-pill py-2 px-4">Regístrate</a>
                   </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar & Hero End -->

    <!-- Notices Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="d-lg-flex justify-content-between align-items-center mb-5">
                <div class="text-center text-lg-start">
                    <h1 class="display-5">Avisos de Donación</h1>
                    <p class="fs-5 text-muted mb-0">Explora las solicitudes activas y encuentra una causa a la que puedas apoyar.</p>
                </div>
                <div class="text-center mt-4 mt-lg-0">
                    <a href="segmentos.html" class="btn btn-success rounded-pill py-2 px-4"><i class="fas fa-plus me-2"></i>Solicitar Donación</a>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="row mb-5">
                <div class="col-12">
                    <form class="row g-3 align-items-center bg-white p-3 rounded shadow-sm">
                        <div class="col-lg-5 col-md-12">
                            <input type="text" class="form-control" placeholder="Buscar por palabra clave...">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <select class="form-select">
                                <option selected>Tipo de donación...</option>
                                <option value="all">Todos</option>
                                <option value="sangre">Sangre</option>
                                <option value="medicamentos">Medicamentos</option>
                                <option value="dispositivos">Dispositivos</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                             <select class="form-select">
                                <option selected>Ubicación...</option>
                                <option value="centro">Centro</option>
                                <option value="cardenas">Cárdenas</option>
                                <option value="comalcalco">Comalcalco</option>
                                <option value="paraiso">Paraíso</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-12 text-end">
                            <button type="submit" class="btn btn-primary w-100">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                <!-- Notice Card 1: Sangre -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s" data-category="sangre">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="position-absolute top-0 end-0 p-3">
                                <i class="fas fa-tint fa-2x text-danger"></i>
                            </div>
                            <h5 class="card-title mt-5">Solicitud de Sangre O+</h5>
                            <p class="card-text text-muted small mb-3"><i class="fas fa-map-marker-alt me-2"></i>Villahermosa, Tabasco</p>
                            <p class="card-text">Se necesitan donantes para una transfusión. Tu donación es vital para ayudar a quien lo necesita.</p>
                            <div class="mt-auto pt-3">
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted small">2 de 8 unidades recolectadas</p>
                                <a href="avisos_detalles.php" class="btn btn-primary rounded-pill w-100">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notice Card 2: Medicamentos -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s" data-category="medicamentos">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column p-4">
                             <div class="position-absolute top-0 end-0 p-3">
                                <i class="fas fa-pills fa-2x text-primary"></i>
                            </div>
                            <h5 class="card-title mt-5">Solicitud de Insulina</h5>
                            <p class="card-text text-muted small mb-3"><i class="fas fa-map-marker-alt me-2"></i>Comalcalco, Tabasco</p>
                            <p class="card-text">Se requiere donación de insulina glargina para un tratamiento médico. Cualquier cantidad es de gran ayuda.</p>
                             <div class="mt-auto pt-3">
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted small">3 de 5 cajas obtenidas</p>
                                <a href="avisos_detalles.php" class="btn btn-primary rounded-pill w-100">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notice Card 3: Dispositivos -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s" data-category="dispositivos">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="position-absolute top-0 end-0 p-3">
                                <i class="fas fa-wheelchair fa-2x text-warning"></i>
                            </div>
                            <h5 class="card-title mt-5">Solicitud de Silla de Ruedas</h5>
                            <p class="card-text text-muted small mb-3"><i class="fas fa-map-marker-alt me-2"></i>Cárdenas, Tabasco</p>
                            <p class="card-text">Se necesita una silla de ruedas en buen estado para mejorar la movilidad y calidad de vida de una persona.</p>
                             <div class="mt-auto pt-3">
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted small">0 de 1 silla obtenida</p>
                                <a href="avisos_detalles.php" class="btn btn-primary rounded-pill w-100">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Add more notice cards as needed -->

            </div>
        </div>
    </div>
    <!-- Notices End -->
        
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

    
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="node_modules/axe-core/axe.min.js"></script>
    

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
</body>

</html>
