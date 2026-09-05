<?php
require_once __DIR__ . '/php/conexion.php';

$productosDestacados = $conexion->query(
    "SELECT nombre_producto, descripcion, precio FROM productos ORDER BY id_producto ASC LIMIT 3"
);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda de Postres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Dulce Encanto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="cliente/productos.php">Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Iniciar sesión</a></li>
                    <li class="nav-item"><a class="btn btn-principal ms-lg-2" href="registro.php">Crear cuenta</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero-section">
            <div class="container py-5">
                <div class="row align-items-center min-vh-50">
                    <div class="col-lg-7">
                        <p class="eyebrow">POSTRES HECHOS CON AMOR</p>
                        <h1>Endulza tus momentos especiales.</h1>
                        <p class="lead">Pasteles, cupcakes y galletas preparados para celebrar contigo.</p>
                        <a class="btn btn-principal btn-lg" href="cliente/productos.php">Ver postres</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="container py-5">
            <div class="text-center mb-4">
                <p class="eyebrow">NUESTROS FAVORITOS</p>
                <h2>Postres destacados</h2>
            </div>
            <div class="row g-4">
                <?php while ($producto = $productosDestacados->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <article class="card h-100 border-0 shadow-sm producto-card">
                            <div class="postre-ilustracion">🍰</div>
                            <div class="card-body p-4">
                                <h3 class="h5"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                                <p class="text-muted"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                <strong>$<?php echo number_format((float) $producto['precio'], 2); ?></strong>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <footer class="py-4 text-center">
        <small>&copy; <?php echo date('Y'); ?> Dulce Encanto - Tienda de postres.</small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
