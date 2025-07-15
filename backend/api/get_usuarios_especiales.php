<?php
require_once '../../config/config.php';

try {
    $stmt = $pdo->query("SELECT id, nombre, apellido, email, telefono, aprobado FROM usuarios_especiales");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($usuarios);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al obtener usuarios: " . $e->getMessage()]);
}
