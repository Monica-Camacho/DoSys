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
    <title>DoSys - Gestionar Donantes</title>
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

    <!-- Header Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Gestionar Donantes</h1>
                <p class="fs-5 text-muted mb-0">Aprueba y da seguimiento a los donantes comprometidos con tus causas.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Donors Management Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <!-- Nav Tabs -->
            <ul class="nav nav-pills nav-fill mb-4" id="donorsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active position-relative" id="pending-donors-tab" data-bs-toggle="tab" data-bs-target="#pending-donors" type="button" role="tab" aria-controls="pending-donors" aria-selected="true">
                        Pendientes de Aprobación
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">7</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="in-progress-tab" data-bs-toggle="tab" data-bs-target="#in-progress" type="button" role="tab" aria-controls="in-progress" aria-selected="false">
                        En Proceso
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-donors-tab" data-bs-toggle="tab" data-bs-target="#completed-donors" type="button" role="tab" aria-controls="completed-donors" aria-selected="false">
                        Completados
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="donorsTabsContent">
                <!-- Pending Approval Tab -->
                <div class="tab-pane fade show active" id="pending-donors" role="tabpanel" aria-labelledby="pending-donors-tab">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Donantes Pendientes de Aprobación</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Donante</th>
                                            <th>Solicitud a la que apoya</th>
                                            <th>Fecha de Compromiso</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Maria López</td>
                                            <td>Silla de Ruedas para Carlos S.</td>
                                            <td>06/07/2025</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-success me-1" title="Aprobar Donante"><i class="fas fa-check"></i></button>
                                                <button class="btn btn-sm btn-danger" title="Rechazar Donante"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Progress Tab -->
                <div class="tab-pane fade" id="in-progress" role="tabpanel" aria-labelledby="in-progress-tab">
                     <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Donantes en Proceso</h5>
                             <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Donante</th>
                                            <th>Solicitud a la que apoya</th>
                                            <th>Fecha de Aprobación</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Juan Pérez García</td>
                                            <td>Medicamentos para Ana G.</td>
                                            <td>03/07/2025</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary" title="Marcar como Recibido"><i class="fas fa-gift"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Tab -->
                <div class="tab-pane fade" id="completed-donors" role="tabpanel" aria-labelledby="completed-donors-tab">
                     <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Historial de Donaciones Recibidas</h5>
                             <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Donante</th>
                                            <th>Solicitud Apoyada</th>
                                            <th>Fecha de Entrega</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Laura Méndez</td>
                                            <td>Sangre para Paciente X</td>
                                            <td>20/06/2025</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Donors Management Content End -->
        
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
    
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
</body>

</html>
