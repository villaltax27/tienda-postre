<?php
$base='../'; require_once __DIR__ . '/../php/conexion.php'; require_once __DIR__ . '/../php/auth.php'; require_once __DIR__ . '/../php/util.php'; requerirInicio();
if (!esCliente() || empty($_SESSION['carrito'])) { header('Location: carrito.php'); exit; }
$conexion->begin_transaction();
try { $items=[]; $total=0; foreach($_SESSION['carrito'] as $id=>$cant) { $id=(int)$id; $cant=(int)$cant; $r=$conexion->query("SELECT id_producto,nombre_producto,precio,cantidad FROM productos WHERE id_producto=$id FOR UPDATE"); $p=$r->fetch_assoc(); if(!$p || $cant<1 || $p['cantidad']<$cant) throw new Exception('No hay existencias suficientes para completar el pedido.'); $items[]=[$p,$cant]; $total += $p['precio']*$cant; }
    $usuario=(int)$_SESSION['usuario']['id_usuario']; $stmt=$conexion->prepare('INSERT INTO ventas (total,id_usuario) VALUES (?,?)'); $stmt->bind_param('di',$total,$usuario); $stmt->execute(); $venta=$conexion->insert_id;
    $det=$conexion->prepare('INSERT INTO detalle_venta (id_venta,id_producto,cantidad,precio) VALUES (?,?,?,?)'); $stock=$conexion->prepare('UPDATE productos SET cantidad=cantidad-? WHERE id_producto=?'); foreach($items as [$p,$cant]) { $id=(int)$p['id_producto']; $precio=(float)$p['precio']; $det->bind_param('iiid',$venta,$id,$cant,$precio); $det->execute(); $stock->bind_param('ii',$cant,$id); $stock->execute(); }
    $conexion->commit(); unset($_SESSION['carrito']); mensaje('success', '¡Compra registrada! Tu número de pedido es #' . $venta . '.'); header('Location: mis_compras.php'); exit;
} catch(Throwable $e) { $conexion->rollback(); mensaje('danger', $e->getMessage()); header('Location: carrito.php'); exit; }
