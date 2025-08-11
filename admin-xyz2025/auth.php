<?php
require_once __DIR__ . '/../config/config.php';

function generarTokenSeguro($longitud = 64) {
    return bin2hex(random_bytes($longitud / 2));
}

function iniciarSesionAdmin($usuario, $recordar = false) {
    session_regenerate_id(true); // evitar fijación de sesión

    $_SESSION['admin_logged_in'] = true;
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['apellido'] = $usuario['apellido'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['admin_id'] = $usuario['id'];

    if ($recordar) {
        $token = generarTokenSeguro(64);
        $caducidad = time() + (30 * 24 * 60 * 60); // 30 días

        // Guardar token hasheado en la BD
        global $pdo;
        $stmt = $pdo->prepare("UPDATE usuarios_especiales SET remember_token = ?, remember_expira = ? WHERE id = ?");
        $stmt->execute([
            password_hash($token, PASSWORD_DEFAULT),
            date('Y-m-d H:i:s', $caducidad),
            $usuario['id']
        ]);

        // Guardar cookie segura
        setcookie(
            "remember_token",
            $usuario['id'] . ":" . $token,
            [
                'expires' => $caducidad,
                'path' => '/',
                'secure' => true,       // solo HTTPS
                'httponly' => true,     // inaccesible por JS
                'samesite' => 'Strict'
            ]
        );
    }
}

function verificarSesionAdmin() {
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        return true;
    }

    if (isset($_COOKIE['remember_token'])) {
        global $pdo;
        list($userId, $token) = explode(':', $_COOKIE['remember_token'], 2);

        $stmt = $pdo->prepare("SELECT * FROM usuarios_especiales WHERE id = ? AND remember_expira > NOW()");
        $stmt->execute([$userId]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($token, $usuario['remember_token'])) {
            iniciarSesionAdmin($usuario, true);
            return true;
        }
    }

    return false;
}

function cerrarSesionAdmin() {
    global $pdo;

    if (isset($_SESSION['admin_id'])) {
        $stmt = $pdo->prepare("UPDATE usuarios_especiales SET remember_token = NULL, remember_expira = NULL WHERE id = ?");
        $stmt->execute([$_SESSION['admin_id']]);
    }

    // Borrar cookie
    setcookie("remember_token", "", time() - 3600, "/", "", true, true);

    // Destruir sesión
    $_SESSION = [];
    session_destroy();
}
