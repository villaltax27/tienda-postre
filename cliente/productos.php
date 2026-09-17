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
$busqueda = trim($_GET['buscar'] ?? '');
$categoriaId = max(0, (int) ($_GET['categoria'] ?? 0));
$categorias = $conexion->query('SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria');
$sql = 'SELECT p.id_producto, p.nombre_producto, p.descripcion, p.precio, p.cantidad, p.imagen, c.nombre_categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id_categoria';
if ($categoriaId && $busqueda !== '') {
    $productos = $conexion->prepare($sql . ' WHERE p.id_categoria = ? AND (p.nombre_producto LIKE ? OR p.descripcion LIKE ?) ORDER BY p.nombre_producto');
    $termino = '%' . $busqueda . '%'; $productos->bind_param('iss', $categoriaId, $termino, $termino);
} elseif ($categoriaId) {
    $productos = $conexion->prepare($sql . ' WHERE p.id_categoria = ? ORDER BY p.nombre_producto'); $productos->bind_param('i', $categoriaId);
} elseif ($busqueda !== '') {
    $productos = $conexion->prepare($sql . ' WHERE p.nombre_producto LIKE ? OR p.descripcion LIKE ? ORDER BY p.nombre_producto');
    $termino = '%' . $busqueda . '%'; $productos->bind_param('ss', $termino, $termino);
} else { $productos = $conexion->prepare($sql . ' ORDER BY c.nombre_categoria, p.nombre_producto'); }
$productos->execute();
$resultadoProductos = $productos->get_result();
require_once __DIR__ . '/../php/encabezado.php';
?>
<main class="container py-5">
    <?php mostrarMensaje(); ?>
    <div class="catalogo-encabezado"><div><p class="eyebrow">ELIGE TU FAVORITO</p><h1>Catálogo de postres</h1><p>Encuentra algo dulce para compartir o disfrutar hoy.</p></div><?php if (esCliente()): ?><a class="btn btn-contorno" href="carrito.php">Ver mi carrito</a><?php endif; ?></div>
    <form class="filtros-catalogo" method="get"><label class="visually-hidden" for="buscar">Buscar postre</label><input id="buscar" class="form-control" name="buscar" value="<?php echo e($busqueda); ?>" placeholder="Buscar por nombre o descripción"><label class="visually-hidden" for="categoria">Categoría</label><select id="categoria" class="form-select" name="categoria"><option value="0">Todas las categorías</option><?php while ($categoria = $categorias->fetch_assoc()): ?><option value="<?php echo (int) $categoria['id_categoria']; ?>" <?php echo $categoriaId === (int) $categoria['id_categoria'] ? 'selected' : ''; ?>><?php echo e($categoria['nombre_categoria']); ?></option><?php endwhile; ?></select><button class="btn btn-principal" type="submit">Buscar</button><?php if ($busqueda !== '' || $categoriaId): ?><a class="btn btn-link" href="productos.php">Limpiar</a><?php endif; ?></form>
    <p class="resultado-catalogo"><?php echo $resultadoProductos->num_rows; ?> postre<?php echo $resultadoProductos->num_rows === 1 ? '' : 's'; ?> encontrado<?php echo $resultadoProductos->num_rows === 1 ? '' : 's'; ?>.</p>
    <div class="row g-4">
        <?php while ($producto = $resultadoProductos->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4">
                <article class="card h-100 border-0 shadow-sm producto-card producto-catalogo">
                    <a class="producto-catalogo-imagen" href="detalle_producto.php?id=<?php echo (int) $producto['id_producto']; ?>"><img src="<?php echo e(imagenProducto($producto)); ?>" alt="<?php echo e($producto['nombre_producto']); ?>"></a>
                    <div class="card-body p-4">
                        <span class="categoria"><?php echo htmlspecialchars($producto['nombre_categoria']); ?></span>
                        <h2 class="h5 mt-2"><a href="detalle_producto.php?id=<?php echo (int) $producto['id_producto']; ?>"><?php echo htmlspecialchars($producto['nombre_producto']); ?></a></h2>
                        <p class="text-muted"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        <strong class="precio-producto">$<?php echo number_format((float) $producto['precio'], 2); ?></strong>
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
