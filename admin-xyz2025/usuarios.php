<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

// Obtener todos los usuarios especiales
$stmt = $pdo->query("SELECT * FROM usuarios_especiales ORDER BY id ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['accion']) && $_GET['accion'] === 'crear') {
    header('Location: usuarios_abm/usuarios_crear.php');
    exit();
}

include ("includes/header.php");
?>

<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-usuario">Usuarios Especiales</h2>

        <?php if (isset($_GET['mensaje'])): ?>
            <p class="mensaje-exito"><?= htmlspecialchars($_GET['mensaje']) ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['mensaje_error'])): ?>
            <p class="mensaje-error"><?= htmlspecialchars($_GET['mensaje_error']) ?></p>
        <?php endif; ?>

        <a href="usuarios_abm/usuarios_crear.php" class="boton-nuevo">
            <i class="fa-solid fa-plus"></i>Agregar Usuario
        </a>

        <table class="tabla-bd">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Aprobado</th>
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
                            <?= $usuario['aprobado'] ? 'Aprobado' : 'No aprobado' ?>
                        </td>

                        <td>
                            <a href="usuarios_abm/usuarios_editar.php?id=<?= $usuario['id'] ?>" class="btn-editar-tabla">Editar</a>
                            <a href="usuarios_abm/usuarios_eliminar.php?id=<?= $usuario['id'] ?>" onclick="return confirm('¿Estás seguro de que querés eliminar este usuario?')" class="btn-eliminar-tabla">Eliminar</a>
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
        <a href="dashboard.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver al Dashboard</a>
    </div>
</body>
</html>
