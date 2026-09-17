<?php
$tituloPagina = 'Pedido personalizado';
$base = '../';
require_once __DIR__ . '/../php/conexion.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/util.php';
requerirInicio();
if (!esCliente()) { header('Location: ../admin/pedidos_personalizados.php'); exit; }

$tiposPedido = ['Pastel personalizado', 'Cupcakes personalizados', 'Mesa de postres'];
$tiposEvento = ['Cumpleaños', 'Boda', 'Baby shower', 'Graduación', 'Aniversario', 'Otro'];
$datos = ['fecha_evento' => '', 'tipo_pedido' => 'Pastel personalizado', 'tipo_evento' => '', 'porciones' => '', 'sabor' => '', 'relleno' => '', 'cobertura' => '', 'mensaje' => '', 'detalles' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($datos as $campo => $valor) $datos[$campo] = trim((string) ($_POST[$campo] ?? ''));
    $datos['porciones'] = (int) $datos['porciones'];
    if (!in_array($datos['tipo_pedido'], $tiposPedido, true) || !in_array($datos['tipo_evento'], $tiposEvento, true)) $error = 'Selecciona una opción válida para el pedido y el evento.';
    elseif ($datos['fecha_evento'] < date('Y-m-d')) $error = 'La fecha del evento no puede ser anterior a hoy.';
    elseif ($datos['porciones'] < 1 || $datos['porciones'] > 500) $error = 'Indica una cantidad de porciones entre 1 y 500.';
    elseif ($datos['sabor'] === '') $error = 'Indica el sabor principal que deseas.';
    elseif (mb_strlen($datos['sabor']) > 100 || mb_strlen($datos['relleno']) > 100 || mb_strlen($datos['cobertura']) > 100 || mb_strlen($datos['mensaje']) > 160 || mb_strlen($datos['detalles']) > 700) $error = 'Uno de los campos excede el límite permitido.';
    if ($error === '') {
        $usuario = (int) $_SESSION['usuario']['id_usuario'];
        $guardar = $conexion->prepare('INSERT INTO pedidos_personalizados (id_usuario, fecha_evento, tipo_pedido, tipo_evento, porciones, sabor, relleno, cobertura, mensaje, detalles) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $guardar->bind_param('isssisssss', $usuario, $datos['fecha_evento'], $datos['tipo_pedido'], $datos['tipo_evento'], $datos['porciones'], $datos['sabor'], $datos['relleno'], $datos['cobertura'], $datos['mensaje'], $datos['detalles']);
        if ($guardar->execute()) { mensaje('success', 'Recibimos tu solicitud. Revisaremos los detalles y actualizaremos su estado.'); header('Location: pedido_personalizado.php'); exit; }
        $error = 'No se pudo guardar la solicitud. Intenta nuevamente.';
    }
}

$usuario = (int) $_SESSION['usuario']['id_usuario'];
$solicitudes = $conexion->prepare('SELECT * FROM pedidos_personalizados WHERE id_usuario = ? ORDER BY fecha_solicitud DESC');
$solicitudes->bind_param('i', $usuario); $solicitudes->execute(); $resultadoSolicitudes = $solicitudes->get_result();
require_once __DIR__ . '/../php/encabezado.php';
?>
<main class="container py-5">
    <?php mostrarMensaje(); ?>
    <section class="personalizado-hero"><div><p class="eyebrow">PARA OCASIONES ESPECIALES</p><h1>Crea un pedido a tu medida.</h1><p>Cuéntanos cómo imaginas tu postre. Guardaremos tu solicitud para que el equipo pueda revisarla.</p></div><span>Pasteles, cupcakes y mesas de postres.</span></section>
    <div class="personalizado-grid">
        <form method="post" class="solicitud-formulario">
            <h2>Detalles del pedido</h2><p class="text-muted">Los campos con asterisco son obligatorios.</p>
            <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
            <div class="row g-3"><div class="col-md-6"><label class="form-label" for="tipo_pedido">¿Qué deseas pedir? *</label><select id="tipo_pedido" class="form-select" name="tipo_pedido" required><?php foreach ($tiposPedido as $tipo): ?><option <?php echo $datos['tipo_pedido'] === $tipo ? 'selected' : ''; ?>><?php echo e($tipo); ?></option><?php endforeach; ?></select></div><div class="col-md-6"><label class="form-label" for="tipo_evento">Ocasión *</label><select id="tipo_evento" class="form-select" name="tipo_evento" required><option value="">Selecciona una opción</option><?php foreach ($tiposEvento as $tipo): ?><option <?php echo $datos['tipo_evento'] === $tipo ? 'selected' : ''; ?>><?php echo e($tipo); ?></option><?php endforeach; ?></select></div><div class="col-md-6"><label class="form-label" for="fecha_evento">Fecha del evento *</label><input id="fecha_evento" class="form-control" type="date" name="fecha_evento" min="<?php echo date('Y-m-d'); ?>" value="<?php echo e($datos['fecha_evento']); ?>" required></div><div class="col-md-6"><label class="form-label" for="porciones">Número de porciones *</label><input id="porciones" class="form-control" type="number" name="porciones" min="1" max="500" value="<?php echo e((string) $datos['porciones']); ?>" placeholder="Ejemplo: 20" required></div><div class="col-md-6"><label class="form-label" for="sabor">Sabor principal *</label><input id="sabor" class="form-control" name="sabor" maxlength="100" value="<?php echo e($datos['sabor']); ?>" placeholder="Ejemplo: chocolate" required></div><div class="col-md-6"><label class="form-label" for="relleno">Relleno</label><input id="relleno" class="form-control" name="relleno" maxlength="100" value="<?php echo e($datos['relleno']); ?>" placeholder="Ejemplo: fresa con crema"></div><div class="col-md-6"><label class="form-label" for="cobertura">Cobertura</label><input id="cobertura" class="form-control" name="cobertura" maxlength="100" value="<?php echo e($datos['cobertura']); ?>" placeholder="Ejemplo: buttercream"></div><div class="col-md-6"><label class="form-label" for="mensaje">Mensaje para el postre</label><input id="mensaje" class="form-control" name="mensaje" maxlength="160" value="<?php echo e($datos['mensaje']); ?>" placeholder="Ejemplo: Feliz cumpleaños, Ana"></div><div class="col-12"><label class="form-label" for="detalles">Cuéntanos los detalles</label><textarea id="detalles" class="form-control" name="detalles" rows="4" maxlength="700" placeholder="Colores, decoración, temática o cualquier detalle importante."><?php echo e($datos['detalles']); ?></textarea></div></div>
            <button class="btn btn-principal w-100 mt-4" type="submit">Enviar solicitud</button>
        </form>
        <aside class="solicitud-ayuda"><p class="eyebrow">¿QUÉ PASA DESPUÉS?</p><ol><li><strong>Envías tu idea</strong><span>Guardamos los detalles de tu celebración.</span></li><li><strong>Revisamos la solicitud</strong><span>El administrador verá las porciones, fecha y preferencias.</span></li><li><strong>Consultas el estado</strong><span>Podrás volver aquí para ver la respuesta.</span></li></ol></aside>
    </div>
    <section class="mis-solicitudes"><div class="titulo-seccion"><div><p class="eyebrow">MIS SOLICITUDES</p><h2>Pedidos personalizados</h2></div></div><?php if (!$resultadoSolicitudes->num_rows): ?><p class="solicitudes-vacias">Todavía no has enviado una solicitud personalizada.</p><?php else: ?><div class="solicitudes-grid"><?php while ($solicitud = $resultadoSolicitudes->fetch_assoc()): ?><article class="solicitud-card"><div class="solicitud-card-cabecera"><span class="estado-personalizado estado-personalizado-<?php echo e($solicitud['estado']); ?>"><?php echo e(etiquetaEstadoPersonalizado($solicitud['estado'])); ?></span><small>Solicitado: <?php echo e($solicitud['fecha_solicitud']); ?></small></div><h3><?php echo e($solicitud['tipo_pedido']); ?></h3><p class="solicitud-evento"><?php echo e($solicitud['tipo_evento']); ?> · <?php echo e($solicitud['fecha_evento']); ?> · <?php echo (int) $solicitud['porciones']; ?> porciones</p><dl><div><dt>Sabor</dt><dd><?php echo e($solicitud['sabor']); ?></dd></div><?php if ($solicitud['relleno']): ?><div><dt>Relleno</dt><dd><?php echo e($solicitud['relleno']); ?></dd></div><?php endif; ?></dl><?php if ($solicitud['respuesta_admin']): ?><div class="respuesta-admin"><strong>Respuesta de Sweet Place</strong><p><?php echo e($solicitud['respuesta_admin']); ?></p></div><?php endif; ?></article><?php endwhile; ?></div><?php endif; ?></section>
</main>
<?php require_once __DIR__ . '/../php/pie.php'; ?>
