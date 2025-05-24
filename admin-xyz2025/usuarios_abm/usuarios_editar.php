<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../index.php");
    exit();
}

require_once '../../config/config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: ../usuarios.php");
    exit();
}

$error = '';
$mensaje = '';

try {
    $stmt = $conn->prepare("SELECT * FROM usuarios_especiales WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        header("Location: ../usuarios.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Error al obtener usuario: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $password = $_POST['password'];

    if (empty($nombre) || empty($apellido) || empty($email) || empty($telefono)) {
        $error = "Todos los campos excepto contraseña son obligatorios.";
    } else {
        try {
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios_especiales SET nombre = ?, apellido = ?, email = ?, telefono = ?, password_hash = ? WHERE id = ?");
                $stmt->execute([$nombre, $apellido, $email, $telefono, $password_hash, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE usuarios_especiales SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE id = ?");
                $stmt->execute([$nombre, $apellido, $email, $telefono, $id]);
            }

            header("Location: ../usuarios.php?mensaje=Usuario actualizado correctamente");
            exit();
        } catch (PDOException $e) {
            $error = "Error al actualizar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
</head>
<body>
    <div class="admin-container">
        <h2 class="titulo">Editar Usuario Especial</h2>

        <?php if ($error): ?>
            <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="form-admin">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>

            <label>Nueva Contraseña (opcional):</label>
            <input type="password" name="password">

            <div class="form-botones">
                <button type="submit" class="btn-guardar">Actualizar</button>
                <a href="../usuarios.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
