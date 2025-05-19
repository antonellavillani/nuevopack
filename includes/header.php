<?php
// Incluir el archivo de configuración de la base de datos
require_once 'config/config.php';

// Verificar si la conexión a la base de datos está configurada
if (!isset($conn)) {
    die("Error: La conexión a la base de datos no está configurada.");
}

// Consulta para obtener los nombres de los productos
try {
    $query = "SELECT id, nombre FROM servicios";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts: Poppins y Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <div class="contenedor-header">
            <!-- Logo -->
            <div class="logo">
                <a href="index.php">
                    <img src="public/img/logo.png" alt="Logo NuevoPack" width="50" height="50">
                    <span class="nuevoPack">
                        <span class="nuevo">Nuevo</span><span class="pack">Pack</span>
                    </span>

                </a>
            </div>

            <!-- Barra de búsqueda -->
            <div class="barra-busqueda">
                <input type="text" placeholder="Buscar...">
                <button type="submit">Buscar</button>
            </div>

             <!-- Botón Hamburguesa (Solo en pantallas táctiles) -->
            <button id="hamburguesa-btn" class="hamburguesa">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Menú de navegación -->
            <nav class="menu">
                <!-- Servicios -->
                <div class="servicios-dropdown">
                    <button class="dropbtn" id="servicios-btn">Servicios</button>
                    <div class="dropdown-content">
                        <?php
                        if (!empty($servicios)) {
                            foreach ($servicios as $servicio) {
                                $nombreServicio = htmlspecialchars($servicio['nombre']);
                                $idServicio = htmlspecialchars($servicio['id']);
                                echo "<a href='ficha_servicio.php?idServicio=$idServicio'>$nombreServicio</a>";
                            }
                        } else {
                            echo "<p>No hay productos disponibles.</p>";
                        }
                        ?>
                    </div>
                </div>

                <!-- Quiénes Somos -->
                <a href="quienes_somos.php" class="menu-link">Quiénes Somos</a>

                <!-- Contáctanos -->
                <a href="contacto.php" class="menu-link">Contáctanos</a>

            </nav>

            <!-- Menú Desplegable del Botón Hamburguesa (Solo para móviles) -->
            <div id="menu-movil" class="menu-movil">
                <a href="#" id="movil-servicios">Servicios</a>
                <div class="submenu-movil">
                    <?php
                    if (!empty($servicios)) {
                        foreach ($servicios as $servicio) {
                            $nombreServicio = htmlspecialchars($servicio['nombre']);
                            $idServicio = htmlspecialchars($servicio['id']);
                            echo "<a href='ficha_servicio.php?idServicio=$idServicio'>$nombreServicio</a>";
                        }
                    }
                    ?>
                </div>

                <a href="quienes_somos.php">Quiénes Somos</a>
                <a href="contacto.php">Contáctanos</a>
            </div>
        </div>

        <script src="../public/js/script.js"></script>

    </header>
</body>

</html>