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
    <title>DoSys - Realizar una Donación</title>
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
                <h1 class='display-5 mb-0'>Realizar Donación</h1>
                <p class="fs-5 text-muted mb-0">Gracias por tu generosidad. Completa los datos de tu donación.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Donation Form Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <form class="row g-5">
                <!-- Main Form Column -->
                <div class="col-lg-7">
                    <!-- Section 1: Donation Details -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">1. ¿Qué vas a donar?</h5>
                            <div class="row g-3">
                                <!-- Dynamic Fields (Corrected Structure) -->
                                <!-- Fields for Sangre (hidden by default) -->
                                <div id="campos-sangre" class="row g-3 d-none ps-2 pe-2">
                                    <div class="col-12">
                                        <p>Estás a punto de registrar tu intención de donar sangre. Se te contactará para agendar una cita en el centro seleccionado.</p>
                                        <div class="alert alert-info">
                                            <strong>Tu tipo de sangre:</strong> O+ (Dato de tu perfil).
                                        </div>
                                    </div>
                                </div>
                                <!-- Fields for Medicamentos (hidden by default) -->
                                <div id="campos-medicamentos" class="row g-3 d-none ps-2 pe-2">
                                    <div class="col-md-6">
                                        <label for="medicamento" class="form-label">Nombre del Medicamento</label>
                                        <input type="text" class="form-control" id="medicamento" placeholder="Ej: Paracetamol">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cantidad" class="form-label">Cantidad a Donar</label>
                                        <input type="text" class="form-control" id="cantidad" placeholder="Ej: 1 caja sellada">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="caducidad" class="form-label">Fecha de Caducidad</label>
                                        <input type="date" class="form-control" id="caducidad">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="foto_medicamento" class="form-label">Foto del Medicamento (Opcional)</label>
                                        <input type="file" class="form-control" id="foto_medicamento">
                                    </div>
                                </div>
                                <!-- Fields for Dispositivos (hidden by default) -->
                                <div id="campos-dispositivos" class="row g-3 d-none ps-2 pe-2">
                                    <div class="col-md-6">
                                        <label for="tipoDispositivo" class="form-label">Tipo de Dispositivo</label>
                                        <input type="text" class="form-control" id="tipoDispositivo" placeholder="Ej: Silla de ruedas">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="estadoDispositivo" class="form-label">Estado del Dispositivo</label>
                                        <select id="estadoDispositivo" class="form-select">
                                            <option>Nuevo</option>
                                            <option>Usado - Buen estado</option>
                                            <option>Usado - Regular</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="foto_dispositivo" class="form-label">Foto del Dispositivo (Opcional)</label>
                                        <input type="file" class="form-control" id="foto_dispositivo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Column (Map and Summary) -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm" style="top: 10px;">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">2. Punto de Entrega</h5>
                            <p class="text-muted">Selecciona en el mapa el hospital o centro de acopio donde llevarás tu donación.</p>
                            <!-- Map Placeholder -->
                            <div class="bg-light d-flex align-items-center justify-content-center mb-3" style="height: 300px; border-radius: .25rem;">
                                <p class="text-muted">[Mapa Interactivo]</p>
                            </div>
                            <div class="mb-3">
                                <label for="centro_donacion" class="form-label">Centro de Donación Seleccionado</label>
                                <input type="text" class="form-control" id="centro_donacion" value="Centro de Acopio DoSys" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="direccion_centro" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion_centro" rows="2" disabled>Calle Ficticia 123, Col. Centro...</textarea>
                            </div>
                            <hr>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" id="terminos">
                                <label class="form-check-label" for="terminos">
                                    Confirmo que la información es correcta y acepto los <a href="#">términos y condiciones</a> de donación.
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 p-3">Confirmar mi Donación</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Donation Form Content End -->
        
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

    <!-- Dynamic Form Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const categoria = urlParams.get('categoria');

            const camposSangre = document.getElementById('campos-sangre');
            const camposMedicamentos = document.getElementById('campos-medicamentos');
            const camposDispositivos = document.getElementById('campos-dispositivos');
            const tituloPagina = document.querySelector('.display-5');

            if (categoria === 'sangre') {
                camposSangre.classList.remove('d-none');
                tituloPagina.textContent = 'Donar Sangre';
            } else if (categoria === 'medicamentos') {
                camposMedicamentos.classList.remove('d-none');
                tituloPagina.textContent = 'Donar Medicamentos';
            } else if (categoria === 'dispositivos') {
                camposDispositivos.classList.remove('d-none');
                tituloPagina.textContent = 'Donar Dispositivos';
            } else {
                tituloPagina.textContent = 'Realizar Donación';
                // Por defecto, se puede mostrar un mensaje o la categoría más común.
                camposMedicamentos.classList.remove('d-none');
            }
        });
    </script>
    
</body>

</html>
