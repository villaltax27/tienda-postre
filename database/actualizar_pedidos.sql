-- Ejecuta este archivo una sola vez si ya habías importado tienda_postres.sql.
USE tienda_postres;

ALTER TABLE ventas
    ADD COLUMN estado ENUM('pendiente', 'en_preparacion', 'listo', 'entregado') NOT NULL DEFAULT 'pendiente' AFTER total,
    ADD COLUMN tipo_entrega ENUM('retiro', 'delivery') NOT NULL DEFAULT 'retiro' AFTER estado,
    ADD COLUMN direccion_entrega VARCHAR(255) DEFAULT NULL AFTER tipo_entrega,
    ADD COLUMN fecha_entrega DATETIME DEFAULT NULL AFTER direccion_entrega,
    ADD COLUMN notas VARCHAR(500) DEFAULT NULL AFTER fecha_entrega;

-- Las imágenes se guardan como URL; evita subir archivos en esta primera versión.
UPDATE productos
SET imagen = CASE nombre_producto
    WHEN 'Pastel de chocolate' THEN 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=900&q=85'
    WHEN 'Cheesecake de fresa' THEN 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=900&q=85'
    WHEN 'Pastel de vainilla' THEN 'https://images.unsplash.com/photo-1558636508-e0db3814bd1d?auto=format&fit=crop&w=900&q=85'
    WHEN 'Tres leches' THEN 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?auto=format&fit=crop&w=900&q=85'
    WHEN id_producto = 5 THEN 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=900&q=85'
    WHEN 'Cupcake de chocolate' THEN 'https://images.unsplash.com/photo-1576610616656-d3aa5d1f4534?auto=format&fit=crop&w=900&q=85'
    WHEN 'Cupcake de vainilla' THEN 'https://images.unsplash.com/photo-1486427944299-d1955d23e34d?auto=format&fit=crop&w=900&q=85'
    WHEN 'Cupcake red velvet' THEN 'https://images.unsplash.com/photo-1614707267537-b85aaf00c4b7?auto=format&fit=crop&w=900&q=85'
    WHEN 'Cupcake de fresa' THEN 'https://images.unsplash.com/photo-1587668178277-295251f900ce?auto=format&fit=crop&w=900&q=85'
    WHEN id_producto = 10 THEN 'https://images.unsplash.com/photo-1519869325930-281384150729?auto=format&fit=crop&w=900&q=85'
    WHEN 'Galleta con chispas' THEN 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=900&q=85'
    WHEN 'Galleta de avena' THEN 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e?auto=format&fit=crop&w=900&q=85'
    WHEN 'Galleta de mantequilla' THEN 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=900&q=85'
    WHEN 'Macaron de fresa' THEN 'https://images.unsplash.com/photo-1569864358642-9d1684040f43?auto=format&fit=crop&w=900&q=85'
    WHEN 'Brownie' THEN 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=900&q=85'
    ELSE imagen
END;
