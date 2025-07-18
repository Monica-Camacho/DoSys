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
    <title>DoSys - Panel de Usuario</title>
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
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class='display-5 mb-0'>¡Hola, Usuario de DoSys!</h1>
                    <p class="fs-5 text-muted mb-0">Bienvenido a tu panel de control.</p>
                </div>
                <a href="segmentos.php" class="btn btn-primary rounded-pill py-2 px-4 d-none d-lg-block"><i class="fas fa-plus me-2"></i>Crear Nueva Solicitud</a>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Main Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">

                <!-- Columna Principal de Acciones -->
                <div class="col-lg-8">
                    <!-- Sección de Acciones Rápidas -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">Acciones Rápidas</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="segmentos.php" class="btn btn-success btn-lg w-100 py-3"><i class="fas fa-bullhorn me-2"></i>Crear Solicitud</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="avisos.php" class="btn btn-info btn-lg w-100 py-3 text-white"><i class="fas fa-eye me-2"></i>Ver Avisos Urgentes</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Solicitudes Activas -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Mis Solicitudes Activas</h4>
                            </div>
                            <!-- Filtros para Solicitudes -->
                            <form class="row g-2 mb-4">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Buscar por palabra clave...">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select">
                                        <option selected>Tipo...</option>
                                        <option value="sangre">Sangre</option>
                                        <option value="medicamentos">Medicamentos</option>
                                        <option value="dispositivos">Dispositivos</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                </div>
                            </form>
                            
                            <!-- Lista de Solicitudes -->
                            <div id="lista-solicitudes">
                                <!-- Ejemplo de solicitud de Sangre -->
                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                    <div class="flex-shrink-0 text-center" style="width: 50px;">
                                        <i class="fas fa-tint fa-2x text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-1">Solicitud de Sangre O+</h5>
                                            <small class="text-muted">Hace 2 días</small>
                                        </div>
                                        <p class="mb-1 text-muted">Progreso: 2 de 8 unidades.</p>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ejemplo de solicitud de Medicamentos -->
                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                    <div class="flex-shrink-0 text-center" style="width: 50px;">
                                        <i class="fas fa-pills fa-2x text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-1">Solicitud de Paracetamol</h5>
                                            <small class="text-muted">Hace 5 días</small>
                                        </div>
                                        <p class="mb-1 text-muted">Progreso: 5 de 10 cajas.</p>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                 <!-- Ejemplo de solicitud de Dispositivos -->
                                 <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 text-center" style="width: 50px;">
                                        <i class="fas fa-wheelchair fa-2x text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-1">Se necesita silla de ruedas</h5>
                                            <small class="text-muted">Hace 1 semana</small>
                                        </div>
                                        <p class="mb-1 text-muted">Progreso: 0 de 1.</p>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección de Donaciones Realizadas -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">Mis Donaciones Realizadas</h4>
                            
                            <!-- Filtros para Donaciones -->
                             <form class="row g-2 mb-4">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Buscar por hospital o producto...">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select">
                                        <option selected>Tipo...</option>
                                        <option value="sangre">Sangre</option>
                                        <option value="medicamentos">Medicamentos</option>
                                        <option value="dispositivos">Dispositivos</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                </div>
                            </form>

                            <!-- Lista de Donaciones -->
                            <div id="lista-donaciones">
                                <!-- Ejemplo de donación 1 -->
                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                    <div class="flex-shrink-0 text-center" style="width: 50px;">
                                        <i class="fas fa-hand-holding-heart fa-2x text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Donación de Sangre (O+)</h5>
                                        <p class="mb-0 text-muted">
                                            <i class="fas fa-hospital me-2"></i>Para: Hospital Rovirosa<br>
                                            <i class="fas fa-map-marker-alt me-2"></i>Ubicación: Villahermosa, Tab.<br>
                                            <i class="fas fa-calendar-alt me-2"></i>Fecha: 20 de Junio, 2024
                                        </p>
                                    </div>
                                </div>

                                <!-- Ejemplo de donación 2 -->
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 text-center" style="width: 50px;">
                                        <i class="fas fa-hand-holding-heart fa-2x text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Donación de Medicamentos</h5>
                                         <p class="mb-0 text-muted">
                                            <i class="fas fa-map-marker-alt me-2"></i>Lugar: Centro de Acopio DoSys<br>
                                            <i class="fas fa-calendar-alt me-2"></i>Fecha: 15 de Mayo, 2024
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barra Lateral -->
                <div class="col-lg-4">
                    <!-- Resumen de Actividad -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">Tu Impacto</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Donaciones Realizadas
                                    <span class="badge bg-primary rounded-pill">5</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Solicitudes Completadas
                                    <span class="badge bg-success rounded-pill">2</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Beneficios Obtenidos
                                    <span class="badge bg-warning rounded-pill text-dark">3</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Beneficios de Empresas Aliadas -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">Beneficios para Donantes</h4>
                            <p class="text-muted">Gracias a tu generosidad, tienes acceso a promociones de nuestras empresas aliadas.</p>
                            <a href="R-Empresas_Aliadas.php" class="btn btn-outline-primary w-100">Ver Beneficios</a>
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
          
</body>

</html>

