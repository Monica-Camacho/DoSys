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
                            <a href="persona_perfil.php" class="dropdown-item">Mi Perfil</a>
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
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class='display-5 mb-0'>¡Hola, Usuario de DoSys!</h1>
                    <p class="fs-5 text-muted mb-0">Bienvenido a tu panel de control.</p>
                </div>
                <a href="segmentos.html" class="btn btn-primary rounded-pill py-2 px-4 d-none d-lg-block"><i class="fas fa-plus me-2"></i>Crear Nueva Solicitud</a>
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
                                    <a href="segmentos.html" class="btn btn-success btn-lg w-100 py-3"><i class="fas fa-bullhorn me-2"></i>Crear Solicitud</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="avisos.html" class="btn btn-info btn-lg w-100 py-3 text-white"><i class="fas fa-eye me-2"></i>Ver Avisos Urgentes</a>
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

