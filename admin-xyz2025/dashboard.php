<?php
session_start();

// Verifica si el admin está logueado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../index.php");
    exit();
}

require_once('../config/config.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración | NuevoPack</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilos/estilos_admin.css">
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin NuevoPack</h2>
        <div class="nav-item"><a href="dashboard.php">📊 Dashboard</a></div>
        <div class="nav-item"><a href="servicios.php">🛠️ Servicios</a></div>
        <div class="nav-item"><a href="precios.php">💲 Precios</a></div>
        <div class="nav-item"><a href="usuarios.php">👤 Usuarios</a></div>
        <div class="nav-item"><a href="logout.php">🔓 Cerrar Sesión</a></div>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <h1>Bienvenido, Admin 👋</h1>

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
            <h2>➕ Accesos Rápidos</h2>
            <button onclick="window.location.href='servicios.php?accion=crear'">➕ Nuevo Servicio</button>
            <button onclick="window.location.href='precios.php?accion=crear'">➕ Nuevo Precio</button>
            <button onclick="window.location.href='usuarios.php?accion=crear'">➕ Nuevo Usuario</button>
        </div>

        <!-- Actividad reciente -->
        <div class="activity-log">
            <h2>🕓 Actividad Reciente</h2>
            <ul>
                <li>Servicio “Tarjetas personales” actualizado ayer</li>
                <li>Nuevo usuario “lucas@example.com” creado el lunes</li>
                <li>Precio actualizado para “Bolsas impresas”</li>
            </ul>
        </div>

        <!-- Alertas -->
        <div class="alerts">
            <h2>⚠️ Alertas</h2>
            <ul>
                <li>2 servicios no tienen imagen</li>
                <li>Faltan precios en algunos servicios</li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>