<?php
$tituloPagina = 'Iniciar sesión';
require_once __DIR__ . '/php/encabezado.php';
?>
<main class="container py-5">
    <div class="formulario-card mx-auto">
        <p class="eyebrow">HOLA DE NUEVO</p>
        <h1 class="h2">Iniciar sesión</h1>
        <p class="text-muted">El acceso se activará al construir el módulo de autenticación.</p>
        <form>
            <label class="form-label" for="correo">Correo electrónico</label>
            <input class="form-control mb-3" id="correo" type="email" placeholder="correo@ejemplo.com" disabled>
            <label class="form-label" for="contrasena">Contraseña</label>
            <input class="form-control mb-3" id="contrasena" type="password" disabled>
            <button class="btn btn-principal w-100" type="button" disabled>Acceso disponible próximamente</button>
        </form>
    </div>
</main>
<?php require_once __DIR__ . '/php/pie.php'; ?>
