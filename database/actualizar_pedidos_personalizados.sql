-- Ejecuta este archivo una sola vez si ya tienes una base de datos de Sweet Place.
USE tienda_postres;

CREATE TABLE IF NOT EXISTS pedidos_personalizados (
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
