<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tituloPagina = $tituloPagina ?? 'Sweet Place';
$base = $base ?? '';
require_once __DIR__ . '/util.php';
$usuarioActual = $_SESSION['usuario'] ?? null;
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($tituloPagina); ?> | Sweet Place</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base; ?>css/estilos.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand marca" href="<?php echo $base; ?>index.php">
    <img class="logo-sweet-place" src="<?php echo $base; ?>img/sweetplace-logo.png" alt="Sweet Place"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 menu-principal">
                <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>cliente/productos.php">Postres</a></li>
                <?php if ($usuarioActual): ?>
                    <?php if (($usuarioActual['tipo_usuario'] ?? '') === 'administrador'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>admin/panel.php">Panel</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>admin/pedidos_personalizados.php">Personalizados</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>cliente/pedido_personalizado.php">Personalizados</a></li>
                        <li class="nav-item"><a class="nav-link nav-carrito" href="<?php echo $base; ?>cliente/carrito.php">Carrito <span><?php echo array_sum($_SESSION['carrito'] ?? []); ?></span></a></li>
                    <?php endif; ?>
                    <li class="nav-item dropdown nav-usuario">
                        <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo ($usuarioActual['tipo_usuario'] ?? '') === 'administrador' ? 'Administración' : e($usuarioActual['nombre']); ?></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (($usuarioActual['tipo_usuario'] ?? '') === 'cliente'): ?><li><a class="dropdown-item" href="<?php echo $base; ?>cliente/mis_compras.php">Mis pedidos</a></li><li><hr class="dropdown-divider"></li><?php endif; ?>
                            <li><a class="dropdown-item" href="<?php echo $base; ?>logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-principal ms-lg-2" href="<?php echo $base; ?>login.php">Iniciar sesión</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
