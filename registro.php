<?php
$tituloPagina = 'Crear cuenta';
require_once __DIR__ . '/php/encabezado.php';
?>
<main class="container py-5">
    <div class="formulario-card mx-auto">
        <p class="eyebrow">BIENVENIDO</p>
        <h1 class="h2">Crea tu cuenta</h1>
        <p class="text-muted">En el siguiente módulo conectaremos este formulario con la tabla de usuarios.</p>
        <form>
            <label class="form-label" for="nombre">Nombre</label>
            <input class="form-control mb-3" id="nombre" type="text" placeholder="Tu nombre" disabled>
            <label class="form-label" for="correo">Correo electrónico</label>
            <input class="form-control mb-3" id="correo" type="email" placeholder="correo@ejemplo.com" disabled>
            <label class="form-label" for="contrasena">Contraseña</label>
            <input class="form-control mb-3" id="contrasena" type="password" disabled>
            <button class="btn btn-principal w-100" type="button" disabled>Registro disponible próximamente</button>
        </form>
    </div>
</main>
<?php require_once __DIR__ . '/php/pie.php'; ?>
