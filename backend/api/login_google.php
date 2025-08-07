<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

$email = $_GET['email'] ?? null;
$nombre = $_GET['nombre'] ?? null;
$apellido = $_GET['apellido'] ?? null;

if (!$email || !$nombre) {
    echo json_encode(['status' => 'error']);
    exit;
}

$stmt = $pdo->prepare("SELECT aprobado FROM usuarios_especiales WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    if ((int)$usuario['aprobado'] === 1) {
        echo json_encode(['status' => 'autorizado']);
    } else {
        echo json_encode(['status' => 'pendiente']);
    }
} else {
    // Registrar un nuevo usuario como pendiente
    $ins = $pdo->prepare("INSERT INTO usuarios_especiales (nombre, apellido, email, aprobado) VALUES (?, ?, ?, 0)");
    $ins->execute([$nombre, $apellido, $email]);

    echo json_encode(['status' => 'nuevo']);
}
