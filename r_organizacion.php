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
        <title>DoSys - Registro de Organización</title>
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
            .password-error-message {
                color: #dc3545;
                font-size: 0.875em;
                display: none;
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
                <div class="col-lg-9">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <i class="fa fa-hands-helping fa-3x text-primary mb-3"></i>
                                <h2 class="card-title mb-2">Registro de Organización</h2>
                                <p class="text-muted">Inscribe a tu fundación, hospital u OSC para gestionar ayuda.</p>
                            </div>
                            
                            <form action="<?php echo BASE_URL; ?>auth/register_process.php" method="POST" id="registrationForm">
                                <input type="hidden" name="user_type" value="organizacion">

                                <h5 class="mb-3 border-bottom pb-2">Paso 1: Tus Datos (Operador de la cuenta)</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4"><label class="form-label">Tu Nombre(s)</label><input type="text" class="form-control" name="operador_nombre" required></div>
                                    <div class="col-md-4"><label class="form-label">Tu Apellido Paterno</label><input type="text" class="form-control" name="operador_apellido_paterno" required></div>
                                    <div class="col-md-4"><label class="form-label">Tu Apellido Materno</label><input type="text" class="form-control" name="operador_apellido_materno"></div>
                                    <div class="col-md-12"><label class="form-label">Tu Correo (para iniciar sesión)</label><input type="email" class="form-control" name="email" required></div>
                                    <div class="col-md-6">
                                        <label class="form-label">Crea una Contraseña</label>
                                        <div class="password-container">
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <i class="fa fa-eye toggle-password" id="togglePassword"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirma la Contraseña</label>
                                        <div class="password-container">
                                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                            <i class="fa fa-eye toggle-password" id="togglePasswordConfirm"></i>
                                        </div>
                                        <div id="passwordMatchError" class="password-error-message mt-1">Las contraseñas no coinciden.</div>
                                    </div>
                                </div>

                                <h5 class="mb-3 border-bottom pb-2">Paso 2: Datos de la Organización</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6"><label class="form-label">Nombre de la Organización</label><input type="text" class="form-control" name="org_nombre" required></div>
                                    <div class="col-md-6"><label class="form-label">CLUNI (Opcional)</label><input type="text" class="form-control" name="org_cluni"></div>
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
                                <div class="d-grid"><button type="submit" class="btn btn-primary btn-lg">Crear Cuenta de Organización</button></div>
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
                // IDs de los campos de contraseña en este formulario
                const passwordField = document.querySelector('input[name="password"]');
                const passwordConfirmField = document.querySelector('input[name="password_confirm"]');
                
                // Asignar IDs para poder seleccionarlos fácilmente
                if (!passwordField.id) passwordField.id = 'password_field';
                if (!passwordConfirmField.id) passwordConfirmField.id = 'password_confirm_field';

                // --- LÓGICA PARA MOSTRAR/OCULTAR CONTRASEÑAS ---
                function togglePasswordVisibility(inputId, toggleIcon) {
                    const passwordInput = document.getElementById(inputId);
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    toggleIcon.classList.toggle('fa-eye-slash');
                }
                
                // Crear y añadir los iconos y el mensaje de error dinámicamente
                const toggleIcon1 = document.createElement('i');
                toggleIcon1.className = 'fa fa-eye toggle-password';
                passwordField.parentElement.classList.add('password-container');
                passwordField.parentElement.appendChild(toggleIcon1);
                
                const toggleIcon2 = document.createElement('i');
                toggleIcon2.className = 'fa fa-eye toggle-password';
                passwordConfirmField.parentElement.classList.add('password-container');
                passwordConfirmField.parentElement.appendChild(toggleIcon2);

                const errorMessage = document.createElement('div');
                errorMessage.className = 'password-error-message mt-1';
                errorMessage.textContent = 'Las contraseñas no coinciden.';
                passwordConfirmField.parentElement.insertAdjacentElement('afterend', errorMessage);

                toggleIcon1.addEventListener('click', () => togglePasswordVisibility(passwordField.id, toggleIcon1));
                toggleIcon2.addEventListener('click', () => togglePasswordVisibility(passwordConfirmField.id, toggleIcon2));

                // --- LÓGICA PARA VALIDAR QUE LAS CONTRASEÑAS COINCIDAN ---
                function validatePasswords() {
                    if (passwordField.value !== passwordConfirmField.value) {
                        errorMessage.style.display = 'block';
                        passwordConfirmField.classList.add('is-invalid');
                        return false;
                    } else {
                        errorMessage.style.display = 'none';
                        passwordConfirmField.classList.remove('is-invalid');
                        return true;
                    }
                }

                passwordField.addEventListener('keyup', validatePasswords);
                passwordConfirmField.addEventListener('keyup', validatePasswords);

                document.getElementById('registrationForm').addEventListener('submit', function(event) {
                    if (!validatePasswords()) {
                        event.preventDefault();
                        alert('Las contraseñas no coinciden. Por favor, corrígelas.');
                    }
                });
            });
        </script>
</body>
</html>
