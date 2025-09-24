<?php
// Incluir el archivo de configuración de la base de datos
require_once 'config/config.php';

// Verificar si la conexión a la base de datos está configurada
if (!isset($pdo)) {
    die("Error: La conexión a la base de datos no está configurada.");
}

// Consulta para obtener los nombres de los productos
try {
    $query = "SELECT id, nombre FROM servicios";
    $stmt = $pdo->prepare($query);
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

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-D9VPD90EQS"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-D9VPD90EQS');
    </script>

    <link rel="stylesheet" href="public/css/estilos.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts: Poppins y Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

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
                <input type="text" id="busqueda-input" placeholder="Buscar...">
                <div id="resultados-busqueda" class="resultados-busqueda"></div>
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
    </header>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const inputBusqueda = document.getElementById('busqueda-input');
    const resultadosDiv = document.getElementById('resultados-busqueda');

    inputBusqueda.addEventListener('input', () => {
        const texto = inputBusqueda.value.trim();

        if (texto.length === 0) {
            resultadosDiv.style.display = 'none';
            resultadosDiv.innerHTML = '';
            return;
        }

        fetch(`backend/buscar_servicios.php?term=${encodeURIComponent(texto)}`)
            .then(response => response.json())
            .then(data => {
                resultadosDiv.innerHTML = '';
                data.forEach(item => {
                    const enlace = document.createElement('a');
                    enlace.textContent = item.nombre;
                    enlace.href = item.url;
                    if (item.url !== '#') {
                        enlace.addEventListener('click', (e) => {
                            e.preventDefault();
                            window.location.href = item.url;
                        });
                    }
                    resultadosDiv.appendChild(enlace);
                });
                resultadosDiv.style.display = 'block';
            });
    });

    // Ocultar resultados si se hace clic fuera
    document.addEventListener('click', (e) => {
        if (!inputBusqueda.contains(e.target) && !resultadosDiv.contains(e.target)) {
            resultadosDiv.style.display = 'none';
        }
    });
});

const BASE_URL = "<?= ApiConfig::BASE_URL ?>";
</script>

</body>

</html>