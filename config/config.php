<?php
// Configuración de conexión a la base de datos
require_once __DIR__ . '/../admin-xyz2025/config_secrets.php';

$host = DB_HOST;
$dbname = DB_NAME;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}
class ApiConfig {
    const BASE_URL = "https://2960f0137575.ngrok-free.app/nuevopack/";
}

?>
