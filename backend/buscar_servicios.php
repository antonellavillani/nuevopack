<?php
require_once '../config/config.php';

if (isset($_GET['term'])) {
    $term = strtolower(trim($_GET['term']));

    if ($term === 'contacto') {
        echo json_encode([
            ['nombre' => 'Ir a Contacto', 'url' => 'contacto.php']
        ]);
        exit;
    }

    $query = "SELECT id, nombre FROM servicios WHERE LOWER(nombre) LIKE :term";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':term' => "%$term%"]);
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultados = [];
    foreach ($servicios as $servicio) {
        $resultados[] = [
            'nombre' => $servicio['nombre'],
            'url' => 'ficha_servicio.php?idServicio=' . $servicio['id']
        ];
    }

    if (empty($resultados)) {
        $resultados[] = ['nombre' => 'No se encontraron resultados.', 'url' => '#'];
    }

    echo json_encode($resultados);
}
?>
