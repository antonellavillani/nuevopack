<?php
session_start();
require_once '../../config/config.php';

$error = '';
$mensaje = '';
$token = $_GET['token'] ?? null;
$origen = $_GET['origen'] ?? null;

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
            <a href="<?= $volverURL ?>" class="btn-guardar"><?= $volverTexto ?></a>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.formulario-admin');
    const passwordInput = form.querySelector('input[name="password"]');
    const confirmarInput = form.querySelector('input[name="confirmar"]');
    const errorContainer = document.createElement('p');
    errorContainer.classList.add('mensaje-error');
    form.insertBefore(errorContainer, form.firstChild);

    form.addEventListener('submit', function (e) {
        const password = passwordInput.value;
        const confirmar = confirmarInput.value;

        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (!password || !confirmar) {
            e.preventDefault();
            errorContainer.textContent = "Ambos campos son obligatorios.";
        } else if (password !== confirmar) {
            e.preventDefault();
            errorContainer.textContent = "Las contraseñas no coinciden.";
        } else if (!regex.test(password)) {
            e.preventDefault();
            errorContainer.textContent = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
        } else {
            errorContainer.textContent = '';
        }
    });
});
</script>

</body>
</html>
