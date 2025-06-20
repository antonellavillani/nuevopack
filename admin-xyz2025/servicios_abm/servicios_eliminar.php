<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../login.php");
    exit();
}

require_once '../../config/config.php';

// Validar ID
if (!isset($_GET['id'])) {
    header("Location: ../servicios.php");
    exit();
}

$id = $_GET['id'];

// Obtener nombre del archivo para eliminar la imagen del servidor
$stmt = $conn->prepare("SELECT foto FROM servicios WHERE id = ?");
$stmt->execute([$id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if ($servicio) {
    $foto = $servicio['foto'];
    
    // Eliminar de la base de datos
    $stmt = $conn->prepare("DELETE FROM servicios WHERE id = ?");
    $stmt->execute([$id]);

    // Eliminar imagen del servidor
    $ruta_imagen = '../../uploads/' . $foto;
    if (file_exists($ruta_imagen)) {
        unlink($ruta_imagen);
    }
}

// Registrar actividad
$descripcionActividad = 'Servicio ID ' . $id . ' eliminado';
$stmtActividad = $conn->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
$stmtActividad->execute(['servicio', $descripcionActividad]);

header("Location: ../servicios.php");
exit();
?>
