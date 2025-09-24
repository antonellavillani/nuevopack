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

    // Verificar si tiene precios asociados
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM precios WHERE servicio_id = ?");
    $stmt->execute([$id]);
    $tienePrecios = $stmt->fetchColumn();

    if ($tienePrecios > 0) {
        http_response_code(409);
        echo "El servicio no puede eliminarse porque tiene precios asociados. Eliminalos primero o desvincúlalos.";
        exit;
    }

    // Buscar la ruta de la imagen antes de eliminar el servicio
    $stmt = $pdo->prepare("SELECT foto FROM servicios WHERE id = ?");
    $stmt->execute([$id]);
    $foto = $stmt->fetchColumn();

    // Eliminar la imagen del servidor si existe
    if ($foto && file_exists("../../../uploads/" . $foto)) {
        unlink("../../../uploads/" . $foto);
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
    echo "Error al eliminar el servicio. Contacte al administrador.";
    error_log("Error al eliminar servicio ID $id: " . $e->getMessage());
}
