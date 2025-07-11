<?php
// Muestra errores solo si algo sale muy mal
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Usar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Cargar el autoloader de Composer
require 'vendor/autoload.php';

// Incluir la conexión a la BD
require 'conexion_local.php';

// Variable para mensajes al usuario
$user_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600);

        $stmt_update = $conexion->prepare("UPDATE usuarios SET reset_token = ?, reset_token_expires_at = ? WHERE email = ?");
        $stmt_update->bind_param("sss", $token, $expires, $email);
        $stmt_update->execute();

        // --- Bloque de envío de correo (verificado y funcional) ---
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP de Hostinger
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'contacto@dosys.mx';
            $mail->Password   = 'wg=WO24L49_R'; // Tu contraseña
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Remitente y Destinatario
            $mail->setFrom('contacto@dosys.mx', 'DoSys Plataforma');
            $mail->addAddress($email);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperacion de Contrasena - DoSys';
            // ¡Importante! Asegúrate de que esta URL apunte a tu sitio real cuando lo subas.
            $reset_link = "http://localhost/dosys/reset_password.php?token=" . $token;
            $mail->Body    = "Hola,<br><br>Para restablecer tu contraseña, haz clic en el siguiente enlace:<br><a href='{$reset_link}'>Restablecer Contraseña</a><br><br>El enlace expirará en 1 hora.";

            $mail->send();
            $user_message = 'Si tu correo está registrado, recibirás un enlace de recuperación.';

        } catch (Exception $e) {
            $user_message = "El mensaje no pudo ser enviado. Por favor, contacta al soporte.";
            // Para ti (desarrollador), puedes registrar el error detallado en un archivo
            // error_log("Mailer Error: {$mail->ErrorInfo}");
        }
    } else {
        // Mensaje genérico por seguridad
        $user_message = 'Si tu correo está registrado, recibirás un enlace de recuperación.';
    }
} else {
    $user_message = 'Por favor, ingresa una dirección de correo.';
}

// Mostrar el mensaje al usuario y redirigir
echo "<script>
        alert('" . addslashes($user_message) . "');
        window.location.href = 'forgot_password.php';
      </script>";
exit();

?>