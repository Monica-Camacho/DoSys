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
    <title>DoSys - Gestionar Solicitudes</title>
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
                <h1 class='display-5 mb-0'>Gestionar Solicitudes</h1>
                <p class="fs-5 text-muted mb-0">Valida, aprueba y da seguimiento a las solicitudes de ayuda.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Requests Management Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <!-- Nav Tabs -->
            <ul class="nav nav-pills nav-fill mb-4" id="requestsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active position-relative" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                        Por Validar
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">4</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="false">
                        Activas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">
                        Historial
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="requestsTabsContent">
                <!-- Pending Validation Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Solicitudes Pendientes de Validación</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Solicitante</th>
                                            <th>Fecha de Solicitud</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-tint text-danger me-2"></i>Sangre</td>
                                            <td>Juan Pérez García</td>
                                            <td>05/07/2025</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info me-1" title="Ver Detalles"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-success me-1" title="Aprobar"><i class="fas fa-check"></i></button>
                                                <button class="btn btn-sm btn-danger" title="Rechazar"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-pills text-primary me-2"></i>Medicamentos</td>
                                            <td>Ana López</td>
                                            <td>04/07/2025</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info me-1" title="Ver Detalles"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm btn-success me-1" title="Aprobar"><i class="fas fa-check"></i></button>
                                                <button class="btn btn-sm btn-danger" title="Rechazar"><i class="fas fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Tab -->
                <div class="tab-pane fade" id="active" role="tabpanel" aria-labelledby="active-tab">
                     <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Solicitudes Activas</h5>
                             <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Solicitante</th>
                                            <th>Progreso</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-wheelchair text-warning me-2"></i>Dispositivo</td>
                                            <td>Carlos Sánchez</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0/1</div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="organizacion_donantes.php" class="btn btn-sm btn-primary" title="Ver Donantes"><i class="fas fa-users"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Tab -->
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                     <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Historial de Solicitudes</h5>
                             <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Solicitante</th>
                                            <th>Fecha de Cierre</th>
                                            <th class="text-center">Estado Final</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-tint text-danger me-2"></i>Sangre</td>
                                            <td>Laura Méndez</td>
                                            <td>25/06/2025</td>
                                            <td class="text-center"><span class="badge bg-success">Completada</span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-pills text-primary me-2"></i>Medicamentos</td>
                                            <td>Pedro Infante</td>
                                            <td>15/06/2025</td>
                                            <td class="text-center"><span class="badge bg-secondary">Cerrada</span></td>
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
    <!-- Requests Management Content End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
    
</body>

</html>
