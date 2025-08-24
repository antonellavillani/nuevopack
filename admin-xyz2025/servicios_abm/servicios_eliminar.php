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

// Verificar si el servicio tiene precios asociados
$stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM precios_servicios WHERE servicio_id = ?");
$stmtCheck->execute([$id]);
$tienePrecios = $stmtCheck->fetchColumn();

if ($tienePrecios > 0) {
    $_SESSION['error'] = "El servicio no puede eliminarse porque tiene precios asociados. Eliminalos primero o desvincúlalos.";
    header("Location: ../servicios.php");
    exit();
}

// Obtener nombre de la foto
$stmt = $pdo->prepare("SELECT foto FROM servicios WHERE id = ?");
$stmt->execute([$id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if ($servicio) {
    $foto = $servicio['foto'];
    
    // Eliminar de la base de datos
    $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = ?");
    $stmt->execute([$id]);

    // Eliminar imagen del servidor
    $ruta_imagen = '../../uploads/' . $foto;
    if (file_exists($ruta_imagen)) {
        unlink($ruta_imagen);
    }

    // Mensaje de éxito
    $_SESSION['success'] = "El servicio se eliminó correctamente.";
}

// Registrar actividad
$descripcionActividad = 'Servicio ID ' . $id . ' eliminado';
$stmtActividad = $pdo->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
$stmtActividad->execute(['servicio', $descripcionActividad]);

header("Location: ../servicios.php");
exit();
?>
