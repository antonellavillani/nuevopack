<?php
session_start();
require_once '../config/config.php';
require_once 'auth.php';

cerrarSesionAdmin();
header("Location: ../index.php");
exit();
