<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>DoSys - Empresas Aliadas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
    <link rel="stylesheet" href="css/R-Empresas.css">
</head>
<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- MENÚ PRINCIPAL -->
        <!-- Navbar & Hero Start -->
        <div class="container-fluid nav-bar px-0 px-lg-4 py-lg-0">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light"> 
                    <a href="index.html" class="navbar-brand p-0">
                        <img src="img/logos/DoSys_largo_fondoTransparente.png" alt="DoSys_Logo" >
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav mx-0 mx-lg-auto">
                            
                        <!-- Botones del menú -->
                        <a href="index.html" class="nav-item nav-link active">Inicio</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                <span class="dropdown-toggle">Donaciones</span>
                            </a>
                            <div class="dropdown-menu">
                                <a href="Donaciones.html" class="dropdown-item">Donar</a>
                                <a href="Mapa.php" class="dropdown-item">Mapa</a>
                            </div>
                        </div>

                            <!-- Boton desplegable novedades
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                    <span class="dropdown-toggle">Novedades</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="N-noticias.html" class="dropdown-item">Noticias</a>
                                    <a href="N-beneficios.html" class="dropdown-item">Beneficios</a>
                                    <a href="N-estadísticas.html" class="dropdown-item">Estadísticas</a>
                                </div>
                            </div>
                            <Fin Boton Novedades -->

                            <!-- Boton desplegable Red de Apoyo  -->
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                    <span class="dropdown-toggle">Red de Apoyo</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="R-Centros_Donacion.php" class="dropdown-item">Centros de Donación</a>
                                    <a href="R-Empresas_Aliadas.php" class="dropdown-item">Empresas Aliadas</a>
                                    <a href="R-Estaciones_Enlace.php" class="dropdown-item">Estaciones de Enlace</a>
                                </div>
                            </div>
                            <!-- Fin Boton Red de Apoyo -->

                            <!-- Boton desplegable Conócenos  -->
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                    <span class="dropdown-toggle">Conócenos</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="C-Sobre_Nosotros.html" class="dropdown-item">Sobre Nosotros</a>
                                    <a href="C-Nuestro_Equipo.html" class="dropdown-item">Nuestro Equipo</a>
                                    <a href="C-Logros.html" class="dropdown-item">Logros</a>
                                </div>
                            </div>
                            <!-- Fin Boton Conócenos -->

                            <!-- Seeción de Registrarse e Iniciar sesión  -->
                            <div class="nav-item dropdown">
                                <div class="nav-btn px-3">
                                    <a class="btn btn-primary rounded-pill py-2 px-4 ms-3 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#preRegistroModal">Pre-registrate</a>
                                </div>
                                </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar & Hero End -->

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb" style="padding: 50px 0;">
        <div class="container text-center" style="max-width: 1000px;">
            <h4 class="text-white display-4 mb-3">Empresas Aliadas</h4>
        </div>
    </div>
    <!-- Header End -->

    <!-- Sección Descripción con fondo blanco -->
    <section class="container-fluid py-4">
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

            <?php
        // Incluir el archivo de conexión
        include 'conexion_local.php';

        // Realizar la consulta a la tabla "empresas"
        $query = "SELECT * FROM empresas";
        $result = $conexion->query($query);

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            $empresas = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $empresas = [];
        }

        // Cerrar la conexión (opcional, se cierra automáticamente al final del script)
        $conexion->close();
        ?>

        <!-- Service Start -->
        <div class="container-fluid service py-3">
            <div class="container py-3">
                <div class="row g-4 justify-content-center">

                    <?php foreach ($empresas as $empresa): ?>
                        <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
                            <div class="service-item">
                                <div class="service-img">
                                    <!-- Construir la ruta de la imagen dinámicamente -->
                                    <?php
                                    $ruta_imagen = "img/Red_Apoyo/Empresas_Aliadas/" . $empresa['imagen'] . ".png";
                                    ?>
                                    <!-- Mostrar la imagen de la empresa -->
                                    <img src="<?php echo $ruta_imagen; ?>" class="img-fluid rounded-top w-100" alt="<?php echo $empresa['nombre_empresa']; ?>">
                                    <div class="service-icon p-3">
                                        <?php
                                    // Asignar el icono según el valor de "tipo_apoyo_id" 
                                    switch ($empresa['tipo_apoyo_id']) {
                                        case 1: // Beneficios y Descuentos
                                            echo '<i class="fa fa-tag fa-2x"></i>'; // Icono de etiqueta de descuento
                                            break;
                                        case 2: // Apoyo Logístico y Operacional
                                            echo '<i class="fa fa-truck fa-2x"></i>'; // Icono de camión/logística
                                            break;
                                        case 3: // Servicios de Salud
                                            echo '<i class="fa fa-heartbeat fa-2x"></i>'; // Icono de salud/latidos del corazón
                                            break;
                                        case 4: // Difusión y Educación
                                            echo '<i class="fa fa-book fa-2x"></i>'; // Icono de libro para educación
                                            break;
                                        case 5: // Apoyo Tecnológico y Administrativo
                                            echo '<i class="fa fa-laptop fa-2x"></i>'; // Icono de computadora para tecnología
                                            break;
                                        case 6: // Oportunidades Laborales y Sociales
                                            echo '<i class="fa fa-briefcase fa-2x"></i>'; // Icono de maletín para empleo
                                            break;
                                        default:
                                            echo '<i class="fa fa-question-circle fa-2x"></i>'; // Icono por defecto
                                            break;
                                    }

                                        ?>
                                    </div>
                                </div>
                                <div class="service-content p-4">
                                    <div class="service-content-inner">
                                        <!-- Mostrar el nombre de la empresa -->
                                        <a href="#" class="d-inline-block h4 mb-4"><?php echo $empresa['nombre_empresa']; ?></a>
                                        <!-- Mostrar la descripción de la empresa -->
                                        <p class="mb-4"><?php echo $empresa['descripcion']; ?></p>
                                        <!-- <a class="btn btn-primary rounded-pill py-2 px-4" href="#">Read More</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
        <!-- Service End -->

            <!-- Sección Contactanos con gris blanco -->
            <section class="container-fluid bg-light py-4">
                <div class="container py-4">
                    <div class="text-center mx-auto pb-4" style="max-width: 800px;">
                        <h1 class="mt-3">¿Te interesa saber más?</1>
                    </div>
                    <div class="row g-4">
                        <div class="col-12">
                            <div style="text-align: center;">
                                <p class="lead">
                                    Si deseas conocer más sobre DoSys, colaborar con nosotros o tienes alguna pregunta, no dudes en contactarnos.
                                </p>
                                <p class="lead">
                                    ¡Estamos siempre abiertos a nuevas ideas y oportunidades!
                                </p>
                            </div>
                            <div class="text-center mt-4">
                                <a href="mailto:contacto@dosys.mx" class="btn btn-primary btn-lg mb-2">
                                    Correo electrónico: contacto@dosys.mx
                                </a>
                                <a href="https://wa.me/529933590931" class="btn btn-success btn-lg mb-2">
                                    WhatsApp: +52 993 359 0931
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

    <!-- PIE DE PÁGINA NO TOCAR -->       
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer py-5 wow fadeIn" data-wow-delay="0.2s">
        <div class="container py-5">
            <div class="row g-5">
                <!-- About Section -->
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-item">
                        <a href="index.html" class="footer-logo p-0">
                            <img src="img/logos/Dosys_largo_colorBlanco.png" alt="DoSys_Logo_Blanco">
                        </a>
                        <p class="text-white mb-4" style="text-align: justify;">DoSys es una herramienta universal que facilita y optimiza las donaciones altruistas, conectando a donadores, beneficiarios y organizaciones de salud en México.</p>
                    </div>
                </div>
                <!-- Quick Links -->
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Enlaces Rápidos</h4>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Inicio</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Donaciones</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Novedades</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Red de Apoyo</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Conócenos</a>
                    </div>
                </div>
                <!-- Legal Information -->
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Información Legal</h4>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Términos y Condiciones</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Política de Privacidad</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Aviso Legal</a>
                    </div>
                </div>
                <!-- Recognitions and Sponsorships -->
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-white mb-4">Reconocimientos y Patrocinios</h4>
                        <p class="text-white mb-4">Nuestro agradecimiento a todas las organizaciones que apoyan esta iniciativa.</p>
                        <!-- Add logos or names of sponsors and recognitions here -->
                    </div>
                </div>
            </div>
                    
            <!-- Social Media & Address -->
            <div class="pt-5" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="row g-0">
                    <div class="col-12">
                        <div class="row g-4">
                            <div class="col-lg-4 col-xl-4">
                                <div class="d-flex">
                                    <div class="btn-xl-square bg-primary text-white rounded p-4 me-4">
                                        <i class="fas fa-map-marker-alt fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white">Dirección</h4>
                                        <p class="mb-0">Carretera Villahermosa-Frontera Km. 3.5, Indeco, Villahermosa, Tabasco, México.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-xl-4">
                                <div class="d-flex">
                                    <div class="btn-xl-square bg-primary text-white rounded p-4 me-4">
                                        <i class="fab fa-whatsapp fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white">WhatsApp</h4>
                                        <p class="mb-0">Asesor: 99-33-59-09-31</p>
                                        <p class="mb-0">Lider de Equipo: 99-31-54-67-94</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-xl-4">
                                <div class="d-flex">
                                    <div class="btn-xl-square bg-primary text-white rounded p-4 me-4">
                                        <i class="fas fa-envelope fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white">Correo</h4>
                                        <p class="mb-0">info@dosys.mx</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>