<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

// Obtener todos los usuarios especiales
$stmt = $conn->query("SELECT id, nombre, apellido, email, telefono FROM usuarios_especiales ORDER BY id DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="estilos/estilos_admin.css">
</head>
<body>
    <div class="admin-container">
        <h2 class="titulo">Usuarios Especiales</h2>

        <?php if (isset($_GET['mensaje'])): ?>
            <p class="mensaje-exito"><?= htmlspecialchars($_GET['mensaje']) ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['mensaje_error'])): ?>
            <p class="mensaje-error"><?= htmlspecialchars($_GET['mensaje_error']) ?></p>
        <?php endif; ?>

        <a class="btn-agregar" href="usuarios_abm/usuarios_crear.php">+ Agregar Usuario</a>

        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                        <td>
                            <a class="btn-editar" href="usuarios_abm/usuarios_editar.php?id=<?= $usuario['id'] ?>">Editar</a>
                            <a class="btn-eliminar" href="usuarios_abm/usuarios_eliminar.php?id=<?= $usuario['id'] ?>" onclick="return confirm('¿Estás seguro de que querés eliminar este usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($usuarios) === 0): ?>
                    <tr>
                        <td colspan="6">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <a href="dashboard.php">← Volver al Dashboard</a>
    </div>
</body>
</html>
