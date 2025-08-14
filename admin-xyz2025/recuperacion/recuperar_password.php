<?php
session_start();
require_once '../../config/config.php';
require_once __DIR__ . '/../../admin-xyz2025/config_secrets.php';
require_once __DIR__ . '/../../admin-xyz2025/utils/mail_helper.php';

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
            $resultado = enviarLinkRecuperacion($pdo, $usuario, $origen);

            if ($resultado === true) {
                $mensaje = "Revisá tu correo electrónico para continuar con la recuperación.";
            } else {
                $error = "No se pudo enviar el correo: " . $resultado;
            }
        } else {
            $error = "El email no está registrado.";
        }
    }
}
?>
