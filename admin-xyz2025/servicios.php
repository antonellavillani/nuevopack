<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

$stmt = $conn->query("SELECT * FROM servicios ORDER BY id ASC");
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['accion']) && $_GET['accion'] === 'crear') {
    header('Location: servicios_abm/servicios_crear.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">    
<head>
    <meta charset="UTF-8">
    <title>Servicios | NuevoPack Dashboard</title>
    <link rel="stylesheet" href="estilos/estilos_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-servicio">Gestión de Servicios</h2>

        <a href="servicios_abm/servicios_crear.php" class="boton-nuevo">
            <i class="fa-solid fa-plus"></i>Nuevo Servicio
        </a>

        <?php if (count($servicios) === 0): ?>
            <p>No hay servicios cargados aún.</p>
        <?php else: ?>
            <table class="tabla-bd">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicios as $serv): ?>
                        <tr>
                            <td><?= $serv['id'] ?></td>
                            <td><?= htmlspecialchars($serv['nombre']) ?></td>
                            <td>
                                <img src="../uploads/<?= $serv['foto'] ?>" alt="Imagen" class="img-tabla">
                            </td>
                            <td>
                                <a href="servicios_abm/servicios_editar.php?id=<?= $serv['id'] ?>" class="btn-editar-tabla">Editar</a>
                                <a href="servicios_abm/servicios_eliminar.php?id=<?= $serv['id'] ?>" class="btn-eliminar-tabla" onclick="return confirm('¿Estás seguro que querés eliminar este servicio?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <br>
        <a href="dashboard.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver al Dashboard</a>
    </div>
</body>
</html>
