<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Aviso Legal</title>
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
</head>
<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
    </div>

    <?php require_once 'templates/topbar.php'; ?>
    <?php require_once 'templates/navbar.php'; ?>

    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div>
                <h1 class='display-5 mb-0'>Aviso Legal</h1>
                <p class="fs-5 text-muted mb-0">Información legal y de titularidad de la plataforma.</p>
            </div>
        </div>
    </div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <p class="text-muted"><strong>Última actualización:</strong> 23 de julio de 2025</p>
                
                <hr class="my-4">

                <h4 class="mb-3">1. Datos de Identificación del Titular</h4>
                <p>De conformidad con la legislación vigente, se informa que este sitio web, dosys.org (en adelante, la "Plataforma"), es titularidad de:</p>
                <ul>
                    <li><strong>Titular:</strong> El equipo de DoSys (entidad en proceso de constitución legal)</li>
                    <li><strong>Domicilio de Referencia:</strong> Villahermosa, Tabasco, México.</li>
                    <li><strong>Correo Electrónico de Contacto:</strong> contacto@dosys.com</li>
                </ul>

                <h4 class="mt-4 mb-3">2. Objeto y Aceptación</h4>
                <p>El presente Aviso Legal regula el acceso y la utilización de la Plataforma. El uso de la Plataforma le atribuye la condición de Usuario e implica la aceptación plena y sin reservas de todas y cada una de las disposiciones incluidas en este Aviso Legal, así como en nuestros Términos y Condiciones de Uso y nuestra Política de Privacidad. Si no está de acuerdo con cualquiera de las condiciones aquí establecidas, no deberá usar esta Plataforma.</p>
                
                <h4 class="mt-4 mb-3">3. Propiedad Intelectual e Industrial</h4>
                <p>Todos los derechos de propiedad intelectual e industrial del contenido de esta Plataforma, incluyendo, sin limitación, imágenes, logotipos, textos, marcas, diseños y software, pertenecen a "El equipo de DoSys" o, en su caso, a terceros que han autorizado su uso. Queda expresamente prohibida la reproducción, distribución y la comunicación pública, incluida su modalidad de puesta a disposición, de la totalidad o parte de los contenidos de esta Plataforma con fines comerciales, en cualquier soporte y por cualquier medio técnico, sin la autorización expresa de DoSys.</p>

                <h4 class="mt-4 mb-3">4. Exclusión de Responsabilidad</h4>
                <p>DoSys actúa exclusivamente como un intermediario tecnológico. No se hace responsable, en ningún caso, de los daños y perjuicios de cualquier naturaleza que pudieran ocasionar, a título enunciativo: errores u omisiones en los contenidos, falta de disponibilidad del portal, o la calidad y seguridad de los insumos médicos intercambiados entre los Usuarios. La responsabilidad sobre las donaciones y su uso recae exclusivamente en los Usuarios, de acuerdo con lo establecido en los Términos y Condiciones.</p>

                <h4 class="mt-4 mb-3">5. Legislación Aplicable</h4>
                <p>El presente Aviso Legal se regirá e interpretará de acuerdo con las leyes de los Estados Unidos Mexicanos.</p>
                
                <h4 class="mt-4 mb-3">6. Contacto</h4>
                <p>Para cualquier consulta, puede contactarnos a través de la dirección de correo electrónico: <strong>contacto@dosys.com</strong></p>
            </div>
        </div>
    </div>
    <?php require_once 'templates/footer.php'; ?>
    
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    
    <?php require_once 'templates/scripts.php'; ?>
</body>
</html>