<?php
$tituloPagina = 'Catálogo';
$base = '../';
require_once __DIR__ . '/../php/conexion.php';
require_once __DIR__ . '/../php/util.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!esCliente()) { mensaje('warning', 'Inicia sesión como cliente para agregar productos.'); header('Location: ../login.php'); exit; }
    $id = (int) ($_POST['id_producto'] ?? 0);
    $stmt = $conexion->prepare('SELECT cantidad FROM productos WHERE id_producto = ?'); $stmt->bind_param('i', $id); $stmt->execute(); $p = $stmt->get_result()->fetch_assoc();
    if ($p && $p['cantidad'] > 0) { $_SESSION['carrito'][$id] = min(($_SESSION['carrito'][$id] ?? 0) + 1, (int)$p['cantidad']); mensaje('success', 'Producto agregado al carrito.'); }
    else { mensaje('warning', 'Este producto ya no tiene existencias.'); }
    header('Location: productos.php'); exit;
}
require_once __DIR__ . '/../php/encabezado.php';
$productos = $conexion->query('SELECT p.id_producto, p.nombre_producto, p.descripcion, p.precio, p.cantidad, c.nombre_categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id_categoria ORDER BY c.nombre_categoria, p.nombre_producto');
?>
<main class="container py-5">
    <?php mostrarMensaje(); ?>
    <div class="text-center mb-5">
        <p class="eyebrow">ELIGE TU FAVORITO</p>
        <h1>Catálogo de postres</h1>
    </div>
    <div class="row g-4">
        <?php while ($producto = $productos->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4">
                <article class="card h-100 border-0 shadow-sm producto-card">
                    <div class="postre-ilustracion">🧁</div>
                    <div class="card-body p-4">
                        <span class="categoria"><?php echo htmlspecialchars($producto['nombre_categoria']); ?></span>
                        <h2 class="h5 mt-2"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h2>
                        <p class="text-muted"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        <strong>$<?php echo number_format((float) $producto['precio'], 2); ?></strong>
                        <p class="small text-muted mb-2">Disponibles: <?php echo (int) $producto['cantidad']; ?></p>
                        <?php if (esCliente()): ?>
                            <form method="post"><input type="hidden" name="id_producto" value="<?php echo (int) $producto['id_producto']; ?>"><button class="btn btn-principal w-100" type="submit" <?php echo $producto['cantidad'] < 1 ? 'disabled' : ''; ?>>Agregar al carrito</button></form>
                        <?php elseif (empty($_SESSION['usuario'])): ?>
                            <a class="btn btn-outline-secondary w-100" href="../login.php">Inicia sesión para comprar</a>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        <?php endwhile; ?>
    </div>
</main>
<?php require_once __DIR__ . '/../php/pie.php'; ?>
