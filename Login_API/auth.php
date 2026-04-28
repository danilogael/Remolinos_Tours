<?php
// Login_API/auth.php
// Incluir este archivo al inicio de cualquier página protegida

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica que el usuario sea ADMIN.
 * Si no lo es → redirige al login.
 * Usar en todas las páginas del panel admin.
 */
function requireAdmin() {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header("Location: ../Login_APP/login.php?error=acceso_restringido");
        exit();
    }
}

/**
 * Verifica que haya una sesión activa (cualquier rol: admin o cliente).
 * Si no hay sesión → redirige al login.
 * Usar en páginas de cliente como perfil, reservas, etc.
 */
function requireAuth() {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../Login_APP/login.php?error=sesion_requerida");
        exit();
    }
}

// Compatibilidad: si se incluye directamente (como antes), protege como admin
if (!function_exists('requireAdmin')) {
    requireAdmin();
}
