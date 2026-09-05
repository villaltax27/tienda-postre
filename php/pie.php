<footer class="footer-principal">
    <section class="newsletter">
        <span class="nota-lateral nota-izquierda">Postres<br>que alegran<br>tu día ♡</span>
        <div>
            <h2>Un poquito de <em>dulzura</em> en tu correo</h2>
            <p>Recibe novedades, promociones y postres irresistibles.</p>
        </div>
        <form class="newsletter-form" action="#" method="post">
            <label class="visually-hidden" for="correo-newsletter">Correo electrónico</label>
            <input id="correo-newsletter" type="email" placeholder="Tu correo electrónico">
            <button type="button">Suscribirme</button>
        </form>
        <span class="nota-lateral nota-derecha">Más<br>dulzura<br>siempre ♡</span>
    </section>
    <section class="footer-contenido container">
        <div class="footer-marca">
            <a class="marca-footer" href="<?php echo $base; ?>index.php">Sweet Place <span>🍓</span></a>
            <p>El lugar donde los mejores postres se encuentran.</p>
            <div class="redes" aria-label="Redes sociales">
                <a href="#" aria-label="Instagram">◎</a>
                <a href="#" aria-label="Facebook">f</a>
                <a href="#" aria-label="TikTok">♪</a>
            </div>
        </div>
        <div>
            <h3>Explora</h3>
            <a href="<?php echo $base; ?>index.php">Inicio</a>
            <a href="<?php echo $base; ?>cliente/productos.php">Postres</a>
            <a href="<?php echo $base; ?>index.php#categorias">Categorías</a>
        </div>
        <div>
            <h3>Sweet Place</h3>
            <a href="<?php echo $base; ?>registro.php">Crear cuenta</a>
            <a href="<?php echo $base; ?>login.php">Iniciar sesión</a>
            <a href="<?php echo $base; ?>admin/panel.php">Administración</a>
        </div>
        <div>
            <h3>Ayuda</h3>
            <a href="<?php echo $base; ?>login.php">Mis pedidos</a>
            <a href="#">Preguntas frecuentes</a>
            <a href="#">Términos y privacidad</a>
        </div>
    </section>
    <div class="footer-legal container">
        <small>&copy; <?php echo date('Y'); ?> Sweet Place. Todos los derechos reservados.</small>
        <span><a href="#">Privacidad</a><a href="#">Términos</a></span>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
