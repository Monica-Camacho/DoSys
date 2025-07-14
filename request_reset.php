<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Rutas desde la raíz del proyecto
require 'vendor/autoload.php';
require_once 'config.php';
require_once 'conexion_local.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // CORRECCIÓN: Buscamos el correo directamente en la tabla `usuarios`
    $sql = "SELECT id, tipo_usuario_id FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $usuario_id = $user['id'];

        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // CORRECCIÓN: Actualizamos el token en la tabla `usuarios`
        $sql_update = "UPDATE usuarios SET reset_token = ?, reset_token_expires_at = ? WHERE id = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("ssi", $token, $expires, $usuario_id);

        if ($stmt_update->execute()) {
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor (usando constantes de config.php)
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = SMTP_PORT;
                $mail->CharSet    = 'UTF-8';

                // Remitente y destinatario
                $mail->setFrom(SMTP_USER, 'Soporte DoSys');
                $mail->addAddress($email);

                // Contenido del correo desde la plantilla
                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de Contraseña - DoSys';

                $reset_link = BASE_URL . "reset_password.php?token=" . $token;
                $logo_url = BASE_URL . "img/logos/Dosys_largo_colorBlanco.png";
                $template_path = 'mail_templates/email_template.html';
                
                if (file_exists($template_path)) {
                    $body = file_get_contents($template_path);
                    $body = str_replace('{{reset_link}}', $reset_link, $body);
                    $body = str_replace('{{logo_url}}', $logo_url, $body);
                    $body = str_replace('{{year}}', date('Y'), $body);
                    $mail->Body = $body;
                } else {
                    $mail->Body = "Para restablecer tu contraseña, haz clic en el siguiente enlace: <a href='$reset_link'>$reset_link</a>";
                }

                $mail->send();
                $_SESSION['user_message'] = 'Si tu correo electrónico está registrado con nosotros, recibirás un enlace para restablecer tu contraseña.';
            } catch (Exception $e) {
                $_SESSION['error_message'] = "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        // Mensaje genérico para no revelar si un correo existe o no
        $_SESSION['user_message'] = 'Si tu correo electrónico está registrado con nosotros, recibirás un enlace para restablecer tu contraseña.';
    }
    
    header("Location: forgot_password.php");
    exit();
}
?>