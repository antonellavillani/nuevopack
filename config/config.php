<?php
// Configuración de conexión a la base de datos
$host = 'localhost'; // o la dirección IP del servidor de la base de datos
$dbname = 'nuevopack_db';
$username = 'root'; // Reemplaza con el nombre de usuario de tu base de datos
$password = 'toor'; // Reemplaza con la contraseña de tu base de datos

try {
    // Crear una instancia de PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Configurar el modo de error de PDO para excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

?>
