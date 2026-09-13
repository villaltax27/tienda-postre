<?php
$base = '../'; $tituloPagina = 'Mi carrito';
require_once __DIR__ . '/../php/conexion.php'; require_once __DIR__ . '/../php/auth.php'; require_once __DIR__ . '/../php/util.php'; requerirInicio();
if (!esCliente()) { header('Location: ../admin/panel.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['vaciar'])) { unset($_SESSION['carrito']); mensaje('success', 'Carrito vaciado.'); }
    else { foreach ($_POST['cantidad'] ?? [] as $id => $cantidad) { $cantidad = max(0, (int)$cantidad); if ($cantidad) $_SESSION['carrito'][(int)$id] = $cantidad; else unset($_SESSION['carrito'][(int)$id]); } mensaje('success', 'Carrito actualizado.'); }
    header('Location: carrito.php'); exit;
}
$ids = array_keys(productosCarrito()); $productos = []; $total = 0;
if ($ids) { $lista = implode(',', array_map('intval', $ids)); $r = $conexion->query("SELECT id_producto,nombre_producto,precio,cantidad FROM productos WHERE id_producto IN ($lista)"); while ($p=$r->fetch_assoc()) { $p['en_carrito']=min((int)productosCarrito()[$p['id_producto']], (int)$p['cantidad']); $productos[]=$p; $total += $p['en_carrito']*(float)$p['precio']; } }
require_once __DIR__ . '/../php/encabezado.php';
?>
<main class="container py-5"><div class="d-flex justify-content-between align-items-center mb-4"><div><p class="eyebrow">TU PEDIDO</p><h1>Mi carrito</h1></div><a href="productos.php" class="btn btn-outline-secondary">Seguir comprando</a></div><?php mostrarMensaje(); ?>
<?php if (!$productos): ?><div class="alert alert-info">Tu carrito está vacío.</div><?php else: ?><form method="post"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Producto</th><th>Precio</th><th style="width:130px">Cantidad</th><th>Subtotal</th></tr></thead><tbody><?php foreach($productos as $p): ?><tr><td><?php echo e($p['nombre_producto']); ?></td><td>$<?php echo number_format($p['precio'],2); ?></td><td><input class="form-control" type="number" min="0" max="<?php echo $p['cantidad']; ?>" name="cantidad[<?php echo $p['id_producto']; ?>]" value="<?php echo $p['en_carrito']; ?>"></td><td>$<?php echo number_format($p['en_carrito']*$p['precio'],2); ?></td></tr><?php endforeach; ?></tbody></table></div><div class="d-flex justify-content-between align-items-center"><button class="btn btn-outline-danger" name="vaciar" value="1">Vaciar</button><div class="text-end"><h3>Total: $<?php echo number_format($total,2); ?></h3><button class="btn btn-principal me-2">Actualizar</button><a class="btn btn-success" href="comprar.php">Confirmar compra</a></div></div></form><?php endif; ?></main>
<?php require_once __DIR__ . '/../php/pie.php'; ?>
