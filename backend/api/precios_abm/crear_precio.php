<?php
include '../../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servicio_id = $_POST['servicio_id'] ?? null;
    $descripcion = $_POST['descripcion'] ?? '';
    $tipo_unidad = $_POST['tipo_unidad'] ?? '';
    $precio = $_POST['precio'] ?? null;

    if (!$servicio_id || !$descripcion || !$tipo_unidad || !$precio) {
        http_response_code(400);
        echo "Faltan campos obligatorios";
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO precios_servicios (servicio_id, descripcion, tipo_unidad, precio) VALUES (?, ?, ?, ?)");
        $stmt->execute([$servicio_id, $descripcion, $tipo_unidad, $precio]);

        echo "Precio creado correctamente";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error al guardar el precio: " . $e->getMessage();
    }
} else {
    http_response_code(405);
    echo "Método no permitido";
}
