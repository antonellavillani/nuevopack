<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../index.php");
    exit();
}

require_once '../../config/config.php';

$error = '';
$mensaje = '';

function esPasswordSegura($password) {
    // Al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+={}[\]:;"\'<>,.?\/]).{8,}$/', $password);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $password = $_POST['password'];

    if (empty($nombre) || empty($apellido) || empty($email) || empty($telefono) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        if (!esPasswordSegura($password)) {
        $error = "La contraseña no cumple con los requisitos mínimos.";
        } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios_especiales (nombre, apellido, email, telefono, password_hash) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellido, $email, $telefono, $password_hash]);
            header("Location: ../usuarios.php?mensaje=Usuario creado correctamente.");
            
            // Registrar actividad en la tabla actividad_admin
            $descripcionActividad = 'Nuevo usuario "' . htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido) . '" creado (' . htmlspecialchars($email) . ')';
            $stmtActividad = $pdo->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
            $stmtActividad->execute(['usuario', $descripcionActividad]);

            exit();
        } catch (PDOException $e) {
            $error = "Error al insertar: " . $e->getMessage();
        }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario | Panel de Administración NuevoPack</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-usuario">Crear Usuario Especial</h2>

        <?php if ($error): ?>
            <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="formulario-admin">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono">

            <label>Contraseña:</label>
            <input type="password" name="password" id="password" required>

            <div class="reglas-password">
                <p id="ruleLength">❌ Mínimo 8 caracteres</p>
                <p id="ruleMayuscula">❌ Al menos una mayúscula</p>
                <p id="ruleMinuscula">❌ Al menos una minúscula</p>
                <p id="ruleNumero">❌ Al menos un número</p>
                <p id="ruleEspecial">❌ Al menos un carácter especial</p>
            </div>

            <label>Repetir Contraseña:</label>
            <input type="password" name="repetir_password" id="repetir_password" required>
            <p id="errorCoincidencia" class="contrasena_no_coincide">
                Las contraseñas no coinciden.
            </p>

            <div class="form-botones">
                <button type="submit" class="btn-guardar">Guardar</button>
            </div>
        </form>
        <a href="../usuarios.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>

<!-- JavaScript -->
<script src="../js/script.js"></script>
</body>
</html>
