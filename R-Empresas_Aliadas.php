<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Empresas Aliadas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/logos/dosyslogochico.ico">

    <!-- Google Web Fonts, Iconos y Estilos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Librerías de Estilos -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Estilos de Bootstrap y de la Plantilla -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .empresa-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .empresa-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
        }
        .empresa-logo-container {
            height: 150px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            border-bottom: 1px solid #eee;
        }
        .empresa-logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
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

    <!-- Navbar -->
    <div class="container-fluid nav-bar px-0 px-lg-4 py-lg-0">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light"> 
                <a href="index.html" class="navbar-brand p-0">
                    <img src="img/logos/DoSys_largo_fondoTransparente.png" alt="DoSys_Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="index.html" class="nav-item nav-link">Inicio</a>
                        <a href="avisos.html" class="nav-item nav-link">Avisos de Donación</a>
                        <a href="mapa.php" class="nav-item nav-link">Mapa</a>
                        <a href="estadisticas.html" class="nav-item nav-link">Estadísticas</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link active" data-bs-toggle="dropdown">
                                <span class="dropdown-toggle">Conócenos</span>
                            </a>
                            <div class="dropdown-menu">
                                <a href="C-Sobre_Nosotros.html" class="dropdown-item">Sobre Nosotros</a>
                                <a href="C-Nuestro_Equipo.html" class="dropdown-item">Nuestro Equipo</a>
                                <a href="C-Logros.html" class="dropdown-item">Logros</a>
                                <a href="R-Empresas_Aliadas.php" class="dropdown-item active">Empresas Aliadas</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center ms-lg-4">
                        <a href="login.php" class="btn btn-primary rounded-pill py-2 px-4 me-2 text-nowrap">Iniciar Sesión</a>
                        <a href="r_seleccionar_tipo.html" class="btn btn-outline-primary rounded-pill py-2 px-4">Regístrate</a>
                   </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container text-center">
            <div>
                <h1 class='display-5 mb-0'>Nuestras Empresas Aliadas</h1>
                <p class="fs-5 text-muted mb-0">Organizaciones que fortalecen nuestra misión con su apoyo y confianza.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Descripción -->
    <div class="container-fluid py-4">
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-12">
                    <div style="text-align: justify;">
                        <p class="lead">
                        Son organizaciones que, aunque no gestionan directamente donaciones de sangre, medicamentos o dispositivos de asistencia, contribuyen significativamente al propósito de DoSys. Su apoyo se refleja en beneficios exclusivos para los usuarios, como descuentos en farmacias, transporte subsidiado, consultas médicas a bajo costo, difusión de campañas, asesoría legal y más. A través de su colaboración, facilitan y motivan la participación en la donación, mejorando el acceso a recursos esenciales para quienes los necesitan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Empresas Aliadas Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <?php
            // Incluir el archivo de conexión
            include 'conexion_local.php';

            // Realizar la consulta a la tabla "empresas"
            $query = "SELECT * FROM empresas";
            $result = $conexion->query($query);

            if ($result && $result->num_rows > 0) {
                $empresas = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $empresas = [];
            }
            $conexion->close();

            // Helper para obtener el tipo de apoyo y su color
            function getTipoApoyoInfo($tipo_id) {
                switch ($tipo_id) {
                    case 1: return ['texto' => 'Beneficios y Descuentos', 'color' => 'success'];
                    case 2: return ['texto' => 'Apoyo Logístico', 'color' => 'info'];
                    case 3: return ['texto' => 'Servicios de Salud', 'color' => 'danger'];
                    case 4: return ['texto' => 'Difusión y Educación', 'color' => 'primary'];
                    case 5: return ['texto' => 'Apoyo Tecnológico', 'color' => 'secondary'];
                    case 6: return ['texto' => 'Oportunidades Laborales', 'color' => 'warning'];
                    default: return ['texto' => 'Colaborador', 'color' => 'dark'];
                }
            }
            ?>

            <div class="row g-4 justify-content-center">
                <?php if (!empty($empresas)): ?>
                    <?php foreach ($empresas as $index => $empresa): ?>
                        <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="<?php echo ($index * 0.1) + 0.1; ?>s">
                            <div class="card h-100 shadow-sm border-0 rounded-3 empresa-card">
                                <div class="empresa-logo-container">
                                    <img src="img/Red_Apoyo/Empresas_Aliadas/<?php echo htmlspecialchars($empresa['imagen']); ?>.png" alt="Logo de <?php echo htmlspecialchars($empresa['nombre_empresa']); ?>">
                                </div>
                                <div class="card-body p-4">
                                    <?php $apoyoInfo = getTipoApoyoInfo($empresa['tipo_apoyo_id']); ?>
                                    <span class="badge bg-<?php echo $apoyoInfo['color']; ?>-subtle text-<?php echo $apoyoInfo['color']; ?> mb-2"><?php echo $apoyoInfo['texto']; ?></span>
                                    <h5 class="card-title mb-2"><?php echo htmlspecialchars($empresa['nombre_empresa']); ?></h5>
                                    <p class="card-text text-muted"><?php echo htmlspecialchars($empresa['descripcion']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="lead">Actualmente no hay empresas aliadas para mostrar. ¡Sé la primera en unirte!</p>
                        <a href="r_empresa.html" class="btn btn-primary rounded-pill py-3 px-5">Únete como Empresa</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Empresas Aliadas End -->

    <!-- Footer -->
    <div class="container-fluid bg-dark text-light footer pt-5 wow fadeIn" data-wow-delay="0.2s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Información Legal</h4>
                        <a class="btn-link" href="#"><i class="fas fa-angle-right me-2"></i> Términos y Condiciones</a>
                        <a class="btn-link" href="#"><i class="fas fa-angle-right me-2"></i> Política de Privacidad</a>
                        <a class="btn-link" href="#"><i class="fas fa-angle-right me-2"></i> Aviso Legal</a>
                    </div>
                </div>
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
    <script src="js/main.js"></script>

</body>
</html>
