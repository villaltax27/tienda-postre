<?php
$base='../'; require_once __DIR__ . '/../php/conexion.php'; require_once __DIR__ . '/../php/auth.php'; require_once __DIR__ . '/../php/util.php'; requerirInicio();
if (!esCliente() || empty($_SESSION['carrito'])) { header('Location: carrito.php'); exit; }
$items = []; $total = 0;
foreach ($_SESSION['carrito'] as $id => $cantidad) {
    $id = (int) $id; $cantidad = (int) $cantidad;
    $consulta = $conexion->prepare('SELECT id_producto, nombre_producto, precio, cantidad FROM productos WHERE id_producto = ?');
    $consulta->bind_param('i', $id); $consulta->execute(); $producto = $consulta->get_result()->fetch_assoc();
    if ($producto && $cantidad > 0) { $producto['en_carrito'] = min($cantidad, (int) $producto['cantidad']); $items[] = $producto; $total += $producto['precio'] * $producto['en_carrito']; }
}
if (!$items) { header('Location: carrito.php'); exit; }

$tipoEntrega = $_POST['tipo_entrega'] ?? 'retiro';
$direccion = trim($_POST['direccion_entrega'] ?? '');
$fechaEntrega = trim($_POST['fecha_entrega'] ?? '');
$notas = trim($_POST['notas'] ?? '');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!in_array($tipoEntrega, ['retiro', 'delivery'], true)) $error = 'Selecciona cómo deseas recibir tu pedido.';
    elseif ($tipoEntrega === 'delivery' && $direccion === '') $error = 'Ingresa la dirección para la entrega a domicilio.';
    elseif (mb_strlen($direccion) > 255 || mb_strlen($notas) > 500) $error = 'La dirección o las notas exceden el límite permitido.';
    if (!$error) {
        $fechaMysql = $fechaEntrega === '' ? null : str_replace('T', ' ', $fechaEntrega) . ':00';
        $conexion->begin_transaction();
        try {
            foreach ($items as $item) {
                $bloqueado = $conexion->query('SELECT cantidad FROM productos WHERE id_producto = ' . (int) $item['id_producto'] . ' FOR UPDATE')->fetch_assoc();
                if (!$bloqueado || (int) $bloqueado['cantidad'] < (int) $item['en_carrito']) throw new Exception('No hay existencias suficientes para completar el pedido.');
            }
            $usuario = (int) $_SESSION['usuario']['id_usuario'];
            $ventaStmt = $conexion->prepare("INSERT INTO ventas (total, estado, tipo_entrega, direccion_entrega, fecha_entrega, notas, id_usuario) VALUES (?, 'pendiente', ?, ?, ?, ?, ?)");
            $direccionGuardar = $tipoEntrega === 'delivery' ? $direccion : null;
            $ventaStmt->bind_param('dssssi', $total, $tipoEntrega, $direccionGuardar, $fechaMysql, $notas, $usuario); $ventaStmt->execute(); $venta = $conexion->insert_id;
            $detalle = $conexion->prepare('INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)');
            $stock = $conexion->prepare('UPDATE productos SET cantidad = cantidad - ? WHERE id_producto = ?');
            foreach ($items as $item) { $id = (int) $item['id_producto']; $cantidad = (int) $item['en_carrito']; $precio = (float) $item['precio']; $detalle->bind_param('iiid', $venta, $id, $cantidad, $precio); $detalle->execute(); $stock->bind_param('ii', $cantidad, $id); $stock->execute(); }
            $conexion->commit(); unset($_SESSION['carrito']); mensaje('success', '¡Pedido registrado! Tu número de pedido es #' . $venta . '.'); header('Location: mis_compras.php'); exit;
        } catch (Throwable $e) { $conexion->rollback(); $error = $e->getMessage(); }
    }
}
$tituloPagina = 'Confirmar pedido'; require_once __DIR__ . '/../php/encabezado.php';
?>
<main class="container py-5"><div class="checkout-encabezado"><div><p class="eyebrow">ÚLTIMO PASO</p><h1>Confirma tu pedido</h1><p>Esta es una compra de demostración: no se procesará ningún pago real.</p></div><a class="btn btn-contorno" href="carrito.php">Volver al carrito</a></div>
<?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
<div class="checkout-grid"><form method="post" class="checkout-form"><h2>¿Cómo quieres recibirlo?</h2><div class="opciones-entrega"><label><input type="radio" name="tipo_entrega" value="retiro" <?php echo $tipoEntrega === 'retiro' ? 'checked' : ''; ?>><span><strong>Retiro en tienda</strong><small>Te avisaremos cuando esté listo.</small></span></label><label><input type="radio" name="tipo_entrega" value="delivery" <?php echo $tipoEntrega === 'delivery' ? 'checked' : ''; ?>><span><strong>Entrega a domicilio</strong><small>Indica una dirección completa.</small></span></label></div><label class="form-label mt-4" for="direccion_entrega">Dirección de entrega</label><textarea id="direccion_entrega" class="form-control" name="direccion_entrega" rows="3" placeholder="Colonia, calle, casa y referencias"><?php echo e($direccion); ?></textarea><label class="form-label mt-3" for="fecha_entrega">Fecha y hora preferida <small>(opcional)</small></label><input id="fecha_entrega" class="form-control" type="datetime-local" name="fecha_entrega" value="<?php echo e($fechaEntrega); ?>"><label class="form-label mt-3" for="notas">Notas para tu pedido <small>(opcional)</small></label><textarea id="notas" class="form-control" name="notas" rows="3" maxlength="500" placeholder="Ejemplo: tocar el timbre o escribir una dedicatoria."><?php echo e($notas); ?></textarea><button class="btn btn-principal btn-lg w-100 mt-4" type="submit">Registrar pedido simulado</button></form><aside class="resumen-pedido"><h2>Resumen</h2><?php foreach ($items as $item): ?><div><span><?php echo (int) $item['en_carrito']; ?> × <?php echo e($item['nombre_producto']); ?></span><strong>$<?php echo number_format($item['en_carrito'] * $item['precio'], 2); ?></strong></div><?php endforeach; ?><hr><div class="resumen-total"><span>Total</span><strong>$<?php echo number_format($total, 2); ?></strong></div></aside></div></main>
<?php require_once __DIR__ . '/../php/pie.php'; ?>
