<?php
require_once '../../config/config.php';
require_once '../../admin-xyz2025/config_secrets.php';

require_once '../PHPMailer-master/PHPMailer-master/src/Exception.php';
require_once '../PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$asunto  = $input['asunto'] ?? '';
$mensaje = $input['mensaje'] ?? '';
$imagenes = $input['imagenes'] ?? [];

if(!$asunto || !$mensaje){
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

    $mail->setFrom(SMTP_USER_SOPORTE, 'NuevoPack Admin App');
    $mail->addAddress(SMTP_USER_SOPORTE, 'Soporte NuevoPack');

    $mail->Subject = $asunto;
    $mail->Body = $mensaje;

    // Si se adjunta foto
    if (is_array($imagenes)) {
        foreach ($imagenes as $index => $base64) {
            $data = base64_decode($base64);
            $temp = tempnam(sys_get_temp_dir(), 'img_') . ".jpg";
            file_put_contents($temp, $data);
            $mail->addAttachment($temp, "imagen_$index.jpg");
        }
    }

    $mail->send();
    echo json_encode(['ok' => true]);

} catch (Exception $e) {
    error_log("Error PHPMailer: " . $e->getMessage());
    echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
}
