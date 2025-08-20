<?php
require_once __DIR__ . '/../../admin-xyz2025/analytics/ga_client.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    $ga = new GAClient();

    // Resumen últimos 7 días
    $summary = $ga->summaryLast7Days([
    'filter' => 'pagePath!~^/admin' // excluir todo lo que empieza con /admin
    ]);

    // Usuarios activos en tiempo real
    $realtime = $ga->realtimeActiveUsers([
        'filter' => 'pagePath!~^/admin'
    ]);

    // Contar envíos de formularios (eventName = 'form_submit')
    $formSubmits = $ga->eventCountLast7Days('form_submit');

    // Contar usos de calculadora (eventName = 'price_calc')
    $priceCalcs = $ga->eventCountLast7Days('price_calc');

    echo json_encode([
        'success' => true,
        'titulo' => 'Estadísticas del sitio',
        'descripcion' => 'Usuarios, sesiones y actividad de los últimos 7 días',
        'data' => [
            'usuarios7d'     => $summary['activeUsers'],
            'sesiones7d'     => $summary['sessions'],
            'usuariosNuevos' => $summary['newUsers'],
            'usuariosTiempoReal' => $realtime,
            'formularios'    => $formSubmits,
            'calculadora'    => $priceCalcs,
        ]
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
