<?php
// Incluye la configuración y inicia la sesión.
require_once 'config.php';
// Inicia la sesión para que el navbar funcione correctamente.
session_start();
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <script src="https://cdn.userway.org/widget.js" data-account="C07GrJafQK"></script>
        <meta charset="utf-8">
        <title>DoSys - Registro de Persona</title>
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
        
        <!-- Estilos para el icono del ojo y mensaje de error -->
        <style>
            .password-container {
                position: relative;
            }
            .toggle-password {
                position: absolute;
                top: 50%;
                right: 15px;
                transform: translateY(-50%);
                cursor: pointer;
                color: #6c757d;
            }
            /* Estilo para el mensaje de error de contraseña */
            .password-error-message {
                color: #dc3545; /* Color rojo de Bootstrap para peligro */
                font-size: 0.875em; /* Un poco más pequeño que el texto normal */
                display: none; /* Oculto por defecto */
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
        <?php require_once 'templates/topbar.php'; ?>
        <!-- Topbar End -->
         
        <!-- Navbar Start -->
        <?php require_once 'templates/navbar.php'; ?>
        <!-- Navbar End -->

    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <i class="fa fa-user-plus fa-3x text-primary mb-3"></i>
                                <h2 class="card-title mb-2">Crear Cuenta Personal</h2>
                                <p class="text-muted">Es rápido y fácil. Podrás completar tu perfil más tarde.</p>
                            </div>
                            
                            <form action="<?php echo BASE_URL; ?>auth/register_process.php" method="POST" id="registrationForm">
                                <input type="hidden" name="user_type" value="persona">

                                <h5 class="mb-3">Datos Esenciales</h5>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="nombre" class="form-label">Nombre(s)</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno">
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h5 class="mb-3">Credenciales de Acceso</h5>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <div class="password-container">
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <i class="fa fa-eye toggle-password" id="togglePassword"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                                        <div class="password-container">
                                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                            <i class="fa fa-eye toggle-password" id="togglePasswordConfirm"></i>
                                        </div>
                                        <div id="passwordMatchError" class="password-error-message mt-1">
                                            Las contraseñas no coinciden.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-check my-4">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        Acepto los <a href="#">Términos y Condiciones</a>.
                                    </label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Crear Mi Cuenta</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Registration Form End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>
        
        <!-- Script para funcionalidades del formulario -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- LÓGICA PARA MOSTRAR/OCULTAR CONTRASEÑAS ---
                function togglePasswordVisibility(inputId, toggleId) {
                    const passwordInput = document.getElementById(inputId);
                    const toggleIcon = document.getElementById(toggleId);
                    
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    toggleIcon.classList.toggle('fa-eye-slash');
                }

                document.getElementById('togglePassword').addEventListener('click', function() {
                    togglePasswordVisibility('password', 'togglePassword');
                });

                document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
                    togglePasswordVisibility('password_confirm', 'togglePasswordConfirm');
                });

                // --- LÓGICA PARA VALIDAR QUE LAS CONTRASEÑAS COINCIDAN ---
                const passwordInput = document.getElementById('password');
                const passwordConfirmInput = document.getElementById('password_confirm');
                const errorMessage = document.getElementById('passwordMatchError');
                const form = document.getElementById('registrationForm');

                function validatePasswords() {
                    if (passwordInput.value !== passwordConfirmInput.value) {
                        errorMessage.style.display = 'block'; // Muestra el mensaje de error
                        passwordConfirmInput.classList.add('is-invalid'); // Añade borde rojo
                        return false;
                    } else {
                        errorMessage.style.display = 'none'; // Oculta el mensaje de error
                        passwordConfirmInput.classList.remove('is-invalid'); // Quita el borde rojo
                        return true;
                    }
                }

                // Validar mientras el usuario escribe en cualquiera de los dos campos
                passwordInput.addEventListener('keyup', validatePasswords);
                passwordConfirmInput.addEventListener('keyup', validatePasswords);

                // Prevenir el envío del formulario si las contraseñas no coinciden
                form.addEventListener('submit', function(event) {
                    if (!validatePasswords()) {
                        event.preventDefault(); // Detiene el envío del formulario
                        alert('Las contraseñas no coinciden. Por favor, corrígelas.');
                    }
                });
            });
        </script>
          
</body>

</html>
