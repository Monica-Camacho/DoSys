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
        <title>DoSys - Error en Solicitud</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

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
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
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

        <!-- Start Mensaje de Error -->
        <div class="container-fluid py-5 bg-light">
            <div class="container text-center">
                <div class="mx-auto" style="max-width: 600px;">
                    
                    <!-- Icono de advertencia -->
                    <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                    
                    <!-- Título del error -->
                    <h1 class="display-5 mb-3">No se Pudo Enviar la Solicitud</h1>
                    
                    <!-- Explicación y llamada a la acción -->
                    <p class="fs-5 text-muted">
                        Parece que faltan algunos datos en tu perfil personal. Para poder crear una solicitud en tu nombre, es necesario que tu información esté completa.
                    </p>
                    
                    <hr class="my-4">
                    
                    <!-- Botones de acción -->
                    <a href="<?php echo BASE_URL; ?>persona_perfil.php" class="btn btn-primary p-3 me-2">Completar Mi Perfil</a>
                    <a href="<?php echo BASE_URL; ?>avisos_crear.php" class="btn btn-outline-secondary p-3">Volver a Intentar</a>
                </div>
            </div>
        </div>
        <!-- End Mensaje de Error -->

        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
          
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

</body>

</html>

