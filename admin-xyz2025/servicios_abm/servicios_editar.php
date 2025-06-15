<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../login.php");
    exit();
}

require_once '../../config/config.php';

// Obtener el servicio a editar
if (!isset($_GET['id'])) {
    header("Location: ../servicios.php");
    exit();
}

$id = $_GET['id'];

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);

    // Validar que no esté vacío
    if ($nombre === '') {
        $error = "El dato 'Nombre' no puede estar vacío.";
    } else {
        // Manejo de la nueva imagen
        if ($_FILES['foto']['name']) {
            $foto_nombre = time() . '_' . basename($_FILES['foto']['name']);
            $ruta_destino = '../../uploads/' . $foto_nombre;
            move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);

            // Actualizar con imagen nueva
            $stmt = $conn->prepare("UPDATE servicios SET nombre = ?, foto = ? WHERE id = ?");
            $stmt->execute([$nombre, $foto_nombre, $id]);
        } else {
            // Actualizar sin cambiar imagen
            $stmt = $conn->prepare("UPDATE servicios SET nombre = ? WHERE id = ?");
            $stmt->execute([$nombre, $id]);
        }

        header("Location: ../servicios.php");
        exit();
    }
}

// Traer los datos actuales del servicio
$stmt = $conn->prepare("SELECT * FROM servicios WHERE id = ?");
$stmt->execute([$id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servicio) {
    echo "Servicio no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Servicio</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
</head>
<body>
    <div class="contenedor">
        <h2>✏️ Editar Servicio</h2>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre del servicio:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($servicio['nombre']) ?>" required>

            <p>Imagen actual:</p>
            <img src="../../uploads/<?= $servicio['foto'] ?>" alt="Imagen actual" class="img-tabla" style="margin-bottom: 10px;">

            <label for="foto">Cambiar imagen (opcional):</label>
            <input type="file" id="foto" name="foto" accept="image/*">

            <button type="submit" class="boton-accion editar">Guardar cambios</button>
            <a href="../servicios.php" class="boton-accion eliminar">Cancelar</a>
        </form>
    </div>
</body>
</html>
