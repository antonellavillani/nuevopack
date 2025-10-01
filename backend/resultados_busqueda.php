<?php
require_once '../config/config.php';

if (isset($_GET['term'])) {
    $term = strtolower(trim($_GET['term']));
    $resultados = [];
    
    // Páginas estáticas: contacto y quienes somos
    $paginasEstaticas = [
        ['nombre' => 'Quiénes Somos', 'url' => 'quienes_somos.php'],
        ['nombre' => 'Contáctanos', 'url' => 'contacto.php']
    ];

    foreach ($paginasEstaticas as $pagina) {
        if (strpos(strtolower($pagina['nombre']), $term) !== false || strpos($term, strtolower($pagina['url'])) !== false) {
            $resultados[] = $pagina;
        }
    }
    
    // Servicios dinámicos
    $query = "SELECT id, nombre FROM servicios WHERE LOWER(nombre) LIKE :term";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':term' => "%$term%"]);
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($servicios as $servicio) {
        $resultados[] = [
            'nombre' => $servicio['nombre'],
            'url' => 'ficha_servicio.php?idServicio=' . $servicio['id']
        ];
    }

    // Si no hay nada
    if (empty($resultados)) {
        $resultados[] = ['nombre' => 'No se encontraron resultados.', 'url' => '#'];
    }

    echo json_encode($resultados);
}
?>
