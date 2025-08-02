<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

$alertas = [];

// 1. Servicios sin imagen o descripción
$stmt = $pdo->query("SELECT nombre, foto, descripcion FROM servicios");
while ($s = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (empty($s['foto'])) {
        $alertas[] = "El servicio \"{$s['nombre']}\" no tiene imagen.";
    }
    if (empty($s['descripcion'])) {
        $alertas[] = "El servicio \"{$s['nombre']}\" no tiene descripción.";
    }
}

// 2. Precios sin descripción o tipo_unidad
$stmt = $pdo->query("SELECT descripcion, tipo_unidad FROM precios_servicios");
while ($p = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (empty($p['descripcion'])) {
        $alertas[] = "Un precio no tiene descripción.";
    }
    if (empty($p['tipo_unidad'])) {
        $alertas[] = "Un precio no tiene tipo de unidad.";
    }
}

// 3. Usuarios especiales sin teléfono o no aprobados
$stmt = $pdo->query("SELECT email, telefono, aprobado FROM usuarios_especiales");
while ($u = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (empty($u['telefono'])) {
        $alertas[] = "El usuario \"{$u['email']}\" no tiene número de teléfono.";
    }
    if ((int)$u['aprobado'] === 0) {
        $alertas[] = "El usuario \"{$u['email']}\" aún no fue aprobado.";
    }
}

echo json_encode([
    "alertas" => $alertas
]);
