<?php
// Incluir el archivo de configuración de la base de datos
require_once 'config/config.php';

// Verificar si la conexión a la base de datos está configurada
if (!isset($conn)) {
    die("Error: La conexión a la base de datos no está configurada.");
}

// Consulta para obtener los nombres de los productos
try {
    $query = "SELECT idServicio, nombre FROM servicio";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error al obtener los servicios: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NuevoPack - Tu Solución en Impresión</title>

    <link rel="stylesheet" href="public/css/estilos.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google Fonts: Poppins y Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="contenedor-header">
            <!-- Logo -->
            <div class="logo">
                <a href="index.php">
                    <img src="public/img/logo.png" alt="Logo NuevoPack" width="50" height="50">
                    <span class="nuevoPack">NuevoPack</span>
                </a>
            </div>

            <!-- Barra de búsqueda -->
            <div class="barra-busqueda">
                <input type="text" placeholder="Buscar...">
                <button type="submit">Buscar</button>
            </div>

            <!-- Menú de navegación -->
            <nav class="menu">
                <!-- Servicios -->
                <div class="productos-dropdown">
                    <button class="dropbtn">Servicios</button>
                    <div class="dropdown-content">
                        <?php
                        // Mostrar cada servicio en el menú desplegable con enlace a ficha_producto.php
                        if (!empty($servicios)) {
                            foreach ($servicios as $servicio) {
                                $nombreServicio = htmlspecialchars($servicio['nombre']);
                                $idServicio = htmlspecialchars($servicio['idServicio']);
                                echo "<a href='ficha_producto.php?idServicio=$idServicio'>$nombreServicio</a>";
                            }
                        } else {
                            echo "<p>No hay productos disponibles.</p>";
                        }
                        ?>
                    </div>
                </div>

                <!-- Quiénes Somos -->
                <a href="quienes_somos.php" class="menu-btn">Quiénes Somos</a>

                <!-- Contáctanos -->
                <a href="contacto.php" class="menu-btn">Contáctanos</a>

            </nav>
        </div>
    </header>
</body>
</html>
