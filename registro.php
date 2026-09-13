<?php
$tituloPagina = 'Crear cuenta';
require_once __DIR__ . '/php/conexion.php';
require_once __DIR__ . '/php/util.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? ''); $correo = trim($_POST['correo'] ?? ''); $contrasena = $_POST['contrasena'] ?? '';
    if (mb_strlen($nombre) < 2 || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($contrasena) < 6) {
        mensaje('danger', 'Completa un nombre, un correo válido y una contraseña de al menos 6 caracteres.');
    } else {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena, tipo_usuario) VALUES (?, ?, ?, 'cliente')");
        $stmt->bind_param('sss', $nombre, $correo, $hash);
        if ($stmt->execute()) { mensaje('success', 'Cuenta creada. Ya puedes iniciar sesión.'); header('Location: login.php'); exit; }
        mensaje('danger', $conexion->errno === 1062 ? 'Ese correo ya está registrado.' : 'No se pudo crear la cuenta.');
    }
}
require_once __DIR__ . '/php/encabezado.php';
?>
<main class="container py-5">
    <div class="formulario-card mx-auto">
        <p class="eyebrow">BIENVENIDO</p>
        <h1 class="h2">Crea tu cuenta</h1>
        <?php mostrarMensaje(); ?>
        <form method="post" novalidate>
            <label class="form-label" for="nombre">Nombre</label>
            <input class="form-control mb-3" id="nombre" name="nombre" type="text" value="<?php echo e($_POST['nombre'] ?? ''); ?>" placeholder="Tu nombre" required>
            <label class="form-label" for="correo">Correo electrónico</label>
            <input class="form-control mb-3" id="correo" name="correo" type="email" value="<?php echo e($_POST['correo'] ?? ''); ?>" placeholder="correo@ejemplo.com" required>
            <label class="form-label" for="contrasena">Contraseña</label>
            <input class="form-control mb-3" id="contrasena" name="contrasena" type="password" minlength="6" required>
            <button class="btn btn-principal w-100" type="submit">Crear cuenta</button>
        </form>
    </div>
</main>
<?php require_once __DIR__ . '/php/pie.php'; ?>
