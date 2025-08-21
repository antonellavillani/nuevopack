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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña | Panel de Administración NuevoPack</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="body-login">
    <div class="login-card">
        <h2>Recuperar contraseña</h2>

        <form id="formRecuperar" method="post" class="form-login">
            <input type="email" name="email" placeholder="Ingresá tu email" required>
            <button type="submit" class="btn-ingresar">Recuperar contraseña</button>
        </form>

        <!-- Spinner -->
        <div id="spinner" class="dot-spinner" style="display: none; margin: 20px auto;">
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
            <div class="dot-spinner__dot"></div>
        </div>

        <!-- Respuesta -->
        <div id="respuesta" class="mensaje-envio-mail texto-centrado"></div>

        <?php if ($mensaje): ?>
            <p class="mensaje-exito"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <a href="../login.php" class="link-volver">← Volver al login</a>
    </div>

    <!-- JavaScript -->
    <script src="../js/script.js"></script>
</body>
</html>
