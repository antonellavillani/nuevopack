<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['descripcion'])) {
    $stmt = $conn->prepare("DELETE FROM actividad_admin WHERE descripcion = ? LIMIT 1");
    $stmt->execute([$_POST['descripcion']]);
}

header("Location: dashboard.php");
exit();
?>
