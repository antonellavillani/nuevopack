<?php
require_once '../../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $aprobado = $_POST['aprobado'] === "1" ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE usuarios_especiales SET nombre = ?, apellido = ?, email = ?, telefono = ?, aprobado = ? WHERE id = ?");
    $stmt->execute([$nombre, $apellido, $email, $telefono, $aprobado, $id]);

    echo json_encode(["estado" => "ok"]);
}
?>
