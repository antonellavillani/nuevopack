<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$cacheFile = __DIR__ . '/../cache/ga.json';
$cacheTime = 600; // 10 minutos

// Verificar si existe cache y está vigente
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    header('Content-Type: application/json');
    echo file_get_contents($cacheFile);
    exit();
}

// Sino, trae datos desde GA
require_once __DIR__ . '/../analytics/ga_client.php';
$ga = new GAClient();

$data = [
    'users'       => $ga->summaryLast7Days()['activeUsers'] ?? 0,
    'sessions'    => $ga->summaryLast7Days()['sessions'] ?? 0,
    'rtUsers'     => $ga->realtimeActiveUsers() ?? 0,
    'formSubmits' => $ga->eventCountLast7Days('form_submit') ?? 0,
    'calcUses'    => $ga->eventCountLast7Days('price_calc') ?? 0,
];

// Guarda en cache
echo "<pre>";
print_r($data);
if (file_put_contents($cacheFile, json_encode($data))) {
    echo "Cache guardado correctamente!";
} else {
    echo "Error al guardar cache!";
}
exit();

header('Content-Type: application/json');
echo json_encode($data);

error_reporting(E_ALL);
ini_set('display_errors', 1);
