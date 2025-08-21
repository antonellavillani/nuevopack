<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/config.php';

// Obtener precios con nombre del servicio
$stmt = $pdo->prepare("
    SELECT ps.id, ps.descripcion, ps.tipo_unidad, ps.precio, s.nombre AS servicio_nombre
    FROM precios_servicios ps
    INNER JOIN servicios s ON ps.servicio_id = s.id
    ORDER BY s.nombre ASC, ps.descripcion ASC
");
$stmt->execute();
$precios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['accion']) && $_GET['accion'] === 'crear') {
    header('Location: precios_abm/precios_crear.php');
    exit();
}

include ("includes/header.php");
?>

<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-precio">Lista de Precios por Servicio</h2>
        <a href="precios_abm/precios_crear.php" class="boton-nuevo">
            <i class="fa-solid fa-plus"></i>Nuevo Precio
        </a>

        <table class="tabla-bd">
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
                        <a href="precios_abm/precios_editar.php?id=<?php echo $precio['id']; ?>" class="btn-editar-tabla">Editar</a>
                        <a href="precios_abm/precios_eliminar.php?id=<?php echo $precio['id']; ?>" class="btn-eliminar-tabla" data-nombre="<?= htmlspecialchars($precio['descripcion']) ?>" 
                            data-servicio="<?= htmlspecialchars($precio['servicio_nombre']) ?>">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver al Dashboard</a>
    </div>

    <!-- Modal de confirmación -->
<div id="modal-confirm" class="modal">
  <div class="modal-content">
    <span id="cerrar-modal-confirm" class="close">&times;</span>
    <h2 id="modal-titulo">Confirmación</h2>
    <p id="modal-mensaje">¿Estás seguro?</p>
    <div class="modal-buttons">
      <button id="modal-cancelar" class="btn-cancelar">Cancelar</button>
      <button id="modal-confirmar" class="btn-confirmar">Confirmar</button>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script src="js/script.js"></script>
</body>
</html>
