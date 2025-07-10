<?php
// Incluye la configuración y inicia la sesión.
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
    <meta charset="utf-8">
    <title>DoSys - Registro de Empresa</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link rel="icon" type="image/png" href="img/logos/DoSys_chico.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet"/>
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
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
         
    <!-- Registration Form Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <i class="fa fa-building fa-3x text-primary mb-3"></i>
                                <h2 class="card-title mb-2">Registro de Empresa</h2>
                                <p class="text-muted">Completa todos los datos para crear la cuenta de tu empresa.</p>
                            </div>
                            
                            <form action="<?php echo BASE_URL; ?>auth/register_process.php" method="POST">
                                <input type="hidden" name="user_type" value="empresa">

                                <h5 class="mb-3 border-bottom pb-2">Paso 1: Tus Datos (Operador de la cuenta)</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4"><label class="form-label">Tu Nombre(s)</label><input type="text" class="form-control" name="operador_nombre" required></div>
                                    <div class="col-md-4"><label class="form-label">Tu Apellido Paterno</label><input type="text" class="form-control" name="operador_apellido_paterno" required></div>
                                    <div class="col-md-4"><label class="form-label">Tu Apellido Materno</label><input type="text" class="form-control" name="operador_apellido_materno"></div>
                                    <div class="col-md-12"><label class="form-label">Tu Correo (para iniciar sesión)</label><input type="email" class="form-control" name="email" required></div>
                                    <div class="col-md-6"><label class="form-label">Crea una Contraseña</label><input type="password" class="form-control" name="password" required></div>
                                    <div class="col-md-6"><label class="form-label">Confirma la Contraseña</label><input type="password" class="form-control" name="password_confirm" required></div>
                                </div>

                                <h5 class="mb-3 border-bottom pb-2">Paso 2: Datos Fiscales de la Empresa</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6"><label class="form-label">Nombre Comercial</label><input type="text" class="form-control" name="empresa_nombre_comercial" required></div>
                                    <div class="col-md-6"><label class="form-label">Razón Social</label><input type="text" class="form-control" name="empresa_razon_social" required></div>
                                    <div class="col-md-12"><label class="form-label">RFC</label><input type="text" class="form-control" name="empresa_rfc" required></div>
                                </div>

                                <h5 class="mb-3 border-bottom pb-2">Paso 3: Datos del Representante Legal</h5>
                                <div class="row g-3">
                                    <div class="col-md-4"><label class="form-label">Nombre(s) del Rep.</label><input type="text" class="form-control" name="rep_nombre" required></div>
                                    <div class="col-md-4"><label class="form-label">Apellido Paterno del Rep.</label><input type="text" class="form-control" name="rep_apellido_paterno" required></div>
                                    <div class="col-md-4"><label class="form-label">Apellido Materno del Rep.</label><input type="text" class="form-control" name="rep_apellido_materno"></div>
                                    <div class="col-md-6"><label class="form-label">Email del Representante</label><input type="email" class="form-control" name="rep_email" required></div>
                                    <div class="col-md-6"><label class="form-label">Teléfono del Representante</label><input type="tel" class="form-control" name="rep_telefono" required></div>
                                </div>
                                
                                <div class="form-check my-4">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">Acepto los <a href="#">Términos y Condiciones</a>.</label>
                                </div>
                                <div class="d-grid"><button type="submit" class="btn btn-primary btn-lg">Crear Cuenta de Empresa</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script src="node_modules/axe-core/axe.min.js"></script>
    

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
</body>
</html>