<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$cacheFile = __DIR__ . '/../cache/ga.json';
$cacheTime = 600; // 10 minutos

header('Content-Type: application/json');

// Si el caché está vigente
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    echo file_get_contents($cacheFile);
    exit();
}

// Sino, pide a GA
require_once __DIR__ . '/../analytics/ga_client.php';
$ga = new GAClient();

$data = [
    'users'       => $ga->summaryLast7Days()['activeUsers'] ?? 0,
    'sessions'    => $ga->summaryLast7Days()['sessions'] ?? 0,
    'rtUsers'     => $ga->realtimeActiveUsers() ?? 0,
    'formSubmits' => $ga->eventCountLast7Days('form_submit') ?? 0,
    'calcUses'    => $ga->eventCountLast7Days('price_calc') ?? 0,
];

// Guardar cache
file_put_contents($cacheFile, json_encode($data));

// Devolver respuesta
echo json_encode($data);
