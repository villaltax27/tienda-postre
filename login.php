<?php
$tituloPagina = 'Iniciar sesión';
require_once __DIR__ . '/php/conexion.php';
require_once __DIR__ . '/php/util.php';

if (!empty($_SESSION['usuario'])) {
    header('Location: ' . (esCliente() ? 'cliente/productos.php' : 'admin/panel.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    $stmt = $conexion->prepare(
        'SELECT id_usuario, nombre, correo, contrasena, tipo_usuario
         FROM usuarios WHERE correo = ? LIMIT 1'
    );
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        unset($usuario['contrasena']);
        $_SESSION['usuario'] = $usuario;

        mensaje('success', '¡Bienvenido/a, ' . $usuario['nombre'] . '!');
        header('Location: ' . (
            $usuario['tipo_usuario'] === 'administrador'
                ? 'admin/panel.php'
                : 'cliente/productos.php'
        ));
        exit;
    }

    mensaje('danger', 'Correo o contraseña incorrectos.');
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión | Sweet Place</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="auth-body">
    <main class="auth-page">
        <section class="auth-shell">
            <div class="auth-form-panel">
                <div class="auth-topbar"><a class="auth-logo" href="index.php"><img src="img/sweetplace-logo.png" alt="Sweet Place"></a><a class="auth-back" href="index.php">← Inicio</a></div>

                <div class="auth-copy">
                    <p class="auth-eyebrow">HOLA DE NUEVO</p>
                    <h1>Bienvenido de nuevo</h1>
                    <p>Inicia sesión para continuar disfrutando tus postres favoritos.</p>
                </div>

                <?php mostrarMensaje(); ?>

                <form method="post" class="auth-form">
                    <label for="correo">Correo electrónico</label>
                    <div class="auth-input">
                        <input
                            id="correo"
                            name="correo"
                            type="email"
                            placeholder="correo@ejemplo.com"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <label for="contrasena">Contraseña</label>
                    <div class="auth-input">
                        <input
                            id="contrasena"
                            name="contrasena"
                            type="password"
                            placeholder="Tu contraseña"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button class="auth-button" type="submit">Iniciar sesión</button>
                </form>

                <p class="auth-switch">
                    ¿No tienes una cuenta?
                    <a href="registro.php">Regístrate</a>
                </p>
            </div>

            <aside class="auth-photo auth-login-photo">
                <p>Tu próximo antojo<br>está más cerca.</p>
            </aside>
        </section>
    </main>
</body>
</html>
