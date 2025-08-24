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

// Verificar si existe
$stmt = $pdo->prepare("SELECT * FROM precios_servicios WHERE id = ?");
$stmt->execute([$id]);
$precio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$precio) {
    header("Location: ../precios.php?mensaje_error=No se encontró el precio.");
    exit();
}

// Eliminar
$stmt = $pdo->prepare("DELETE FROM precios_servicios WHERE id = ?");
$stmt->execute([$id]);

// Registrar actividad
$descripcionActividad = 'Precio ID ' . $id . ' eliminado';
$stmtActividad = $pdo->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
$stmtActividad->execute(['precio', $descripcionActividad]);

// Mensaje de confirmación
$_SESSION['success'] = "Precio eliminado correctamente.";

header("Location: ../precios.php");
exit();
?>
