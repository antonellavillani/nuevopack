<?php
include '../../../config/config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

try {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo "Falta el ID del servicio a eliminar";
        exit;
    }

    // Buscar la ruta de la imagen antes de eliminar el servicio
    $stmt = $pdo->prepare("SELECT foto FROM servicios WHERE id = ?");
    $stmt->execute([$id]);
    $foto = $stmt->fetchColumn();

    // Eliminar la imagen del servidor si existe
    if ($foto && file_exists("../../../" . $foto)) {
        unlink("../../../" . $foto);
    }

    // Eliminar el registro del servicio
    $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo "Servicio eliminado";
    } else {
        http_response_code(404);
        echo "No se encontró el servicio";
    }

} catch (Exception $e) {
    http_response_code(500);
    echo "Error al eliminar: " . $e->getMessage();
}
