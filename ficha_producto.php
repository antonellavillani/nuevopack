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
?>

<body>
<div class="contenedor-ficha-producto">
    <!-- Nombre del producto -->
    <h1 class="nombre-producto"><?= htmlspecialchars($producto['nombre'] ?? 'Producto no encontrado') ?></h1>

    <!-- Imagen del producto y calculadora de precios -->
    <div class="producto-imagen-calculadora">
        <div class="imagen-producto">
            <img src="public/img/<?= htmlspecialchars($producto['foto'] ?? 'foto_producto_null.jpg') ?>" alt="<?= htmlspecialchars($producto['nombre'] ?? 'Producto') ?>">
        </div>
        <div class="calculadora-precio">
            <h3>Calculadora de Precios</h3>
            <p>Calculadora de precios (No funcional)</p>
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
                <!-- Añadir más métodos de pago si es necesario -->
            </ul>
        </div>
    </div>

    <!-- Formulario de contacto -->
    <div class="formulario-contacto">
        <h3>Contacto</h3>
        <form action="#" method="post">
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

</body>
</html>
