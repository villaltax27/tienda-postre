CREATE DATABASE IF NOT EXISTS tienda_postres CHARACTER SET utf8mb4;
USE tienda_postres;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('administrador', 'cliente') NOT NULL DEFAULT 'cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(80) NOT NULL,
    descripcion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(120) NOT NULL,
    descripcion VARCHAR(255),
    precio DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) DEFAULT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT fk_producto_categoria FOREIGN KEY (id_categoria)
        REFERENCES categorias(id_categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'en_preparacion', 'listo', 'entregado') NOT NULL DEFAULT 'pendiente',
    tipo_entrega ENUM('retiro', 'delivery') NOT NULL DEFAULT 'retiro',
    direccion_entrega VARCHAR(255) DEFAULT NULL,
    fecha_entrega DATETIME DEFAULT NULL,
    notas VARCHAR(500) DEFAULT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_venta_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE detalle_venta (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detalle_venta FOREIGN KEY (id_venta)
        REFERENCES ventas(id_venta) ON DELETE CASCADE,
    CONSTRAINT fk_detalle_producto FOREIGN KEY (id_producto)
        REFERENCES productos(id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE pedidos_personalizados (
    id_pedido_personalizado INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_solicitud DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_evento DATE NOT NULL,
    tipo_pedido VARCHAR(80) NOT NULL,
    tipo_evento VARCHAR(80) DEFAULT NULL,
    porciones INT NOT NULL,
    sabor VARCHAR(100) NOT NULL,
    relleno VARCHAR(100) DEFAULT NULL,
    cobertura VARCHAR(100) DEFAULT NULL,
    mensaje VARCHAR(160) DEFAULT NULL,
    detalles VARCHAR(700) DEFAULT NULL,
    estado ENUM('solicitado', 'en_revision', 'confirmado', 'listo', 'entregado') NOT NULL DEFAULT 'solicitado',
    respuesta_admin VARCHAR(500) DEFAULT NULL,
    CONSTRAINT fk_pedido_personalizado_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categorias (nombre_categoria, descripcion) VALUES
('Pasteles', 'Postres para compartir y celebraciones'),
('Cupcakes', 'Pastelitos individuales decorados'),
('Galletas', 'Galletas artesanales recién horneadas');

-- Cuenta de demostración para el panel de administración.
-- Correo: admin@sweetplace.test | Contraseña: password
INSERT INTO usuarios (nombre, correo, contrasena, tipo_usuario) VALUES
('Administración Sweet Place', 'admin@sweetplace.test', '$2y$10$EFLp5FvOLDEZ8sZIGbKNvu6uJWnzA/9Ahoi92pzp4zNSjDeFqu2KK', 'administrador');

INSERT INTO productos (nombre_producto, descripcion, precio, cantidad, imagen, id_categoria) VALUES
('Pastel de chocolate', 'Bizcocho de chocolate con crema', 18.00, 10, 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=900&q=85', 1),
('Cheesecake de fresa', 'Cheesecake cremoso con fresas', 16.00, 8, 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=900&q=85', 1),
('Pastel de vainilla', 'Pastel de vainilla con crema', 15.00, 10, 'https://images.unsplash.com/photo-1558636508-e0db3814bd1d?auto=format&fit=crop&w=900&q=85', 1),
('Tres leches', 'Pastel tradicional de tres leches', 14.00, 12, 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?auto=format&fit=crop&w=900&q=85', 1),
('Tiramisú', 'Postre italiano con café y cacao', 17.00, 6, 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=900&q=85', 1),
('Cupcake de chocolate', 'Cupcake con cobertura de chocolate', 2.50, 30, 'https://images.unsplash.com/photo-1576610616656-d3aa5d1f4534?auto=format&fit=crop&w=900&q=85', 2),
('Cupcake de vainilla', 'Cupcake con crema de vainilla', 2.25, 30, 'https://images.unsplash.com/photo-1486427944299-d1955d23e34d?auto=format&fit=crop&w=900&q=85', 2),
('Cupcake red velvet', 'Cupcake de terciopelo rojo', 2.75, 25, 'https://images.unsplash.com/photo-1614707267537-b85aaf00c4b7?auto=format&fit=crop&w=900&q=85', 2),
('Cupcake de fresa', 'Cupcake con sabor a fresa', 2.50, 25, 'https://images.unsplash.com/photo-1587668178277-295251f900ce?auto=format&fit=crop&w=900&q=85', 2),
('Cupcake de limón', 'Cupcake con crema de limón', 2.50, 20, 'https://images.unsplash.com/photo-1519869325930-281384150729?auto=format&fit=crop&w=900&q=85', 2),
('Galleta con chispas', 'Galleta clásica con chocolate', 1.25, 40, 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=900&q=85', 3),
('Galleta de avena', 'Galleta de avena y pasas', 1.20, 35, 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e?auto=format&fit=crop&w=900&q=85', 3),
('Galleta de mantequilla', 'Galleta suave de mantequilla', 1.00, 40, 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=900&q=85', 3),
('Macaron de fresa', 'Galleta francesa rellena', 1.75, 30, 'https://images.unsplash.com/photo-1569864358642-9d1684040f43?auto=format&fit=crop&w=900&q=85', 3),
('Brownie', 'Brownie de chocolate intenso', 2.00, 25, 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=900&q=85', 3);
