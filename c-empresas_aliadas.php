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
    <meta charset="utf-8">
    <title>DoSys - Empresas Aliadas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">

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
    <script src="js/main.js"></script>

</body>
</html>
