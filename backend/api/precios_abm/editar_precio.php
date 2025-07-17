<?php
require_once '../../../config/config.php';
header('Content-Type: application/json');

try {
    $id = $_POST['id'] ?? null;
    $servicio_id = $_POST['servicio_id'] ?? null;
    $descripcion = $_POST['descripcion'] ?? '';
    $tipo_unidad = $_POST['tipo_unidad'] ?? '';
    $precio = $_POST['precio'] ?? null;

    if (!$id || !$servicio_id || !$descripcion || !$tipo_unidad || !$precio) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan datos']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE precios_servicios SET servicio_id = ?, descripcion = ?, tipo_unidad = ?, precio = ? WHERE id = ?");
    $stmt->execute([$servicio_id, $descripcion, $tipo_unidad, $precio, $id]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al editar el precio']);
}
