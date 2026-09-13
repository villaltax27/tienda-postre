<?php
session_start(); session_unset(); session_destroy(); session_start();
$_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Sesión cerrada correctamente.'];
header('Location: login.php'); exit;
