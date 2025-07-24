<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>DoSys - Términos y Condiciones</title>
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
                <h1 class='display-5 mb-0'>Términos y Condiciones de Uso</h1>
                <p class="fs-5 text-muted mb-0">Reglas y responsabilidades para el uso de nuestra plataforma.</p>
            </div>
        </div>
    </div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <p class="text-muted"><strong>Última actualización:</strong> 23 de julio de 2025</p>

                <p>Bienvenido/a a DoSys (en adelante, la "Plataforma"). Estos Términos y Condiciones de Uso (en adelante, los "Términos") rigen el acceso y uso de la plataforma web y los servicios ofrecidos por DoSys. Le rogamos que lea atentamente este documento antes de utilizar nuestros servicios.</p>
                <p>Al registrarse o utilizar la Plataforma, usted (en adelante, el "Usuario") acepta y se compromete a cumplir con los presentes Términos, así como con nuestra Política de Privacidad y Aviso Legal.</p>
                
                <hr class="my-4">

                <h4 class="mb-3">1. Definiciones</h4>
                <ul>
                    <li><strong>Plataforma:</strong> El sitio web y los servicios tecnológicos operados por DoSys.</li>
                    <li><strong>Usuario:</strong> Toda persona física o moral que se registra y utiliza la Plataforma. Existen tres tipos de Usuarios:
                        <ul>
                            <li><strong>Beneficiario:</strong> Persona que, a través de una Organización, solicita la donación de insumos médicos.</li>
                            <li><strong>Organización:</strong> Entidad verificada que actúa como intermediaria para gestionar las solicitudes de los Beneficiarios y la entrega de donaciones.</li>
                            <li><strong>Donante:</strong> Persona o entidad que ofrece insumos médicos en donación.</li>
                        </ul>
                    </li>
                    <li><strong>Insumos Médicos:</strong> Productos, materiales o equipos relacionados con la salud que se donan a través de la Plataforma.</li>
                </ul>

                <h4 class="mt-4 mb-3">2. Naturaleza de los Servicios y Rol de DoSys</h4>
                <h5>2.1. Mero Intermediario</h5>
                <p>DoSys es una plataforma tecnológica que actúa como un mero intermediario. Nuestro único propósito es poner a disposición de los Usuarios un espacio virtual para facilitar la conexión entre Donantes que desean ofrecer insumos médicos y Organizaciones que gestionan las necesidades de Beneficiarios. DoSys no es parte de ninguna transacción, acuerdo o donación que pueda surgir entre los Usuarios.</p>
                <h5>2.2. Exclusión de Propiedad y Verificación</h5>
                <p>DoSys no es propietario, proveedor, fabricante, distribuidor, ni tiene posesión de los insumos médicos listados en la Plataforma. En consecuencia, DoSys no verifica, inspecciona, certifica ni garantiza la calidad, seguridad, legalidad, estado, caducidad o idoneidad de ningún producto ofrecido. La responsabilidad de garantizar estos aspectos recae exclusivamente en los Usuarios correspondientes, como se define en la sección 4 de estos Términos.</p>

                <h4 class="mt-4 mb-3">3. Exclusión de Garantías y Limitación de Responsabilidad</h4>
                <h5>3.1. Uso Bajo Propio Riesgo</h5>
                <p>El uso de la Plataforma DoSys es bajo su propio riesgo. Los servicios se proporcionan "tal como están" y "según disponibilidad", sin garantías de ningún tipo, ya sean expresas o implícitas. DoSys no garantiza que la Plataforma operará de manera ininterrumpida, segura o libre de errores.</p>
                <h5>3.2. Limitación de Responsabilidad</h5>
                <p>En la máxima medida permitida por la ley aplicable, DoSys, sus directores, empleados y afiliados no serán responsables por ningún daño directo, indirecto, incidental, especial, consecuente o punitivo, incluyendo, pero no limitado a, daños por lesiones personales, daños a la salud, pérdidas económicas o de otro tipo, que surjan de o en conexión con:</p>
                <ul>
                    <li>a) El uso o la imposibilidad de usar la Plataforma.</li>
                    <li>b) La calidad, seguridad, legalidad o resultado de cualquier insumo médico donado o recibido a través de la Plataforma.</li>
                    <li>c) La conducta, acción u omisión de cualquier Usuario (Beneficiario, Organización o Donante).</li>
                    <li>d) Cualquier contenido o comunicación de terceros en la Plataforma.</li>
                </ul>
                <h5>3.3. Excepción por Dolo o Negligencia Grave</h5>
                <p>La limitación de responsabilidad mencionada en el punto 3.2 no aplicará en casos de dolo o negligencia grave directamente atribuibles a DoSys en la prestación de sus servicios de intermediación tecnológica.</p>

                <h4 class="mt-4 mb-3">4. Roles y Responsabilidades del Usuario</h4>
                <p>El correcto funcionamiento y la seguridad de la comunidad DoSys dependen del compromiso de cada Usuario con sus responsabilidades.</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Rol de Usuario</th>
                                <th>Responsabilidades Clave</th>
                                <th>Prohibiciones Específicas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Beneficiario</strong></td>
                                <td>
                                    <ul>
                                        <li>Proporcionar información veraz, precisa y completa sobre su identidad, necesidad y datos de salud.</li>
                                        <li>Utilizar los insumos médicos donados exclusivamente para el fin personal previsto.</li>
                                        <li>Comunicar de manera oportuna a la Organización gestora cualquier incidente o reacción adversa.</li>
                                        <li>Mantener la confidencialidad de la información de otros Usuarios.</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>Revender, intercambiar o dar un uso comercial a los insumos recibidos.</li>
                                        <li>Crear múltiples cuentas o solicitudes para un mismo caso con el fin de obtener más donaciones de las necesarias.</li>
                                        <li>Proporcionar información falsa o engañosa.</li>
                                        <li>Intentar contactar a los Donantes directamente fuera de los canales provistos.</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Organización</strong></td>
                                <td>
                                    <ul>
                                        <li>Realizar una debida diligencia razonable sobre los casos de los Beneficiarios que gestiona.</li>
                                        <li>Gestionar la logística de recepción y entrega de donaciones de manera segura, eficiente y transparente.</li>
                                        <li>Mantener la más estricta confidencialidad sobre los datos personales y sensibles de los Beneficiarios.</li>
                                        <li>Cumplir con todas las leyes y regulaciones aplicables a su operación.</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>Utilizar los datos de los Beneficiarios para cualquier fin distinto a la gestión de su solicitud.</li>
                                        <li>Solicitar o aceptar cualquier tipo de pago o contraprestación por parte de los Beneficiarios.</li>
                                        <li>Condicionar la ayuda a la adhesión a creencias religiosas, políticas o de otra índole.</li>
                                        <li>Compartir los datos de los Beneficiarios con terceros no autorizados.</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Donante</strong></td>
                                <td>
                                    <ul>
                                        <li>Garantizar, según su leal saber y entender, que los insumos médicos donados no están caducados, dañados, alterados o son ilegales.</li>
                                        <li>Proporcionar una descripción precisa y veraz del producto que se dona.</li>
                                        <li>Entender que la donación es un acto altruista y no esperar ninguna contraprestación.</li>
                                        <li>Empaquetar los insumos de manera segura para su transporte.</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <li>Donar productos que requieran receta médica, sustancias controladas o cualquier insumo ilegal.</li>
                                        <li>Proporcionar información falsa o engañosa sobre el producto donado.</li>
                                        <li>Intentar contactar directamente a los Beneficiarios o a las Organizaciones fuera de los canales autorizados.</li>
                                        <li>Utilizar la plataforma para promocionar productos o servicios comerciales.</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="mt-4 mb-3">5. Propiedad Intelectual</h4>
                <p>Todo el contenido de la Plataforma, incluyendo, pero no limitado a, texto, gráficos, logos, iconos, imágenes y software, es propiedad de DoSys o de sus licenciantes y está protegido por las leyes de propiedad intelectual.</p>

                <h4 class="mt-4 mb-3">6. Duración y Terminación</h4>
                <p>Estos Términos permanecerán en vigor mientras utilice la Plataforma. DoSys se reserva el derecho de suspender o cancelar el acceso de cualquier Usuario a la Plataforma, sin previo aviso, si considera que ha habido un incumplimiento de estos Términos.</p>

                <h4 class="mt-4 mb-3">7. Modificación de los Términos</h4>
                <p>DoSys se reserva el derecho de modificar estos Términos en cualquier momento. Notificaremos a los Usuarios sobre cambios sustanciales a través de la Plataforma o por correo electrónico. El uso continuado de la Plataforma después de la notificación constituirá la aceptación de los nuevos Térmimos.</p>

                <h4 class="mt-4 mb-3">8. Ley Aplicable y Jurisdicción</h4>
                <p>Estos Términos se regirán e interpretarán de conformidad con las leyes de los Estados Unidos Mexicanos. Para cualquier controversia que surja en relación con la Plataforma, las partes se someten a la jurisdicción de los tribunales competentes de la Ciudad de México, renunciando a cualquier otro fuero que pudiera corresponderles.</p>

                <h4 class="mt-4 mb-3">9. Contacto</h4>
                <p>Para cualquier pregunta o aclaración sobre estos Términos y Condiciones, por favor contáctenos a través de:<br><strong>contacto@dosys.com</strong></p>

            </div>
        </div>
    </div>
    <?php require_once 'templates/footer.php'; ?>
    
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> 
    
    <?php require_once 'templates/scripts.php'; ?>
</body>
</html>