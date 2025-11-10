<?php
require_once('../includes/auth_admin.php');
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

        // Registrar actividad
        $descripcionActividad = 'Nuevo precio agregado para "' . htmlspecialchars($descripcion) . '"';
        $stmtActividad = $pdo->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
        $stmtActividad->execute(['precio', $descripcionActividad]);

        // Guardar mensaje en sesión y redirigir
        $_SESSION['success'] = "Precio agregado correctamente.";
        header("Location: ../precios.php");
        exit();

    } else {
        //$error = "Por favor, completá todos los campos correctamente.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Precio | Panel de Administración NuevoPack</title>
    <link rel="icon" href="/favicon.ico?v=3" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico?v=3" type="image/x-icon">
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

        <form id="form-precio-crear" method="POST" class="formulario-admin" novalidate>
            <label>Servicio asociado:</label>
            <select name="servicio_id" id="servicio_id" required>
                <option value="">Seleccionar servicio</option>
                <?php foreach ($servicios as $s): ?>
                    <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="mensaje-advertencia" id="error-servicio_id"></p>

            <label>Descripción del precio:</label>
            <input id="descripcion" type="text" name="descripcion" placeholder="Millar, postura, bocas..." required>
            <p class="mensaje-advertencia" id="error-descripcion"></p>

            <label>Tipo de unidad:</label>
            <input id="tipo_unidad" type="text" name="tipo_unidad" placeholder="Ej: unidad, metro, caja..." required>
            <p class="mensaje-advertencia" id="error-tipo_unidad"></p>

            <label>Precio ($):</label>
            <input id="precio" type="number" name="precio" step="0.01" min="0" placeholder="(Solo números)" required>
            <p class="mensaje-advertencia" id="error-precio"></p>

            <button type="submit" class="btn-guardar">Guardar</button>
        </form>
        <a href="../precios.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>

<script src="../js/script.js"></script>
</body>
</html>
