<?php
session_start();

// Verifica si el admin está logueado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../index.php");
    exit();
}

require_once('../config/config.php');

// Mostrar máx. 5 actividades recientes
$stmt = $pdo->query("SELECT descripcion, fecha FROM actividad_admin ORDER BY fecha DESC LIMIT 5");
$actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

include ("includes/header.php");
?>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="sidebar-title">Panel de Administrador NuevoPack</h2>
        <div class="nav-item"><a href="dashboard.php" class="inicio">Dashboard</a></div>
        <div class="nav-item"><a href="servicios.php" class="servicios">Servicios</a></div>
        <div class="nav-item"><a href="precios.php" class="precios">Precios</a></div>
        <div class="nav-item"><a href="usuarios.php" class="usuarios">Usuarios</a></div>
        <div class="nav-item">
            <a href="#" id="btn-mi-cuenta" class="mi-cuenta">Mi cuenta</a>
        </div>
        <div class="nav-item"><a href="soporte.php" class="soporte">Soporte</a></div>
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

    <!-- Contenido principal -->
    <div class="main-content">
        <h1 class="titulo-pagina">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>

        <!-- Resumen -->
        <div class="card-container">
            <div class="card">
                <h3>Servicios</h3>
                <p><?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM servicios");
                    echo $stmt->fetchColumn() . " cargados";
                ?></p>
            </div>
            <div class="card">
                <h3>Precios</h3>
                <p><?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM precios_servicios");
                    echo $stmt->fetchColumn() . " registrados";
                ?></p>
            </div>
            <div class="card">
                <h3>Usuarios</h3>
                <p><?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios_especiales");
                    echo $stmt->fetchColumn() . " activos";
                ?></p>
            </div>
        </div>

        <!-- Accesos rápidos -->
        <div class="quick-actions">
            <h2 class="titulo-seccion">Accesos Rápidos</h2>
            <div class="botones-rapidos">
                <button onclick="window.location.href='servicios.php?accion=crear'" class="boton-acceso">
                    <i class="fa-solid fa-plus"></i>Nuevo Servicio
                </button>
                <button onclick="window.location.href='precios.php?accion=crear'" class="boton-acceso">
                    <i class="fa-solid fa-plus"></i>Nuevo Precio
                </button>
                <button onclick="window.location.href='usuarios.php?accion=crear'" class="boton-acceso">
                    <i class="fa-solid fa-plus"></i>Nuevo Usuario
                </button>
            </div>        
        </div>

        <!-- Actividad reciente -->
        <div class="activity-log">
            <h2 class="actividad-titulo titulo-seccion">Actividad Reciente</h2>
            <ul class="lista-actividad">
                <?php
                require_once '../config/config.php';

                $stmt = $pdo->query("SELECT descripcion, fecha FROM actividad_admin ORDER BY fecha DESC LIMIT 10");
                $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($actividades) === 0) {
                    echo "<li class='actividad-ok'>Sin actividad reciente.</li>";
                } else {
                    foreach ($actividades as $act) {
                        $descripcion = htmlspecialchars($act['descripcion']);
                        $fechaFormateada = date("d/m/Y H:i", strtotime($act['fecha']));
                        echo "<li>$descripcion <span class='fecha-actividad'>($fechaFormateada)</span>
                            <form action='eliminar_actividad.php' method='POST' style='display:inline; margin-left:10px;'>
                                <input type='hidden' name='descripcion' value='" . htmlspecialchars($act['descripcion'], ENT_QUOTES) . "'>
                                <button type='submit' class='btn-eliminar-actividad' title='Eliminar esta actividad'>🗑️</button>
                            </form>
                        </li>";
                    }
                }
                ?>
            </ul>
        </div>

        <!-- Alertas -->
        <div class="alerts">
            <h2 class="alertas-titulo titulo-seccion">Alertas</h2>
            <ul class="lista-actividad">
                <?php
                    require_once '../config/config.php';
                    $alertas = [];

                    // 1. Servicios sin imagen o descripción
                    $stmt = $pdo->query("SELECT nombre, foto, descripcion FROM servicios");
                    while ($s = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (empty($s['foto'])) {
                            $alertas[] = "El servicio \"{$s['nombre']}\" no tiene imagen.";
                        }
                        if (empty($s['descripcion'])) {
                            $alertas[] = "El servicio \"{$s['nombre']}\" no tiene descripción.";
                        }
                    }

                    // 2. Precios sin descripción o tipo_unidad
                    $stmt = $pdo->query("SELECT descripcion, tipo_unidad FROM precios_servicios");
                    while ($p = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (empty($p['descripcion'])) {
                            $alertas[] = "Un precio no tiene descripción.";
                        }
                        if (empty($p['tipo_unidad'])) {
                            $alertas[] = "Un precio no tiene tipo de unidad.";
                        }
                    }

                    // 3. Usuarios especiales sin teléfono o no aprobados
                    $stmt = $pdo->query("SELECT email, telefono, aprobado FROM usuarios_especiales");
                    while ($u = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (empty($u['telefono'])) {
                            $alertas[] = "El usuario \"{$u['email']}\" no tiene número de teléfono.";
                        }
                        if ((int)$u['aprobado'] === 0) {
                            $alertas[] = "El usuario \"{$u['email']}\" aún no fue aprobado.";
                        }
                    }

                    // Mostrar
                    if (empty($alertas)) {
                        echo "<li class='alerta-ok'>No hay alertas. Todo está en orden</li>";
                    } else {
                        foreach ($alertas as $a) {
                            echo "<li class='alerta-item'>" . htmlspecialchars($a) . "</li>";
                        }
                    }
                ?>
            </ul>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="js/script.js"></script>
</body>
</html>