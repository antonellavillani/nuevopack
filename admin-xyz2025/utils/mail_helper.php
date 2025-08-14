<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '../../../backend/PHPMailer-master/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '../../../backend/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '../../../backend/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '../../config_secrets.php';

function enviarLinkRecuperacion($pdo, $usuario, $origen = 'admin') {
    $token = bin2hex(random_bytes(32));
    $expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Guardar token
    $stmt = $pdo->prepare("UPDATE usuarios_especiales SET token_recuperacion = ?, token_expiracion = ? WHERE id = ?");
    $stmt->execute([$token, $expiracion, $usuario['id']]);

    // Enlace
    $enlace = ApiConfig::BASE_URL . "admin-xyz2025/recuperacion/resetear_password.php?token=$token&origen=" . urlencode($origen);

    // Cuerpo HTML
    $cuerpo = "
    <div style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;'>
      <div style='max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
        <h2 style='color: #333333;'>🔐 Restablecer contraseña</h2>
        <p>Hola <strong>" . htmlspecialchars($usuario['nombre']) . "</strong>,</p>
        <p>Para restablecer tu contraseña, hacé clic en el siguiente enlace:</p>
        <p><a href='$enlace' style='color: #6A7348;'>Restablecer contraseña</a></p>
        <p>Este enlace será válido por <strong>1 hora</strong>.</p>
        <p>Si no solicitaste esto, ignorá este mensaje.</p>
      </div>
    </div>";

    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port = SMTP_PORT;

        $mail->setFrom(SMTP_USER, 'NuevoPack');
        $mail->addAddress($usuario['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Restablecer contraseña - NuevoPack';
        $mail->Body = $cuerpo;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}
