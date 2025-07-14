<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
    SELECT ps.id, ps.servicio_id, s.nombre AS nombre_servicio, ps.descripcion, ps.tipo_unidad, ps.precio
    FROM precios_servicios ps
    JOIN servicios s ON ps.servicio_id = s.id
    ORDER BY ps.id ASC
    ");
    $precios = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($precios);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los precios']);
}