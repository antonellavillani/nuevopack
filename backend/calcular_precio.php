<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    require_once('../config/config.php');

    // Obtener la tabla de precios desde la base de datos
    $queryPrecios = "SELECT * FROM precios_servicios";
    $stmtPrecios = $conn->prepare($queryPrecios);
    $stmtPrecios->execute();
    $precios = $stmtPrecios->fetchAll(PDO::FETCH_ASSOC);

    // Convertir precios a estructura accesible
    $preciosServicios = [];
    foreach ($precios as $precio) {
        $id = intval($precio['servicio_id']);
        $clave = strtolower(trim($precio['descripcion']));
        $preciosServicios[$id][$clave] = floatval($precio['precio']);
    }

    // Obtener datos del formulario
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

    // Realizar cálculos
    $total += $ctp * ($preciosServicios[1]['ctp'] ?? 0);
    $total += $postura_impresion * ($preciosServicios[1]['postura'] ?? 0);
    $total += $millar_impresion * ($preciosServicios[1]['millares'] ?? 0);

    $total += $bocas * ($preciosServicios[3]['bocas'] ?? 0);
    $total += $millar_troquelado * ($preciosServicios[3]['millares'] ?? 0);

    $total += $postura_barniz * ($preciosServicios[2]['postura'] ?? 0);
    $total += $millar_barniz * ($preciosServicios[2]['millares'] ?? 0);

    if (!empty($medida_estuche)) {
        $clave_estuche = strtolower(trim($medida_estuche));
        $total += $cantidad_estuches * ($preciosServicios[4][$clave_estuche] ?? 0);
    }

    // Mostrar el resultado
    echo "<div id='resultado-precio' class='resultado-precio'>
            <strong>Total estimado: $ " . number_format($total, 2, ',', '.') . "</strong>
            <p><strong>Para trabajos especiales, enviar mail para cotizar su pedido.</strong></p>
            <button id='btn-enviar-consulta' class='boton-consulta'>Enviar consulta con este pedido</button>
          </div>";
}