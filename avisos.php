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
        <?php require_once 'templates/topbar.php'; ?>
        <!-- Topbar End -->  
         
        <!-- Navbar Start -->
        <?php require_once 'templates/navbar.php'; ?>
        <!-- Navbar End -->

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
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="node_modules/axe-core/axe.min.js"></script>
    

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
</body>

</html>
