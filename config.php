<?php
/**
 * Archivo de Configuración Principal de DoSys
 */

// ==================================================================
// PARA DEPURACIÓN: Muestra errores detallados.
// ¡Recuerda quitar estas 3 líneas antes de pasar a producción!
// ==================================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// ==================================================================
// URL BASE DEL PROYECTO
// Define la "dirección completa" de tu sitio para que las rutas
// a imágenes, CSS y otros enlaces siempre funcionen.
//
// -> Si tu proyecto se accede desde http://localhost/
//    Usa: define('BASE_URL', '/');
//
// -> Si tu proyecto se accede desde http://localhost/dosys/
//    Usa: define('BASE_URL', '/dosys/');
// ==================================================================
define('BASE_URL', 'http://localhost/Dosys/');

/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN DE SERVIDOR DE CORREO (SMTP)
|--------------------------------------------------------------------------
|
| Credenciales para el envío de correos a través de PHPMailer.
| Es una buena práctica mantenerlas aquí en lugar de directamente en el script.
|
*/
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_USER', 'contacto@dosys.mx');
define('SMTP_PASS', 'wg=WO24L49_R'); // ¡Considera usar variables de entorno para esto en producción!
define('SMTP_PORT', 465);

?>