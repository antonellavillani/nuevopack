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
        $stmt = $conn->prepare("SELECT * FROM usuarios_especiales WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_nombre'] = $usuario['nombre'];
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
    <title>Login Admin</title>
    <link rel="stylesheet" href="estilos_admin.css">
</head>
<body class="admin-login">
    <div class="login-container">
        <h2>Panel de Administración</h2>
        <form method="post" class="form-login">
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Contraseña:</label>
            <input type="password" name="password" required>
            <button type="submit">Ingresar</button>
        </form>
        <?php if ($error): ?>
            <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <br>
        <a href="../index.php">← Volver al Inicio</a>
    </div>
</body>
</html>
