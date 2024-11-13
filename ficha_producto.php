<?php
// Incluir el header
include('includes/header.php');

// Incluir el archivo de configuración de la base de datos
require_once('config/config.php');

// Obtener el ID del producto de la URL (a través del método GET)
$idProducto = $_GET['idProducto'] ?? '';

// Consulta para obtener la información del producto
$query = "SELECT * FROM producto WHERE idProducto = :idProducto";
$stmt = $conn->prepare($query);
$stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
$stmt->execute();
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Consulta para obtener la tabla de precios
$queryPrecios = "SELECT * FROM precios";
$stmtPrecios = $conn->prepare($queryPrecios);
$stmtPrecios->execute();
$precios = $stmtPrecios->fetchAll(PDO::FETCH_ASSOC);

// Convertir precios a un formato fácil de usar
$preciosServicios = [];
foreach ($precios as $precio) {
    $preciosServicios[strtolower($precio['servicio'])] = $precio['precio'];
}

// Inicializar variables
$total = 0;
$errores = [];

// Verificar si hay datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario de la calculadora
    $cantidadPliegos = $_POST['cantidadPliegos'] ?? 1;
    $cantidadPosturas = $_POST['cantidadPosturas'] ?? 1;
    $cantidadMillares = $_POST['cantidadMillares'] ?? 1;
    $fotocromo = isset($_POST['fotocromo']) ? true : false;
    $barniz = isset($_POST['barniz']) ? true : false;
    $cantidadBarnizMillares = $_POST['cantidadBarnizMillares'] ?? 0;
    $barnizSectorizado = isset($_POST['barnizSectorizado']) ? true : false;
    $troquelado = isset($_POST['troquelado']) ? true : false;
    $pegadoEstuches = isset($_POST['pegadoEstuches']) ? true : false;

        // Calcular el precio de los pliegos (convertir a millares)
        if (isset($preciosServicios['millar'])) {
            // Redondear hacia arriba para el cálculo de pliegos
            $cantidadMillaresCalculados = ceil($cantidadPliegos / 1000) * 1000;
            $precioMillar = ceil($cantidadPliegos / 1000) * $preciosServicios['millar'];
            $total += $precioMillar;
        } else {
            $errores[] = "No se encontró el precio para 'Millar'.";
        }

            // Calcular el precio de las posturas
    if (isset($preciosServicios['postura'])) {
        $precioPostura = $cantidadPosturas * $preciosServicios['postura'];
        $total += $precioPostura;
    } else {
        $errores[] = "No se encontró el precio para 'Postura'.";
    }

        // Calcular el precio de Fotocromo
        if ($fotocromo && isset($preciosServicios['ctp'])) {
            $precioFotocromo = $preciosServicios['ctp'] * 4;
            $total += $precioFotocromo;
        } elseif ($fotocromo) {
            $errores[] = "No se encontró el precio para 'CTP'.";
        }

            // Calcular el precio de Barniz
    if ($barniz) {
        if (isset($preciosServicios['barniz general']) && isset($preciosServicios['barniz general por millar'])) {
            $precioBarniz = $preciosServicios['barniz general'] + ($cantidadBarnizMillares * $preciosServicios['barniz general por millar']);
            $total += $precioBarniz;
        } else {
            $errores[] = "No se encontró el precio para 'Barniz General' o 'Barniz por Millar'.";
        }

        if ($barnizSectorizado && isset($preciosServicios['barniz sectorizado'])) {
            $precioBarnizSectorizado = $preciosServicios['barniz sectorizado'];
            $total += $precioBarnizSectorizado;
        } elseif ($barnizSectorizado) {
            $errores[] = "No se encontró el precio para 'Barniz Sectorizado'.";
        }
    }

        // Calcular el precio de Troquelado
        if ($troquelado && isset($preciosServicios['troquelado por boca']) && isset($preciosServicios['troquelado por millar'])) {
            $precioTroquelado = ($cantidadMillares * $preciosServicios['troquelado por millar']) + ($cantidadPosturas * $preciosServicios['troquelado por boca']);
            $total += $precioTroquelado;
        } else {
            $errores[] = "No se encontró el precio para 'Troquelado'.";
        }

    // Si hay errores, redirigir con los errores
    if (count($errores) > 0) {
        $erroresStr = urlencode(implode(', ', $errores));
        header("Location: ficha_producto.php?idProducto={$idProducto}&errores={$erroresStr}");
        exit;
    }
}
?>

<body>
<div class="contenedor-ficha-producto">
    <!-- Nombre del producto -->
    <h1 class="nombre-producto"><?= htmlspecialchars($producto['nombre'] ?? 'Producto no encontrado') ?></h1>

    <!-- Imagen del producto y calculadora de precios -->
    <div class="producto-imagen-calculadora">
        <div class="imagen-producto">
            <img src="<?= htmlspecialchars($producto['fotoProducto'] ?? 'foto_producto_null.jpg') ?>" alt="<?= htmlspecialchars($producto['nombre'] ?? 'Producto') ?>">
        </div>

        <div class="calculadora-precio">
            <h2 class="nombre-producto">Calculadora de Precios</h2>
                <!-- Formulario de la calculadora -->
                <form id="calculadora-form" method="POST" action="ficha_producto.php?idProducto=<?= htmlspecialchars($idProducto) ?>">

                <!-- Cantidad de pliegos -->
                <label for="cantidadPliegos">Cantidad de Pliegos:</label>
                <input type="number" id="cantidadPliegos" name="cantidadPliegos" value="1" min="1">

                <!-- Cantidad de posturas -->
                <label for="cantidadPosturas">Cantidad de Posturas:</label>
                <input type="number" id="cantidadPosturas" name="cantidadPosturas" value="1" min="1">

                <!-- Fotocromo -->
                <div class="opcion-servicio">
                    <input type="checkbox" id="fotocromo" name="fotocromo">
                    <label for="fotocromo">Fotocromo (cyan - magenta - negro - amarillo)</label>
                    <p class="aclaracion">Colores especiales (oro - plata - rojo - etc.) se cotizan aparte.</p>
                </div>

                <!-- Barniz -->
                <div class="opcion-servicio">
                    <input type="checkbox" id="barniz" name="barniz">
                    <label for="barniz">Barniz</label>
                </div>

                <div class="barniz-adicional" style="display: none;">
                    <label for="cantidadBarnizMillares">Cantidad de Millares a Barnizar:</label>
                    <input type="number" id="cantidadBarnizMillares" name="cantidadBarnizMillares" value="1" min="1">
        
                    <div class="opcion-servicio">
                        <input type="checkbox" id="barnizSectorizado" name="barnizSectorizado">
                        <label for="barnizSectorizado">Barniz Sectorizado</label>
                    </div>
                </div>

                <!-- Troquelado -->
                <div class="opcion-servicio">
                    <input type="checkbox" id="troquelado" name="troquelado">
                    <label for="troquelado">Troquelado</label>
                </div>

                <!-- Pegado de estuches -->
                <div class="opcion-servicio">
                    <input type="checkbox" id="pegadoEstuches" name="pegadoEstuches">
                    <label for="pegadoEstuches">Pegado de Estuches</label>
                </div>

                <button type="submit">Calcular Precio</button>
            </form>

                <!-- Resultado de cálculo -->
            <div class="resultado-precio">
                <h3>Total: $<?= number_format($total, 2) ?></h3>
                </div>

                <!-- Aclaraciones -->
                <div class="aclaraciones">
                    <p>Precio estimado.</p>
                    <p>Diseño personalizado no incluido.</p>
                </div>

            </form>
        </div>
    </div>

    <!-- Descripción del producto y medios de pago -->
    <div class="descripcion-y-pagos">
        <div class="descripcion-producto">
            <h3>Descripción</h3>
            <p><?= htmlspecialchars($producto['descripcion'] ?? 'Descripción no disponible.') ?></p>
        </div>
        <div class="medios-pago">
            <h3>Medios de Pago</h3>
            <ul>
                <li>Efectivo</li>
                <li>Transferencia Bancaria</li>
                <li>MercadoPago</li>
            </ul>
        </div>
    </div>
</div>

    <!-- Formulario de contacto -->
    <div class="formulario-contacto">
        <h3>Contacto</h3>
        <form action="#" method="post" id="form-contacto">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
            
            <button type="submit">Enviar Consulta</button>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script src="public/js/script.js" defer></script>

<?php
// Incluir el footer
include('includes/footer.php');
?>
