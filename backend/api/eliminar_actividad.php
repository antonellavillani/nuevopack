<?php
include '../../config/config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$id = $_POST['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "Falta el ID de la actividad"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM actividad_admin WHERE id = ? LIMIT 1");
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Actividad no encontrada"]);
}