<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

// Configurar destinatario
$destinatario = 'nuevopack@gmail.com';

// Recolectar datos del formulario
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$servicios = isset($_POST['servicios']) ? implode(', ', $_POST['servicios']) : 'Ninguno';
$descripcion = $_POST['descripcion'] ?? '';
$diseno = $_POST['diseno'] ?? '';
$medio = $_POST['medio'] ?? '';
$conocio = $_POST['conocio'] ?? 'No especificado';
if ($conocio === 'otro' && !empty($_POST['conocio_otro'])) {
    $conocio = 'Otro: ' . $_POST['conocio_otro'];
}

// Crear el cuerpo del mensaje
$mensaje = "
<h2>Nueva consulta desde el formulario</h2>
<p><strong>Nombre completo:</strong> {$nombre}</p>
<p><strong>Correo electrónico:</strong> {$email}</p>
<p><strong>Teléfono:</strong> {$telefono}</p>
<p><strong>Servicios requeridos:</strong> {$servicios}</p>
<p><strong>Descripción del pedido:</strong><br>{$descripcion}</p>
<p><strong>¿Tiene diseño?:</strong> {$diseno}</p>
<p><strong>Medio de contacto preferido:</strong> {$medio}</p>
<p><strong>¿Cómo nos conoció?:</strong> {$conocio}</p>
";

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
