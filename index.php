<?php
$tituloPagina = 'Inicio';
require_once __DIR__ . '/php/conexion.php';
require_once __DIR__ . '/php/encabezado.php';
$categorias = $conexion->query('SELECT id_categoria, nombre_categoria, descripcion FROM categorias ORDER BY id_categoria');
$productosDestacados = $conexion->query('SELECT id_producto, nombre_producto, descripcion, precio, imagen FROM productos ORDER BY id_producto ASC LIMIT 4');
$imagenesCategorias = [
    'Pasteles' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=700&q=85',
    'Cupcakes' => 'https://images.unsplash.com/photo-1486427944299-d1955d23e34d?auto=format&fit=crop&w=700&q=85',
    'Galletas' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=700&q=85',
];
?>
<main>
    <section class="hero-section">
        <div class="container hero-contenedor">
            <div class="hero-texto">
                <p class="etiqueta-hero">SWEET PLACE · PASTELERÍA ARTESANAL</p>
                <h1>Postres para disfrutar y <em>compartir.</em></h1>
                <p>Pasteles, cupcakes y galletas para los días cotidianos y las celebraciones que importan.</p>
                <div class="hero-acciones">
                    <a class="btn btn-principal btn-lg" href="cliente/productos.php">Ver el catálogo</a>
                    <a class="hero-enlace" href="#categorias">Explorar categorías <span>→</span></a>
                </div>
            </div>
            <div class="hero-imagen" role="img" aria-label="Pastel de fresa y postres variados"><span>Preparado para tu ocasión</span></div>
        </div>
    </section>

    <section class="container beneficios" aria-label="Cómo comprar en Sweet Place">
        <article><span>01</span><div><strong>Elige tu postre</strong><small>Explora el catálogo por categoría.</small></div></article>
        <article><span>02</span><div><strong>Define la entrega</strong><small>Selecciona retiro o entrega a domicilio.</small></div></article>
        <article><span>03</span><div><strong>Sigue tu pedido</strong><small>Consulta su estado desde tu cuenta.</small></div></article>
    </section>

    <section class="container seccion" id="categorias">
        <div class="titulo-seccion">
            <div><p class="eyebrow">ELIGE UNA CATEGORÍA</p><h2>Encuentra tu favorito</h2></div>
            <a href="cliente/productos.php">Ver catálogo completo <span>→</span></a>
        </div>
        <div class="categorias-grid">
            <?php while ($categoria = $categorias->fetch_assoc()): ?>
                <?php $imagen = $imagenesCategorias[$categoria['nombre_categoria']] ?? 'https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=700&q=85'; ?>
                <a class="categoria-card" href="cliente/productos.php?categoria=<?php echo (int) $categoria['id_categoria']; ?>">
                    <img src="<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>">
                    <div><strong><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></strong><small><?php echo htmlspecialchars($categoria['descripcion']); ?></small></div>
                </a>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="container seccion" id="destacados">
        <div class="titulo-seccion">
            <div><p class="eyebrow">SELECCIÓN DE LA CASA</p><h2>Postres del catálogo</h2></div>
            <a href="cliente/productos.php">Ver todos <span>→</span></a>
        </div>
        <div class="productos-grid">
            <?php while ($producto = $productosDestacados->fetch_assoc()): ?>
                <?php $imagen = imagenProducto($producto); ?>
                <article class="producto-card">
                    <a class="producto-imagen" href="cliente/detalle_producto.php?id=<?php echo (int) $producto['id_producto']; ?>"><img src="<?php echo e($imagen); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"></a>
                    <div class="producto-info">
                        <h3><a href="cliente/detalle_producto.php?id=<?php echo (int) $producto['id_producto']; ?>"><?php echo htmlspecialchars($producto['nombre_producto']); ?></a></h3>
                        <strong>$<?php echo number_format((float) $producto['precio'], 2); ?></strong>
                        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        <a class="boton-agregar" href="cliente/detalle_producto.php?id=<?php echo (int) $producto['id_producto']; ?>">Ver detalle <span>→</span></a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/php/pie.php'; ?>
