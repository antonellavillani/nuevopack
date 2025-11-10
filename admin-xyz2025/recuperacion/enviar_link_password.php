<?php
header('Content-Type: application/json');
require_once('../includes/auth_admin.php');

require_once '../../config/config.php';
require_once '../config_secrets.php';
require_once '../utils/mail_helper.php';

$id = $_POST['id'] ?? null;
$origen = $_POST['origen'] ?? 'admin';

if (!$id) {
    echo json_encode(['ok' => false, 'msg' => 'ID de usuario no proporcionado']);
    exit();
}

// Obtener usuario
try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios_especiales WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(['ok' => false, 'msg' => 'Usuario no encontrado']);
        exit();
    }

    $resultado = enviarLinkRecuperacion($pdo, $usuario, $origen);

    if ($resultado === true) {
        echo json_encode([
            'ok' => true,
            'msg' => 'Link enviado correctamente.'
        ]);
    } else {
        echo json_encode([
            'ok' => false,
            'msg' => '❌ Error al enviar correo: ' . htmlspecialchars($resultado)
        ]);
    }

} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'msg' => 'Error de base de datos: ' . $e->getMessage()]);
}
