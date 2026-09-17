<?php
$tituloPagina = 'Pedidos personalizados';
$base = '../';
require_once __DIR__ . '/../php/conexion.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/util.php';
requerirAdministrador();
$estados = ['solicitado', 'en_revision', 'confirmado', 'listo', 'entregado'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id_pedido_personalizado'] ?? 0); $estado = $_POST['estado'] ?? ''; $respuesta = trim($_POST['respuesta_admin'] ?? '');
    if ($id && in_array($estado, $estados, true) && mb_strlen($respuesta) <= 500) {
        $actualizar = $conexion->prepare('UPDATE pedidos_personalizados SET estado = ?, respuesta_admin = ? WHERE id_pedido_personalizado = ?');
        $actualizar->bind_param('ssi', $estado, $respuesta, $id); $actualizar->execute(); mensaje('success', 'Solicitud actualizada.');
    } else mensaje('danger', 'No se pudo actualizar la solicitud.');
    header('Location: pedidos_personalizados.php'); exit;
}
$solicitudes = $conexion->query('SELECT pp.*, u.nombre, u.correo FROM pedidos_personalizados pp JOIN usuarios u ON u.id_usuario = pp.id_usuario ORDER BY pp.fecha_evento ASC, pp.fecha_solicitud DESC');
require_once __DIR__ . '/../php/encabezado.php';
?>
<main class="container py-5"><p class="eyebrow">PEDIDOS ESPECIALES</p><h1>Solicitudes personalizadas</h1><?php mostrarMensaje(); ?><p class="lead">Revisa los requisitos de cada celebración y responde directamente desde el panel.</p><?php if (!$solicitudes->num_rows): ?><div class="alert alert-info mt-4">Aún no hay solicitudes personalizadas.</div><?php else: ?><div class="admin-solicitudes-grid"><?php while ($solicitud = $solicitudes->fetch_assoc()): ?><article class="admin-solicitud-card"><div class="d-flex justify-content-between gap-3 align-items-start"><div><span class="estado-personalizado estado-personalizado-<?php echo e($solicitud['estado']); ?>"><?php echo e(etiquetaEstadoPersonalizado($solicitud['estado'])); ?></span><h2><?php echo e($solicitud['tipo_pedido']); ?></h2></div><small>#<?php echo (int) $solicitud['id_pedido_personalizado']; ?></small></div><p class="admin-solicitud-cliente"><strong><?php echo e($solicitud['nombre']); ?></strong><br><?php echo e($solicitud['correo']); ?></p><dl class="admin-solicitud-datos"><div><dt>Evento</dt><dd><?php echo e($solicitud['tipo_evento']); ?> · <?php echo e($solicitud['fecha_evento']); ?></dd></div><div><dt>Porciones y sabor</dt><dd><?php echo (int) $solicitud['porciones']; ?> · <?php echo e($solicitud['sabor']); ?></dd></div><?php if ($solicitud['relleno'] || $solicitud['cobertura']): ?><div><dt>Relleno y cobertura</dt><dd><?php echo e($solicitud['relleno'] ?: 'No indicado'); ?> · <?php echo e($solicitud['cobertura'] ?: 'No indicada'); ?></dd></div><?php endif; ?><?php if ($solicitud['mensaje']): ?><div><dt>Mensaje</dt><dd><?php echo e($solicitud['mensaje']); ?></dd></div><?php endif; ?><?php if ($solicitud['detalles']): ?><div><dt>Detalles</dt><dd><?php echo e($solicitud['detalles']); ?></dd></div><?php endif; ?></dl><form method="post" class="admin-solicitud-form"><input type="hidden" name="id_pedido_personalizado" value="<?php echo (int) $solicitud['id_pedido_personalizado']; ?>"><label class="form-label">Estado</label><select class="form-select" name="estado"><?php foreach ($estados as $estado): ?><option value="<?php echo e($estado); ?>" <?php echo $solicitud['estado'] === $estado ? 'selected' : ''; ?>><?php echo e(etiquetaEstadoPersonalizado($estado)); ?></option><?php endforeach; ?></select><label class="form-label mt-3">Respuesta para el cliente</label><textarea class="form-control" name="respuesta_admin" rows="3" maxlength="500" placeholder="Ejemplo: Podemos realizarlo. Te contactaremos con la cotización."><?php echo e($solicitud['respuesta_admin']); ?></textarea><button class="btn btn-principal w-100 mt-3">Guardar actualización</button></form></article><?php endwhile; ?></div><?php endif; ?></main>
<?php require_once __DIR__ . '/../php/pie.php'; ?>
