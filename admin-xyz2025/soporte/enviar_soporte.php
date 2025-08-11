<?php
session_start();

require_once '../../config/config.php';
require_once '../config_secrets.php';

require_once '../../backend/PHPMailer-master/PHPMailer-master/src/Exception.php';
require_once '../../backend/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once '../../backend/PHPMailer-master/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno] $errstr en $errfile:$errline");
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'msg' => "Error interno: $errstr",
    ]);
    exit;
});

// Sanitizar entradas
$asunto  = trim($_POST['asunto'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

if (!$asunto || !$mensaje) {
    echo json_encode(['ok' => false, 'msg' => 'Faltan campos']);
    exit;
}

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER_SOPORTE;
    $mail->Password   = SMTP_PASSWORD_SOPORTE;
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(SMTP_USER_SOPORTE, 'NuevoPack Admin Web');
    $mail->addAddress(SMTP_USER_SOPORTE, 'Soporte NuevoPack');

    // Info del usuario logueado
    $nombre   = $_SESSION['nombre'] ?? '';
    $apellido = $_SESSION['apellido'] ?? '';
    $email    = $_SESSION['email'] ?? '';

    $mail->Subject = "[Soporte Web] " . $asunto;
    $mail->Body    = 
        "Mensaje enviado desde el Panel Web\n\n" .
        "Usuario: $nombre $apellido\n" .
        "Email: $email\n\n" .
        "Asunto: $asunto\n" .
        "Mensaje:\n$mensaje";

    // Si se adjunta imagen
    if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['tmp_name'])) {
        if (is_array($_FILES['imagen']['tmp_name'])) {
            // Varios archivos
            $numFiles = count($_FILES['imagen']['tmp_name']);
            for ($i = 0; $i < $numFiles; $i++) {
                if ($_FILES['imagen']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['imagen']['tmp_name'][$i];
                    $name = $_FILES['imagen']['name'][$i];
                    $mail->addAttachment($tmpName, $name);
                }
            }
        } else {
            // Solo un archivo
            if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['imagen']['tmp_name'];
                $name = $_FILES['imagen']['name'];
                $mail->addAttachment($tmpName, $name);
            }
        }
    }

    $mail->send();
    echo json_encode(['ok' => true]);

} catch (Exception $e) {
    error_log("Error PHPMailer: " . $e->getMessage());
    echo json_encode([
    'ok' => false, 
    'msg' => 'Error al enviar: ' . $e->getMessage(),
    'trace' => $e->getTraceAsString()
]);
}
