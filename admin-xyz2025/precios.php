<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/config.php';

// Obtener precios con nombre del servicio
$stmt = $conn->prepare("
    SELECT ps.id, ps.descripcion, ps.tipo_unidad, ps.precio, s.nombre AS servicio_nombre
    FROM precios_servicios ps
    INNER JOIN servicios s ON ps.servicio_id = s.id
    ORDER BY s.nombre ASC, ps.descripcion ASC
");
$stmt->execute();
$precios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Precios</title>
    <link rel="stylesheet" href="estilos/estilos_admin.css">
</head>
<body>
    <div class="admin-container">
        <h2 class="titulo">💲 Lista de Precios por Servicio</h2>
        <a href="precios_abm/precios_crear.php" class="btn-agregar">+ Nuevo Precio</a>
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($precios as $precio): ?>
                <tr>
                    <td><?php echo htmlspecialchars($precio['servicio_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($precio['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($precio['tipo_unidad']); ?></td>
                    <td>$<?php echo number_format($precio['precio'], 2); ?></td>
                    <td>
                        <a href="precios_abm/precios_editar.php?id=<?php echo $precio['id']; ?>" class="btn-editar">Editar</a>
                        <a href="precios_abm/precios_eliminar.php?id=<?php echo $precio['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que querés eliminar este precio?');">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn-volver">← Volver</a>
    </div>
</body>
</html>
