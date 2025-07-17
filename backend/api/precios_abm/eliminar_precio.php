<?php
include '../../../config/config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

try {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo "Falta el ID del precio a eliminar";
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM precios_servicios WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo "OK";
    } else {
        http_response_code(404);
        echo "No se encontró el precio";
    }

} catch (Exception $e) {
    http_response_code(500);
    echo "Error al eliminar: " . $e->getMessage();
}
