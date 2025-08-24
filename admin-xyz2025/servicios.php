<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

$stmt = $pdo->query("SELECT * FROM servicios ORDER BY id ASC");
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['accion']) && $_GET['accion'] === 'crear') {
    header('Location: servicios_abm/servicios_crear.php');
    exit();
}

include ("includes/header.php");
?>

<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-servicio">Gestión de Servicios</h2>

        <a href="servicios_abm/servicios_crear.php" class="boton-nuevo">
            <i class="fa-solid fa-plus"></i>Nuevo Servicio
        </a>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alerta error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alerta success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>


        <?php if (count($servicios) === 0): ?>
            <p>No hay servicios cargados aún.</p>
        <?php else: ?>
            <table class="tabla-bd">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
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
                                <?php
                                    $descripcion = htmlspecialchars($serv['descripcion']);
                                    $resumen = strlen($descripcion) > 100 ? substr($descripcion, 0, 100) . '...' : $descripcion;
                                ?>
                                <?= $resumen ?>
                                <?php if (strlen($descripcion) > 100): ?>
                                    <button class="btn-ver-mas" onclick="mostrarModal(`<?= addslashes($descripcion) ?>`)">Ver más</button>
                                <?php endif; ?>
                            </td>

                            <td>
                                <img src="../uploads/<?= $serv['foto'] ?>" alt="Imagen" class="img-tabla">
                            </td>
                            <td>
                                <a href="servicios_abm/servicios_editar.php?id=<?= $serv['id'] ?>" class="btn-editar-tabla">Editar</a>
                                <a href="servicios_abm/servicios_eliminar.php?id=<?= $serv['id'] ?>" class="btn-eliminar-tabla" data-nombre="<?= htmlspecialchars($serv['nombre']) ?>">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <br>
        <a href="dashboard.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver al Dashboard</a>
    </div>

    <!-- Modal personalizado -->
<div id="modalDescripcion" class="modal-descripcion">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModal()">&times;</span>
        <p id="textoCompleto"></p>
    </div>
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
