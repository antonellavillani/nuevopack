<?php
session_start();
require_once '../config/config.php';
require_once 'auth.php';

if (!isset($_GET['volver_web'])) {
    cerrarSesionAdmin();
}

// Redirigir
if (isset($_GET['volver_web'])) {
    header("Location: ../index.php");
} else {
    header("Location: login.php");
}

exit();
