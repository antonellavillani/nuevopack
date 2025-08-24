<?php
// Detectar el archivo actual
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="navbar">
    <a href="dashboard.php" class="nav-link inicio <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>" data-title="Dashboard"></a>
    <a href="servicios.php" class="nav-link servicios <?= ($currentPage == 'servicios.php') ? 'active' : '' ?>" data-title="Servicios"></a>
    <a href="precios.php" class="nav-link precios <?= ($currentPage == 'precios.php') ? 'active' : '' ?>" data-title="Precios"></a>
    <a href="usuarios.php" class="nav-link usuarios <?= ($currentPage == 'usuarios.php') ? 'active' : '' ?>" data-title="Usuarios"></a>
    <a href="#" id="btn-mi-cuenta" class="nav-link mi-cuenta <?= ($currentPage == 'mi-cuenta.php') ? 'active' : '' ?>" data-title="Mi cuenta"></a>
    <a href="soporte.php" class="nav-link soporte <?= ($currentPage == 'soporte.php') ? 'active' : '' ?>" data-title="Soporte"></a>
    <a href="logout.php?volver_web=1" class="nav-link volver-pagina" data-title="Volver a la web"></a>
</div>

<!-- Modal Mi Cuenta -->
<div id="modal-mi-cuenta" class="modal">
    <div class="modal-content">
        <span id="cerrar-modal" class="close">&times;</span>
        <i class="fa-duotone fa-solid fa-circle-user" id="modal-mi-cuenta-icono"></i>
        <h2 id="titulo-modal"><?= htmlspecialchars($_SESSION['nombre']) . ' ' . htmlspecialchars($_SESSION['apellido']) ?></h2>
        <p id="email-modal"><?= htmlspecialchars($_SESSION['email']) ?></p>
        <button type="button" id="btn-logout" class="btn-cerrar-sesion">Cerrar sesión</button>
    </div>
</div>

<!-- Modal Cerrar Sesión -->
<div id="modal-logout" class="modal">
    <div class="modal-content">
        <span id="cerrar-logout" class="close">&times;</span>
        <h2>¿Cerrar sesión?</h2>
        <p>¿Estás seguro que querés cerrar sesión?</p>
        <div class="modal-buttons">
            <button id="cancel-logout" class="btn-cancelar">Cancelar</button>
            <button id="confirm-logout" class="btn-confirmar">Cerrar sesión</button>
        </div>
    </div>
</div>
