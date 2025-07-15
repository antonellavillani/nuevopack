<?php
include '../../../config/config.php';

$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$imagen_base64 = $_POST['foto'] ?? '';

$foto = '';

try {
    if ($imagen_base64) {
        $nombreArchivo = uniqid() . '.jpg';
        $ruta = '../../../uploads/servicios/' . $nombreArchivo;
        $foto = 'uploads/servicios/' . $nombreArchivo;

        $imagen_base64 = str_replace(' ', '+', $imagen_base64);
        file_put_contents($ruta, base64_decode($imagen_base64));
    }

    if ($nombre && $descripcion) {
        $stmt = $pdo->prepare("INSERT INTO servicios (nombre, descripcion, foto) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $foto]);

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo "Servicio creado";
        } else {
            http_response_code(500);
            echo "No se insertó ningún registro";
        }
    } else {
        http_response_code(400);
        echo "Faltan datos";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
