<!DOCTYPE html>
<html lang="es">

    <head>
        <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
        <meta charset="utf-8">
        <title>DoSys - Tipo de Registro</title>
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
            .selection-card-v4 {
                border: 1px solid #e9ecef;
                border-top-width: 4px;
                border-radius: 0.5rem;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                background-color: #fff;
            }
            .selection-card-v4:hover {
                transform: translateY(-8px);
                box-shadow: 0 18px 30px rgba(0,0,0,0.1);
            }
            .selection-card-v4 .icon-container {
                font-size: 3rem;
                line-height: 1;
            }
            .selection-card-v4 .btn-outline-custom {
                border-width: 2px;
                font-weight: 600;
                padding: 0.6rem 1.5rem;
            }
            .selection-card-v4 .btn-outline-custom:hover {
                color: #fff;
            }

            /* Card specific styles */
            .card-user { border-top-color: #00A99D; }
            .card-user .icon-container { color: #00A99D; }
            .card-user .btn-outline-custom { border-color: #00A99D; color: #00A99D; }
            .card-user .btn-outline-custom:hover { background-color: #00A99D; }

            .card-company { border-top-color: #6A0DAD; }
            .card-company .icon-container { color: #6A0DAD; }
            .card-company .btn-outline-custom { border-color: #6A0DAD; color: #6A0DAD; }
            .card-company .btn-outline-custom:hover { background-color: #6A0DAD; }

            .card-org { border-top-color: #28a745; }
            .card-org .icon-container { color: #28a745; }
            .card-org .btn-outline-custom { border-color: #28a745; color: #28a745; }
            .card-org .btn-outline-custom:hover { background-color: #28a745; }
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

    <!-- Selection Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="text-center mx-auto" style="max-width: 800px;">
                <h1 class="display-5 mb-3">Regístrate</h1>
                <p class="fs-5 text-muted mb-5">Cada cuenta está diseñada para una necesidad específica dentro de nuestra comunidad.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <!-- Persona Card -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card selection-card-v4 card-user h-100 text-center">
                        <div class="card-body d-flex flex-column p-4 p-lg-5">
                            <div class="icon-container mb-4">
                                <i class="fa fa-user"></i>
                            </div>
                            <h4 class="card-title mb-3">Persona</h4>
                            <p class="card-text text-muted flex-grow-1">Regístrate para ofrecer ayuda como donante o para recibirla como donatario.</p>
                            <a href="r_persona.html" class="btn btn-outline-custom rounded-pill mt-auto">Soy Persona <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Empresa Card -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="card selection-card-v4 card-company h-100 text-center">
                        <div class="card-body d-flex flex-column p-4 p-lg-5">
                            <div class="icon-container mb-4">
                                <i class="fa fa-building"></i>
                            </div>
                            <h4 class="card-title mb-3">Empresa</h4>
                            <p class="card-text text-muted flex-grow-1">Ofrece promociones y beneficios a los donantes de la comunidad.</p>
                            <a href="r_empresa.html" class="btn btn-outline-custom rounded-pill mt-auto">Soy Empresa <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Organización Altruista Card -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card selection-card-v4 card-org h-100 text-center">
                        <div class="card-body d-flex flex-column p-4 p-lg-5">
                            <div class="icon-container mb-4">
                                <i class="fa fa-hands-helping"></i>
                            </div>
                            <h4 class="card-title mb-3">Organización Altruista</h4>
                            <p class="card-text text-muted flex-grow-1">Gestiona la ayuda recibida y las solicitudes de tu organización.</p>
                            <a href="r_organizacion.html" class="btn btn-outline-custom rounded-pill mt-auto">Soy Organización <i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Selection End -->
        
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
