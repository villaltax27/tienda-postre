<?php
$tituloPagina = 'Panel administrativo';
$base = '../';
require_once __DIR__ . '/../php/conexion.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/util.php';
requerirAdministrador();
$conteos = [];
foreach (['productos' => 'Productos', 'usuarios' => 'Usuarios', 'ventas' => 'Ventas', 'pedidos_personalizados' => 'Solicitudes'] as $tabla => $etiqueta) { $conteos[$etiqueta] = (int)$conexion->query("SELECT COUNT(*) total FROM $tabla")->fetch_assoc()['total']; }
$total = $conexion->query('SELECT COALESCE(SUM(total),0) total FROM ventas')->fetch_assoc()['total'];
require_once __DIR__ . '/../php/encabezado.php';
?>
<main class="container py-5">
    <p class="eyebrow">ADMINISTRACIÓN</p>
    <h1>Panel administrativo</h1>
    <?php mostrarMensaje(); ?><p class="lead">Gestiona el catálogo, pedidos y solicitudes especiales de Sweet Place.</p>
    <div class="row g-3 my-3"><?php foreach($conteos as $etiqueta=>$cantidad): ?><div class="col-md-3"><div class="card h-100 shadow-sm border-0"><div class="card-body"><small><?php echo $etiqueta; ?></small><div class="display-6"><?php echo $cantidad; ?></div></div></div></div><?php endforeach; ?></div>
    <div class="card border-0 shadow-sm"><div class="card-body d-flex flex-wrap gap-2 align-items-center justify-content-between"><div><strong>Ventas acumuladas</strong><div class="h3 mb-0 text-success">$<?php echo number_format($total,2); ?></div></div><div><a class="btn btn-principal" href="agregar_producto.php">Agregar producto</a> <a class="btn btn-outline-secondary" href="productos.php">Administrar productos</a> <a class="btn btn-outline-secondary" href="ventas.php">Pedidos</a> <a class="btn btn-outline-secondary" href="pedidos_personalizados.php">Personalizados</a> <a class="btn btn-outline-secondary" href="usuarios.php">Usuarios</a></div></div></div>
</main>
<?php require_once __DIR__ . '/../php/pie.php'; ?>
