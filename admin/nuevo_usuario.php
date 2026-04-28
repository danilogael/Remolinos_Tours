<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../Login_APP/login.php"); exit();
}
include('../Database/conexion.php');

$errores = [];
$datos = ['nombre_completo'=>'','email'=>'','telefono'=>'','rol'=>'cliente','activo'=>1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim(mysqli_real_escape_string($conexion, $_POST['nombre_completo'] ?? ''));
    $email    = trim(mysqli_real_escape_string($conexion, $_POST['email'] ?? ''));
    $tel      = trim(mysqli_real_escape_string($conexion, $_POST['telefono'] ?? ''));
    $password = $_POST['password'] ?? '';
    $rol      = in_array($_POST['rol'] ?? '', ['admin','cliente']) ? $_POST['rol'] : 'cliente';
    $activo   = isset($_POST['activo']) ? 1 : 0;

    $datos = compact('nombre','email','tel','rol','activo');
    $datos['nombre_completo'] = $nombre;

    if ($nombre === '') $errores[] = 'El nombre es obligatorio.';
    if ($email  === '') $errores[] = 'El email es obligatorio.';
    if (strlen($password) < 6) $errores[] = 'La contraseña debe tener mínimo 6 caracteres.';

    $chk = mysqli_query($conexion, "SELECT id FROM usuarios WHERE email='$email' LIMIT 1");
    if (mysqli_num_rows($chk) > 0) $errores[] = 'Ese email ya está registrado.';

    if (empty($errores)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql  = "INSERT INTO usuarios (nombre_completo, email, telefono, password, rol, activo)
                 VALUES ('$nombre','$email','$tel','$hash','$rol',$activo)";
        if (mysqli_query($conexion, $sql)) {
            $_SESSION['flash'] = ['tipo'=>'success','msg'=>'Usuario creado correctamente.'];
            header('Location: usuarios.php'); exit();
        }
        $errores[] = 'Error al guardar: ' . mysqli_error($conexion);
    }
}

$tituloPagina = 'Nuevo Usuario';
$paginaActual = 'usuarios';
require_once 'includes/header.php';
?>
<div class="mb-4">
    <a href="usuarios.php" class="text-muted small text-decoration-none">
        <i class="fa-solid fa-arrow-left me-1"></i> Volver a Usuarios
    </a>
    <h4 class="fw-bold mt-1 mb-0">Nuevo Usuario</h4>
</div>

<?php if ($errores): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card panel-card" style="max-width:520px;">
    <div class="card-body p-4">
        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label fw-medium">Nombre completo</label>
                <input type="text" name="nombre_completo" class="form-control" value="<?= htmlspecialchars($datos['nombre_completo']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($datos['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($datos['tel'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="row g-3 mb-4">
                <div class="col">
                    <label class="form-label fw-medium">Rol</label>
                    <select name="rol" class="form-select">
                        <option value="cliente" <?= $datos['rol']==='cliente'?'selected':'' ?>>Cliente</option>
                        <option value="admin"   <?= $datos['rol']==='admin'?'selected':'' ?>>Admin</option>
                    </select>
                </div>
                <div class="col-auto d-flex align-items-end pb-1">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" id="activo" <?= $datos['activo'] ? 'checked' : '' ?>>
                        <label class="form-check-label fw-medium" for="activo">Activo</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Guardar</button>
                <a href="usuarios.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
