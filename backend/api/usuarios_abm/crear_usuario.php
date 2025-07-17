<?php
include '../../../config/config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

try {
    // Obtener datos
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validaciones
    if (!$nombre || !$apellido || !$email || !$password) {
        http_response_code(400);
        echo "Faltan campos obligatorios";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Email inválido";
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios_especiales 
        (nombre, apellido, email, telefono, password_hash, aprobado) 
        VALUES (?, ?, ?, ?, ?, 0)");

    $stmt->execute([$nombre, $apellido, $email, $telefono, $hash]);

    echo "OK";

} catch (PDOException $e) {
    http_response_code(500);
    echo "Error de servidor: " . $e->getMessage();
}
