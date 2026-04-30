<?php
// Login_API/auth.php
// Incluir al inicio de CUALQUIER página protegida

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Refresca el rol del usuario desde la BD.
 * Esto soluciona el problema de que si cambias el rol en la BD
 * sin cerrar sesión, la sesión sigue con el rol viejo.
 */
function refrescarRolSesion($conexion) {
    if (!isset($_SESSION['id_usuario'])) return;

    $id  = (int)$_SESSION['id_usuario'];
    $row = mysqli_fetch_assoc(
        mysqli_query($conexion, "SELECT rol, nombre_completo, activo FROM usuarios WHERE id=$id LIMIT 1")
    );

    // Si el usuario fue eliminado o desactivado, cerrar sesión
    if (!$row || (int)$row['activo'] === 0) {
        session_unset();
        session_destroy();
        header("Location: ../Login_APP/login.php?error=cuenta_desactivada");
        exit();
    }

    // Actualizar sesión con datos frescos de la BD
    $_SESSION['rol']    = $row['rol'];
    $_SESSION['nombre'] = $row['nombre_completo'];
}

/**
 * Verifica que el usuario sea ADMIN.
 * Refresca el rol desde la BD antes de verificar.
 * Usar en todas las páginas del panel admin.
 */
function requireAdmin($conexion = null) {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../Login_APP/login.php?error=sesion_requerida");
        exit();
    }

    // Refrescar rol si tenemos conexión disponible
    if ($conexion) {
        refrescarRolSesion($conexion);
    }

    if ($_SESSION['rol'] !== 'admin') {
        header("Location: ../index.php?error=acceso_restringido");
        exit();
    }
}

/**
 * Verifica que haya sesión activa (cualquier rol).
 * Refresca el rol desde la BD antes de verificar.
 * Usar en páginas de cliente: perfil, reservas, etc.
 */
function requireAuth($conexion = null) {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../Login_APP/login.php?error=sesion_requerida");
        exit();
    }

    if ($conexion) {
        refrescarRolSesion($conexion);
    }

    // Si es admin y está en zona de cliente, redirigir al panel admin
    if ($_SESSION['rol'] === 'admin') {
        // Solo redirigir si no venimos ya del admin
        $origen = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($origen, '/admin/') === false) {
            header("Location: ../admin/index.php");
            exit();
        }
    }
}
