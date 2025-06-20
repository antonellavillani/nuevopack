<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../index.php");
    exit();
}

require_once '../../config/config.php';

if (!isset($_GET['id'])) {
    header("Location: ../precios.php");
    exit();
}

$id = $_GET['id'];

// Verificamos que exista
$stmt = $conn->prepare("SELECT * FROM precios_servicios WHERE id = ?");
$stmt->execute([$id]);
$precio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$precio) {
    header("Location: ../precios.php?mensaje_error=No se encontró el precio.");
    exit();
}

// Eliminamos
$stmt = $conn->prepare("DELETE FROM precios_servicios WHERE id = ?");
$stmt->execute([$id]);

// Registrar actividad
$descripcionActividad = 'Precio ID ' . $id . ' eliminado';
$stmtActividad = $conn->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
$stmtActividad->execute(['precio', $descripcionActividad]);

header("Location: ../precios.php?mensaje=Precio eliminado correctamente.");
exit();
?>
