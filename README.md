# Sweet Place

Tienda web de postres creada con PHP, MySQL, HTML, CSS y Bootstrap.

## Ejecución local

1. Inicia Apache y MySQL desde XAMPP.
2. En phpMyAdmin, crea una base de datos llamada `tienda_postres` e importa el único archivo de la carpeta `database`: `tienda_postres.sql`.
3. Copia la carpeta del proyecto a `C:\xampp\htdocs\IT-Proyecto` y abre `http://localhost/IT-Proyecto/`.

## Funciones incluidas

- Registro e inicio/cierre de sesión con contraseñas protegidas mediante hash.
- Roles separados: cliente y administrador.
- Catálogo con búsqueda, filtro por categoría, imágenes y páginas de detalle.
- Carrito, compra simulada, retiro en tienda o entrega a domicilio, descuento de inventario e historial de pedidos.
- Pedidos personalizados con sabores, porciones, mensaje, fecha de evento y seguimiento de estado.
- Panel administrativo con CRUD de productos, imágenes, gestión de pedidos, ventas y usuarios.

## Cuenta administrativa de demostración

- Correo: `admin@sweetplace.test`
- Contraseña: `password`
