<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'nuevopack_db';
$username = 'root';
$password = 'toor';

try {
    // Instancia de PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Configuración del modo de error de PDO para excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

?>
