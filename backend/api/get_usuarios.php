<?php
header('Content-Type: application/json');
require_once '../../config/config.php';

try {
    $stmt = $pdo->query("SELECT id, nombre, email, password_hash FROM usuarios_especiales");
    $usuarios = $stmt->fetchAll();
    echo json_encode($usuarios);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener usuarios: ' . $e->getMessage()]);
}
?>
