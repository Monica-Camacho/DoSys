<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Política de Privacidad</title>
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
                <h1 class='display-5 mb-0'>Política de Privacidad</h1>
                <p class="fs-5 text-muted mb-0">Tu confianza y la seguridad de tus datos son nuestra prioridad.</p>
            </div>
        </div>
    </div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <p class="text-muted"><strong>Última actualización:</strong> 23 de julio de 2025</p>

                <p>Bienvenido a DoSys. Su confianza es fundamental para nosotros, y estamos comprometidos con la protección de su información personal. Esta Política de Privacidad explica cómo <strong>El equipo de DoSys</strong>, entidad en proceso de constitución legal (en adelante, “DoSys” o “nosotros”), con domicilio de referencia en <strong>Villahermosa, Tabasco, México</strong>, recopila, utiliza y protege sus datos a través de nuestra plataforma (la "Plataforma").</p>
                <p>Nuestro compromiso se alinea con la <strong>Ley Federal de Protección de Datos Personales en Posesión de los Particulares (LFPDPPP)</strong> de México y las mejores prácticas de seguridad.</p>
                
                <hr class="my-4">

                <h4 class="mb-3">1. Información que Recopilamos</h4>
                <p>Para ofrecer nuestros servicios, recopilamos la siguiente información según su rol en la Plataforma:</p>
                <h6><strong>Para todos los Usuarios</strong> (Beneficiarios, Organizaciones y Donantes):</h6>
                <ul>
                    <li><strong>Datos de Identificación:</strong> Nombre, correo electrónico, nombre de usuario y contraseña.</li>
                    <li><strong>Datos de Contacto:</strong> Número telefónico y dirección para fines logísticos.</li>
                    <li><strong>Datos de Uso Técnico:</strong> Información sobre su interacción con la Plataforma, como dirección IP, tipo de navegador y dispositivo utilizado.</li>
                </ul>
                
                <h6 class="mt-4"><strong>Específicamente para Beneficiarios</strong>:</h6>
                <ul>
                    <li><strong>Datos Personales Sensibles:</strong> Información sobre su estado de salud, como enfermedades crónicas y alergias a medicamentos, con el único fin de garantizar la seguridad de las donaciones. El manejo de estos datos se detalla en la sección 3.</li>
                </ul>

                <h4 class="mt-4 mb-3">2. Cómo y Por Qué Usamos su Información</h4>
                <p>Su información se utiliza para las siguientes finalidades:</p>
                <h6><strong>Finalidades Esenciales para el Servicio</strong>:</h6>
                <ul>
                    <li>Operar, mantener y mejorar la funcionalidad de la Plataforma.</li>
                    <li>Verificar la identidad de los usuarios y la legitimidad de las organizaciones.</li>
                    <li>Facilitar la comunicación necesaria para la gestión y logística de las donaciones.</li>
                    <li>Permitir que las organizaciones evalúen las solicitudes de los beneficiarios.</li>
                    <li>Garantizar la seguridad de nuestra Plataforma y proteger a nuestros usuarios.</li>
                </ul>

                <h6 class="mt-4"><strong>Finalidades Adicionales</strong>:</h6>
                <ul>
                    <li>Como entidad sin fines de lucro, <strong>DoSys no utiliza sus datos para fines de marketing o publicidad de terceros</strong>.</li>
                    <li>Ocasionalmente, podríamos enviarle comunicaciones importantes sobre el servicio, de las cuales podrá optar por no recibir.</li>
                </ul>

                <h4 class="mt-4 mb-3">3. Tratamiento de Datos Sensibles</h4>
                <p>El modelo de DoSys se enfoca en donaciones seguras. Para ello, es indispensable recabar datos de salud (considerados "sensibles" por la ley), como alergias o enfermedades crónicas.</p>
                <p>La <strong>única y exclusiva finalidad</strong> de tratar estos datos es proteger la salud de los beneficiarios, permitiendo a las organizaciones verificar que los insumos médicos donados sean seguros y adecuados. De acuerdo con la LFPDPPP, <strong>solicitaremos su consentimiento expreso</strong> a través de un mecanismo claro y separado durante su registro (como una casilla desmarcada por defecto). Sin este consentimiento, no podremos procesar sus solicitudes de ayuda.</p>
                
                <h4 class="mt-4 mb-3">4. Cómo Compartimos su Información</h4>
                <p>Para que la Plataforma opere, es necesario compartir cierta información. Al enviar una solicitud como Beneficiario, usted acepta que los siguientes datos sean visibles para las organizaciones verificadas en DoSys:</p>
                <ul>
                    <li>Su nombre de usuario.</li>
                    <li>Los detalles de su solicitud.</li>
                    <li>La información de salud proporcionada bajo su consentimiento.</li>
                </ul>
                <p>Esta transferencia tiene como <strong>único propósito</strong> que las organizaciones evalúen su caso y gestionen la donación. <strong>DoSys no vende ni alquila su información personal</strong>. Exigimos por contrato que todas las organizaciones cumplan con estrictas normas de confidencialidad y usen sus datos exclusivamente para la finalidad descrita, en cumplimiento con la LFPDPPP.</p>

                <h4 class="mt-4 mb-3">5. Sus Derechos ARCO</h4>
                <p>Usted tiene derecho a ejercer en cualquier momento sus derechos de <strong>Acceso, Rectificación, Cancelación y Oposición (ARCO)</strong> sobre sus datos personales.</p>
                <ul>
                    <li><strong>Acceder:</strong> Conocer qué datos personales tenemos de usted.</li>
                    <li><strong>Rectificar:</strong> Solicitar la corrección de su información si está desactualizada o es inexacta.</li>
                    <li><strong>Cancelar:</strong> Pedir que eliminemos sus registros de nuestras bases de datos.</li>
                    <li><strong>Oponerse:</strong> Negarse al uso de sus datos para fines específicos.</li>
                </ul>
                <p>Para ejercer cualquiera de estos derechos, por favor envíe una solicitud a <strong>contacto@dosys.com</strong>, cumpliendo con los requisitos que marca la ley.</p>

                <h4 class="mt-4 mb-3">6. Seguridad de la Información</h4>
                <p>Implementamos medidas de seguridad administrativas, técnicas y físicas para proteger su información contra cualquier acceso, uso o tratamiento no autorizado.</p>

                <h4 class="mt-4 mb-3">7. Uso de Cookies</h4>
                <p>Utilizamos cookies para fines de seguridad, análisis de tráfico y para mejorar y personalizar su experiencia en la Plataforma. Puede gestionar sus preferencias de cookies desde la configuración de su navegador.</p>

                <h4 class="mt-4 mb-3">8. Cambios a esta Política</h4>
                <p>Nos reservamos el derecho de modificar esta política en cualquier momento. Cualquier cambio sustancial le será notificado a través de la Plataforma o por correo electrónico.</p>

                <h4 class="mt-4 mb-3">9. Contacto</h4>
                <p>Si tiene dudas o comentarios sobre esta Política de Privacidad, no dude en contactarnos en:
                <strong>contacto@dosys.com</strong></p>
            </div>
        </div>
    </div>
    <?php require_once 'templates/footer.php'; ?>
    
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    
    <?php require_once 'templates/scripts.php'; ?>
</body>
</html>