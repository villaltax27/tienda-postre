<?php
function requerirInicio(): void
{
    if (empty($_SESSION['usuario'])) {
        header('Location: ../login.php');
        exit;
    }
}

function requerirAdministrador(): void
{
    requerirInicio();

    if (($_SESSION['usuario']['tipo_usuario'] ?? '') !== 'administrador') {
        header('Location: ../cliente/productos.php');
        exit;
    }
}
