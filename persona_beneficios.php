<?php
// Simulación de una sesión de usuario iniciada
// En una aplicación real, aquí verificarías la sesión.
session_start();
// $_SESSION['usuario_id'] = 1; // Ejemplo
// $_SESSION['usuario_nombre'] = "Mónica Camacho"; // Ejemplo

// Datos de ejemplo (Mock Data) - Reemplazar con tu lógica de backend
$beneficios_disponibles = [
    [
        'id' => 1,
        'empresa_nombre' => 'Farmacias del Ahorro',
        'empresa_logo' => 'img/empresas/farmacias_ahorro.png',
        'titulo' => '20% de Descuento en Medicamentos',
        'descripcion' => 'Válido en la compra de medicamentos de marca propia. No acumulable con otras promociones.',
        'codigo' => 'DOSYS-FAH-A2B4C6',
        'fecha_expiracion' => '2025-12-31'
    ],
    [
        'id' => 2,
        'empresa_nombre' => 'Salud Digna',
        'empresa_logo' => 'img/empresas/salud_digna.png',
        'titulo' => 'Estudio de Química Sanguínea Gratis',
        'descripcion' => 'Presenta este código en cualquier sucursal para obtener un estudio de 6 elementos sin costo.',
        'codigo' => 'DOSYS-SDG-X8Y1Z3',
        'fecha_expiracion' => '2025-11-30'
    ],
    [
        'id' => 3,
        'empresa_nombre' => 'Ópticas Devlyn',
        'empresa_logo' => 'img/empresas/devlyn.png',
        'titulo' => '30% en Lentes Oftálmicos',
        'descripcion' => 'Aplica en la compra de armazón y micas graduadas. Válido una vez por donante.',
        'codigo' => 'DOSYS-DEV-L9M5N8',
        'fecha_expiracion' => '2026-01-15'
    ],
    [
        'id' => 4,
        'empresa_nombre' => 'GNC',
        'empresa_logo' => 'img/empresas/gnc.png',
        'titulo' => '15% de Descuento en Vitaminas',
        'descripcion' => 'Válido en toda la línea de vitaminas y suplementos GNC. No aplica con otras ofertas.',
        'codigo' => 'DOSYS-GNC-P7Q2R9',
        'fecha_expiracion' => '2025-10-31'
    ]
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Mis Beneficios</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">

    <!-- Google Web Fonts, Iconos y Estilos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos de Bootstrap y de la Plantilla -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- Estilos para los beneficios -->
    <style>
        .benefit-card {
            background-color: #fff;
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .benefit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .benefit-logo-container {
            height: 120px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px dashed #ccc;
        }
        .benefit-logo-container img {
            max-width: 100%;
            max-height: 80px;
            object-fit: contain;
        }
        .benefit-card .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .benefit-card .card-title {
            font-weight: bold;
        }
        .benefit-card .btn-canjear {
            margin-top: auto;
        }
        #benefitModal .modal-body img {
            display: block;
            margin: 1rem auto;
        }
        #benefitModal .coupon-code {
            background-color: #e9ecef;
            border: 2px dashed #adb5bd;
            padding: 1rem;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            color: #343a40;
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
                            <a href="mapa.php" class="text-muted small"><i class="fas fa-map-marker-alt text-primary me-2"></i>Lugares de donación</a>
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
                            <a href="persona_beneficios.php" class="dropdown-item">Mis Beneficios</a>
                            <a href="persona_perfil.php" class="dropdown-item">Mi Perfil</a>
                            <a href="persona_configuracion.php" class="dropdown-item">Configuración</a>
                            <hr class="dropdown-divider">
                            <a href="php/logout.php" class="dropdown-item text-danger">Cerrar Sesión</a>
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
                <h1 class='display-5 mb-0'>Mis Beneficios</h1>
                <p class="fs-5 text-muted mb-0">Gracias por tu generosidad. Aquí tienes las recompensas de nuestras empresas aliadas.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Main Content -->
    <main class="container py-5">
        <div class="row g-4">
            <?php if (!empty($beneficios_disponibles)): ?>
                <?php foreach ($beneficios_disponibles as $beneficio): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card benefit-card h-100">
                            <div class="benefit-logo-container">
                                <img src="<?php echo htmlspecialchars($beneficio['empresa_logo']); ?>" alt="Logo de <?php echo htmlspecialchars($beneficio['empresa_nombre']); ?>">
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title"><?php echo htmlspecialchars($beneficio['titulo']); ?></h5>
                                <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars($beneficio['descripcion']); ?></p>
                                <p class="card-text small mb-3">
                                    <i class="far fa-calendar-alt me-2"></i><strong>Expira:</strong> <?php echo date("d/m/Y", strtotime($beneficio['fecha_expiracion'])); ?>
                                </p>
                                <button class="btn btn-primary w-100 btn-canjear" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#benefitModal"
                                        data-titulo="<?php echo htmlspecialchars($beneficio['titulo']); ?>"
                                        data-empresa="<?php echo htmlspecialchars($beneficio['empresa_nombre']); ?>"
                                        data-codigo="<?php echo htmlspecialchars($beneficio['codigo']); ?>"
                                        data-logo="<?php echo htmlspecialchars($beneficio['empresa_logo']); ?>">
                                    <i class="fas fa-cut me-2"></i>Ver Código
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h4 class="alert-heading">¡Aún no tienes beneficios!</h4>
                        <p>Realiza una donación para empezar a recibir recompensas de nuestra increíble red de empresas aliadas.</p>
                        <hr>
                        <a href="avisos.html" class="btn btn-primary">Ver Oportunidades de Donación</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal para Canjear Beneficio -->
    <div class="modal fade" id="benefitModal" tabindex="-1" aria-labelledby="benefitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="benefitModalLabel">Tu Beneficio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalEmpresaLogo" src="" alt="Logo de la Empresa" class="mb-3" style="max-height: 60px;">
                    <h4 id="modalTituloBeneficio" class="mb-3"></h4>
                    <p>Presenta este código en la sucursal para hacerlo válido:</p>
                    <div class="coupon-code text-center my-3">
                        <span id="modalCodigo"></span>
                    </div>
                    <img id="modalQrCode" src="" alt="Código QR" style="width: 200px; height: 200px;">
                    <p class="mt-3 text-muted small">
                        Este código es único e intransferible. Válido una sola vez.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
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
    <script src="js/main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var benefitModal = document.getElementById('benefitModal');
            benefitModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var titulo = button.getAttribute('data-titulo');
                var empresa = button.getAttribute('data-empresa');
                var codigo = button.getAttribute('data-codigo');
                var logo = button.getAttribute('data-logo');
                var qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(codigo)}`;

                var modalTitle = benefitModal.querySelector('.modal-title');
                var modalLogo = benefitModal.querySelector('#modalEmpresaLogo');
                var modalTituloBeneficio = benefitModal.querySelector('#modalTituloBeneficio');
                var modalCodigo = benefitModal.querySelector('#modalCodigo');
                var modalQrCode = benefitModal.querySelector('#modalQrCode');

                modalTitle.textContent = 'Beneficio de ' + empresa;
                modalLogo.src = logo;
                modalTituloBeneficio.textContent = titulo;
                modalCodigo.textContent = codigo;
                modalQrCode.src = qrUrl;
            });
        });
    </script>

</body>
</html>
