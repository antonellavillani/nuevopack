<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, descripcion, fecha FROM actividad_admin ORDER BY fecha DESC LIMIT 5");
    $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($actividades);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener actividades']);
}