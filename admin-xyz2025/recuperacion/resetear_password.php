<?php
session_start();
require_once '../../config/config.php';

$error = '';
$mensaje = '';
$token = $_GET['token'] ?? null;

if (!$token) {
    die("Token inválido.");
}

$stmt = $conn->prepare("SELECT * FROM usuarios_especiales WHERE token_recuperacion = ?");
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
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios_especiales SET password_hash = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE id = ?");
        $stmt->execute([$hash, $usuario['id']]);
        $mensaje = "Tu contraseña fue restablecida correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña | NuevoPack</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
</head>
<body>
    <div class="contenedor">
        <h2 class="titulo-pagina">Nueva Contraseña</h2>
        <?php if ($error): ?>
            <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($mensaje): ?>
            <p class="mensaje-ok"><?= htmlspecialchars($mensaje) ?></p>
            <a href="../login.php" class="btn-guardar">Iniciar sesión</a>
        <?php else: ?>
            <form method="POST" class="formulario-admin">
                <label>Nueva contraseña:</label>
                <input type="password" name="password" required>
                <label>Confirmar contraseña:</label>
                <input type="password" name="confirmar" required>
                <button type="submit" class="btn-guardar">Restablecer</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
