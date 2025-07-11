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
    <title>DoSys - Perfil de Organización</title>
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
                <h1 class='display-5 mb-0'>Perfil de la Organización</h1>
                <p class="fs-5 text-muted mb-0">Administra la información pública y de contacto de tu organización.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Profile Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Profile Picture Column -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm text-center p-4 h-100">
                        <img src="img/Red_Apoyo/Empresas_Aliadas/Bioplasti-ka.png" class="img-fluid rounded-circle mx-auto mb-3" alt="Logo de la Organización" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 class="mb-1">Nombre de la Organización</h4>
                        <p class="text-muted">Organización Verificada</p>
                        <button class="btn btn-primary btn-sm mt-2">Cambiar Logo</button>
                    </div>
                </div>

                <!-- Profile Tabs Column -->
                <div class="col-lg-8">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs nav-pills nav-fill mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="org-tab" data-bs-toggle="tab" data-bs-target="#org" type="button" role="tab" aria-controls="org" aria-selected="true">
                                <i class="fas fa-sitemap me-2"></i>Perfil de Organización
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="representative-tab" data-bs-toggle="tab" data-bs-target="#representative" type="button" role="tab" aria-controls="representative" aria-selected="false">
                                <i class="fas fa-user-tie me-2"></i>Representante
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                                <i class="fas fa-shield-alt me-2"></i>Seguridad
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Organization Info Tab -->
                        <div class="tab-pane fade show active" id="org" role="tabpanel" aria-labelledby="org-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Datos de la Organización</h5>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="nombre_org" class="form-label">Nombre de la Organización</label>
                                                <input type="text" class="form-control" id="nombre_org" value="Ayuda y Corazón A.C." disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cluni" class="form-label">CLUNI</label>
                                                <input type="text" class="form-control" id="cluni" value="ABC123456789" disabled>
                                            </div>
                                            <div class="col-12">
                                                <label for="direccion_org" class="form-label">Dirección</label>
                                                <textarea class="form-control" id="direccion_org" rows="2" disabled>Calle de la Esperanza 789, Col. Solidaridad, Villahermosa, Tabasco.</textarea>
                                            </div>
                                            <div class="col-12">
                                                <label for="mision_org" class="form-label">Misión de la Organización</label>
                                                <textarea class="form-control" id="mision_org" rows="3" disabled>Brindar apoyo a comunidades vulnerables a través de la gestión de donaciones y voluntariado.</textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Legal Representative Tab -->
                        <div class="tab-pane fade" id="representative" role="tabpanel" aria-labelledby="representative-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Datos del Representante</h5>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="rep_nombre" class="form-label">Nombre(s)</label>
                                                <input type="text" class="form-control" id="rep_nombre" value="Roberto" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="rep_apellidos" class="form-label">Apellidos</label>
                                                <input type="text" class="form-control" id="rep_apellidos" value="Gómez Bolaños" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="rep_email" class="form-label">Correo Electrónico</label>
                                                <input type="email" class="form-control" id="rep_email" value="roberto.gomez@ayudaycorazon.org" disabled>
                                            </div>
                                             <div class="col-md-6">
                                                <label for="rep_telefono" class="form-label">Teléfono</label>
                                                <input type="tel" class="form-control" id="rep_telefono" value="993-444-5566" disabled>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Cambiar Correo y Contraseña de la Cuenta</h5>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="email_cuenta" class="form-label">Correo de la Cuenta</label>
                                                <input type="email" class="form-control" id="email_cuenta" value="contacto@ayudaycorazon.org" disabled>
                                            </div>
                                            <hr>
                                            <div class="col-md-6">
                                                <label for="current_password" class="form-label">Contraseña Actual</label>
                                                <input type="password" class="form-control" id="current_password" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="new_password" class="form-label">Nueva Contraseña</label>
                                                <input type="password" class="form-control" id="new_password" disabled>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-secondary">Editar Perfil</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Content End -->
        
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
