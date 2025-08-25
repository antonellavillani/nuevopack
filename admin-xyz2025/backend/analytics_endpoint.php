<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../analytics/ga_client.php';

$ga = new GAClient();

echo json_encode([
    'users'       => $ga->summaryLast7Days()['activeUsers'] ?? 0,
    'sessions'    => $ga->summaryLast7Days()['sessions'] ?? 0,
    'rtUsers'     => $ga->realtimeActiveUsers() ?? 0,
    'formSubmits' => $ga->eventCountLast7Days('form_submit') ?? 0,
    'calcUses'    => $ga->eventCountLast7Days('price_calc') ?? 0,
]);