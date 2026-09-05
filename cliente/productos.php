<?php
$tituloPagina = 'Catálogo';
$base = '../';
require_once __DIR__ . '/../php/conexion.php';
require_once __DIR__ . '/../php/encabezado.php';
$productos = $conexion->query('SELECT p.nombre_producto, p.descripcion, p.precio, c.nombre_categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id_categoria ORDER BY c.nombre_categoria, p.nombre_producto');
?>
<main class="container py-5">
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
                    </div>
                </article>
            </div>
        <?php endwhile; ?>
    </div>
</main>
<?php require_once __DIR__ . '/../php/pie.php'; ?>
