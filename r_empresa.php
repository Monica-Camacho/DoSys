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
        <?php require_once 'templates/topbar.php'; ?>
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
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="node_modules/axe-core/axe.min.js"></script>
    

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
</body>
</html>