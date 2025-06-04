<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

// Configurar destinatario
$destinatario = 'villxni@gmail.com';

// Recolectar datos del formulario
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$servicios = isset($_POST['servicios']) ? formatearOpciones(implode(',', $_POST['servicios'])) : 'Ninguno';
$descripcion = $_POST['descripcion'] ?? '';
$diseno = !empty($_POST['diseno']) ? formatearOpciones($_POST['diseno']) : 'No especificado';
$medio = !empty($_POST['medio']) ? formatearOpciones($_POST['medio']) : 'No especificado';
$conocio = $_POST['conocio'] ?? '';
if ($conocio === 'otro' && !empty($_POST['conocio_otro'])) {
    $conocio = 'Otro: ' . mb_convert_case(trim($_POST['conocio_otro']), MB_CASE_TITLE, "UTF-8");
} else {
    $conocio = formatearOpciones($conocio);
}


// Función de formato
function formatearOpciones($cadena) {
    $opciones = explode(',', $cadena);
    $formateadas = array_map(function($item) {
        $item = trim($item);
        $item = str_replace('_', ' ', $item); // Reemplaza guiones bajos por espacios
        $item = mb_convert_case($item, MB_CASE_TITLE, "UTF-8"); // Capitaliza cada palabra
        return $item;
    }, $opciones);
    return implode(', ', $formateadas);
}

// Crear el cuerpo del mensaje
$mensaje = '
<div style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
  <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h2 style="color: #333333;">📩 Nueva consulta desde el formulario</h2>
    <p><strong>👤 Nombre completo:</strong> ' . htmlspecialchars($nombre) . '</p>
    <p><strong>✉️ Correo electrónico:</strong> ' . htmlspecialchars($email) . '</p>
    <p><strong>📞 Teléfono:</strong> ' . htmlspecialchars($telefono) . '</p>
    <p><strong>🛠️ Servicios requeridos:</strong> ' . htmlspecialchars($servicios) . '</p>
    <p><strong>📝 Descripción del pedido:</strong><br>' . nl2br(htmlspecialchars($descripcion)) . '</p>
    <p><strong>🎨 ¿Tiene diseño?:</strong> ' . htmlspecialchars($diseno) . '</p>
    <p><strong>📱 Medio de contacto preferido:</strong> ' . htmlspecialchars($medio) . '</p>
    <p><strong>🌐 ¿Cómo nos conoció?:</strong> ' . htmlspecialchars($conocio) . '</p>
  </div>
</div>';


// Configurar PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'villxni@gmail.com';  // Correo de Gmail que recibe los emails
    $mail->Password = 'yefmddgboqolwlsr';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Configuración del remitente y destinatario
    $mail->setFrom('villxni@gmail.com', 'Formulario Imprenta');
    $mail->addAddress($destinatario);

    // Adjuntar archivo si fue enviado
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $mail->addAttachment($_FILES['archivo']['tmp_name'], $_FILES['archivo']['name']);
    }

    // Contenido del mensaje
    $mail->isHTML(true);
    $mail->Subject = 'Nueva consulta desde la web';
    $mail->Body = $mensaje;

    $mail->send();
    echo "¡Mensaje enviado correctamente!";
} catch (Exception $e) {
    echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
}
