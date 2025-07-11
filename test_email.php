<?php
// Muestra todos los errores para una depuración completa
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

// Crea una nueva instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Habilitar la salida de depuración detallada
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Esto nos dará muchísima información

    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contacto@dosys.mx';
    $mail->Password   = 'wg=WO24L49_R'; // La contraseña correcta
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Remitente y Destinatario
    $mail->setFrom('contacto@dosys.mx', 'Prueba DoSys');
    $mail->addAddress('realloyal1a@gmail.com', 'Receptor de Prueba'); // <-- PON AQUÍ TU CORREO PERSONAL

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Correo de prueba desde DoSys';
    $mail->Body    = 'Si recibes este correo, ¡la conexión SMTP funciona!';
    $mail->AltBody = 'La conexión SMTP funciona.';

    $mail->send();
    echo '<h3>¡Correo enviado exitosamente!</h3>';

} catch (Exception $e) {
    echo "<h3>El mensaje no pudo ser enviado. Error de PHPMailer:</h3><pre>{$mail->ErrorInfo}</pre>";
}
?>