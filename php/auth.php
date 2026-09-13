<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
function requerirInicio(): void
{
    if (empty($_SESSION['usuario'])) {
        $base = $GLOBALS['base'] ?? '';
        header('Location: ' . $base . 'login.php');
        exit;
    }
}

function requerirAdministrador(): void
{
    requerirInicio();

    if (($_SESSION['usuario']['tipo_usuario'] ?? '') !== 'administrador') {
        $base = $GLOBALS['base'] ?? '';
        header('Location: ' . $base . 'cliente/productos.php');
        exit;
    }
}
