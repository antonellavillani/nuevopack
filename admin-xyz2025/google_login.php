<?php
require_once __DIR__ . '/config_secrets.php';
require_once '../vendor/autoload.php';
use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;
require_once '../config/config.php';
session_start();

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri('http://localhost/nuevopack/admin-xyz2025/google_login.php');
$client->addScope("email");
$client->addScope("profile");

if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
} else {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Obtener perfil del usuario
    $oauth2 = new Google_Service_Oauth2($client);
    $perfil = $oauth2->userinfo->get();
    $email = $perfil->email;
    $nombre = $perfil->givenName;
    $apellido = $perfil->familyName;

    // Verificar si el usuario existe y está aprobado
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios_especiales WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $usuario['aprobado']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['admin_id'] = $usuario['id'];
            header("Location: dashboard.php");
            exit();
        } elseif ($usuario && !$usuario['aprobado']) {
            echo '
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <link rel="stylesheet" href="estilos/estilos_admin.css">
                    <title>Acceso pendiente | Panel de Administración NuevoPack</title>
                </head>
                <body class="body-login">
                    <div class="mensaje-container">
                        <h2>Acceso pendiente</h2>
                        <p>Tu cuenta está pendiente de aprobación.</p>
                        <a href="../index.php" class="btn-volver-login">Volver al inicio</a>
                    </div>
                </body>
                </html>';
        } else {
            // Insertar nuevo usuario pendiente de aprobación
            $stmt = $pdo->prepare("INSERT INTO usuarios_especiales (nombre, apellido, email, aprobado) VALUES (?, ?, ?, 0)");
            $stmt->execute([$nombre, $apellido, $email]);
            echo '
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <link rel="stylesheet" href="estilos/estilos_admin.css">
                    <title>Acceso pendiente | Panel de Administración NuevoPack</title>
                </head>
                <body class="body-login">
                    <div class="mensaje-container">
                        <h2>Acceso pendiente</h2>
                        <p>Tu cuenta fue registrada pero necesita aprobación del administrador.</p>
                        <a href="../index.php" class="btn-volver-login">Volver al inicio</a>
                    </div>
                </body>
                </html>';
        }
    } catch (PDOException $e) {
        echo "Error al verificar el usuario.";
    }
}
?>
