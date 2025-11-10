<?php
require_once('includes/auth_admin.php');
require_once '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['descripcion'])) {
    $stmt = $pdo->prepare("DELETE FROM actividad_admin WHERE descripcion = ? LIMIT 1");
    $stmt->execute([$_POST['descripcion']]);
}

header("Location: dashboard.php");
exit();
?>
