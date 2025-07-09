<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../login.php");
    exit();
}

require_once '../../config/config.php';

// Obtener lista de servicios
$stmt = $pdo->prepare("SELECT id, nombre FROM servicios ORDER BY nombre ASC");
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
        $stmt = $pdo->prepare("INSERT INTO precios_servicios (servicio_id, descripcion, tipo_unidad, precio) VALUES (?, ?, ?, ?)");
        $stmt->execute([$servicio_id, $descripcion, $tipo_unidad, $precio]);
        $exito = "Precio agregado correctamente.";

        // Registrar actividad en la tabla actividad_admin
        $descripcionActividad = 'Nuevo precio agregado para "' . htmlspecialchars($descripcion) . '"';
        $stmtActividad = $pdo->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
        $stmtActividad->execute(['precio', $descripcionActividad]);

    } else {
        $error = "Por favor, completá todos los campos correctamente.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Precio | Panel de Administración NuevoPack</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-precio">Crear Nuevo Precio</h2>

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
        <a href="../precios.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>
</body>
</html>
