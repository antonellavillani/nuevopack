<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

require_once '../config/config.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios_especiales WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['admin_id'] = $usuario['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Email o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        $error = "Error al conectarse a la base de datos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Panel de Administración NuevoPack</title>
    <link rel="stylesheet" href="estilos/estilos_admin.css">
</head>
<body class="body-login">
    <div class="login-card">
        <h2>Panel de Administración</h2>
        <form method="post" class="form-login">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <a href="recuperacion/recuperar_password.php" class="link-olvido">¿Olvidaste tu contraseña?</a>
            <button type="submit" class="btn-ingresar">Ingresar</button>
        </form>

        <div class="separador">o</div>

        <a href="google_login.php" class="btn-google">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" class="icono-google">
            Iniciar sesión con Google
        </a>

        <?php if ($error): ?>
            <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <a href="../index.php" class="link-volver">← Volver al Inicio</a>

    </div>
</body>
</html>
