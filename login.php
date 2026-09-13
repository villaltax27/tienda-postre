<?php
$tituloPagina = 'Iniciar sesión';
require_once __DIR__ . '/php/conexion.php';
require_once __DIR__ . '/php/util.php';
if (!empty($_SESSION['usuario'])) { header('Location: ' . (esCliente() ? 'cliente/productos.php' : 'admin/panel.php')); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? ''); $contrasena = $_POST['contrasena'] ?? '';
    $stmt = $conexion->prepare('SELECT id_usuario, nombre, correo, contrasena, tipo_usuario FROM usuarios WHERE correo = ? LIMIT 1');
    $stmt->bind_param('s', $correo); $stmt->execute(); $usuario = $stmt->get_result()->fetch_assoc();
    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        unset($usuario['contrasena']); $_SESSION['usuario'] = $usuario;
        mensaje('success', '¡Bienvenido/a, ' . $usuario['nombre'] . '!');
        header('Location: ' . ($usuario['tipo_usuario'] === 'administrador' ? 'admin/panel.php' : 'cliente/productos.php')); exit;
    }
    mensaje('danger', 'Correo o contraseña incorrectos.');
}
require_once __DIR__ . '/php/encabezado.php';
?>
<main class="container py-5">
    <div class="formulario-card mx-auto">
        <p class="eyebrow">HOLA DE NUEVO</p>
        <h1 class="h2">Iniciar sesión</h1>
        <?php mostrarMensaje(); ?>
        <form method="post" novalidate>
            <label class="form-label" for="correo">Correo electrónico</label>
            <input class="form-control mb-3" id="correo" name="correo" type="email" placeholder="correo@ejemplo.com" required>
            <label class="form-label" for="contrasena">Contraseña</label>
            <input class="form-control mb-3" id="contrasena" name="contrasena" type="password" required>
            <button class="btn btn-principal w-100" type="submit">Iniciar sesión</button>
        </form>
        <p class="text-center mt-3 mb-0">¿Aún no tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>
</main>
<?php require_once __DIR__ . '/php/pie.php'; ?>
