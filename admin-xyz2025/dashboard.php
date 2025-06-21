<?php
session_start();

// Verifica si el admin está logueado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../index.php");
    exit();
}

require_once('../config/config.php');

// Mostrar máx. 5 actividades recientes
$stmt = $conn->query("SELECT descripcion, fecha FROM actividad_admin ORDER BY fecha DESC LIMIT 5");
$actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración | NuevoPack</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="estilos/estilos_admin.css">
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="sidebar-title">Panel de Administrador NuevoPack</h2>
        <div class="nav-item"><a href="dashboard.php" class="inicio">Dashboard</a></div>
        <div class="nav-item"><a href="servicios.php" class="servicios">Servicios</a></div>
        <div class="nav-item"><a href="precios.php" class="precios">Precios</a></div>
        <div class="nav-item"><a href="usuarios.php" class="usuarios">Usuarios</a></div>
        <div class="nav-item"><a href="logout.php" class="logout">Cerrar Sesión</a></div>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <h1 class="titulo-pagina">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>

        <!-- Resumen -->
        <div class="card-container">
            <div class="card">
                <h3>Servicios</h3>
                <p><?php
                    $stmt = $conn->query("SELECT COUNT(*) FROM servicios");
                    echo $stmt->fetchColumn() . " cargados";
                ?></p>
            </div>
            <div class="card">
                <h3>Precios</h3>
                <p><?php
                    $stmt = $conn->query("SELECT COUNT(*) FROM precios_servicios");
                    echo $stmt->fetchColumn() . " registrados";
                ?></p>
            </div>
            <div class="card">
                <h3>Usuarios</h3>
                <p><?php
                    $stmt = $conn->query("SELECT COUNT(*) FROM usuarios_especiales");
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

                $stmt = $conn->query("SELECT descripcion, fecha FROM actividad_admin ORDER BY fecha DESC LIMIT 10");
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
                    $stmt = $conn->query("SELECT nombre, foto, descripcion FROM servicios");
                    while ($s = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (empty($s['foto'])) {
                            $alertas[] = "El servicio \"{$s['nombre']}\" no tiene imagen.";
                        }
                        if (empty($s['descripcion'])) {
                            $alertas[] = "El servicio \"{$s['nombre']}\" no tiene descripción.";
                        }
                    }

                    // 2. Precios sin descripción o tipo_unidad
                    $stmt = $conn->query("SELECT descripcion, tipo_unidad FROM precios_servicios");
                    while ($p = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if (empty($p['descripcion'])) {
                            $alertas[] = "Un precio no tiene descripción.";
                        }
                        if (empty($p['tipo_unidad'])) {
                            $alertas[] = "Un precio no tiene tipo de unidad.";
                        }
                    }

                    // 3. Usuarios especiales sin teléfono o no aprobados
                    $stmt = $conn->query("SELECT email, telefono, aprobado FROM usuarios_especiales");
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

</body>
</html>