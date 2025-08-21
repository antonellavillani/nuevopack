<?php
session_start();
require_once '../config/config.php';
require_once 'auth.php';

// Cerrar sesión si el usuario hizo logout manual
cerrarSesionAdmin();

// Redirigir
if (isset($_GET['volver_web'])) {
    header("Location: ../index.php");
} else {
    header("Location: login.php");
}

exit();
