<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../index.php");
    exit();
}

require_once '../../config/config.php';
require_once '../../admin-xyz2025/config_secrets.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../backend/PHPMailer-master/PHPMailer-master/src/Exception.php';
require '../../backend/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../../backend/PHPMailer-master/PHPMailer-master/src/SMTP.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: ../usuarios.php");
    exit();
}

$error = '';
$mensaje = '';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['enviar_link_password'])) {
        // Generar token y guardar en la BD
        $token = bin2hex(random_bytes(32));
        $expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $stmt = $copdonn->prepare("UPDATE usuarios_especiales SET token_recuperacion = ?, token_expiracion = ? WHERE id = ?");
        $stmt->execute([$token, $expiracion, $id]);

        // Enviar correo con PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->Port = SMTP_PORT;

            $mail->setFrom(SMTP_USER, 'NuevoPack');
            $mail->addAddress($usuario['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Restablecer contraseña - NuevoPack';

            $enlace = "http://localhost/nuevopack/admin-xyz2025/recuperacion/resetear_password.php?token=$token&origen=admin";

            $cuerpo = "
                <div style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;'>
                  <div style='max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
                    <h2 style='color: #333333;'>🔐 Restablecer contraseña</h2>
                    <p>Hola <strong>" . htmlspecialchars($usuario['nombre']) . "</strong>,</p>
                    <p>Para restablecer la contraseña, hacé clic en el siguiente enlace:</p>
                    <p><a href='$enlace' style='color: #6A7348;'>Restablecer contraseña</a></p>
                    <p>Este enlace será válido por <strong>1 hora</strong>.</p>
                    <p>Si no solicitaste esto, podés ignorar este mensaje.</p>
                  </div>
                </div>
            ";

            $mail->Body = $cuerpo;
            $mail->send();

            $mensaje = "Se envió el enlace para restablecer la contraseña al email del usuario.";

        } catch (Exception $e) {
            $error = "Error al enviar correo: " . $mail->ErrorInfo;
        }

    } elseif (isset($_POST['actualizar'])) {
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $aprobado = isset($_POST['aprobado']) ? 1 : 0;

        if (empty($nombre) || empty($apellido) || empty($email) || empty($telefono)) {
            $error = "Todos los campos son obligatorios.";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE usuarios_especiales SET nombre = ?, apellido = ?, email = ?, telefono = ?, aprobado = ? WHERE id = ?");
                $stmt->execute([$nombre, $apellido, $email, $telefono, $aprobado, $id]);

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
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>

            <label>
                <input type="checkbox" name="aprobado" <?= $usuario['aprobado'] ? 'checked' : '' ?>>
                Usuario aprobado
            </label>
            <small id="usuario-aprobado">
                Si esta opción está marcada, el usuario podrá ingresar al sistema. Si no, quedará deshabilitado.
            </small>

            <button type="submit" name="enviar_link_password" class="btn-guardar">Restablecer contraseña con link</button>

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
</body>
</html>
