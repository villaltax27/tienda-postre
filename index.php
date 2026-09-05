<?php
$tituloPagina = 'Inicio';
require_once __DIR__ . '/php/conexion.php';
require_once __DIR__ . '/php/encabezado.php';
$categorias = $conexion->query('SELECT nombre_categoria, descripcion FROM categorias ORDER BY id_categoria');
$productosDestacados = $conexion->query('SELECT nombre_producto, descripcion, precio FROM productos ORDER BY id_producto ASC LIMIT 4');
$imagenesCategorias = [
    'Pasteles' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=700&q=85',
    'Cupcakes' => 'https://images.unsplash.com/photo-1486427944299-d1955d23e34d?auto=format&fit=crop&w=700&q=85',
    'Galletas' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=700&q=85',
];
$imagenesProductos = [
    'Pastel de chocolate' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=900&q=85',
    'Cheesecake de fresa' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=900&q=85',
    'Pastel de vainilla' => 'https://images.unsplash.com/photo-1558636508-e0db3814bd1d?auto=format&fit=crop&w=900&q=85',
    'Tres leches' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?auto=format&fit=crop&w=900&q=85',
];
?>
<main>
    <section class="hero-section">
        <div class="container hero-contenedor">
            <div class="hero-texto">
                <p class="etiqueta-hero">POSTRES HECHOS CON AMOR</p>
                <h1>Tu próximo <em>antojo</em> está aquí.</h1>
                <p>Descubre postres irresistibles preparados para convertir cualquier momento en algo especial.</p>
                <div class="hero-acciones">
                    <a class="btn btn-principal btn-lg" href="cliente/productos.php">Explorar postres</a>
                    <a class="btn btn-contorno btn-lg" href="registro.php">Crear mi cuenta</a>
                </div>
                <span class="frase-manuscrita">La vida sabe mejor<br>en dulce ♡</span>
            </div>
            <div class="hero-imagen" role="img" aria-label="Pastel de fresa y postres variados"></div>
        </div>
    </section>

    <section class="container beneficios" aria-label="Beneficios de la tienda">
        <article><span>🍃</span><div><strong>Postres frescos</strong><small>Hechos con ingredientes de calidad</small></div></article>
        <article><span>🛡</span><div><strong>Compra segura</strong><small>Tus datos siempre protegidos</small></div></article>
        <article><span>🏪</span><div><strong>Atención cercana</strong><small>Endulzamos tu comunidad</small></div></article>
    </section>

    <section class="container seccion" id="categorias">
        <div class="titulo-seccion">
            <h2>Explora por categoría</h2>
            <a href="cliente/productos.php">Ver todos los postres →</a>
        </div>
        <div class="categorias-grid">
            <?php while ($categoria = $categorias->fetch_assoc()): ?>
                <?php $imagen = $imagenesCategorias[$categoria['nombre_categoria']] ?? 'https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=700&q=85'; ?>
                <a class="categoria-card" href="cliente/productos.php">
                    <img src="<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>">
                    <strong><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></strong>
                </a>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="container seccion" id="destacados">
        <div class="titulo-seccion">
            <h2>Favoritos de la semana</h2>
            <a href="cliente/productos.php">Ver más productos →</a>
        </div>
        <div class="productos-grid">
            <?php while ($producto = $productosDestacados->fetch_assoc()): ?>
                <?php $imagen = $imagenesProductos[$producto['nombre_producto']] ?? 'https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=900&q=85'; ?>
                <article class="producto-card">
                    <div class="producto-imagen"><img src="<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"><button type="button" aria-label="Añadir a favoritos">♡</button></div>
                    <div class="producto-info">
                        <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                        <strong>$<?php echo number_format((float) $producto['precio'], 2); ?></strong>
                        <p>★ 4.8 <span>(reseñas)</span></p>
                        <a class="boton-agregar" href="login.php">🛍 Agregar</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/php/pie.php'; ?>
