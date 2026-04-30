<?php
// user/actualizar_perfil.php
include('../Database/conexion.php');

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../Login_APP/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: viewdata.php");
    exit();
}

$id     = (int)$_SESSION['id_usuario'];
$accion = $_POST['accion'] ?? '';

// ══════════════════════════════════════════════════════════
//  ACCIÓN 1: Actualizar datos del perfil (incluye email)
// ══════════════════════════════════════════════════════════
if ($accion === 'perfil') {

    $nombre   = trim(mysqli_real_escape_string($conexion, $_POST['nombre']              ?? ''));
    $email    = trim(mysqli_real_escape_string($conexion, $_POST['email']               ?? ''));
    $telefono = trim(mysqli_real_escape_string($conexion, $_POST['telefono']            ?? ''));
    $fecha    = trim(mysqli_real_escape_string($conexion, $_POST['fecha_nac']           ?? ''));
    $passConf = $_POST['pass_confirmar_cambio'] ?? '';

    // ── Validaciones básicas ──────────────────────────────
    if ($nombre === '') {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'El nombre no puede quedar vacío.'];
        header("Location: viewdata.php?tab=datos"); exit();
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'Escribe un correo electrónico válido.'];
        header("Location: viewdata.php?tab=datos"); exit();
    }

    // ── Verificar contraseña actual (siempre requerida) ───
    if ($passConf === '') {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'Debes ingresar tu contraseña actual para guardar cambios.'];
        header("Location: viewdata.php?tab=datos"); exit();
    }

    $rowPass = mysqli_fetch_assoc(
        mysqli_query($conexion, "SELECT password FROM usuarios WHERE id=$id LIMIT 1")
    );
    if (!password_verify($passConf, $rowPass['password'])) {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'La contraseña actual es incorrecta. No se guardaron los cambios.'];
        header("Location: viewdata.php?tab=datos"); exit();
    }

    // ── Verificar que el nuevo email no esté en uso ───────
    $chk = mysqli_query($conexion,
        "SELECT id FROM usuarios WHERE email='$email' AND id != $id LIMIT 1"
    );
    if (mysqli_num_rows($chk) > 0) {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'Ese correo ya está registrado por otro usuario.'];
        header("Location: viewdata.php?tab=datos"); exit();
    }

    // ── Validar fecha si se envió ─────────────────────────
    $fechaSQL = 'NULL';
    if ($fecha !== '') {
        $dt = DateTime::createFromFormat('Y-m-d', $fecha);
        if ($dt && $dt->format('Y-m-d') === $fecha) {
            $fechaSQL = "'$fecha'";
        } else {
            $_SESSION['flash'] = ['tipo'=>'error','msg'=>'Formato de fecha inválido.'];
            header("Location: viewdata.php?tab=datos"); exit();
        }
    }

    // ── Guardar ───────────────────────────────────────────
    $sql = "UPDATE usuarios
            SET nombre_completo  = '$nombre',
                email            = '$email',
                telefono         = '$telefono',
                fecha_nacimiento = $fechaSQL
            WHERE id = $id";

    if (mysqli_query($conexion, $sql)) {
        // Actualizar sesión
        $_SESSION['nombre'] = $nombre;
        $_SESSION['flash']  = ['tipo'=>'success','msg'=>'¡Perfil actualizado correctamente!'];
    } else {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'Error al guardar: ' . mysqli_error($conexion)];
    }

    header("Location: viewdata.php?tab=datos");
    exit();
}

// ══════════════════════════════════════════════════════════
//  ACCIÓN 2: Cambiar contraseña
// ══════════════════════════════════════════════════════════
if ($accion === 'password') {

    $passActual    = $_POST['pass_actual']    ?? '';
    $passNueva     = $_POST['pass_nueva']     ?? '';
    $passConfirmar = $_POST['pass_confirmar'] ?? '';

    $row = mysqli_fetch_assoc(
        mysqli_query($conexion, "SELECT password FROM usuarios WHERE id=$id LIMIT 1")
    );

    if (!password_verify($passActual, $row['password'])) {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'La contraseña actual es incorrecta.'];
        header("Location: viewdata.php?tab=password"); exit();
    }

    if (strlen($passNueva) < 6) {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'La nueva contraseña debe tener al menos 6 caracteres.'];
        header("Location: viewdata.php?tab=password"); exit();
    }

    if ($passNueva !== $passConfirmar) {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'Las contraseñas nuevas no coinciden.'];
        header("Location: viewdata.php?tab=password"); exit();
    }

    $hash    = password_hash($passNueva, PASSWORD_DEFAULT);
    $hashEsc = mysqli_real_escape_string($conexion, $hash);

    if (mysqli_query($conexion, "UPDATE usuarios SET password='$hashEsc' WHERE id=$id")) {
        $_SESSION['flash'] = ['tipo'=>'success','msg'=>'¡Contraseña actualizada correctamente!'];
    } else {
        $_SESSION['flash'] = ['tipo'=>'error','msg'=>'Error al actualizar: ' . mysqli_error($conexion)];
    }

    header("Location: viewdata.php?tab=password");
    exit();
}

header("Location: viewdata.php");
exit();
