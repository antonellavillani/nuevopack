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
    $descripcion = trim($_POST['descripcion']);

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
            $stmt = $pdo->prepare("UPDATE servicios SET nombre = ?, descripcion = ?, foto = ? WHERE id = ?");
            $stmt->execute([$nombre, $descripcion, $foto_nombre, $id]);
        } else {
            // Actualizar sin cambiar imagen
            $stmt = $pdo->prepare("UPDATE servicios SET nombre = ?, descripcion = ? WHERE id = ?");
            $stmt->execute([$nombre, $descripcion, $id]);
        }

        // Mensaje de éxito
        $_SESSION['success'] = "Servicio actualizado correctamente.";

        // Registrar actividad
        $descripcionActividad = 'Servicio "' . htmlspecialchars($nombre) . '" (ID ' . $id . ') actualizado';
        $stmtActividad = $pdo->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
        $stmtActividad->execute(['servicio', $descripcionActividad]);
        
        header("Location: ../servicios.php");
        exit();
    }
}

// Traer los datos actuales del servicio
$stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
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
    <title>Editar Servicio | Panel de Administración NuevoPack</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-editar">Editar Servicio</h2>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data" class="formulario-admin">
            <label for="nombre">Nombre del servicio:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($servicio['nombre']) ?>" required>

            <p>Imagen actual:</p>
            <img src="../../uploads/<?= $servicio['foto'] ?>" alt="Imagen actual" class="img-tabla" style="margin-bottom: 10px;">

            <label for="foto">Cambiar imagen (opcional):</label>
            <input type="file" id="foto" name="foto" accept="image/*">

            <label for="descripcion">Descripción del servicio:</label>
            <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($servicio['descripcion']) ?></textarea>

            <button type="submit" class="btn-guardar">Guardar cambios</button>
        </form>
        <a href="../servicios.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>
</body>
</html>
