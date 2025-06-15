<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../index.php");
    exit();
}

require_once '../../config/config.php';

if (!isset($_GET['id'])) {
    header("Location: ../precios.php");
    exit();
}

$id = $_GET['id'];
$mensaje = "";
$mensaje_error = "";

// Obtener datos del precio
$stmt = $conn->prepare("SELECT * FROM precios_servicios WHERE id = ?");
$stmt->execute([$id]);
$precio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$precio) {
    $mensaje_error = "El precio no existe.";
} else {
    // Obtener servicios para el select
    $stmt = $conn->prepare("SELECT id, nombre FROM servicios ORDER BY nombre ASC");
    $stmt->execute();
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $servicio_id = $_POST['servicio_id'];
        $descripcion = $_POST['descripcion'];
        $tipo_unidad = $_POST['tipo_unidad'];
        $valor = $_POST['precio'];

        if ($servicio_id && $descripcion && $tipo_unidad && is_numeric($valor)) {
            $stmt = $conn->prepare("UPDATE precios_servicios SET servicio_id = ?, descripcion = ?, tipo_unidad = ?, precio = ? WHERE id = ?");
            $stmt->execute([$servicio_id, $descripcion, $tipo_unidad, $valor, $id]);
            $mensaje = "Precio actualizado correctamente.";
        } else {
            $mensaje_error = "Todos los campos son obligatorios y deben tener formato válido.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Precio</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-editar">Editar Precio</h2>

        <?php if ($mensaje): ?>
            <p class="mensaje-exito"><?php echo $mensaje; ?></p>
        <?php elseif ($mensaje_error): ?>
            <p class="mensaje-error"><?php echo $mensaje_error; ?></p>
        <?php endif; ?>

        <?php if ($precio): ?>
            <form method="POST" class="formulario-admin">
                <label>Servicio asociado:</label>
                <select name="servicio_id" required>
                    <?php foreach ($servicios as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo $s['id'] == $precio['servicio_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Descripción del precio:</label>
                <input type="text" name="descripcion" value="<?php echo htmlspecialchars($precio['descripcion']); ?>" required>

                <label>Tipo de unidad:</label>
                <input type="text" name="tipo_unidad" value="<?php echo htmlspecialchars($precio['tipo_unidad']); ?>" required>

                <label>Precio ($):</label>
                <input type="number" name="precio" value="<?php echo htmlspecialchars($precio['precio']); ?>" step="0.01" min="0" required>

                <button type="submit" class="btn-guardar">Guardar cambios</button>
            </form>
            <a href="../precios.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        <?php endif; ?>
    </div>
</body>
</html>
