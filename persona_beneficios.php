<?php
require_once 'config.php'; // Incluye la configuración y la URL base.
require_once 'conexion_local.php'; // Incluye la conexión a la base de datos.
session_start();

// Muestra una alerta si hay un error en el inicio de sesión.
if (isset($_GET['error']) && $_GET['error'] == 1) {
    // Se recomienda usar un modal o mensaje más integrado en lugar de alert().
    // echo "<script>alert('Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.');</script>";
}

// 1. AUTENTICACIÓN Y OBTENCIÓN DE ID DE USUARIO
if (!isset($_SESSION['id'])) {
    // Si el usuario no está logueado, redirigir a la página de login.
    header('Location: login.php');
    exit();
}
$usuario_id = $_SESSION['id'];

// --- Obtener Beneficios Disponibles para el Usuario ---
$beneficios_disponibles = [];
// Consulta para obtener los beneficios que el usuario ha canjeado
// y que están en estado 'Disponible' o 'Canjeado' (si se quiere mostrar el historial)
// y que pertenecen a la empresa del beneficio.
$sql_beneficios = "SELECT db.id, ea.titulo, ea.descripcion, ea.fecha_expiracion, db.codigo_canje,
                          ep.nombre_comercial AS empresa_nombre,
                          d.ruta_archivo AS empresa_logo,
                          img_benefit.ruta_archivo AS imagen_beneficio_ruta
                   FROM donantes_beneficios db
                   JOIN empresas_apoyos ea ON db.apoyo_id = ea.id
                   JOIN empresas_perfil ep ON ea.empresa_id = ep.id
                   LEFT JOIN documentos d ON ep.logo_documento_id = d.id
                   LEFT JOIN documentos img_benefit ON ea.imagen_documento_id = img_benefit.id
                   WHERE db.usuario_id = ? AND db.estado IN ('Disponible', 'Canjeado')
                   ORDER BY db.fecha_otorgado DESC";

$stmt_beneficios = $conexion->prepare($sql_beneficios);
$stmt_beneficios->bind_param("i", $usuario_id);
$stmt_beneficios->execute();
$resultado_beneficios = $stmt_beneficios->get_result();

while ($row = $resultado_beneficios->fetch_assoc()) {
    $beneficios_disponibles[] = $row;
}
$stmt_beneficios->close();
$conexion->close(); // Cerrar la conexión a la base de datos.

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
            overflow: hidden; /* Asegura que la imagen no se salga del borde redondeado */
        }
        .benefit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .benefit-image-container {
            width: 100%;
            height: 180px; /* Altura fija para las imágenes */
            overflow: hidden;
            background-color: #f8f9fa; /* Fondo claro si la imagen no cubre todo */
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .benefit-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Cubre el área manteniendo el aspecto */
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }
        .benefit-logo-container {
            height: 80px; /* Altura más pequeña para el logo de la empresa */
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px dashed #ccc;
            background-color: #fff; /* Asegura que el logo tenga un fondo blanco si el contenedor de la imagen es diferente */
        }
        .benefit-logo-container img {
            max-width: 100px; /* Tamaño máximo para el logo */
            max-height: 60px;
            object-fit: contain;
        }
        .benefit-card .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding-top: 1rem; /* Ajuste de padding */
        }
        .benefit-card .card-title {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .benefit-card .card-text {
            font-size: 0.9rem;
            flex-grow: 1; /* Permite que la descripción ocupe el espacio disponible */
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
            word-break: break-all; /* Para asegurar que códigos largos no desborden */
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
                            <div class="benefit-image-container">
                                <?php if (!empty($beneficio['imagen_beneficio_ruta'])): ?>
                                    <img src="<?php echo htmlspecialchars($beneficio['imagen_beneficio_ruta']); ?>" alt="Imagen de <?php echo htmlspecialchars($beneficio['titulo']); ?>">
                                <?php else: ?>
                                    <!-- Placeholder si no hay imagen de beneficio -->
                                    <img src="https://placehold.co/400x180/E9ECEF/6C757D?text=No+Imagen" alt="No hay imagen disponible">
                                <?php endif; ?>
                            </div>
                            <div class="benefit-logo-container">
                                <?php if (!empty($beneficio['empresa_logo'])): ?>
                                    <img src="<?php echo htmlspecialchars($beneficio['empresa_logo']); ?>" alt="Logo de <?php echo htmlspecialchars($beneficio['empresa_nombre']); ?>">
                                <?php else: ?>
                                    <!-- Placeholder si no hay logo de empresa -->
                                    <img src="https://placehold.co/100x60/E9ECEF/6C757D?text=Logo" alt="Logo de Empresa">
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title"><?php echo htmlspecialchars($beneficio['titulo']); ?></h5>
                                <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars($beneficio['descripcion']); ?></p>
                                <p class="card-text small mb-3">
                                    <i class="far fa-calendar-alt me-2"></i><strong>Expira:</strong> <?php echo $beneficio['fecha_expiracion'] ? date("d/m/Y", strtotime($beneficio['fecha_expiracion'])) : 'N/A'; ?>
                                </p>
                                <button class="btn btn-primary w-100 btn-canjear" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#benefitModal"
                                        data-titulo="<?php echo htmlspecialchars($beneficio['titulo']); ?>"
                                        data-empresa="<?php echo htmlspecialchars($beneficio['empresa_nombre']); ?>"
                                        data-codigo="<?php echo htmlspecialchars($beneficio['codigo_canje']); ?>"
                                        data-logo="<?php echo htmlspecialchars($beneficio['empresa_logo']); ?>"
                                        data-imagen-beneficio="<?php echo htmlspecialchars($beneficio['imagen_beneficio_ruta'] ?? ''); ?>">
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
                        <a href="avisos.php" class="btn btn-primary">Ver Oportunidades de Donación</a>
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
                    <img id="modalImagenBeneficio" src="" alt="Imagen del Beneficio" class="mb-3" style="max-width: 100%; height: auto; border-radius: 5px; display: none;">
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
        <?php require_once 'templates/footer.php'; ?>
        <!-- Footer End -->
         
        <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
        
        <?php require_once 'templates/scripts.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var benefitModal = document.getElementById('benefitModal');
            benefitModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var titulo = button.getAttribute('data-titulo');
                var empresa = button.getAttribute('data-empresa');
                var codigo = button.getAttribute('data-codigo');
                var logo = button.getAttribute('data-logo');
                var imagenBeneficio = button.getAttribute('data-imagen-beneficio'); // Nueva línea
                var qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(codigo)}`;

                var modalTitle = benefitModal.querySelector('.modal-title');
                var modalLogo = benefitModal.querySelector('#modalEmpresaLogo');
                var modalImagenBeneficio = benefitModal.querySelector('#modalImagenBeneficio'); // Nueva línea
                var modalTituloBeneficio = benefitModal.querySelector('#modalTituloBeneficio');
                var modalCodigo = benefitModal.querySelector('#modalCodigo');
                var modalQrCode = benefitModal.querySelector('#modalQrCode');

                modalTitle.textContent = 'Beneficio de ' + empresa;
                modalLogo.src = logo;
                modalTituloBeneficio.textContent = titulo;
                modalCodigo.textContent = codigo;
                modalQrCode.src = qrUrl;

                // Mostrar u ocultar la imagen de beneficio en el modal
                if (imagenBeneficio && imagenBeneficio !== 'N/A') {
                    modalImagenBeneficio.src = imagenBeneficio;
                    modalImagenBeneficio.style.display = 'block';
                } else {
                    modalImagenBeneficio.style.display = 'none';
                    modalImagenBeneficio.src = ''; // Limpiar src para evitar mostrar imagen rota
                }
            });
        });
    </script>

</body>
</html>
