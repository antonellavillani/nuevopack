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
    $preciosServicios[$precio['servicio']] = $precio['precio'];
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
            <!-- Calculadora de Precios -->
            <h2 class="nombre-producto">Calculadora de Precios</h2>
            <form id="calculadora-form">
                <!-- Cantidad de pliegos -->
                <label for="cantidadPliegos">Cantidad de Pliegos:</label>
                <input type="number" id="cantidadPliegos" name="cantidadPliegos" value="1" min="1" onchange="calcularPrecio()">

                <!-- Fotocromia -->
                <div class="opcion-servicio">
                    <input type="checkbox" id="fotocromia" name="fotocromia" value="4" onclick="calcularPrecio()">
                    <label for="fotocromia">Fotocromia</label>
                </div>

                <!-- Cantidad de posturas -->
                <label for="cantidadPosturas">Cantidad de Posturas:</label>
                <input type="number" id="cantidadPosturas" name="cantidadPosturas" value="1" min="1" onchange="calcularPrecio()">

                <!-- Cantidad de millares -->
                <label for="cantidadMillares">Cantidad de Millares:</label>
                <input type="number" id="cantidadMillares" name="cantidadMillares" value="1" min="1" onchange="calcularPrecio()">

                <!-- Barniz -->
                <div class="opcion-servicio">
                    <input type="checkbox" id="barniz" name="barniz" value="barniz" onclick="calcularPrecio()">
                    <label for="barniz">Barniz</label>
                </div>
                <label for="cantidadBarniz">Cantidad de Millares para Barniz:</label>
                <input type="number" id="cantidadBarniz" name="cantidadBarniz" value="1" min="1" onchange="calcularPrecio()">

                <!-- Barniz sectorizado -->
                <div class="opcion-servicio">
                    <input type="checkbox" id="barnizSectorizado" name="barnizSectorizado" value="1" onclick="calcularPrecio()">
                    <label for="barnizSectorizado">Barniz Sectorizado</label>
                </div>

                <!-- Troquelado -->
                <div class="opcion-servicio">
                    <input type="checkbox" id="troquelado" name="troquelado" value="1" onclick="calcularPrecio()">
                    <label for="troquelado">Troquelado</label>
                </div>

                <!-- Pegado de estuches -->
                <div class="opcion-servicio">
                    <input type="checkbox" id="pegadoEstuches" name="pegadoEstuches" value="1" onclick="calcularPrecio()">
                    <label for="pegadoEstuches">Pegado de Estuches (Precio mínimo por 5 millares)</label>
                </div>

                <div class="resultado-precio">
                    <h3>Precio Estimado: $<span id="precioTotal">0.00</span></h3>
                    <p>Diseño personalizado NO incluido. Precio aproximado.</p>
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
