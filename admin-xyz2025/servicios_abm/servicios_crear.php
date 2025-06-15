<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../login.php");
    exit();
}

require_once '../../config/config.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $foto = $_FILES["foto"];

    if (!empty($nombre) && $foto["error"] === 0) {
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $nombre_original = $foto["name"];
        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

        if (in_array($extension, $extensiones_permitidas)) {
            $nombre_archivo = uniqid("serv_", true) . "." . $extension;
            $ruta_destino = "../../uploads/" . $nombre_archivo;

            if (move_uploaded_file($foto["tmp_name"], $ruta_destino)) {
                $stmt = $conn->prepare("INSERT INTO servicios (nombre, foto) VALUES (:nombre, :foto)");
                $stmt->bindParam(":nombre", $nombre);
                $stmt->bindParam(":foto", $nombre_archivo);
                $stmt->execute();

                $mensaje = "Servicio creado correctamente.";
            } else {
                $mensaje = "Error al mover el archivo.";
            }
        } else {
            $mensaje = "Extensión de imagen no permitida.";
        }
    } else {
        $mensaje = "Completá todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Servicio</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
</head>
<body>
    <div class="contenedor">
        <h2>🛠️ Crear Nuevo Servicio</h2>

        <?php if ($mensaje): ?>
            <p><?= $mensaje ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre del servicio:</label><br>
            <input type="text" name="nombre" id="nombre" required><br><br>

            <label for="foto">Imagen del servicio:</label><br>
            <input type="file" name="foto" id="foto" accept="image/*" required><br><br>

            <button type="submit">Crear servicio</button>
            <a href="../servicios.php">← Volver</a>
        </form>
    </div>
</body>
</html>
