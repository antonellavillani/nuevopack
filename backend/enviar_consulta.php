<?php
require_once __DIR__ . '/../admin-xyz2025/config_secrets.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

header('Content-Type: text/plain; charset=utf-8');

// Configurar destinatario
$destinatario = SMTP_USER;

// Recolectar datos del formulario
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$consulta = $_POST['consulta'] ?? '';

// Crear el cuerpo del mensaje
$mensaje = '
<div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
  <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h2 style="color: #333333;">📩 Nueva consulta desde la web</h2>
    <p><strong>👤 Nombre:</strong> ' . htmlspecialchars($nombre) . '</p>
    <p><strong>✉️ Correo electrónico:</strong> ' . htmlspecialchars($email) . '</p>
    <p><strong>📝 Consulta:</strong> ' . htmlspecialchars($consulta) . '</p>
  </div>
</div>';

// Configurar PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;  // Correo de Gmail que recibe los emails
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = 'tls';
    $mail->Port = SMTP_PORT;

    // Configuración del remitente y destinatario
    $mail->setFrom(SMTP_USER, 'Consulta Web');
    $mail->addAddress($destinatario);

    // Contenido del mensaje
    $mail->isHTML(true);
    $mail->Subject = 'Nueva consulta desde la web';
    $mail->Body = $mensaje;

    $mail->send();
    echo "Mensaje enviado correctamente.";
} catch (Exception $e) {
    http_response_code(500);
    echo "Ocurrió un error al enviar el mensaje. Intente nuevamente.";
}
