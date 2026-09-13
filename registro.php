<?php
$tituloPagina = 'Crear cuenta';
require_once __DIR__ . '/php/conexion.php';
require_once __DIR__ . '/php/util.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmarContrasena = $_POST['confirmar_contrasena'] ?? '';
    $aceptaTerminos = isset($_POST['acepta_terminos']);

    if (
        mb_strlen($nombre) < 2 ||
        !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
        strlen($contrasena) < 6
    ) {
        mensaje('danger', 'Completa un nombre, un correo válido y una contraseña de al menos 6 caracteres.');
    } elseif ($contrasena !== $confirmarContrasena) {
        mensaje('danger', 'Las contraseñas no coinciden.');
    } elseif (!$aceptaTerminos) {
        mensaje('danger', 'Debes aceptar los términos para crear tu cuenta.');
    } else {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $conexion->prepare(
            "INSERT INTO usuarios (nombre, correo, contrasena, tipo_usuario)
             VALUES (?, ?, ?, 'cliente')"
        );
        $stmt->bind_param('sss', $nombre, $correo, $hash);

        if ($stmt->execute()) {
            mensaje('success', 'Cuenta creada. Ya puedes iniciar sesión.');
            header('Location: login.php');
            exit;
        }

        mensaje(
            'danger',
            $conexion->errno === 1062
                ? 'Ese correo ya está registrado.'
                : 'No se pudo crear la cuenta.'
        );
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cuenta | Sweet Place</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <main class="auth-page">
        <section class="auth-shell auth-shell-register">
            <div class="auth-form-panel">
                <a class="auth-logo" href="index.php">
                    <img src="img/sweetplace-logo.png" alt="Sweet Place">
                </a>

                <div class="auth-copy">
                    <p class="auth-eyebrow">ÚNETE A NOSOTROS</p>
                    <h1>Crea tu cuenta</h1>
                    <p>Descubre postres deliciosos y guarda tus compras en Sweet Place.</p>
                </div>

                <?php mostrarMensaje(); ?>

                <form method="post" class="auth-form">
                    <label for="nombre">Nombre completo</label>
                    <div class="auth-input">
                        <span>◉</span>
                        <input
                            id="nombre"
                            name="nombre"
                            type="text"
                            value="<?php echo e($_POST['nombre'] ?? ''); ?>"
                            placeholder="Tu nombre completo"
                            autocomplete="name"
                            required
                        >
                    </div>

                    <label for="correo">Correo electrónico</label>
                    <div class="auth-input">
                        <span>✉</span>
                        <input
                            id="correo"
                            name="correo"
                            type="email"
                            value="<?php echo e($_POST['correo'] ?? ''); ?>"
                            placeholder="correo@ejemplo.com"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <label for="contrasena">Contraseña</label>
                    <div class="auth-input">
                        <span>🔒</span>
                        <input
                            id="contrasena"
                            name="contrasena"
                            type="password"
                            placeholder="Mínimo 6 caracteres"
                            minlength="6"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <label for="confirmar_contrasena">Confirmar contraseña</label>
                    <div class="auth-input">
                        <span>🔒</span>
                        <input
                            id="confirmar_contrasena"
                            name="confirmar_contrasena"
                            type="password"
                            placeholder="Escribe la contraseña otra vez"
                            minlength="6"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <label class="auth-check">
                        <input
                            type="checkbox"
                            name="acepta_terminos"
                            required
                        >
                        <span>Acepto los términos y la política de privacidad.</span>
                    </label>

                    <button class="auth-button" type="submit">Crear cuenta</button>
                </form>

                <p class="auth-switch">
                    ¿Ya tienes una cuenta?
                    <a href="login.php">Inicia sesión</a>
                </p>
            </div>

            <aside class="auth-photo auth-register-photo">
                <p>Pequeños placeres,<br>grandes historias.</p>
            </aside>
        </section>
    </main>
</body>
</html>