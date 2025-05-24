<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../login.php");
    exit();
}

require_once '../../config/config.php';

// Obtener lista de servicios
$stmt = $conn->prepare("SELECT id, nombre FROM servicios ORDER BY nombre ASC");
$stmt->execute();
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = "";
$exito = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servicio_id = $_POST['servicio_id'];
    $descripcion = $_POST['descripcion'];
    $tipo_unidad = $_POST['tipo_unidad'];
    $precio = $_POST['precio'];

    if ($servicio_id && $descripcion && $tipo_unidad && is_numeric($precio)) {
        $stmt = $conn->prepare("INSERT INTO precios_servicios (servicio_id, descripcion, tipo_unidad, precio) VALUES (?, ?, ?, ?)");
        $stmt->execute([$servicio_id, $descripcion, $tipo_unidad, $precio]);
        $exito = "Precio agregado correctamente.";
    } else {
        $error = "Por favor, completá todos los campos correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Precio</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
</head>
<body>
    <div class="admin-container">
        <h2 class="titulo">💲 Crear Nuevo Precio</h2>
        <a href="../precios.php" class="btn-volver">← Volver</a>

        <?php if ($error): ?>
            <p class="mensaje-error"><?php echo $error; ?></p>
        <?php elseif ($exito): ?>
            <p class="mensaje-exito"><?php echo $exito; ?></p>
        <?php endif; ?>

        <form method="POST" class="formulario-admin">
            <label>Servicio asociado:</label>
            <select name="servicio_id" required>
                <option value="">Seleccionar servicio</option>
                <?php foreach ($servicios as $s): ?>
                    <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['nombre']); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Descripción del precio:</label>
            <input type="text" name="descripcion" required>

            <label>Tipo de unidad:</label>
            <input type="text" name="tipo_unidad" placeholder="Ej: unidad, metro, caja..." required>

            <label>Precio ($):</label>
            <input type="number" name="precio" step="0.01" min="0" required>

            <button type="submit" class="btn-guardar">Guardar</button>
        </form>
    </div>
</body>
</html>
