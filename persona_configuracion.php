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
    <title>DoSys - Configuración</title>
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
                <h1 class='display-5 mb-0'>Configuración de la Cuenta</h1>
                <p class="fs-5 text-muted mb-0">Ajusta las preferencias de tu cuenta, notificaciones y privacidad.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Settings Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <!-- Settings Navigation -->
                <div class="col-md-3">
                    <div class="list-group" id="settings-menu" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="v-pills-notifications-tab" data-bs-toggle="pill" href="#v-pills-notifications" role="tab" aria-controls="v-pills-notifications" aria-selected="true">
                            <i class="fas fa-bell me-2"></i>Notificaciones
                        </a>
                        <a class="list-group-item list-group-item-action" id="v-pills-privacy-tab" data-bs-toggle="pill" href="#v-pills-privacy" role="tab" aria-controls="v-pills-privacy" aria-selected="false">
                            <i class="fas fa-user-shield me-2"></i>Privacidad
                        </a>
                        <a class="list-group-item list-group-item-action" id="v-pills-account-tab" data-bs-toggle="pill" href="#v-pills-account" role="tab" aria-controls="v-pills-account" aria-selected="false">
                            <i class="fas fa-user-cog me-2"></i>Cuenta
                        </a>
                    </div>
                </div>

                <!-- Settings Content -->
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <!-- Notifications Tab -->
                        <div class="tab-pane fade show active" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Gestión de Notificaciones</h5>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" id="notifSolicitudes" checked>
                                        <label class="form-check-label" for="notifSolicitudes">Actualizaciones sobre mis solicitudes</label>
                                        <small class="d-block text-muted">Recibe un correo cuando alguien done a tu solicitud o se complete.</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="notifPromociones">
                                        <label class="form-check-label" for="notifPromociones">Noticias y promociones de DoSys</label>
                                        <small class="d-block text-muted">Mantente al día con las novedades y beneficios de empresas aliadas.</small>
                                    </div>
                                    <div class="text-end mt-4">
                                        <button class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Tab -->
                        <div class="tab-pane fade" id="v-pills-privacy" role="tabpanel" aria-labelledby="v-pills-privacy-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Privacidad de la Cuenta</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="privacyOptions" id="privacyPublic" checked>
                                        <label class="form-check-label" for="privacyPublic">
                                            Perfil Público
                                        </label>
                                        <small class="d-block text-muted">Otros usuarios pueden ver tu nombre y foto si realizas una donación a su solicitud.</small>
                                    </div>
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="radio" name="privacyOptions" id="privacyPrivate">
                                        <label class="form-check-label" for="privacyPrivate">
                                            Perfil Privado
                                        </label>
                                        <small class="d-block text-muted">Tu nombre se mostrará como "Anónimo" en las donaciones públicas.</small>
                                    </div>
                                    <div class="text-end mt-4">
                                        <button class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Tab (Danger Zone) -->
                        <div class="tab-pane fade" id="v-pills-account" role="tabpanel" aria-labelledby="v-pills-account-tab">
                            <div class="card border-danger shadow-sm">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Zona de Peligro</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6>Desactivar cuenta</h6>
                                            <p class="text-muted mb-0 small">Puedes reactivar tu cuenta en cualquier momento.</p>
                                        </div>
                                        <button class="btn btn-warning">Desactivar</button>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>Eliminar cuenta</h6>
                                            <p class="text-muted mb-0 small">Esta acción es permanente y no se puede deshacer.</p>
                                        </div>
                                        <button class="btn btn-danger">Eliminar mi cuenta</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Settings Content End -->
        
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
