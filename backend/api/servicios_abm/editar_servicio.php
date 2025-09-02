<?php
include '../../../config/config.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$imagen_base64 = $_POST['foto'] ?? '';

if (!$id || !$nombre || !$descripcion) {
    http_response_code(400);
    echo "Faltan datos obligatorios";
    exit;
}

try {
    // Buscar imagen actual
    $stmt = $pdo->prepare("SELECT foto FROM servicios WHERE id = ?");
    $stmt->execute([$id]);
    $servicio = $stmt->fetch();

    $foto = $servicio['foto'] ?? '';
    $nuevaRuta = $foto;

    // Si se mandó una imagen nueva:
    if ($imagen_base64 === "ELIMINAR") {
        if ($foto && file_exists("../../../uploads/" . $foto)) {
            unlink("../../../uploads/" . $foto);
        }
        $nuevaRuta = '';
    } elseif ($imagen_base64) {
        // Eliminar la imagen anterior si existía
        if ($foto && file_exists("../../../uploads/" . $foto)) {
            unlink("../../../uploads/" . $foto);
        }

        // Guardar la nueva imagen
        $imagen_data = base64_decode($imagen_base64);
        $nombreArchivo = uniqid("servicio_") . ".jpg";
        $rutaArchivo = "../../../uploads/" . $nombreArchivo;

        file_put_contents($rutaArchivo, $imagen_data);
        
        // Guardar el nombre en BD
        $nuevaRuta = $nombreArchivo;
    }

    // Actualizar el registro
    $stmt = $pdo->prepare("UPDATE servicios SET nombre = ?, descripcion = ?, foto = ? WHERE id = ?");
    $stmt->execute([$nombre, $descripcion, $nuevaRuta, $id]);

    echo "Servicio actualizado";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al actualizar: " . $e->getMessage();
}
