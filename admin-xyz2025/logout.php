<?php
session_start();
require_once '../config/config.php';
require_once 'auth.php';

$tieneCookie = isset($_COOKIE['remember_token']);

// Si no tiene cookie, cerrar sesión completamente
if (!$tieneCookie) {
    cerrarSesionAdmin();
} else {
    // Destruir la sesión actual y dejar la cookie viva para re-log automático
    $_SESSION = [];
    session_destroy();
}

if (isset($_GET['volver_web'])) {
    header("Location: ../index.php");
} else {
    header("Location: login.php");
}

exit();
