<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../index.php");
    exit();
}

require_once '../../config/config.php';
require_once '../../admin-xyz2025/config_secrets.php';
require_once '../../admin-xyz2025/utils/mail_helper.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: ../usuarios.php");
    exit();
}

$error = '';
$mensaje = '';
$origen = $_GET['origen'] ?? 'admin';

// Obtener usuario
try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios_especiales WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        header("Location: ../usuarios.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Error al obtener usuario: " . $e->getMessage();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['enviar_link_password'])) {
        $resultado = enviarLinkRecuperacion($pdo, $usuario, $origen);

        if ($resultado === true) {
            $mensaje = "Se envió el enlace para restablecer la contraseña al email del usuario.";
        } else {
            $error = "Error al enviar correo: " . htmlspecialchars($resultado);
        }
        
    } elseif (isset($_POST['actualizar'])) {
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $aprobado = isset($_POST['aprobado']) ? 1 : 0;

        if (empty($nombre) || empty($apellido) || empty($email)) {
            $error = "Nombre, apellido y email son obligatorios.";
        } else {
            try {
                // Si $telefono es vacío, insertar NULL para la BD
                $telefonoBD = $telefono === '' ? null : $telefono;

                $stmt = $pdo->prepare("UPDATE usuarios_especiales SET nombre = ?, apellido = ?, email = ?, telefono = ?, aprobado = ? WHERE id = ?");
                $stmt->execute([$nombre, $apellido, $email, $telefonoBD, $aprobado, $id]);

                // Registrar actividad
                $descripcionActividad = 'Usuario "' . htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido) . '" editado (' . htmlspecialchars($email) . ')';
                $stmtActividad = $pdo->prepare("INSERT INTO actividad_admin (tipo, descripcion) VALUES (?, ?)");
                $stmtActividad->execute(['usuario', $descripcionActividad]);

                header("Location: ../usuarios.php?mensaje=Usuario actualizado correctamente.");
                exit();
            } catch (PDOException $e) {
                $error = "Error al actualizar: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario | Panel de Administración NuevoPack</title>
    <link rel="stylesheet" href="../estilos/estilos_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="contenedor">
        <h2 class="titulo-pagina icono-editar">Editar Usuario Especial</h2>

        <?php if ($error): ?>
            <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="formulario-admin">
            <input type="hidden" name="origen" value="<?= htmlspecialchars($_GET['origen'] ?? '') ?>">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>

            <label>Email:</label>
            <input id="cursor-not-allowed" type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" readonly>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">

            <label>
                <input type="checkbox" name="aprobado" <?= $usuario['aprobado'] ? 'checked' : '' ?>>
                Usuario aprobado
            </label>
            <small id="usuario-aprobado">
                Si esta opción está marcada, el usuario podrá ingresar al sistema. Si no, quedará deshabilitado.
            </small>

            <button type="button" name="enviar_link_password" id="btn-reset-password" class="btn-guardar" data-id="<?= htmlspecialchars($usuario['id']) ?>" data-origen="<?= htmlspecialchars($origen) ?>">Restablecer contraseña con link</button>

            <div id="spinner" class="dot-spinner" style="display: none; margin: 20px auto;">
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
                <div class="dot-spinner__dot"></div>
            </div>

            <div id="respuesta-reset" style="margin-top: 10px;"></div>

            <?php if ($mensaje): ?>
                <div class="mensaje-ok">
                    <?= htmlspecialchars($mensaje) ?>
                    <div id="mensaje-aclaracion">Si esta no es tu cuenta, recomendale al usuario que revise su correo para continuar con el cambio de contraseña.</div>
                </div>
            <?php endif; ?>

            <div class="form-botones">
                <button type="submit" name="actualizar" class="btn-guardar">Actualizar</button>
            </div>
        </form>
        <a href="../usuarios.php" class="link-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>
    
<!-- JavaScript -->
<script src="../js/script.js"></script>
</body>
</html>
