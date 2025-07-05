<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Mi Perfil</title>
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
                    <div class="navbar-nav mx-auto">
                        <!-- Menú de navegación principal (público) -->
                        <a href="index.html" class="nav-item nav-link">Inicio</a>
                        <a href="avisos.html" class="nav-item nav-link">Avisos de Donación</a>
                        <a href="mapa.php" class="nav-item nav-link">Mapa</a>
                        <a href="estadisticas.html" class="nav-item nav-link">Estadísticas</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                <span class="dropdown-toggle">Conócenos</span>
                            </a>
                            <div class="dropdown-menu">
                                <a href="C-Sobre_Nosotros.html" class="dropdown-item">Sobre Nosotros</a>
                                <a href="C-Nuestro_Equipo.html" class="dropdown-item">Nuestro Equipo</a>
                                <a href="C-Logros.html" class="dropdown-item">Logros</a>
                                <a href="R-Empresas_Aliadas.php" class="dropdown-item">Empresas Aliadas</a>
                            </div>
                        </div>
                    </div>
                    <!-- Menú de Usuario Desplegable -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-2"></i> Usuario de DoSys
                        </a>
                        <div class="dropdown-menu dropdown-menu-end m-0">
                            <a href="persona_dashboard.php" class="dropdown-item">Mi Panel</a>
                            <a href="persona_perfil.php" class="dropdown-item active">Editar Perfil</a>
                            <a href="persona_configuracion.php" class="dropdown-item">Configuración</a>
                            <hr class="dropdown-divider">
                            <a href="logout.php" class="dropdown-item text-danger">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Mi Perfil</h1>
                <p class="fs-5 text-muted mb-0">Administra tu información personal, médica y de seguridad.</p>
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
                        <img src="img/team-1.jpg" class="img-fluid rounded-circle mx-auto mb-3" alt="Foto de Perfil" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 class="mb-1">Usuario de DoSys</h4>
                        <p class="text-muted">Donante Activo</p>
                        <button class="btn btn-primary btn-sm mt-2">Cambiar Foto</button>
                    </div>
                </div>

                <!-- Profile Tabs Column -->
                <div class="col-lg-8">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs nav-pills nav-fill mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                                <i class="fas fa-user-edit me-2"></i>Información Personal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab" aria-controls="medical" aria-selected="false">
                                <i class="fas fa-briefcase-medical me-2"></i>Información Médica
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
                        <!-- Personal Info Tab -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Datos Personales</h5>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="nombre" class="form-label">Nombre(s)</label>
                                                <input type="text" class="form-control" id="nombre" value="Juan" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                                <input type="text" class="form-control" id="apellido_paterno" value="Pérez" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                                <input type="text" class="form-control" id="apellido_materno" value="García" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                                <input type="date" class="form-control" id="fecha_nacimiento" value="1990-05-15" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="sexo" class="form-label">Sexo al Nacer</label>
                                                <select id="sexo" class="form-select" disabled>
                                                    <option>Femenino</option>
                                                    <option selected>Masculino</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label for="direccion" class="form-label">Dirección</label>
                                                <textarea class="form-control" id="direccion" rows="2" disabled>Calle Ficticia 123, Colonia Centro, Villahermosa, Tabasco.</textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Info Tab -->
                        <div class="tab-pane fade" id="medical" role="tabpanel" aria-labelledby="medical-tab">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Datos Médicos y Documentos</h5>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
                                                <select id="tipo_sangre" class="form-select" disabled>
                                                    <option>A+</option>
                                                    <option selected>O+</option>
                                                    <option>B-</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="credencial_lector" class="form-label">Credencial del Lector (INE)</label>
                                                <input type="file" class="form-control" id="credencial_lector" disabled>
                                            </div>
                                            <div class="col-12">
                                                <label for="enfermedades" class="form-label">Enfermedades Crónicas</label>
                                                <textarea class="form-control" id="enfermedades" rows="2" disabled>Ninguna reportada.</textarea>
                                            </div>
                                            <div class="col-12">
                                                <label for="alergias" class="form-label">Alergias</label>
                                                <textarea class="form-control" id="alergias" rows="2" disabled>Alergia al polvo.</textarea>
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
                                    <h5 class="card-title mb-4">Cambiar Correo y Contraseña</h5>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="email" class="form-label">Correo Electrónico</label>
                                                <input type="email" class="form-control" id="email" value="juan.perez@email.com" disabled>
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
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
</body>

</html>
