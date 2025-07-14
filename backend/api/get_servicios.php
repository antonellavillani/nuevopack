<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, nombre, descripcion, foto FROM servicios ORDER BY id ASC");
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($servicios);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener servicios: ' . $e->getMessage()]);
}
?>
