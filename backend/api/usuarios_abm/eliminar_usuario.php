<?php
include '../../../config/config.php';

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        echo json_encode(["success" => false, "error" => "ID no enviado"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios_especiales WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente"]);
        } else {
            echo json_encode(["success" => false, "error" => "Usuario no encontrado"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Método no permitido"]);
}
