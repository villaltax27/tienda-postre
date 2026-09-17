<?php
$tituloPagina = 'Detalle del postre';
$base = '../';
require_once __DIR__ . '/../php/conexion.php';
require_once __DIR__ . '/../php/util.php';
$id = max(0, (int) ($_GET['id'] ?? $_POST['id_producto'] ?? 0));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!esCliente()) { mensaje('warning', 'Inicia sesión como cliente para agregar productos.'); header('Location: ../login.php'); exit; }
    $stock = $conexion->prepare('SELECT cantidad FROM productos WHERE id_producto = ?');
    $stock->bind_param('i', $id); $stock->execute(); $existencia = $stock->get_result()->fetch_assoc();
    if ($existencia && $existencia['cantidad'] > 0) {
        $_SESSION['carrito'][$id] = min(($_SESSION['carrito'][$id] ?? 0) + 1, (int) $existencia['cantidad']);
        mensaje('success', 'Producto agregado al carrito.');
    } else { mensaje('warning', 'Este producto ya no tiene existencias.'); }
    header('Location: detalle_producto.php?id=' . $id); exit;
}

$consulta = $conexion->prepare('SELECT p.*, c.nombre_categoria FROM productos p JOIN categorias c ON c.id_categoria = p.id_categoria WHERE p.id_producto = ? LIMIT 1');
$consulta->bind_param('i', $id); $consulta->execute(); $producto = $consulta->get_result()->fetch_assoc();
if (!$producto) { http_response_code(404); $tituloPagina = 'Producto no encontrado'; }
require_once __DIR__ . '/../php/encabezado.php';
?>
<main class="container py-5">
    <?php mostrarMensaje(); ?>
    <?php if (!$producto): ?>
        <div class="alert alert-warning">El postre que buscas no está disponible.</div><a class="btn btn-principal" href="productos.php">Volver al catálogo</a>
    <?php else: ?>
        <a class="enlace-volver" href="productos.php">← Volver al catálogo</a>
        <article class="detalle-producto mt-3">
            <div class="detalle-producto-imagen"><img src="<?php echo e(imagenProducto($producto)); ?>" alt="<?php echo e($producto['nombre_producto']); ?>"></div>
            <div class="detalle-producto-info"><span class="categoria"><?php echo e($producto['nombre_categoria']); ?></span><h1><?php echo e($producto['nombre_producto']); ?></h1><p class="detalle-descripcion"><?php echo e($producto['descripcion']); ?></p><p class="precio-detalle">$<?php echo number_format((float) $producto['precio'], 2); ?></p><p class="stock-detalle <?php echo $producto['cantidad'] < 1 ? 'agotado' : ''; ?>"><?php echo $producto['cantidad'] < 1 ? 'Sin existencias' : (int) $producto['cantidad'] . ' disponibles'; ?></p><div class="detalle-nota"><strong>Preparado con cariño</strong><span>Consulta ingredientes o necesidades especiales antes de ordenar.</span></div><?php if (esCliente()): ?><form method="post"><input type="hidden" name="id_producto" value="<?php echo (int) $producto['id_producto']; ?>"><button class="btn btn-principal btn-lg" type="submit" <?php echo $producto['cantidad'] < 1 ? 'disabled' : ''; ?>>Agregar al carrito</button></form><?php elseif (empty($_SESSION['usuario'])): ?><a class="btn btn-principal btn-lg" href="../login.php">Inicia sesión para comprar</a><?php endif; ?></div>
        </article>
    <?php endif; ?>
</main>
<?php require_once __DIR__ . '/../php/pie.php'; ?>
