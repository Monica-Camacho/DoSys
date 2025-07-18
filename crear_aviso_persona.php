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
    <title>DoSys - Crear Solicitud de Donación</title>
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
        <?php require_once 'templates/topbar.php'; ?>
        <!-- Topbar End -->
             

        <!-- Navbar Start -->
        <?php require_once 'templates/navbar.php'; ?>
        <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Crear Solicitud</h1>
                <p class="fs-5 text-muted mb-0">Completa la siguiente información para validar tu caso.</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Request Form Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <form class="row g-5">
                <!-- Main Form Column -->
                <div class="col-lg-7">
                    <!-- Section 1: Request Details -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">1. ¿Qué se necesita?</h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="titulo" class="form-label">Título de la Solicitud</label>
                                    <input type="text" class="form-control" id="titulo" placeholder="Ej: Urge Paracetamol para adulto mayor">
                                </div>
                                <div class="col-md-6">
                                    <label for="urgencia" class="form-label">Nivel de Urgencia</label>
                                    <select id="urgencia" class="form-select">
                                        <option>Normal</option>
                                        <option>Urgente</option>
                                        <option>Crítico</option>
                                    </select>
                                </div>
                                
                                <!-- Dynamic Fields Container -->
                                <div class="col-12">
                                    <!-- Fields for Sangre (hidden by default) -->
                                    <div id="campos-sangre" class="row g-3 d-none">
                                        <div class="col-md-6">
                                            <label for="tipoSangre" class="form-label">Tipo de Sangre</label>
                                            <select id="tipoSangre" class="form-select">
                                                <option>O+</option><option>O-</option><option>A+</option><option>A-</option><option>B+</option><option>B-</option><option>AB+</option><option>AB-</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cantidadDonadores" class="form-label">Cantidad de Donadores</label>
                                            <input type="number" class="form-control" id="cantidadDonadores" placeholder="Ej: 5">
                                        </div>
                                    </div>
                                    <!-- Fields for Medicamentos (hidden by default) -->
                                    <div id="campos-medicamentos" class="row g-3 d-none">
                                        <div class="col-md-6">
                                            <label for="medicamento" class="form-label">Medicamento Solicitado</label>
                                            <input type="text" class="form-control" id="medicamento" placeholder="Nombre del medicamento">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cantidad" class="form-label">Cantidad Requerida</label>
                                            <input type="text" class="form-control" id="cantidad" placeholder="Ej: 2 cajas, 1 paquete">
                                        </div>
                                        <div class="col-12">
                                            <label for="receta" class="form-label">Receta Médica (Indispensable)</label>
                                            <input type="file" class="form-control" id="receta">
                                        </div>
                                    </div>
                                    <!-- Fields for Dispositivos (hidden by default) -->
                                    <div id="campos-dispositivos" class="row g-3 d-none">
                                        <div class="col-md-6">
                                            <label for="tipoDispositivo" class="form-label">Tipo de Dispositivo</label>
                                            <input type="text" class="form-control" id="tipoDispositivo" placeholder="Ej: Silla de ruedas, muletas">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="caracteristicasDispositivo" class="form-label">Características Específicas</label>
                                            <input type="text" class="form-control" id="caracteristicasDispositivo" placeholder="Ej: Pediátrica, plegable">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Patient Info -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">2. ¿Para quién es?</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="nombre_paciente" class="form-label">Nombre(s) del Paciente</label>
                                    <input type="text" class="form-control" id="nombre_paciente">
                                </div>
                                <div class="col-md-4">
                                    <label for="paterno_paciente" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="paterno_paciente">
                                </div>
                                <div class="col-md-4">
                                    <label for="materno_paciente" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="materno_paciente">
                                </div>
                                 <div class="col-md-6">
                                    <label for="nacimiento_paciente" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="nacimiento_paciente">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sexo al nacer</label>
                                    <div class="d-flex pt-2">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="sexo" id="masculino">
                                            <label class="form-check-label" for="masculino">Masculino</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sexo" id="femenino">
                                            <label class="form-check-label" for="femenino">Femenino</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <!-- Section 3: Justification & Contact -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">3. Justificación y Contacto</h5>
                            <div class="row g-3">
                                 <div class="col-md-6">
                                    <label for="responsable" class="form-label">Persona Responsable (Contacto)</label>
                                    <input type="text" class="form-control" id="responsable">
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono de Contacto</label>
                                    <input type="tel" class="form-control" id="telefono">
                                </div>
                                <div class="col-12">
                                    <label for="descripcion" class="form-label">Descripción Detallada de la Situación</label>
                                    <textarea class="form-control" id="descripcion" rows="4" placeholder="Explica brevemente por qué necesitas la ayuda..."></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="documentos" class="form-label">Documentos de Soporte (Opcional)</label>
                                    <input type="file" class="form-control" id="documentos" multiple>
                                    <small class="form-text text-muted">Puedes subir dictámenes médicos, estudios socioeconómicos, etc.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Column (Map and Summary) -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm" style="top: 10px">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">4. Punto de Entrega y Validación</h5>
                            <p class="text-muted">Selecciona en el mapa el hospital o centro de acopio donde se recibirá la donación. Este centro validará tu solicitud.</p>
                            <!-- Map Placeholder -->
                            <div class="bg-light d-flex align-items-center justify-content-center mb-3" style="height: 300px; border-radius: .25rem;">
                                <p class="text-muted">[Mapa Interactivo]</p>
                            </div>
                            <div class="mb-3">
                                <label for="centro_donacion" class="form-label">Centro de Donación Seleccionado</label>
                                <input type="text" class="form-control" id="centro_donacion" value="Hospital Rovirosa" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="direccion_centro" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion_centro" rows="2" disabled>Av. Usumacinta, Col. Casa Blanca...</textarea>
                            </div>
                            <hr>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" id="terminos">
                                <label class="form-check-label" for="terminos">
                                    Acepto los <a href="#">términos y condiciones</a>.
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 p-3">Enviar Solicitud para Validación</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Request Form Content End -->
        
        <!-- Footer Start -->
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>

    <!-- Dynamic Form Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Obtener el parámetro 'categoria' de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const categoria = urlParams.get('categoria');

            // 2. Seleccionar los contenedores de los campos
            const camposSangre = document.getElementById('campos-sangre');
            const camposMedicamentos = document.getElementById('campos-medicamentos');
            const camposDispositivos = document.getElementById('campos-dispositivos');
            const tituloPagina = document.querySelector('.display-5'); // El H1 del encabezado

            // 3. Mostrar solo el grupo que corresponde a la categoría
            if (categoria === 'sangre') {
                camposSangre.classList.remove('d-none');
                tituloPagina.textContent = 'Solicitud de Sangre';
            } else if (categoria === 'medicamentos') {
                camposMedicamentos.classList.remove('d-none');
                tituloPagina.textContent = 'Solicitud de Medicamentos';
            } else if (categoria === 'dispositivos') {
                camposDispositivos.classList.remove('d-none');
                tituloPagina.textContent = 'Solicitud de Dispositivos';
            } else {
                // Opcional: si no hay categoría, mostrar un mensaje o redirigir a segmentos.php
                tituloPagina.textContent = 'Crear Solicitud';
                // Podrías mostrar un mensaje pidiendo que seleccionen una categoría primero.
            }
        });
    </script>
    
</body>

</html>
