<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios_especiales");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['total' => $count['total']]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener el total de usuarios: ' . $e->getMessage()]);
}
?>
