<?php
session_start();
require_once '../../config/config.php';
require_once __DIR__ . '/../../admin-xyz2025/config_secrets.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../backend/PHPMailer-master/PHPMailer-master/src/Exception.php';
require '../../backend/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../../backend/PHPMailer-master/PHPMailer-master/src/SMTP.php';

$error = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Por favor, ingresá tu email.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios_especiales WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $origen = $_POST['origen'] ?? $_GET['origen'] ?? 'admin';
            $token = bin2hex(random_bytes(32));
            $expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Guardar token en la base de datos
            $stmt = $pdo->prepare("UPDATE usuarios_especiales SET token_recuperacion = ?, token_expiracion = ? WHERE email = ?");
            $stmt->execute([$token, $expiracion, $email]);

            // Enviar email
            $enlace = ApiConfig::BASE_URL . "admin-xyz2025/recuperacion/resetear_password.php?token=$token&origen=" . urlencode($origen);
            $asunto = "Recuperación de contraseña - NuevoPack";

            $cuerpo = '
            <div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
              <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h2 style="color: #333333;">🔐 Recuperación de contraseña</h2>
                <p>Hola <strong>' . htmlspecialchars($usuario['nombre']) . '</strong>,</p>
                <p>Recibimos una solicitud para restablecer tu contraseña. Hacé clic en el siguiente enlace para continuar:</p>
                <p><a href="' . $enlace . '" style="color: #6A7348;">Restablecer contraseña</a></p>
                <p>Este enlace será válido por <strong>1 hora</strong>.</p>
                <p>Si no solicitaste esto, ignorá este mensaje.</p>
              </div>
            </div>';

            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';

            try {
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USER;
                $mail->Password = SMTP_PASSWORD;
                $mail->SMTPSecure = 'tls';
                $mail->Port = SMTP_PORT;

                $mail->setFrom(SMTP_USER, 'NuevoPack');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = $asunto;
                $mail->Body = $cuerpo;

                $mail->send();
                $mensaje = "Revisá tu correo electrónico para continuar con la recuperación.";
            } catch (Exception $e) {
                $error = "No se pudo enviar el correo: " . $mail->ErrorInfo;
            }
        } else {
            $error = "El email no está registrado.";
        }
    }
}
?>
