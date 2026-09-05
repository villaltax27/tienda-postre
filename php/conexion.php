<?php
$conexion = new mysqli('localhost', 'root', '', 'tienda_postres');

if ($conexion->connect_error) {
    exit('No fue posible conectar con la base de datos. Verifica que MySQL esté iniciado en XAMPP.');
}

$conexion->set_charset('utf8mb4');
