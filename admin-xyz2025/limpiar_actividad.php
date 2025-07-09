<?php
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Acceso no autorizado.');
}

require_once '../config/config.php';

$logFile = __DIR__ . '/logs/actividad.log';
$logMensaje = "[" . date("Y-m-d H:i:s") . "] ";

try {
    $stmt = $pdo->prepare("DELETE FROM actividad_admin WHERE fecha < DATE_SUB(NOW(), INTERVAL 60 DAY)");
    $stmt->execute();
    $cantidad = $stmt->rowCount();
    $logMensaje .= "Limpieza realizada correctamente. Registros eliminados: $cantidad\n";
} catch (PDOException $e) {
    $logMensaje .= "Error al limpiar actividad: " . $e->getMessage() . "\n";
}

file_put_contents($logFile, $logMensaje, FILE_APPEND);
?>
