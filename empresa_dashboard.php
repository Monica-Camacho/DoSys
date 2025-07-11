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
    <title>DoSys - Panel de Empresa</title>
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
                <h1 class='display-5 mb-0'>Panel de Administrador</h1>
                <p class="fs-5 text-muted mb-0">Bienvenido, [Nombre del Admin]. Aquí tienes un resumen de la actividad de tu empresa.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Main Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <!-- KPIs -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-tags fa-3x text-primary mb-3"></i>
                            <h2 class="card-title">15</h2>
                            <p class="card-text text-muted">Beneficios Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h2 class="card-title">128</h2>
                            <p class="card-text text-muted">Beneficios Canjeados (Mes)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="card-body">
                            <i class="fas fa-users fa-3x text-info mb-3"></i>
                            <h2 class="card-title">8</h2>
                            <p class="card-text text-muted">Usuarios Registrados</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-5">
                <!-- Main Column -->
                <div class="col-lg-8">
                    <!-- Chart -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Actividad de Beneficios (Últimos 6 meses)</h5>
                            <!-- Simple Bar Chart Visualization -->
                            <div class="d-flex align-items-end justify-content-around" style="height: 250px; border: 1px solid #eee; padding: 20px 10px 0 10px; border-radius: .25rem;">
                                <div class="text-center">
                                    <div class="progress" style="height: 120px; width: 30px; writing-mode: vertical-lr;">
                                        <div class="progress-bar" role="progressbar" style="height: 60%; width: 100%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">Ene</small>
                                </div>
                                <div class="text-center">
                                     <div class="progress" style="height: 120px; width: 30px; writing-mode: vertical-lr;">
                                        <div class="progress-bar" role="progressbar" style="height: 80%; width: 100%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">Feb</small>
                                </div>
                                <div class="text-center">
                                     <div class="progress" style="height: 120px; width: 30px; writing-mode: vertical-lr;">
                                        <div class="progress-bar" role="progressbar" style="height: 75%; width: 100%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">Mar</small>
                                </div>
                                <div class="text-center">
                                     <div class="progress" style="height: 120px; width: 30px; writing-mode: vertical-lr;">
                                        <div class="progress-bar" role="progressbar" style="height: 90%; width: 100%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">Abr</small>
                                </div>
                                <div class="text-center">
                                     <div class="progress" style="height: 120px; width: 30px; writing-mode: vertical-lr;">
                                        <div class="progress-bar" role="progressbar" style="height: 85%; width: 100%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">May</small>
                                </div>
                                 <div class="text-center">
                                     <div class="progress" style="height: 120px; width: 30px; writing-mode: vertical-lr;">
                                        <div class="progress-bar" role="progressbar" style="height: 100%; width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">Jun</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Column -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Acciones Rápidas</h5>
                            <a href="empresa_beneficios.php" class="btn btn-primary w-100 mb-2">Gestionar Beneficios</a>
                            <a href="empresa_usuarios.php" class="btn btn-secondary w-100">Gestionar Usuarios</a>
                        </div>
                    </div>
                    <!-- Recent Users -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Usuarios Recientes</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Carlos Sánchez
                                    <span class="badge bg-success rounded-pill">Creador</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Laura Méndez
                                    <span class="badge bg-info rounded-pill">Visualizador</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Pedro Infante
                                    <span class="badge bg-info rounded-pill">Visualizador</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content End -->
        
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

