<?php
session_start();
require_once '../../config/config.php';

$error = '';
$mensaje = '';
$token = $_GET['token'] ?? null;
$origen = $_POST['origen'] ?? $_GET['origen'] ?? null;

// Definir a dónde volver según el origen
if ($origen === 'admin') {
    $volverURL = "../usuarios.php";
    $volverTexto = "Volver al panel de usuarios";
} else {
    $volverURL = "../login.php";
    $volverTexto = "Iniciar sesión";
}

if (!$token) {
    die("Token inválido.");
}

$stmt = $pdo->prepare("SELECT * FROM usuarios_especiales WHERE token_recuperacion = ?");
$stmt->execute([$token]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario || strtotime($usuario['token_expiracion']) < time()) {
    die("El enlace expiró o no es válido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirmar = $_POST['confirmar'];

    if (empty($password) || empty($confirmar)) {
        $error = "Ambos campos son obligatorios.";
    } elseif ($password !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios_especiales SET password_hash = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE id = ?");
        $stmt->execute([$hash, $usuario['id']]);
        $mensaje = "Tu contraseña fue restablecida correctamente."; 
    }
}
?>

<!DOCTYPE html>
<html lang="es" class="html_resetear_password">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña | NuevoPack</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
</head>
<body class="body_resetear_password">
    <div class="contenedor_resetear_password">
        <h2 class="titulo_pagina_resetear_password">Nueva Contraseña</h2>

        <?php if ($error): ?>
            <p class="mensaje-error_resetear_password"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($mensaje): ?>
            <p class="mensaje-ok_resetear_password"><?= htmlspecialchars($mensaje) ?></p>
            
            <?php if ($origen === 'android'): ?>
                <a id="volver-app-btn" href="nuevopack://com.nuevopack.admin?mensaje=ok">Volver a la app</a>
            <?php else: ?>
                <a href="<?= $volverURL ?>" class="btn_guardar_resetear_password"><?= $volverTexto ?></a>
            <?php endif; ?>

        <?php endif; ?>

        <?php if (!$mensaje): ?>
            <form method="POST" class="formulario-admin_resetear_password" novalidate>
                <label>Nueva contraseña:</label>
                <input type="password" name="password" required autocomplete="new-password" aria-required="true" />

                <label>Confirmar contraseña:</label>
                <input type="password" name="confirmar" required autocomplete="new-password" aria-required="true" />

                <input type="hidden" name="origen" value="<?= htmlspecialchars($origen) ?>">

                <button type="submit" class="btn_guardar_resetear_password">Restablecer</button>
            </form>
        <?php endif; ?>
    </div>

<!-- JavaScript -->
<script src="../js/script.js"></script>
</body>
</html>
