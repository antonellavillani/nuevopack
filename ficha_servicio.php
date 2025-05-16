<?php
// Incluir el header
include('includes/header.php');

// Incluir el archivo de configuración de la base de datos
require_once('config/config.php');

// Obtener el ID del servicio de la URL (a través del método GET)
$idServicio = $_GET['idServicio'] ?? '';

// Consulta para obtener la información del servicio
$query = "SELECT * FROM servicios WHERE id = :idServicio";
$stmt = $conn->prepare($query);
$stmt->bindParam(':idServicio', $idServicio, PDO::PARAM_INT);
$stmt->execute();
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

// Consulta para obtener la tabla de precios
$queryPrecios = "SELECT * FROM precios_servicios";
$stmtPrecios = $conn->prepare($queryPrecios);
$stmtPrecios->execute();
$precios = $stmtPrecios->fetchAll(PDO::FETCH_ASSOC);

// Convertir precios a un formato fácil de usar
$preciosServicios = [];
foreach ($precios as $precio) {
    $id = intval($precio['servicio_id']);
    $clave = strtolower(trim($precio['descripcion']));
    $preciosServicios[$id][$clave] = floatval($precio['precio']);
}
?>

<body>
<div class="contenedor-ficha-producto">

    <!-- CONTENEDOR 1: FOTO DEL SERVICIO + CALCULADORA -->
    <div class="fila-doble">
        <div class="columna-izquierda">
            <h1 class="nombre-producto"><?= htmlspecialchars($servicio['nombre'] ?? 'Servicio no encontrado') ?></h1>
            <div class="imagen-producto">
                <img src="<?= htmlspecialchars($servicio['foto'] ?? 'foto_producto_null.jpg') ?>" alt="<?= htmlspecialchars($servicio['nombre'] ?? 'Servicio') ?>">
            </div>
        </div>

        <div class="columna-derecha">
            <form action="" method="POST">
                <section class="calculadora">
                <h2 class="nombre-producto">Calculadora de precios</h2>

                <!-- Impresión -->
                <h3>Impresión</h3>
                <div class="grupo-campo">
                    <label for="ctp">CTP (chapa)</label>
                    <input type="number" name="ctp" id="ctp" placeholder="Ingrese la cantidad de CTP (chapas)">
                </div>
                <div class="grupo-campo">
                    <label for="postura_impresion">Postura</label>
                    <input type="number" name="postura_impresion" id="postura_impresion" placeholder="Ingrese la cantidad de posturas">
                </div>
                <div class="grupo-campo">
                    <label for="millar_impresion">Millares</label>
                    <input type="number" name="millar_impresion" id="millar_impresion" placeholder="Ingrese la cantidad de millares">
                </div>

                <!-- Troquelado -->
                <h3><input type="checkbox" name="troquelado_toggle" id="troquelado_toggle"> Troquelado</h3>
                <div id="troquelado_seccion">
                    <div class="grupo-campo">
                        <label for="bocas">Cantidad de bocas (simple)</label>
                        <input type="number" name="bocas" id="bocas" placeholder="Ingrese la cantidad de bocas">
                    </div>
                    <div class="grupo-campo">
                        <label for="millar_troquelado">Millares</label>
                        <input type="number" name="millar_troquelado" id="millar_troquelado" placeholder="Ingrese la cantidad de millares">
                    </div>
                </div>

                <!-- Barniz -->
                <h3><input type="checkbox" name="barniz_toggle" id="barniz_toggle"> Barniz</h3>
                <div id="barniz_seccion">
                    <div class="grupo-campo">
                        <label for="postura_barniz">Postura</label>
                        <input type="number" name="postura_barniz" id="postura_barniz" placeholder="Ingrese la cantidad de posturas">
                    </div>
                    <div class="grupo-campo">
                        <label for="millar_barniz">Millar</label>
                        <input type="number" name="millar_barniz" id="millar_barniz" placeholder="Ingrese la cantidad de millares">
                    </div>
                </div>

                <!-- Pegado de estuches -->
                <h3><input type="checkbox" name="estuches_toggle" id="estuches_toggle"> Pegado de estuches</h3>
                <div id="estuches_seccion">
                    <p>Medida del estuche abierto</p>
                    <div class="grupo-campo">
                        <label><input type="radio" name="medida_estuche" value="estuches de 10cm a 15cm"> 10cm a 15cm</label><br>
                        <label><input type="radio" name="medida_estuche" value="estuches de 16cm a 25cm"> 16cm a 25cm</label><br>
                        <label><input type="radio" name="medida_estuche" value="estuches de 26cm a 50cm"> 26cm a 50cm</label>
                    </div>
                    <div class="grupo-campo">
                        <label for="cantidad_estuches">Cantidad</label>
                        <input type="number" name="cantidad_estuches" id="cantidad_estuches" placeholder="Ingrese la cantidad de estuches">
                    </div>
                </div>

                <p><strong>Para trabajos especiales, enviar mail para cotizar su pedido.</strong></p>

                <button type="submit" name="calcular_precio">Calcular</button>
                </section>
            </form>

            <?php           
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ctp = intval($_POST['ctp'] ?? 0);
                $postura_impresion = intval($_POST['postura_impresion'] ?? 0);
                $millar_impresion = intval($_POST['millar_impresion'] ?? 0);

                $bocas = intval($_POST['bocas'] ?? 0);
                $millar_troquelado = intval($_POST['millar_troquelado'] ?? 0);

                $postura_barniz = intval($_POST['postura_barniz'] ?? 0);
                $millar_barniz = intval($_POST['millar_barniz'] ?? 0);

                $medida_estuche = $_POST['medida_estuche'] ?? '';
                $cantidad_estuches = intval($_POST['cantidad_estuches'] ?? 0);

                $total = 0;
                
                // Servicio de Impresión (id = 1)
                $total += $ctp * ($preciosServicios[1]['ctp'] ?? 0);
                $total += $postura_impresion * ($preciosServicios[1]['postura'] ?? 0);
                $total += $millar_impresion * ($preciosServicios[1]['millares'] ?? 0);

                // Servicio de Troquelado (id = 3)
                $total += $bocas * ($preciosServicios[3]['bocas'] ?? 0);
                $total += $millar_troquelado * ($preciosServicios[3]['millares'] ?? 0);

                // Servicio de Barniz (id = 2)
                $total += $postura_barniz * ($preciosServicios[2]['postura'] ?? 0);
                $total += $millar_barniz * ($preciosServicios[2]['millares'] ?? 0);

                // Servicio de Pegado de Estuches (id = 4)
                if (!empty($medida_estuche)) {
                    $clave_estuche = strtolower(trim($medida_estuche));
                    $total += $cantidad_estuches * ($preciosServicios[4][$clave_estuche] ?? 0);
                }                

                echo "<div class='resultado-precio'><strong>Total estimado: $ " . number_format($total, 2, ',', '.') . "</strong></div>";
            }
            ?>
        </div>
    </div>

    <!-- CONTENEDOR 2: DESCRIPCIÓN + MEDIOS DE PAGO -->
    <div class="fila-doble">
        <div class="columna-izquierda">
            <h3>Descripción</h3>
            <p><?= htmlspecialchars($servicio['descripcion'] ?? 'Descripción no disponible.') ?></p>
        </div>
        <div class="columna-derecha">
            <h3>Medios de Pago</h3>
            <ul>
                <li>Efectivo</li>
                <li>Transferencia Bancaria</li>
                <li>MercadoPago</li>
            </ul>
        </div>
    </div>

    <!-- CONTENEDOR 3: FORMULARIO DE CONTACTO -->
    <div class="formulario-contacto">
        <h3 class="nombre-producto">Contacto</h3>
        <form id="form-contacto" action="backend/enviar_correo.php" method="POST">
            <label for="nombre">Indícanos tu nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="email">Tu email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="mensaje">Tu consulta:</label>
            <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
            
            <button type="submit">Enviar Consulta</button>
        </form>
        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'success'): ?>
        <div class="mensaje-exito">Tu consulta fue enviada con éxito.</div>
        <?php endif; ?>
    </div>
</div>

<script>
    const preciosServicios = <?= json_encode($preciosServicios) ?>;
</script>

<script src="public/js/script.js"></script>

<?php
// Incluir el footer
include('includes/footer.php');
?>

</body>