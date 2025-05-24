<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../index.php");
    exit();
}

require_once '../../config/config.php';

$error = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $password = $_POST['password'];

    if (empty($nombre) || empty($apellido) || empty($email) || empty($telefono) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO usuarios_especiales (nombre, apellido, email, telefono, password_hash) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellido, $email, $telefono, $password_hash]);
            header("Location: ../usuarios.php?mensaje=Usuario creado correctamente");
            exit();
        } catch (PDOException $e) {
            $error = "Error al insertar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
</head>
<body>
    <div class="admin-container">
        <h2 class="titulo">Crear Usuario Especial</h2>

        <?php if ($error): ?>
            <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="form-admin">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required>

            <div class="form-botones">
                <button type="submit" class="btn-guardar">Guardar</button>
                <a href="../usuarios.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
        <br>
        <a href="../usuarios.php">← Volver</a>
    </div>
</body>
</html>
