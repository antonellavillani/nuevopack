<?php
require_once('../includes/auth_admin.php');
require_once '../../config/config.php';

if (!isset($_GET['id'])) {
    header("Location: ../precios.php");
    exit();
}

$id = $_GET['id'];
$mensaje = "";
$mensaje_error = "";

// Obtener datos del precio
$stmt = $pdo->prepare("SELECT * FROM precios_servicios WHERE id = ?");
$stmt->execute([$id]);
$precio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$precio) {
    $mensaje_error = "El precio no existe.";
} else {
    // Obtener servicios para el select
    $stmt = $pdo->prepare("SELECT id, nombre FROM servicios ORDER BY nombre ASC");
    $stmt->execute();
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $servicio_id = $_POST['servicio_id'];
        $descripcion = $_POST['descripcion'];
        $tipo_unidad = $_POST['tipo_unidad'];
        $valor = $_POST['precio'];

        if ($servicio_id && $descripcion && $tipo_unidad && is_numeric($valor)) {
            $stmt = $pdo->prepare("UPDATE precios_servicios SET servicio_id = ?, descripcion = ?, tipo_unidad = ?, precio = ? WHERE id = ?");
            $stmt->execute([$servicio_id, $descripcion, $tipo_unidad, $valor, $id]);
            
        // Mensaje de éxito y redirigir
        $_SESSION['success'] = "Precio actualizado correctamente.";
        header("Location: ../precios.php");
        exit();

        } else {
            //$mensaje_error = "Todos los campos son obligatorios y deben tener formato válido.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Precio | Panel de Administración NuevoPack</title>
    <link rel="icon" href="/favicon.ico?v=3" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico?v=3" type="image/x-icon">
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
            <form id="form-precio-editar" method="POST" class="formulario-admin" novalidate>
                <label>Servicio asociado:</label>
                <select id="servicio_id" name="servicio_id" required>
                    <?php foreach ($servicios as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo $s['id'] == $precio['servicio_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="mensaje-advertencia" id="error-servicio_id"></p>

                <label>Descripción del precio:</label>
                <input id="descripcion" type="text" name="descripcion" value="<?php echo htmlspecialchars($precio['descripcion']); ?>" required>
                <p class="mensaje-advertencia" id="error-descripcion"></p>

                <label>Tipo de unidad:</label>
                <input id="tipo_unidad" type="text" name="tipo_unidad" value="<?php echo htmlspecialchars($precio['tipo_unidad']); ?>" required>
                <p class="mensaje-advertencia" id="error-tipo_unidad"></p>

                <label>Precio ($):</label>
                <input id="precio" type="number" name="precio" value="<?php echo htmlspecialchars($precio['precio']); ?>" step="0.01" min="0" required>
                <p class="mensaje-advertencia" id="error-precio"></p>

                <button type="submit" class="btn-guardar">Guardar cambios</button>
            </form>
            <a href="../precios.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        <?php endif; ?>
    </div>

<script src="../js/script.js"></script>
</body>
</html>
