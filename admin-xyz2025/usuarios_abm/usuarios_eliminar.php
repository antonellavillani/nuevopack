<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../index.php");
    exit();
}

require_once '../../config/config.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $conn->prepare("DELETE FROM usuarios_especiales WHERE id = ?");
        $stmt->execute([$id]);

        // Registrar actividad
        $descripcionActividad = 'Usuario ID ' . $id . ' eliminado';
        $stmtActividad = $conn->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
        $stmtActividad->execute(['usuario', $descripcionActividad]);

        header("Location: ../usuarios.php?mensaje=✅ Usuario eliminado correctamente.");
        exit();
    } catch (PDOException $e) {
        header("Location: ../usuarios.php?error=❌ Error al eliminar usuario: " . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: ../usuarios.php?error=❌ ID no especificado");
    exit();
}
