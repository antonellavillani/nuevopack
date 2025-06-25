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
    $aprobado = isset($_POST['aprobado']) ? 1 : 0;

    if (empty($nombre) || empty($apellido) || empty($email) || empty($telefono)) {
        $error = "Todos los campos excepto 'Contraseña' son obligatorios.";
    } else {
        try {
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios_especiales SET nombre = ?, apellido = ?, email = ?, telefono = ?, password_hash = ?, aprobado = ? WHERE id = ?");
                $stmt->execute([$nombre, $apellido, $email, $telefono, $password_hash, $aprobado, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE usuarios_especiales SET nombre = ?, apellido = ?, email = ?, telefono = ?, aprobado = ? WHERE id = ?");
                $stmt->execute([$nombre, $apellido, $email, $telefono, $aprobado, $id]);
            }

            // Registrar actividad
            $descripcionActividad = 'Usuario "' . htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido) . '" editado (' . htmlspecialchars($email) . ')';
            $stmtActividad = $conn->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
            $stmtActividad->execute(['usuario', $descripcionActividad]);

            header("Location: ../usuarios.php?mensaje=Usuario actualizado correctamente.");
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
    <title>Editar Usuario | Panel de Administración NuevoPack</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-editar">Editar Usuario Especial</h2>

        <?php if ($error): ?>
            <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="formulario-admin">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>

            <label>Nueva contraseña (opcional):</label>
            <input type="password" name="password">

            <label>
                <input type="checkbox" name="aprobado" <?= $usuario['aprobado'] ? 'checked' : '' ?>>
                Usuario aprobado
            </label>
            <small id="usuario-aprobado">
                Si esta opción está marcada, el usuario podrá ingresar al sistema. Si no, quedará deshabilitado.
            </small>

            <div class="form-botones">
                <button type="submit" class="btn-guardar">Actualizar</button>
            </div>
        </form>
        <a href="../usuarios.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>
</body>
</html>
