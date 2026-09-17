<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
function e(?string $valor): string { return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8'); }
function mensaje(string $tipo, string $texto): void { $_SESSION['mensaje'] = ['tipo' => $tipo, 'texto' => $texto]; }
function mostrarMensaje(): void {
    if (!empty($_SESSION['mensaje'])) {
        $m = $_SESSION['mensaje']; unset($_SESSION['mensaje']);
        echo '<div class="alert alert-' . e($m['tipo']) . ' alert-dismissible fade show" role="alert">' . e($m['texto']) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}
function esCliente(): bool { return ($_SESSION['usuario']['tipo_usuario'] ?? '') === 'cliente'; }
function productosCarrito(): array { return $_SESSION['carrito'] ?? []; }
function imagenProducto(array $producto): string {
    return trim((string) ($producto['imagen'] ?? '')) ?: 'https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=900&q=85';
}
function etiquetaEstadoPedido(string $estado): string {
    return [
        'pendiente' => 'Pendiente',
        'en_preparacion' => 'En preparación',
        'listo' => 'Listo para retirar',
        'entregado' => 'Entregado',
    ][$estado] ?? 'Pendiente';
}
function etiquetaEstadoPersonalizado(string $estado): string {
    return [
        'solicitado' => 'Solicitud recibida',
        'en_revision' => 'En revisión',
        'confirmado' => 'Confirmado',
        'listo' => 'Listo',
        'entregado' => 'Entregado',
    ][$estado] ?? 'Solicitud recibida';
}
