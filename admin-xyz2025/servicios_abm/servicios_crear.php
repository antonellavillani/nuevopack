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
    $descripcion = $_POST["descripcion"];
    $foto = $_FILES["foto"];

    if (!empty($nombre) && $foto["error"] === 0) {
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $nombre_original = $foto["name"];
        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

        if (in_array($extension, $extensiones_permitidas)) {
            $nombre_archivo = uniqid("serv_", true) . "." . $extension;
            $ruta_destino = "../../uploads/" . $nombre_archivo;

            if (move_uploaded_file($foto["tmp_name"], $ruta_destino)) {
                $stmt = $conn->prepare("INSERT INTO servicios (nombre, descripcion, foto) VALUES (:nombre, :descripcion, :foto)");
                $stmt->bindParam(":nombre", $nombre);
                $stmt->bindParam(":descripcion", $descripcion);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-servicio">Crear Nuevo Servicio</h2>

        <?php if ($mensaje): ?>
            <p><?= $mensaje ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="formulario-admin">
            <label for="nombre">Nombre del servicio:</label><br>
            <input type="text" name="nombre" id="nombre" required><br><br>

            <label for="foto">Imagen del servicio:</label><br>
            <input type="file" name="foto" id="foto" accept="image/*"><br><br>

            <label for="descripcion">Descripción del servicio:</label><br>
            <textarea name="descripcion" id="descripcion" rows="4"></textarea><br><br>

            <button type="submit" class="btn-guardar">Crear servicio</button>
        </form>
        <a href="../servicios.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>
</body>
</html>
