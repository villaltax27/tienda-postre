# Sweet Place

Tienda web de postres creada con PHP, MySQL, HTML, CSS y Bootstrap.

## Ejecución local

1. Inicia Apache y MySQL desde XAMPP.
2. Crea o importa la base de datos desde `database/tienda_postres.sql` en phpMyAdmin.
3. Copia la carpeta del proyecto a `C:\xampp\htdocs\tienda-postre` y abre `http://localhost/tienda-postre/`.

## Funciones incluidas

- Registro e inicio/cierre de sesión con contraseñas cifradas.
- Roles separados: cliente y administrador.
- Catálogo con búsqueda, filtro por categoría, imágenes y páginas de detalle.
- Carrito, compra simulada, retiro en tienda o entrega a domicilio, descuento de inventario e historial de pedidos.
- Pedidos personalizados con sabores, porciones, mensaje, fecha de evento y seguimiento de estado.
- Panel administrativo con CRUD de productos, imágenes, gestión de pedidos, ventas y usuarios.

## Actualizar una base de datos existente

Si ya importaste una versión anterior, ejecuta una sola vez `database/actualizar_pedidos.sql` y `database/actualizar_pedidos_personalizados.sql` en phpMyAdmin. Estos archivos agregan los datos de entrega y la tabla de pedidos personalizados. Para corregir imágenes repetidas en productos existentes, ejecuta `database/actualizar_imagenes_productos.sql`.

## Cuenta administrativa de demostración

- Correo: `admin@sweetplace.test`
- Contraseña: `password`
